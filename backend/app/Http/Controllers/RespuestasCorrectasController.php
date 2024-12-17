<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\RespuestasCorrectas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;


class RespuestasCorrectasController extends Controller
{
    public function frRespCorrectas(Request $request)
    {
        Log::info($request->all());

        // Validar los archivos subidos
        $request->validate([
            'files.*' => 'required|file|mimetypes:text/plain,application/octet-stream',
            'frArea.*' => 'required'
        ]);

        // Obtener el área seleccionada
        $area = $request->input('frArea');  // Ahora capturamos el valor de frArea
        Log::info("AREA ELEGIDA -fr RESPUESTAS CORRECTAS:", [$area]);

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
        $destinationPath = storage_path('app/fichasRespuestasCorrectas');

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

        // Traer area_id con area
        // $area_id = Area::where('nombreArea', $area)->first();
        $area_id = Area::where('nombreArea', $area)->value('id');

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
                'area_id' => $area_id,     
                'id_proceso' => 1, // 1er proceso
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
                'area_id' => $area_id,
                'id_proceso' => 1, // 1er proceso
            ]);
        }
    }

    private function validarData($camp1, $camp3, $dni)
    {
        return !is_null($camp1) && !is_null($camp3) && !is_null($dni);
    }

    public function datosRespuestas(Request $request)
    {
        // Número de elementos por página, valor por defecto es 5
        $perPage = $request->input('per_page', 5);

        // Obtener los datos de FichasRespuestas
        $datos = RespuestasCorrectas::paginate($perPage);

        // Obtener todos los lithos de FichasRespuestasCorrectas
        $lithos = $datos->pluck('litho')->unique();


        Log::info('Datos de Ficha Respuestas Correctas:', $datos->toArray());

        return response()->json($datos);
    }


    public function listarArchivos(Request $request)
    {
        $files = Storage::files('fichasRespuestasCorrectas');
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


}
