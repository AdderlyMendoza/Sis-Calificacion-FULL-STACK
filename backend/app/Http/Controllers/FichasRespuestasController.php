<?php

namespace App\Http\Controllers;

use App\Models\FichasRespuestas;
use App\Models\Ponderacion;
use App\Models\FichasIdentificacion;
use App\Models\RespuestasCorrectas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;




class FichasRespuestasController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    ///////////////////////////////////////////////////////// SUBIDA DE ARCHIVOS Y GUARDADO DE DATOS EN LA DB ///////////////////////////////////////////

    public function frRespPostulantes(Request $request)
    {
        Log::info($request->all());

        // Validar los archivos subidos
        $request->validate([
            'files.*' => 'required|file|mimetypes:text/plain,application/octet-stream',
            'frArea.*' => 'required'
        ]);

        // Obtener el área seleccionada
        $area = $request->input('frArea');  // Ahora capturamos el valor de frArea
        Log::info("AREA ELEGIDA -fr RESPUESTAS POSTULANTES:", [$area]);

        // Procesar cada archivo subido
        foreach ($request->file('files') as $file) {
            $this->procesarFile($file, $area);
        }

        return response()->json(['message' => 'Datos cargados con éxito']);
    }

    /**
     * Procesa un archivo y lo almacena en la base de datos.
     *
     * @param \Illuminate\Http\UploadedFile $file
     */
    private function procesarFile($file, $area)
    {
        // Obtener el nombre original del archivo
        $originalName = $file->getClientOriginalName();

        // Definir la ruta donde quieres almacenar el archivo
        $destinationPath = storage_path('app/fichasRespuestas');

        // Mover el archivo al directorio deseado con su nombre original
        $file->move($destinationPath, $originalName);

        // Leer el contenido del archivo
        $data = file_get_contents($destinationPath . '/' . $originalName);

        $lines = explode("\n", $data);

        foreach ($lines as $line) {
            if (trim($line) !== '') {
                $this->extractAndSaveData($line, $area);
            }
        }
    }

    /**
     * Extrae datos de una línea y los guarda en la base de datos.
     * 
     * @param string $line
     * 
     */

    private function extractAndSaveData($line, $area)
    {
        // Extraer campos según el formato
        $camp1 = substr($line, 0, 21);
        $camp2 = substr(substr($line, 21, 8), 3, 5);
        $camp3 = substr(substr($line, 26, 9), 3, 5);
        $camp4 = substr($line, 38, 1);
        $campo5 = substr($line, 40);

        // Descomposición de campo5
        $litho = substr($campo5, 0, 6);
        $tipo = substr($campo5, 6, 1);
        $respuestas = substr($campo5, 7, 60);


        // Asignar un valor predeterminado para id_archivo
        $id_archivo = "no se xd";
        Log::info("DATOS PARA:", [$litho]);

        $puntaje = $this->obtenerPuntaje($respuestas, $tipo, $area); // 1 = ING , 2 = BIO, 3 = SOC


        // Validar datos antes de guardar
        if ($this->validarData($camp1, $camp2, $camp3)) {
            FichasRespuestas::create([
                'campo1' => $camp1,
                'campo2' => $camp2,
                'campo3' => $camp3,
                'campo4' => $camp4,
                'id_archivo' => $id_archivo,
                'litho' => $litho,
                'tipo' => $tipo,
                'respuestas' => $respuestas,
                'puntaje' => $puntaje,
                // falta area_id
                'id_proceso' => 1,
            ]);
        } else {
            Log::warning('Datos inválidos, no se guardaron:', [
                'campo1' => $camp1,
                'campo2' => $camp2,
                'campo3' => $camp3,
                'campo4' => $camp4,
                'id_archivo' => $id_archivo,
                'litho' => $litho,
                'tipo' => $tipo,
                'respuestas' => $respuestas,
                'puntaje' => $puntaje,
                // falta area_id
                'id_proceso' => 1,
            ]);
        }
    }

    private function validarData($camp1, $camp3, $dni)
    {
        return !is_null($camp1) && !is_null($camp3) && !is_null($dni);
    }

    /////////////////////////////////////////////////////////////// OBTENER PUNTAJE DE POSTULANTE ////////////////////////////////////////////////////////

    private function obtenerPuntaje($respuesta, $tipo, $areaId) // respuestas, tipo de examen y área
    {
        // Datos recibidos
        Log::info("areaId:", [$areaId]);
        Log::info("Tipo de prueba:", [$tipo]);
        Log::info("respuestas:", [$respuesta]);

        if ($areaId == 'ingenieria') { // PonderacionSeeder.php
            $extraeArea = 1;
        } else if ($areaId == 'biomedicas') {
            $extraeArea = 2;
        } else if ($areaId == 'sociales') {
            $extraeArea = 3;
        }

        $puntaje = 0;

        // $respuestasFijas = optional(RespuestasCorrectas::where('tipo', $tipo)->first())->respuestas; // Solo por tipo

        $respuestasFijas = optional( // tipo y area
            RespuestasCorrectas::where('tipo', $tipo)
                            ->where('area_id', $extraeArea)
                            ->first()
        )->respuestas;

        Log::info("respuestasFijas:", [$respuestasFijas]);

        // Si no hay respuestas fijas, retornar 0
        if (!$respuestasFijas) {
            return 0;
        }

        // Obtener las ponderaciones para el área
        $ponderaciones = Ponderacion::where('area_id', $extraeArea)->get();

        // Verificar si se encontraron ponderaciones
        if ($ponderaciones->isEmpty()) {
            return 0;
        }

        $ponderacionCalificar = [];

        foreach ($ponderaciones as $ponderacion) {
            // Añadimos la ponderación para cada pregunta del curso
            for ($i = 0; $i < $ponderacion->cantidadPreguntas; $i++) {
                $ponderacionCalificar[] = $ponderacion->ponderacion;
            }
        }

        // cantidad de preguntas
        $length = 60; 

        // Log::info("TODAS LAS PONDERACIONS:", ['ponderaciones' => $ponderacionCalificar]);

        // Recorremos cada respuesta y calculamos puntaje
        for ($i = 0; $i < $length; $i++) {

            $ponderacionActual = (float)$ponderacionCalificar[$i];

            if (isset($respuesta[$i]) && isset($respuestasFijas[$i])) {

                Log::info("Evaluando respuesta", [
                    'pregunta' => $i+1,
                    'respuesta_marcada' => $respuesta[$i],
                    'respuesta_correcta' => $respuestasFijas[$i],
                ]);

                Log::info("ponderacionActual:", ['valor' => $ponderacionActual]);

                if ($respuesta[$i] === $respuestasFijas[$i]) { // Respuesta correcta
                    Log::info("respt : x 10");
                    $puntaje = $puntaje + (10 * $ponderacionActual);
                }

                if ($respuesta[$i] === ' ') { // Respuesta vacía
                    Log::info("vacio : x 2");
                    $puntaje = $puntaje + (2 * $ponderacionActual);
                }

                Log::info("puntaje:", [$puntaje]);

            } else {
                Log::warning("Respuesta o respuestas fijas fuera de rango", ['respuesta' => $respuesta, 'respuestasFijas' => $respuestasFijas]);
            }

        }

        // Retornamos el puntaje total calculado
        return $puntaje;
    }

    /////////////////////////////////////////////////////////////////// LISTADO DE DATOS Y ARCHIVOS ///////////////////////////////////////////////////////

    public function datosRespuestas(Request $request)
    {
        // Número de elementos por página, valor por defecto es 5
        $perPage = $request->input('per_page', 5);

        // Obtener los datos de FichasRespuestas
        $datos = FichasRespuestas::paginate($perPage);

        // Obtener todos los lithos de FichasRespuestas
        $lithos = $datos->pluck('litho')->unique();

        // Obtener solo los DNI y tipo de FichasIdentificacion que correspondan a los lithos
        $postulantes = FichasIdentificacion::whereIn('litho', $lithos)
            ->get(['tipo', 'litho', 'dni']) // Obtener solo dni, tipo y litho
            ->keyBy('litho'); // Usa 'litho' como clave

        // Agregar los datos del postulante a cada dato de FichasRespuestas
        foreach ($datos as $item) {
            // Solo asignar si el litho existe en postulantes
            if (isset($postulantes[$item->litho])) {
                $item->tipo_identificacion = $postulantes[$item->litho]->tipo; // Asignar TIPO
                $item->dni_identificacion = $postulantes[$item->litho]->dni; // Asignar DNI
            } else {
                $item->tipo_identificacion = null; // Si no hay coincidencia, asignar null
                $item->dni_identificacion = null; // Si no hay coincidencia, asignar null
            }
        }

        Log::info('Datos de ficha de respuestas:', $datos->toArray());

        return response()->json($datos);
    }

    public function listarArchivos(Request $request)
    {
        $files = Storage::files('fichasRespuestas');
        $fileNames = array_map('basename', $files); // Extraer solo los nombres de los archivos

        // Obtener los parámetros de paginación
        $currentPage = $request->input('page', 1);
        $perPage = $request->input('per_page', 10); // Número de elementos por página

        // Crear una instancia de LengthAwarePaginator
        $currentItems = array_slice($fileNames, ($currentPage - 1) * $perPage, $perPage);
        $paginator = new LengthAwarePaginator($currentItems, count($fileNames), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return response()->json($paginator);
    }

    ///////////////////////////////////////////////////////////////////// EXPORTACIÓN DE ERRORES ///////////////////////////////////////////////////////////

    public function exportarErroresFichRespANTERIOR()
    {
        // Obtener los datos de FichasRespuestas
        $allDatos = FichasRespuestas::all();

        // Obtener todos los lithos de FichasRespuestas
        $lithos = $allDatos->pluck('litho')->unique();

        // Obtener solo tipo de FichasIdentificacion que correspondan a los lithos
        // $postulantes = FichasIdentificacion::whereIn('litho', $lithos)
        //     ->get(['tipo', 'litho','dni']) // Obtener solo dni, tipo y litho
        //     ->keyBy('litho'); // Usa 'litho' como clave

        $postulantes = FichasIdentificacion::whereIn('litho', $lithos)
            ->join('postulantes', 'fichas_identificacions.dni', '=', 'postulantes.dni')
            ->get([
                'fichas_identificacions.tipo',
                'fichas_identificacions.litho',
                'fichas_identificacions.dni',
                'postulantes.nombre',
                'postulantes.paterno',
                'postulantes.materno'
            ])
            ->keyBy('litho'); // Usa 'litho' como clave


        foreach ($allDatos as $item) {
            if (isset($postulantes[$item->litho])) {
                $item->tipo_identificacion = $postulantes[$item->litho]->tipo;
            } else {
                $item->tipo_identificacion = null;
            }
        }

        // Log::info("ALL-DATOS-ERROR:", [$allDatos]);


        $datos = [];

        Log::info('exportarErroresFichResp');
        foreach ($allDatos as $dato) {
            // Log::info('Datos de errores:', [$dato]);
            if ($dato->tipo != $dato->tipo_identificacion) {
                $datos[] = $dato;
            }
        }
        Log::info("DATOS-ERROR:", [$datos]);


        // Generar el HTML que queremos convertir a PDF
        $html = '
        <h1 style="text-align:center;">Errores de las Fichas de Respuestas</h1>
        <hr>
        <table width="100%" cellspacing="0" cellpadding="10" border="1">
            <thead>
                <tr>
                    <th style="text-align:left;">N</th>
                    <th style="text-align:left;">DNI</th>
                    <th style="text-align:left;">Apellidos y Nombres</th>
                    <th style="text-align:left;">Carrera</th>
                    <th style="text-align:left;">Error</th>
                </tr>
            </thead>
            <tbody>';


        foreach ($datos as $index => $resultado) {
            $puesto = $index + 1; // Poner el puesto comenzando desde 1
            $postulante =
                $nombreCompleto = trim($resultado['apPaterno'] . ' ' . $resultado['apMaterno'] . ', ' . $resultado['nombre']);
            Log::info("nombreCompleto-ERROR:", [$nombreCompleto]);


            $html .= '
            <tr>
                <td>' . $puesto . '</td>
                <td>' . $resultado['dni'] . '</td>
                <td>' . $nombreCompleto . '</td>
                <td>' . $resultado['carrera'] . '</td>
                <td>' . "DNI" . '</td>
            </tr>';
        }

        $html .= '
            </tbody>
        </table>';

        // Generar el PDF con Dompdf
        $pdf = Pdf::loadHTML($html);

        // Descargar el PDF
        return $pdf->download('erroresFichaRespuestas.pdf');
    }


    public function exportarErroresFichResp()
    {
        // Obtener todos los datos de FichasRespuestas
        $allDatos = FichasRespuestas::all();

        // Obtener todos los lithos de FichasRespuestas
        $lithos = $allDatos->pluck('litho')->unique();

        // Obtener los datos de FichasIdentificacion y Postulantes usando el DNI
        $postulantes = FichasIdentificacion::whereIn('litho', $lithos)
            ->join('postulantes', 'fichas_identificacions.dni', '=', 'postulantes.dni')
            ->get([
                'fichas_identificacions.tipo',
                'fichas_identificacions.litho',
                'fichas_identificacions.dni',
                'postulantes.nombre',
                'postulantes.paterno',
                'postulantes.materno',
                'postulantes.carrera'
            ])
            ->keyBy('litho'); // Usa 'litho' como clave

        // Asignar el tipo de identificación a los datos de FichasRespuestas
        foreach ($allDatos as $item) {
            if (isset($postulantes[$item->litho])) {
                $postulante = $postulantes[$item->litho];
                $item->tipo_identificacion = $postulante->tipo;
                $item->nombre = $postulante->nombre;
                $item->paterno = $postulante->paterno;
                $item->materno = $postulante->materno;
                $item->carrera = $postulante->carrera;
                $item->dni = $postulante->dni;
            } else {
                $item->tipo_identificacion = null;
            }
        }

        $datos = [];
        foreach ($allDatos as $dato) {
            Log::info('Datos de errores:', [$dato]);
            if ($dato->tipo != $dato->tipo_identificacion) {
                if ($dato->dni != NULL) {
                    $datos[] = $dato;
                }
            }
        }

        // Si no hay datos, generar un PDF con mensaje "Sin errores"
        if (empty($datos)) {
            $html = '
            <h1 style="text-align:center;">Errores de las Fichas de Respuestas</h1>
            <hr>
            <table width="100%" cellspacing="0" cellpadding="10" border="1">
                <thead>
                    <tr>
                        <th style="text-align:left;">Nº</th>
                        <th style="text-align:left;">DNI</th>
                        <th style="text-align:left;">Apellidos y Nombres</th>
                        <th style="text-align:left;">Error</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" style="text-align:center; font-style:italic;">Sin errores</td>
                    </tr>
                </tbody>
            </table>
        ';
        } else {
            // Generar el HTML para convertir a PDF si hay errores
            $html = '
        <h1 style="text-align:center;">Errores de las Fichas de Respuestas</h1>
        <hr>
        <table width="100%" cellspacing="0" cellpadding="10" border="1">
            <thead>
                <tr>
                    <th style="text-align:left;">Nº</th>
                    <th style="text-align:left;">DNI</th>
                    <th style="text-align:left;">Apellidos y Nombres</th>
                    <th style="text-align:left;">Error</th>
                </tr>
            </thead>
            <tbody>';

            $puesto = 0;
            // Agregar filas al HTML para cada error detectado
            foreach ($datos as $index => $resultado) {
                $puesto = $puesto + 1; // Poner el puesto comenzando desde 1
                $nombreCompleto = trim($resultado->paterno . ' ' . $resultado->materno . ', ' . $resultado->nombre);

                $html .= '
            <tr>
                <td>' . $puesto . '</td>
                <td>' . $resultado->dni . '</td>
                <td>' . $nombreCompleto . '</td>
                <td>Tipo de Prueba</td>
            </tr>';
            }

            $html .= '
            </tbody>
        </table>';
        }

        // Generar el PDF con Dompdf
        $pdf = Pdf::loadHTML($html);

        // Descargar el PDF
        return $pdf->download('erroresFichaRespuestas.pdf');
    }
}
