<?php

namespace Modules\SISIG\Entities;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'sisig_modulos';
    
    protected $fillable = [
        'titulo',
        'title',
        'norma',
        'estado',
        'descripcion',
        'imagen',
        'fondo_imagen',
        'fondo_opacidad',
        'contenido_html',
        'canvas_state',
        'progreso',
        'icon_class',
        'icon_bg',
        'border_class'
    ];

    /**
     * Casters automáticos de Eloquent para transformar el estado del lienzo
     */
    protected $casts = [
        'canvas_state' => 'array',
    ];

    /**
     * Accesor para permitir invocar 'title' o 'titulo' indistintamente.
     */
    public function getTitleAttribute()
    {
        return $this->attributes['titulo'] ?? null;
    }

    /**
     * Mutador para sincronizar 'title' hacia la columna 'titulo'.
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['titulo'] = $value;
    }

    public function editores()
    {
        return $this->belongsToMany(\App\Models\User::class, 'editor_modulos', 'modulo_id', 'user_id');
    }

    public function quices()
    {
        return $this->hasMany(Quiz::class, 'modulo_id', 'id');
    }

    /**
     * Determina si el módulo tiene entidades, evaluaciones, editores o contenidos asociados.
     */
    public function tieneAsociaciones(): bool
    {
        // 1. Quices / Evaluaciones asociadas
        if (\Illuminate\Support\Facades\DB::table('sisig_quices')->where('modulo_id', $this->id)->exists()) {
            return true;
        }

        // 2. Editores asignados en tabla pivote
        if (\Illuminate\Support\Facades\DB::table('editor_modulos')->where('modulo_id', $this->id)->exists()) {
            return true;
        }

        // 3. Contenido interactivo guardado en lienzo o HTML elaborado
        if (!empty($this->canvas_state)) {
            return true;
        }
        if (!empty($this->contenido_html) && trim(strip_tags($this->contenido_html)) !== '') {
            return true;
        }

        return false;
    }

    /**
     * Retorna descripción de los elementos asociados para informar al usuario.
     */
    public function getMotivoAsociaciones(): string
    {
        $motivos = [];

        $quices = \Illuminate\Support\Facades\DB::table('sisig_quices')->where('modulo_id', $this->id)->count();
        if ($quices > 0) {
            $motivos[] = $quices . ' evaluación(es)';
        }

        $editores = \Illuminate\Support\Facades\DB::table('editor_modulos')->where('modulo_id', $this->id)->count();
        if ($editores > 0) {
            $motivos[] = $editores . ' editor(es) asignado(s)';
        }

        if (!empty($this->canvas_state) || (!empty($this->contenido_html) && trim(strip_tags($this->contenido_html)) !== '')) {
            $motivos[] = 'contenido interactivo guardado en el lienzo';
        }

        return count($motivos) > 0 ? implode(', ', $motivos) : 'elementos asociados';
    }
}
