<?php

namespace Modules\SISIG\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Modules\SICA\Entities\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class GestionUsersController extends Controller
{
    /**
     * Muestra la lista y gestión de usuarios del módulo SISIG.
     */
    public function index(Request $request)
    {
        $query = User::withTrashed()->with(['person', 'roles']);

        // Filtro de búsqueda
        if ($request->filled('search')) {
            $s = trim($request->input('search'));
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('nickname', 'like', "%{$s}%")
                  ->orWhereHas('person', fn($qp) => $qp->where('first_name', 'like', "%{$s}%")
                      ->orWhere('first_last_name', 'like', "%{$s}%")
                      ->orWhere('second_last_name', 'like', "%{$s}%")
                      ->orWhere('document_number', 'like', "%{$s}%"));
            });
        }

        // Filtro por rol SISIG
        if ($request->filled('role')) {
            $r = $request->input('role');
            if ($r === 'admin') {
                $query->whereHas('roles', fn($qr) => $qr->whereIn('slug', ['admin_sisig', 'sisig.admin']));
            } elseif ($r === 'editor') {
                $query->whereHas('roles', fn($qr) => $qr->whereIn('slug', ['editor_sisig']));
            } elseif ($r === 'aprendiz') {
                $query->where(fn($qa) => $qa->whereHas('roles', fn($qr) => $qr->whereIn('slug', ['aprendiz_sisig', 'sisig.aprendiz']))->orWhere('nickname', 'aprendiz'));
            } elseif ($r === 'sin_rol') {
                $query->whereDoesntHave('roles');
            }
        }

        // Filtro por estado
        if ($request->filled('status')) {
            $request->input('status') === 'activos' ? $query->whereNull('deleted_at') : $query->whereNotNull('deleted_at');
        }

        $users = $query->orderBy('name')->get();

        // KPIs de usuarios
        $totalUsuarios = User::withTrashed()->count();
        $totalActivos = User::count();
        $totalInactivos = User::onlyTrashed()->count();
        $totalAdmins = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['admin_sisig', 'sisig.admin']))->count();
        $totalEditores = User::whereHas('roles', fn($q) => $q->whereIn('slug', ['editor_sisig']))->count();
        $totalAprendices = User::where(fn($qa) => $qa->whereHas('roles', fn($qr) => $qr->whereIn('slug', ['aprendiz_sisig', 'sisig.aprendiz']))->orWhere('nickname', 'aprendiz'))->count();

        // Totales globales para cálculo de avance
        $totalSecciones = DB::table('sisig_secciones')->count();
        $totalQuices = DB::table('sisig_quices')->where('activo', 1)->count();

        // Mapeo optimizado de usuarios
        $usersData = $users->map(function ($u) use ($totalSecciones) {
            $roleInfo = $this->resolveSisigRole($u);

            $isAprendiz = ($roleInfo['slug'] === 'aprendiz_sisig');
            $quicesAprobados = 0;
            $progresoPromedio = 0;
            $ficha = null;

            if ($isAprendiz) {
                $quicesAprobados = DB::table('sisig_resultados')
                    ->join('sisig_quices', 'sisig_resultados.id_quiz', '=', 'sisig_quices.id_quiz')
                    ->where('sisig_resultados.user_id', $u->id)
                    ->where('sisig_quices.activo', 1)
                    ->where('sisig_resultados.aprobado', 1)
                    ->distinct()
                    ->count('sisig_resultados.id_quiz');

                $progresoPromedio = $totalSecciones > 0
                    ? round((float) (DB::table('progreso_aprendiz_seccion')->where('user_id', $u->id)->avg('porcentaje_visto') ?? 0))
                    : 0;

                $ficha = $u->person_id ? DB::table('apprentices')->where('person_id', $u->person_id)->value('course_id') : null;
            }

            return (object) [
                'id'                => $u->id,
                'name'              => $u->name,
                'full_name'         => $u->full_name,
                'email'             => $u->email,
                'nickname'          => $u->nickname,
                'first_name'        => $u->person->first_name ?? '',
                'first_last_name'   => $u->person->first_last_name ?? '',
                'second_last_name'  => $u->person->second_last_name ?? '',
                'document_type'     => $u->person->document_type ?? 'Cédula de ciudadanía',
                'document_number'   => $u->person->document_number ?? 'N/A',
                'person'            => $u->person,
                'avatar'            => $u->person ? $u->person->avatar : null,
                'rol_slug'          => $roleInfo['slug'],
                'rol_nombre'        => $roleInfo['name'],
                'rol_badge'         => $roleInfo['badge'],
                'ficha'             => $ficha,
                'quices_aprobados'  => $quicesAprobados,
                'progreso_promedio' => $progresoPromedio,
                'is_active'         => ($u->deleted_at === null),
                'has_activity'      => $this->hasUserActivity($u->id),
                'created_at'        => $u->created_at,
            ];
        });

        return view('sisig::admin.GestionUsers', compact(
            'usersData', 'totalUsuarios', 'totalActivos', 'totalInactivos',
            'totalAdmins', 'totalEditores', 'totalAprendices', 'totalSecciones', 'totalQuices'
        ));
    }

    /**
     * Registra un nuevo usuario y su persona vinculada en el sistema.
     */
    public function store(Request $request)
    {
        $request->validate($this->userRules(), $this->userMessages());

        DB::beginTransaction();
        try {
            $person = $this->syncPerson($request);

            $nickname = $request->filled('nickname')
                ? trim($request->nickname)
                : strtolower(explode('@', $request->email)[0]);

            if (User::withTrashed()->where('nickname', $nickname)->exists()) {
                $nickname .= '_' . rand(10, 99);
            }

            $user = User::create([
                'name'      => trim($request->first_name . ' ' . $request->first_last_name . ' ' . ($request->second_last_name ?? '')),
                'nickname'  => $nickname,
                'person_id' => $person->id,
                'email'     => trim($request->email),
                'password'  => Hash::make($request->password),
            ]);

            $rolNombre = $this->syncRole($user, $request->role_slug);
            $this->syncApprenticeFicha($person->id, $request->ficha, $request->role_slug);
            $this->logActivity('usuario_creado', "Se registró al usuario {$user->name} con rol: {$rolNombre}");

            DB::commit();
            return redirect()->route('sisig.admin.users.index')
                ->with('success', "¡Usuario \"{$user->name}\" creado exitosamente con rol: {$rolNombre}!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error al registrar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Actualiza información personal, credenciales y rol del usuario.
     */
    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $request->validate($this->userRules($user->id), $this->userMessages());

        DB::beginTransaction();
        try {
            $person = $this->syncPerson($request, $user->person);
            if (!$user->person_id) {
                $user->person_id = $person->id;
            }

            $user->name = trim($request->first_name . ' ' . $request->first_last_name . ' ' . ($request->second_last_name ?? ''));
            $user->email = trim($request->email);
            if ($request->filled('nickname')) $user->nickname = trim($request->nickname);
            if ($request->filled('password')) $user->password = Hash::make($request->password);
            $user->save();

            $rolNombre = $this->syncRole($user, $request->role_slug);
            $this->syncApprenticeFicha($person->id, $request->ficha, $request->role_slug);
            $this->logActivity('usuario_actualizado', "Se actualizaron los datos y rol de {$user->name}");

            DB::commit();
            return redirect()->route('sisig.admin.users.index')
                ->with('success', "¡Datos del usuario \"{$user->name}\" actualizados correctamente!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Activa o desactiva (suspende) la cuenta mediante SoftDeletes.
     */
    public function toggleStatus($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Operación denegada: No puedes desactivar tu propia cuenta.');
        }

        $user->trashed() ? $user->restore() : $user->delete();
        $accion = $user->trashed() ? 'desactivó' : 'reactivó';

        $this->logActivity("usuario_{$accion}", "Se {$accion} la cuenta de usuario de {$user->name}");

        $msg = $user->trashed()
            ? "La cuenta de {$user->name} ha sido desactivada."
            : "¡La cuenta de {$user->name} ha sido activada exitosamente!";

        return redirect()->route('sisig.admin.users.index')->with('success', $msg);
    }

    /**
     * Actualiza el rol del usuario (método de conveniencia).
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role_slug' => 'nullable|string|in:admin_sisig,editor_sisig,aprendiz_sisig,ninguno']);
        $rolNombre = $this->syncRole($user, $request->role_slug);
        $this->logActivity('rol_actualizado', "Se actualizó el rol de {$user->name} a: {$rolNombre}");

        return redirect()->route('sisig.admin.users.index')
            ->with('success', "¡Rol de {$user->name} actualizado a: {$rolNombre}!");
    }

    /**
     * Elimina permanentemente a un usuario solo si NO tiene ninguna actividad registrada en el sistema.
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        // 1. Evitar auto-eliminación
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'Operación denegada: No puedes eliminar tu propia cuenta de usuario.');
        }

        // 2. Verificar si tiene actividad registrada
        if ($this->hasUserActivity($user->id)) {
            return redirect()->back()->with('error', "Operación denegada: El usuario \"{$user->name}\" no puede ser eliminado porque cuenta con actividad o historial registrado en la plataforma. Para bloquear su acceso preservando la trazabilidad, puedes desactivar su cuenta.");
        }

        DB::beginTransaction();
        try {
            // 3. Desvincular roles asignados
            $user->roles()->detach();

            // 4. Si tiene registro de aprendiz sin notas ni evaluaciones, retirarlo
            if ($user->person_id && Schema::hasTable('apprentices')) {
                DB::table('apprentices')->where('person_id', $user->person_id)->delete();
            }

            $userName = $user->name;

            // 5. Eliminar permanentemente al usuario
            $user->forceDelete();

            $this->logActivity('usuario_eliminado', "Se eliminó permanentemente al usuario {$userName} (sin actividad registrada)");

            DB::commit();
            return redirect()->route('sisig.admin.users.index')
                ->with('success', "¡El usuario \"{$userName}\" fue eliminado exitosamente del sistema!");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al eliminar el usuario: ' . $e->getMessage());
        }
    }

    /**
     * Verifica si un usuario tiene actividad registrada (evaluaciones, progreso, o bitácora).
     */
    public function hasUserActivity(int $userId): bool
    {
        $hasResultados = DB::table('sisig_resultados')->where('user_id', $userId)->exists();
        $hasProgreso = DB::table('progreso_aprendiz_seccion')->where('user_id', $userId)->exists();
        $hasActividad = DB::table('actividadreciente')->where('user_id', $userId)->exists();

        return ($hasResultados || $hasProgreso || $hasActividad);
    }

    /**
     * Retorna los datos de un usuario en JSON para el modal de edición.
     */
    public function show($id)
    {
        $user = User::withTrashed()->with(['person', 'roles'])->findOrFail($id);
        $role = $this->resolveSisigRole($user);
        $ficha = ($user->person_id && $role['slug'] === 'aprendiz_sisig') 
            ? DB::table('apprentices')->where('person_id', $user->person_id)->value('course_id') 
            : null;

        return response()->json([
            'id'               => $user->id,
            'name'             => $user->name,
            'email'            => $user->email,
            'nickname'         => $user->nickname,
            'first_name'       => $user->person->first_name ?? '',
            'first_last_name'  => $user->person->first_last_name ?? '',
            'second_last_name' => $user->person->second_last_name ?? '',
            'document_type'    => $user->person->document_type ?? 'Cédula de ciudadanía',
            'document_number'  => $user->person->document_number ?? '',
            'rol_slug'         => $role['slug'] ?? 'ninguno',
            'ficha'            => $ficha,
            'avatar_url'       => ($user->person && $user->person->avatar) ? asset('storage/' . $user->person->avatar) : null,
            'is_active'        => ($user->deleted_at === null),
        ]);
    }

    // =========================================================================
    // MÉTODOS AUXILIARES REUTILIZABLES (DRY & SOLID)
    // =========================================================================

    private function userRules(?int $userId = null): array
    {
        return [
            'document_type'    => 'required|string|max:50',
            'document_number'  => 'required|string|max:30',
            'first_name'       => 'required|string|max:60',
            'first_last_name'  => 'required|string|max:60',
            'second_last_name' => 'nullable|string|max:60',
            'email'            => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($userId)],
            'nickname'         => ['nullable', 'string', 'max:50', Rule::unique('users', 'nickname')->ignore($userId)],
            'password'         => $userId ? 'nullable|string|min:6' : 'required|string|min:6',
            'role_slug'        => 'nullable|string|in:admin_sisig,editor_sisig,aprendiz_sisig,ninguno',
            'ficha'            => 'nullable|string|max:30',
        ];
    }

    private function userMessages(): array
    {
        return [
            'email.unique'             => 'Este correo electrónico ya se encuentra registrado en el sistema.',
            'nickname.unique'          => 'Este nombre de usuario / alias ya está en uso.',
            'password.min'             => 'La contraseña debe tener al menos 6 caracteres.',
            'first_name.required'      => 'El nombre es obligatorio.',
            'first_last_name.required' => 'El primer apellido es obligatorio.',
            'document_number.required' => 'El número de documento es obligatorio.',
        ];
    }

    private function syncPerson(Request $request, ?Person $person = null): Person
    {
        $data = [
            'document_type'    => $request->document_type,
            'document_number'  => trim($request->document_number),
            'first_name'       => mb_strtoupper(trim($request->first_name)),
            'first_last_name'  => mb_strtoupper(trim($request->first_last_name)),
            'second_last_name' => $request->filled('second_last_name') ? mb_strtoupper(trim($request->second_last_name)) : null,
            'personal_email'   => trim($request->email),
            'sena_email'       => trim($request->email),
        ];

        if ($person) {
            $person->update($data);
            return $person;
        }

        return Person::updateOrCreate(['document_number' => $data['document_number']], $data);
    }

    private function syncRole(User $user, ?string $roleSlug): string
    {
        $sisigRoles = ['admin_sisig', 'editor_sisig', 'aprendiz_sisig', 'sisig.admin', 'sisig.aprendiz'];
        $roleIds = DB::table('roles')->whereIn('slug', $sisigRoles)->pluck('id')->toArray();
        $user->roles()->detach($roleIds);

        if (!empty($roleSlug) && $roleSlug !== 'ninguno') {
            $role = DB::table('roles')->where('slug', $roleSlug)->first();
            if ($role) {
                $user->roles()->attach($role->id);
                return $role->name;
            }
        }
        return 'Sin Rol en SISIG';
    }

    private function syncApprenticeFicha(int $personId, ?string $ficha, ?string $roleSlug = null): void
    {
        if ($roleSlug === 'aprendiz_sisig' && !empty($ficha)) {
            DB::table('apprentices')->updateOrInsert(
                ['person_id' => $personId],
                ['course_id' => trim($ficha), 'apprentice_status' => 'En formación', 'updated_at' => now()]
            );
        } elseif ($roleSlug && $roleSlug !== 'aprendiz_sisig') {
            DB::table('apprentices')->where('person_id', $personId)->delete();
        }
    }

    private function resolveSisigRole(User $u): array
    {
        if ($u->hasRole('admin_sisig') || $u->hasRole('sisig.admin')) {
            return ['slug' => 'admin_sisig', 'name' => 'Admin SISIG', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200'];
        }
        if ($u->hasRole('editor_sisig')) {
            return ['slug' => 'editor_sisig', 'name' => 'Editor SISIG', 'badge' => 'bg-sky-50 text-sky-700 border-sky-200'];
        }
        if ($u->hasRole('aprendiz_sisig') || $u->hasRole('sisig.aprendiz') || $u->nickname === 'aprendiz') {
            return ['slug' => 'aprendiz_sisig', 'name' => 'Aprendiz SISIG', 'badge' => 'bg-teal-50 text-teal-700 border-teal-200'];
        }
        return ['slug' => null, 'name' => 'Sin Rol Asignado', 'badge' => 'bg-slate-100 text-slate-600 border-slate-200'];
    }

    private function logActivity(string $eventType, string $message): void
    {
        try {
            DB::table('actividadreciente')->insert([
                'user_id'     => Auth::id(),
                'tipo_evento' => $eventType,
                'mensaje'     => $message,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } catch (\Exception $e) {}
    }
}
