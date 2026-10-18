<?php

namespace Modules\SISIG\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class InfraccionQuiz extends Model
{
    protected $table = 'infraccionesquiz';
    protected $primaryKey = 'id_infraccion';

    protected $fillable = [
        'id_resultado',
        'user_id',
        'id_quiz',
        'tipo_infraccion',
        'tipo_evento',
        'detalle',
        'marca_tiempo',
        'navegador_detalles',
        'ip_address'
    ];

    protected $casts = [
        'marca_tiempo' => 'datetime',
    ];

    /**
     * Relación con el Resultado/Intento al que pertenece la infracción.
     */
    public function resultado()
    {
        return $this->belongsTo(Resultado::class, 'id_resultado', 'id_resultado');
    }

    /**
     * Relación con el Quiz donde ocurrió el incidente.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'id_quiz', 'id_quiz');
    }

    /**
     * Relación con el Usuario involucrado.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
