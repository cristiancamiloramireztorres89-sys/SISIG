<?php

namespace Modules\SISIG\Entities;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'sisig_modulos';
    
    protected $fillable = [
        'titulo',
        'norma',
        'estado',
        'descripcion',
        'contenido_html',
        'progreso',
        'icon_class',
        'icon_bg',
        'border_class'
    ];

    public function editores()
    {
        return $this->belongsToMany(\App\Models\User::class, 'editor_modulos', 'modulo_id', 'user_id');
    }

    public function quices()
    {
        return $this->hasMany(Quiz::class, 'modulo_id', 'id');
    }
}
