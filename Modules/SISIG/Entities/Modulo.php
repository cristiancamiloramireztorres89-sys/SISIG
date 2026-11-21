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
}
