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
}
