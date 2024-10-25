<?php

namespace App\Http\Controllers;

use App\Models\FichasRespuestas;
use App\Models\Ponderacion;
use App\Models\RespuestasCorrectas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class FichasRespuestasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        $filePath = $file->store('fichasRespuestas');
        $data = file_get_contents(storage_path('app/' . $filePath)); // almacenar

        // Log::info("Contenido del archivo:", [$data]);

        $lines = explode("\n", $data);
        // Log::info("Líneas extraídas:", ['count' => count($lines)]);

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

        $puntaje = $this->obtenerPuntaje($respuestas, $tipo, 3); // 1 = CAMBIAR: ingenierias

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
                'puntaje' => "puntaje",
            ]);
        }
    }

    private function validarData($camp1, $camp3, $dni)
    {
        return !is_null($camp1) && !is_null($camp3) && !is_null($dni);
    }

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
        $length = min(strlen($respuesta), strlen($respuestasFijas));
        $recorrerPonderacion = 0; // Contador para recorrer la ponderación

        // Iteramos sobre las respuestas
        for ($i = 0; $i < $length; $i++) {
            if ($respuesta[$i] === $respuestasFijas[$i]) {
                $ponderacionActual = (float)$ponderacionCalificar[$recorrerPonderacion];
                Log::info("ponderacionActual:", ['valor' => $ponderacionActual]);

                $puntaje = $puntaje + (10 * $ponderacionActual);

                // Aumentamos el índice de ponderación si aún queda margen
                if ($recorrerPonderacion < count($ponderacionCalificar) - 1) {
                    $recorrerPonderacion++;
                }
            }
        }

        // Retornamos el puntaje total calculado
        return $puntaje;
    }
}
