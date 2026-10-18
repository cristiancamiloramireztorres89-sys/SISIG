<?php

namespace Modules\SISIG\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Configuracion extends Model
{
    protected $table = 'sisig_configuraciones';

    protected $fillable = [
        'clave',
        'valor',
        'tipo',
        'grupo',
        'titulo',
        'descripcion',
        'orden',
    ];

    const CACHE_KEY = 'sisig_configuraciones_all';

    /**
     * Obtener el valor de una configuración por su clave.
     * Utiliza caché para máximo rendimiento.
     */
    public static function get(string $clave, $default = null)
    {
        try {
            $all = self::getAll();
            if (isset($all[$clave])) {
                $item = $all[$clave];
                $val = $item->valor;

                if ($item->tipo === 'boolean') {
                    return filter_var($val, FILTER_VALIDATE_BOOLEAN);
                }
                if ($item->tipo === 'number') {
                    return is_numeric($val) ? (float) $val : $default;
                }
                return $val !== null && $val !== '' ? $val : $default;
            }
        } catch (\Throwable $th) {
            // En caso de que la tabla aún no exista o BD desconectada
            return $default;
        }

        return $default;
    }

    /**
     * Establecer o actualizar el valor de una configuración.
     */
    public static function set(string $clave, $valor): bool
    {
        $updated = self::where('clave', $clave)->update(['valor' => $valor]);
        self::clearCache();
        return (bool) $updated;
    }

    /**
     * Obtener todas las configuraciones indexadas por clave (cacheadas).
     */
    public static function getAll()
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            if (!Schema::hasTable('sisig_configuraciones')) {
                return collect();
            }
            return self::orderBy('orden', 'asc')->get()->keyBy('clave');
        });
    }

    /**
     * Obtener configuraciones agrupadas por su categoría.
     */
    public static function getGrouped()
    {
        return self::orderBy('orden', 'asc')->get()->groupBy('grupo');
    }

    /**
     * Limpiar la caché de configuraciones del sistema.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
