<?php

namespace Modules\SISIG\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PlataformaConfiguracion extends Model
{
    protected $table = 'sisig_plataforma_configuraciones';

    protected $fillable = [
        'nombre_plataforma',
        'logo_path',
        'terminos_condiciones',
        'politicas_privacidad',
        'editor_bloquear_edicion_critica',
        'editor_limitar_creacion_modulos',
        'editor_restringir_eliminacion_quices',
        'editor_requerir_aprobacion',
        'updated_by',
    ];

    protected $casts = [
        'editor_bloquear_edicion_critica' => 'boolean',
        'editor_limitar_creacion_modulos' => 'boolean',
        'editor_restringir_eliminacion_quices' => 'boolean',
        'editor_requerir_aprobacion' => 'boolean',
    ];

    /**
     * Retorna la instancia de configuración global activa (patrón Singleton).
     */
    public static function getActual(): self
    {
        return static::firstOrCreate([], [
            'nombre_plataforma' => 'SISIG - SENA Empresa',
            'logo_path' => 'modules/sisig/images/logo.png',
            'editor_bloquear_edicion_critica' => true,
            'editor_limitar_creacion_modulos' => false,
            'editor_restringir_eliminacion_quices' => true,
            'editor_requerir_aprobacion' => false,
            'terminos_condiciones' => '<h2>Términos y Condiciones de Uso</h2><p>El acceso a la plataforma SISIG está reservado a los aprendices, editores y administradores de SENA Empresa. Las credenciales son personales e intransferibles.</p>',
            'politicas_privacidad' => '<h2>Políticas de Tratamiento de Información</h2><p>En cumplimiento con las directrices institucionales del SENA, los datos recopilados durante las evaluaciones serán de uso académico y de auditoría interna.</p>'
        ]);
    }

    /**
     * Relación con el usuario administrador que realizó el último ajuste.
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
