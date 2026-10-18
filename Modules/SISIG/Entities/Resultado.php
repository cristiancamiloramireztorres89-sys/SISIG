<?php

namespace Modules\SISIG\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Resultado extends Model
{
    protected $table = 'sisig_resultados';
    protected $primaryKey = 'id_resultado';

    protected $fillable = [
        'user_id',
        'id_quiz',
        'intento',
        'puntaje',
        'aprobado',
        'fue_anulado',
        'motivo_anulacion',
        'fecha_presentacion',
        'tiempo_segundos_empleado',
        'infracciones_detectadas'
    ];

    protected $casts = [
        'aprobado' => 'boolean',
        'fue_anulado' => 'boolean',
        'puntaje' => 'float',
        'fecha_presentacion' => 'datetime',
        'tiempo_segundos_empleado' => 'integer',
        'infracciones_detectadas' => 'integer'
    ];

    /**
     * Relación con el Quiz al que pertenece este intento.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'id_quiz', 'id_quiz');
    }

    /**
     * Relación con el Usuario/Aprendiz que presentó el intento.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Infracciones de seguridad registradas durante este intento.
     */
    public function infracciones()
    {
        return $this->hasMany(InfraccionQuiz::class, 'id_resultado', 'id_resultado');
    }
}
