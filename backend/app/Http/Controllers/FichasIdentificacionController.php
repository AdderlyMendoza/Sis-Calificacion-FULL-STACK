<?php

namespace App\Http\Controllers;

use App\Models\FichasIdentificacion;
use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;


class FichasIdentificacionController extends Controller
{
    public function datosIdentificacion(Request $request)
    {
        // Número de elementos por página, valor por defecto es 5
        $perPage = $request->input('per_page', 5);

        // Obtener los datos de FichasIdentificacion
        $datos = FichasIdentificacion::paginate($perPage);

        // Obtener todos los DNI, aula y tipo en un solo paso
        $dnis = $datos->pluck('dni')->unique();
        $aulas = $datos->pluck('aula')->unique();
        $tipos = $datos->pluck('tipo')->unique();

        // Cargar los postulantes que coinciden con los DNI -> FALTA AULA Y TIPO EN MI BASE DE DATOS XD
        $postulantes = Postulante::whereIn('dni', $dnis)
            // ->orWhereIn('aula', $aulas)
            // ->orWhereIn('tipo', $tipos)
            ->get()
            ->keyBy('dni'); // Puedes usar el campo que necesites como clave

        // Agregar los datos del postulante a cada dato
        foreach ($datos as $item) {
            $postulante = $postulantes->get($item->dni);
            $item->dni_postulante = $postulante ? $postulante->dni : null;
            // $item->aula_postulante = $postulante ? $postulante->aula : null;
            // $item->tipo_postulante = $postulante ? $postulante->tipo : null;
        }

        Log::info('Datos de identificación:', $datos->toArray());

        return response()->json($datos);
    }


    public function frIdPostulantes(Request $request)
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
        $destinationPath = storage_path('app/fichasIdentificacion');

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
        $dni = substr($campo5, 7, 8);
        $aula = substr($campo5, 15, 3);

        // Asignar un valor predeterminado para id_archivo
        $id_archivo = "no se xd";

        // Validar datos antes de guardar
        if ($this->validarData($camp1, $camp3, $dni)) {
            FichasIdentificacion::create([
                'campo1' => $camp1,
                'campo2' => $camp2,
                'campo3' => $camp3,
                'campo4' => $camp4,
                'dni' => preg_replace('/[^0-9]/', '', $dni), // Asegurarse de que el DNI solo contenga números
                'id_archivo' => $id_archivo,
                'litho' => $litho,
                'tipo' => $tipo,
                'aula' => $aula,
                'id_proceso' => 1,
            ]);
        } else {
            Log::warning('Datos inválidos, no se guardaron:', [
                'campo1' => $camp1,
                'campo2' => $camp2,
                'campo3' => $camp3,
                'campo4' => $camp4,
                'dni' => preg_replace('/[^0-9]/', '', $dni), // Asegurarse de que el DNI solo contenga números
                'id_archivo' => $id_archivo,
                'litho' => $litho,
                'tipo' => $tipo,
                'aula' => $aula,
                'id_proceso' => 1,
            ]);
        }
    }

    private function validarData($camp1, $camp3, $dni)
    {
        return !is_null($camp1) && !is_null($camp3) && !is_null($dni);
    }


    public function listarArchivos(Request $request)
    {
        $files = Storage::files('fichasIdentificacion');
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
