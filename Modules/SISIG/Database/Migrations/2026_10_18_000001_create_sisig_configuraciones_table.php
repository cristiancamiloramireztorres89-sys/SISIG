<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sisig_configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 100)->unique();
            $table->longText('valor')->nullable();
            $table->string('tipo', 50)->default('text'); // text, textarea, boolean, file, color, number
            $table->string('grupo', 50)->default('general'); // identidad_visual, restricciones_editor, politicas_sistema
            $table->string('titulo', 255);
            $table->text('descripcion')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // Valores por defecto del sistema
        $now = now();
        $configuraciones = [
            // 1. Identidad Visual
            [
                'clave' => 'app_name',
                'valor' => 'SISIG',
                'tipo' => 'text',
                'grupo' => 'identidad_visual',
                'titulo' => 'Nombre Visible del Sistema',
                'descripcion' => 'Nombre que se muestra en el encabezado, sidebar y pestañas del navegador.',
                'orden' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'app_slogan',
                'valor' => 'Sistema Integral de Seguimiento e Inducción a la Gestión',
                'tipo' => 'text',
                'grupo' => 'identidad_visual',
                'titulo' => 'Lema / Descripción Institucional',
                'descripcion' => 'Subtítulo o lema que acompaña el nombre del sistema en portales y encabezados.',
                'orden' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'app_logo',
                'valor' => null,
                'tipo' => 'file',
                'grupo' => 'identidad_visual',
                'titulo' => 'Logotipo Principal del Sistema',
                'descripcion' => 'Imagen institucional en formato PNG, JPG o SVG. Si está vacío, se usa el logo oficial de SENA.',
                'orden' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'app_favicon',
                'valor' => null,
                'tipo' => 'file',
                'grupo' => 'identidad_visual',
                'titulo' => 'Favicon (Icono del Navegador)',
                'descripcion' => 'Icono que aparece en la pestaña del navegador (formato .ico, .png).',
                'orden' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'color_primario',
                'valor' => '#39A900',
                'tipo' => 'color',
                'grupo' => 'identidad_visual',
                'titulo' => 'Color Primario de Acento',
                'descripcion' => 'Color representativo para botones de acción, bordes activos y elementos destacados (Por defecto: Verde SENA).',
                'orden' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'color_sidebar',
                'valor' => '#001A29',
                'tipo' => 'color',
                'grupo' => 'identidad_visual',
                'titulo' => 'Color de Fondo del Sidebar',
                'descripcion' => 'Color oscuro predominante para la barra de navegación lateral.',
                'orden' => 6,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 2. Restricciones para Editores
            [
                'clave' => 'editor_puede_eliminar_quizzes',
                'valor' => '0',
                'tipo' => 'boolean',
                'grupo' => 'restricciones_editor',
                'titulo' => 'Permitir a los Editores Eliminar Evaluaciones',
                'descripcion' => 'Si está desactivado, solo el Administrador podrá borrar quizzes/evaluaciones del sistema.',
                'orden' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'editor_puede_editar_quizzes_con_respuestas',
                'valor' => '0',
                'tipo' => 'boolean',
                'grupo' => 'restricciones_editor',
                'titulo' => 'Permitir Modificar Evaluaciones con Intentos Registrados',
                'descripcion' => 'Bloquea la edición de preguntas u opciones en evaluaciones que ya fueron presentadas por aprendices para proteger la validez de los resultados.',
                'orden' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'editor_requiere_aprobacion_publicacion',
                'valor' => '0',
                'tipo' => 'boolean',
                'grupo' => 'restricciones_editor',
                'titulo' => 'Requerir Aprobación para Publicar Contenido',
                'descripcion' => 'Si se activa, los contenidos o evaluaciones creadas por editores quedarán en revisión hasta que el administrador los apruebe.',
                'orden' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'editor_limite_quizzes_por_modulo',
                'valor' => '10',
                'tipo' => 'number',
                'grupo' => 'restricciones_editor',
                'titulo' => 'Límite Máximo de Evaluaciones por Módulo',
                'descripcion' => 'Cantidad tope de evaluaciones que un editor puede asociar a un mismo módulo formativo.',
                'orden' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 3. Políticas Generales del Sistema
            [
                'clave' => 'examen_tiempo_defecto',
                'valor' => '30',
                'tipo' => 'number',
                'grupo' => 'politicas_sistema',
                'titulo' => 'Tiempo Límite por Defecto (Minutos)',
                'descripcion' => 'Duración sugerida al crear nuevas evaluaciones si el creador no especifica un tiempo.',
                'orden' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'examen_advertencias_maximas',
                'valor' => '3',
                'tipo' => 'number',
                'grupo' => 'politicas_sistema',
                'titulo' => 'Tolerancia de Infracciones Anti-Trampa',
                'descripcion' => 'Número de advertencias por cambio de ventana o salida de pantalla completa antes de bloquear o anular el examen del aprendiz.',
                'orden' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'examen_mostrar_resultados_inmediatos',
                'valor' => '1',
                'tipo' => 'boolean',
                'grupo' => 'politicas_sistema',
                'titulo' => 'Mostrar Resultados Inmediatos al Aprendiz',
                'descripcion' => 'Permite que el aprendiz vea su puntuación y retroalimentación automáticamente al enviar la evaluación.',
                'orden' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'mensaje_mantenimiento_activo',
                'valor' => '0',
                'tipo' => 'boolean',
                'grupo' => 'politicas_sistema',
                'titulo' => 'Banner de Mantenimiento / Aviso Global',
                'descripcion' => 'Activa una barra de advertencia superior visible para todos los roles.',
                'orden' => 4,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'clave' => 'mensaje_mantenimiento_texto',
                'valor' => 'Atención: La plataforma entrará en mantenimiento programado. Por favor guarda tus actividades.',
                'tipo' => 'text',
                'grupo' => 'politicas_sistema',
                'titulo' => 'Texto del Aviso Global',
                'descripcion' => 'Mensaje informativo que se mostrará en el banner superior cuando esté activo.',
                'orden' => 5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('sisig_configuraciones')->insert($configuraciones);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sisig_configuraciones');
    }
};
