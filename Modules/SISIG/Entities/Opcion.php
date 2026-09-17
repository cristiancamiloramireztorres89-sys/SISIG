<?php

namespace Modules\SISIG\Entities;

use Illuminate\Database\Eloquent\Model;

class Opcion extends Model
{
    protected $table = 'sisig_opciones';
    protected $primaryKey = 'id_opcion';
    
    protected $fillable = [
        'id_pregunta',
        'opcion',
        'es_correcta'
    ];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'id_pregunta', 'id_pregunta');
    }
}
