<?php

namespace Modules\SISIG\Entities;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table = 'sisig_preguntas';
    protected $primaryKey = 'id_pregunta';
    
    protected $fillable = [
        'id_quiz',
        'pregunta',
        'tipo',
        'puntaje',
        'retroalimentacion',
        'configuracion_json'
    ];

    protected $casts = [
        'configuracion_json' => 'array',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'id_quiz', 'id_quiz');
    }

    public function opciones()
    {
        return $this->hasMany(Opcion::class, 'id_pregunta', 'id_pregunta');
    }
}
