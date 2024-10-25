<?php

namespace App\Http\Controllers;

use App\Models\RespuestasCorrectas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RespuestasCorrectasController extends Controller
{
    public function frRespCorrectas(Request $request)
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


        // Validar datos antes de guardar
        if ($this->validarData($camp1, $camp2, $camp3)) {
            RespuestasCorrectas::create([
                'campo1' => $camp1,
                'campo2' => $camp2,
                'campo3' => $camp3,
                'campo4' => $camp4,
                'id_archivo' => $id_archivo,
                'litho' => $litho,
                'tipo' => $tipo,
                'respuestas' => $respuestas,
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
            ]);
        }
    }

    private function validarData($camp1, $camp3, $dni)
    {
        return !is_null($camp1) && !is_null($camp3) && !is_null($dni);
    }
}
