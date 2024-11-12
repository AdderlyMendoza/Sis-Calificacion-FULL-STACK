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
        ]);

        // Procesar cada archivo subido
        foreach ($request->file('files') as $file) {
            $this->procesarFile($file);
        }

        return response()->json(['message' => 'Datos cargados con éxito']);
    }

    /**
     * Procesa un archivo y lo almacena en la base de datos.
     *
     * @param \Illuminate\Http\UploadedFile $file
     */
    private function procesarFile($file)
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
                $this->extractAndSaveData($line);
            }
        }
    }

    /**
     * Extrae datos de una línea y los guarda en la base de datos.
     * 
     * @param string $line
     * 
     */

    private function extractAndSaveData($line)
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

        $puntaje = $this->obtenerPuntaje($respuestas, $tipo, 3); // 3 = sociales


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
        $puntaje = 0;
        Log::info("Valor de tipo:", [$tipo]);
        Log::info("respuestas:", [$respuesta]);


        // ! FALTA PONER AREA
        $respuestasFijas = optional(RespuestasCorrectas::where('tipo', $tipo)->first())->respuestas;
        Log::info("respuestasFijas:", [$respuestasFijas]);

        // Si no hay respuestas fijas, retornar 0
        if (!$respuestasFijas) {
            return 0;
        }

        // Obtener las ponderaciones para el área dada desde la base de datos
        $ponderaciones = Ponderacion::where('area_id', $areaId)->get();
        Log::info("ponderaciones:", [$ponderaciones]);


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

        // Determinamos la longitud mínima entre la respuesta y las respuestas fijas
        $length = 60; # cantidad de PREGUNTAS
        $recorrerPonderacion = 0; // Contador para recorrer la ponderación
        Log::info("TODAS LAS PONDERACIONS:", ['ponderaciones' => $ponderacionCalificar]);

        // Iteramos sobre las respuestas
        for ($i = 0; $i < $length; $i++) {
            $ponderacionActual = (float)$ponderacionCalificar[$i];


            if ($respuesta[$i] === $respuestasFijas[$i]) {
                // $ponderacionActual = (float)$ponderacionCalificar[$recorrerPonderacion];
                Log::info("INDICE:", [$i]);

                Log::info("ponderacionActual:", ['valor' => $ponderacionActual]);


                $puntaje = $puntaje + (10 * $ponderacionActual);
                Log::info("puntaje:", [$puntaje]);
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
            ->get(['tipo', 'litho']) // Obtener solo dni, tipo y litho
            ->keyBy('litho'); // Usa 'litho' como clave

        // Agregar los datos del postulante a cada dato de FichasRespuestas
        foreach ($datos as $item) {
            // Solo asignar si el litho existe en postulantes
            if (isset($postulantes[$item->litho])) {
                $item->tipo_identificacion = $postulantes[$item->litho]->tipo; // Asignar TIPO
            } else {
                $item->tipo_identificacion = null; // Si no hay coincidencia, asignar null
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

    public function exportarErroresFichResp()
    {
        // Obtener los datos de FichasRespuestas
        $allDatos = FichasRespuestas::all();

        // Obtener todos los lithos de FichasRespuestas
        $lithos = $allDatos->pluck('litho')->unique();

        // Obtener solo tipo de FichasIdentificacion que correspondan a los lithos
        $postulantes = FichasIdentificacion::whereIn('litho', $lithos)
            ->get(['tipo', 'litho']) // Obtener solo dni, tipo y litho
            ->keyBy('litho'); // Usa 'litho' como clave


        foreach ($allDatos as $item) {
            if (isset($postulantes[$item->litho])) {
                $item->tipo_identificacion = $postulantes[$item->litho]->tipo; 
            } else {
                $item->tipo_identificacion = null; 
            }
        }

        $datos = [];
        Log::info('exportarErroresFichResp');
        foreach ($allDatos as $dato) {
            // Log::info('Datos de errores:', [$dato]);
            if($dato->tipo != $dato->tipo_identificacion){
                $datos[] = $dato;
            }
        }

        // Generar el HTML que queremos convertir a PDF
        $html = '
        <h1 style="text-align:center;">Errores de las Fichas de Respuestas</h1>
        <hr>
        <table width="100%" cellspacing="0" cellpadding="10" border="1">
            <thead>
                <tr>
                    <th style="text-align:left;">Nº</th>
                    <th style="text-align:left;">DNI</th>
                    <th style="text-align:left;">Apellidos y Nombres</th>
                    <th style="text-align:left;">Carrera</th>
                    <th style="text-align:left;">Error</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($datos as $index => $resultado) {
            $puesto = $index + 1; // Poner el puesto comenzando desde 1
            $nombreCompleto = trim($resultado['apPaterno'] . ' ' . $resultado['apMaterno'] . ', ' . $resultado['nombre']);

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
}
