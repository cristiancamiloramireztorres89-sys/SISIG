<?php

namespace Modules\SISIG\Http\Controllers\Perfilusers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class PerfilusersController extends Controller
{
    /**
     * Muestra la vista de perfil reutilizable para cualquier rol autenticado en SISIG.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->load('person', 'roles');
        $person = $user->person;
        $roles = $user->roles;

        // Si es aprendiz, consultar datos de su ficha / formación
        $aprendiz = null;
        if ($person && Schema::hasTable('apprentices')) {
            $aprendiz = DB::table('apprentices')
                ->where('person_id', $person->id)
                ->first();
        }

        return view('sisig::perfil.perfilusers', compact('user', 'person', 'roles', 'aprendiz'));
    }

    /**
     * Actualiza información de contacto del usuario y foto de perfil.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'personal_email' => 'nullable|email|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'email.required' => 'El correo institucional es obligatorio.',
            'email.email' => 'El formato del correo institucional no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado por otro usuario.',
            'personal_email.email' => 'El formato del correo personal no es válido.',
            'avatar.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'avatar.mimes' => 'La foto de perfil debe ser de formato JPG, JPEG, PNG o WEBP.',
            'avatar.max' => 'La foto de perfil no debe superar los 2MB de tamaño.',
        ]);

        $cambiosRealizados = false;
        $avatarActualizado = false;

        $nuevoEmail = trim($request->email);
        if ($user->email !== $nuevoEmail) {
            $user->email = $nuevoEmail;
            $user->save();
            $cambiosRealizados = true;
        }

        if ($user->person) {
            $person = $user->person;
            $nuevoPersonalEmail = $request->filled('personal_email') ? trim($request->personal_email) : null;

            if ($person->sena_email !== $nuevoEmail) {
                $person->sena_email = $nuevoEmail;
                $cambiosRealizados = true;
            }

            if ($person->personal_email !== $nuevoPersonalEmail) {
                $person->personal_email = $nuevoPersonalEmail;
                $cambiosRealizados = true;
            }

            // Procesar nueva foto de perfil
            if ($request->hasFile('avatar')) {
                if ($person->avatar && Storage::disk('public')->exists($person->avatar)) {
                    Storage::disk('public')->delete($person->avatar);
                }
                $path = $request->file('avatar')->store('avatars', 'public');
                $person->avatar = $path;
                $cambiosRealizados = true;
                $avatarActualizado = true;
            }

            // Eliminar foto si se solicita
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
        }

        if (!$cambiosRealizados) {
            return back()->with('info', 'No se detectaron cambios: los datos ya se encuentran actualizados.');
        }

        $mensajeExito = $avatarActualizado
            ? '¡Información de perfil y foto actualizadas exitosamente!'
            : '¡Información de perfil actualizada exitosamente!';

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

        // Validar que la nueva contraseña no sea exactamente la misma
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'La nueva contraseña debe ser diferente a la contraseña actual.']);
        }

        // Actualizar contraseña encriptada
        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', '¡Tu contraseña ha sido cambiada exitosamente!');
    }
}
