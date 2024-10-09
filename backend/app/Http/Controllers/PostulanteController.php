<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PostulanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = Postulante::orderBy('id', 'desc')->get();

        // Retornar los usuarios como respuesta JSON
        return response()->json($users);
    }

    public function uploadCsv(Request $request)
    {
        // Validar que el archivo es un CSV
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'El archivo debe ser un CSV.'], 400);
        }

        // Abrir el archivo CSV
        if ($file = $request->file('file')) {
            $path = $file->getRealPath();
            $data = array_map('str_getcsv', file($path));

            // Obtener los encabezados
            $header = $data[0];
            unset($data[0]); // Eliminar la fila de encabezados del array

            // Insertar datos en la base de datos
            foreach ($data as $row) {
                // Asegurarse de que el número de columnas coincida
                if (count($row) == count($header)) {
                    $postulante = array_combine($header, $row);

                    // Crear un nuevo registro en la base de datos
                    Postulante::create([
                        'dni' => $postulante['dni'],
                        'nombre' => $postulante['nombre'],
                        'paterno' => $postulante['paterno'],
                        'materno' => $postulante['materno'],
                        'ubigeo' => $postulante['ubigeo'],
                        'colegio' => $postulante['colegio'],
                        'celular' => $postulante['celular'],
                        'email' => $postulante['email'],
                        'carrera' => $postulante['carrera'],
                    ]);
                }
            }

            return response()->json(['success' => 'Archivo CSV subido y procesado correctamente.']);
        }

        return response()->json(['error' => 'Error al subir el archivo.'], 400);
    }
}
