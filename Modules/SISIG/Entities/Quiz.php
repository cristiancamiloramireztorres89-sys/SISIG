<?php

namespace Modules\SISIG\Entities;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'sisig_quices';
    protected $primaryKey = 'id_quiz';
    
    protected $fillable = [
        'modulo_id',
        'titulo',
        'puntaje_minimo',
        'numero_preguntas',
        'numero_intentos',
        'tiempo_minutos',
        'fecha_limite',
        'activo'
    ];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class, 'modulo_id');
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class, 'id_quiz', 'id_quiz');
    }
}
