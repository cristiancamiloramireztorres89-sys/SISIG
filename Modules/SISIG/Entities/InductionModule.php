<?php

namespace Modules\SISIG\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InductionModule extends Model
{
    use HasFactory;

    /**
     * Tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'induction_modules';

    /**
     * Atributos asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'canvas_state',
    ];

    /**
     * Casting de atributos para el parseo automático entre JSON y array nativo.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'canvas_state' => 'array',
    ];
}

