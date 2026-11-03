<?php

namespace Modules\SISIG\Http\Controllers\Perfilusers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Modules\SICA\Entities\Person;

class PerfilusersController extends Controller
{
    /**
     * Muestra la vista de perfil de usuario en SISIG (reutilizable y adaptativa).
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('person', 'roles');
        $person = $user->person;
        $roles = $user->roles;

        // Asegurar que exista el registro en la tabla people
        if (!$person && $user->person_id) {
            $person = Person::find($user->person_id);
        }

        if (!$person) {
            $person = Person::create([
                'first_name' => $user->name,
                'sena_email' => $user->email,
                'personal_email' => $user->email,
            ]);
            $user->person_id = $person->id;
            $user->save();
        }

        // Consultar datos de formación académica institucional (Ficha y Programa/Tecnólogo)
        $aprendiz = null;
        $ficha = null;
        $programa = null;

        if ($person && Schema::hasTable('apprentices')) {
            $aprendiz = DB::table('apprentices')
                ->where('person_id', $person->id)
                ->first();

            if ($aprendiz) {
                $ficha = $aprendiz->course_id ?? null;
                $programa = $aprendiz->program ?? ($ficha ? 'ADSO' : null);
            }
        }

        // Si es rol aprendiz pero no tiene registro en tabla apprentices, asignar valores oficiales institucionales
        $esAprendiz = $user->hasRole('aprendiz_sisig') || strtolower($user->nickname ?? '') === 'aprendiz' || !empty($aprendiz);
        if ($esAprendiz) {
            if (empty($ficha)) {
                $ficha = '3288036';
            }
            if (empty($programa)) {
                $programa = 'ADSO';
            }
        }

        $personalEmail = $person->personal_email ?? '';
        $phone = $person->phone ?? '';

        return view('sisig::perfil.perfilusers', compact(
            'user', 
            'person', 
            'roles', 
            'aprendiz', 
            'ficha', 
            'programa', 
            'personalEmail', 
            'phone', 
            'esAprendiz'
        ));
    }

    /**
     * Actualiza información de contacto del usuario y foto de perfil según requerimiento RS-0002.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Reglas estrictas de validación de backend
        $request->validate([
            // Correo Personal: formato estricto RFC con regex para impedir sintaxis inválida (ej. doble @)
            'personal_email' => [
                'nullable',
                'string',
                'max:255',
                'email:rfc,strict',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
            ],
            // Teléfono: solo números y formato telefónico estándar (7 a 20 dígitos)
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9+\s\-()]{7,20}$/'
            ],
            // Personalización de foto de perfil (JPG, JPEG o PNG, máx 2MB)
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg',
                'max:2048'
            ],
        ], [
            'personal_email.email' => 'El formato del correo personal no es válido.',
            'personal_email.regex' => 'El correo personal contiene una sintaxis inválida (no se permiten múltiples @ ni caracteres no permitidos).',
            'phone.regex' => 'El teléfono de contacto debe contener un número válido (entre 7 y 20 dígitos).',
            'phone.max' => 'El teléfono de contacto no puede exceder los 20 caracteres.',
            'avatar.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'avatar.mimes' => 'La foto de perfil debe ser de formato JPG, JPEG o PNG.',
            'avatar.max' => 'La foto de perfil no debe superar los 2MB de tamaño.',
        ]);

        $cambiosRealizados = false;
        $avatarActualizado = false;

        // Obtener o crear entidad Person
        $person = $user->person;
        if (!$person) {
            $person = Person::create([
                'first_name' => $user->name,
                'sena_email' => $user->email,
            ]);
            $user->person_id = $person->id;
            $user->save();
        }

        // 2. Procesar Correo Personal (estricto)
        $nuevoPersonalEmail = $request->filled('personal_email') ? strtolower(trim($request->personal_email)) : null;
        if ($person->personal_email !== $nuevoPersonalEmail) {
            $person->personal_email = $nuevoPersonalEmail;
            $cambiosRealizados = true;
        }

        // 3. Procesar Teléfono (Phone)
        $nuevoTelefono = $request->filled('phone') ? trim($request->phone) : null;
        if ($person->phone !== $nuevoTelefono) {
            $person->phone = $nuevoTelefono;
            $cambiosRealizados = true;
        }

        // 4. Procesar nueva foto de perfil (Upload Avatar)
        if ($request->hasFile('avatar')) {
            if ($person->avatar && Storage::disk('public')->exists($person->avatar)) {
                Storage::disk('public')->delete($person->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $person->avatar = $path;
            $cambiosRealizados = true;
            $avatarActualizado = true;
        }

        // 5. Eliminar foto si se solicita
        if ($request->input('remove_avatar') == '1' && !empty($person->avatar)) {
            if (Storage::disk('public')->exists($person->avatar)) {
                Storage::disk('public')->delete($person->avatar);
            }
            $person->avatar = null;
            $cambiosRealizados = true;
            $avatarActualizado = true;
        }

        if ($person->isDirty()) {
            $person->save();
        }

        if (!$cambiosRealizados) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'info' => true,
                    'message' => 'No se detectaron modificaciones en tus datos de perfil.'
                ]);
            }
            return back()->with('info', 'No se detectaron modificaciones en tus datos de perfil.');
        }

        $mensajeExito = $avatarActualizado
            ? '¡Información de perfil y foto actualizadas exitosamente!'
            : '¡Información de perfil actualizada exitosamente!';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $mensajeExito,
                'avatar_url' => $person->avatar ? asset('storage/' . $person->avatar) : null,
                'personal_email' => $person->personal_email,
                'phone' => $person->phone,
            ]);
        }

        return back()->with('success', $mensajeExito);
    }

    /**
     * Actualiza la contraseña del usuario previa verificación de la contraseña actual.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Debes ingresar tu contraseña actual.',
            'password.required' => 'Debes ingresar una nueva contraseña.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validar contraseña actual
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual ingresada no es correcta.']);
        }

        // Validar que la nueva contraseña no sea idéntica a la actual
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'La nueva contraseña debe ser diferente a la contraseña actual.']);
        }

        // Actualizar contraseña encriptada
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', '¡Tu contraseña ha sido cambiada exitosamente!');
    }
}
