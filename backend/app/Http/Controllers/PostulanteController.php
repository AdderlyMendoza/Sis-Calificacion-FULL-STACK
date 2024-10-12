<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Schema;


class PostulanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener todos los usuarios
        $perPage = $request->input('per_page', 7); // Número de elementos por página, por defecto 10
        $postulantes = Postulante::orderBy('id', 'desc')->paginate($perPage);

        // Retornar los usuarios como respuesta JSON
        return response()->json($postulantes);
    }


    public function uploadExcel(Request $request)
    {
        try {
            $file = $request->file('file');

            // Asegúrate de que el archivo fue recibido
            if (!$file) {
                return response()->json(['error' => 'No file uploaded'], 400);
            }

            // Cargar el archivo
            $spreadsheet = IOFactory::load($file->getRealPath());
            $data = $spreadsheet->getActiveSheet()->toArray();

            // Iterar sobre las filas y guardarlas en la base de datos
            foreach ($data as $row) {
                // Suponiendo que la primera fila contiene los encabezados
                if ($row[0] !== 'dni') { // Ajusta este chequeo según tus encabezados
                    Postulante::create([
                        'dni'     => $row[0],
                        'nombre'  => $row[1],
                        'paterno' => $row[2],
                        'materno' => $row[3],
                        'ubigeo'  => $row[4],
                        'colegio' => $row[5],
                        'celular' => $row[6],
                        'email'   => $row[7],
                        'carrera' => $row[8],
                    ]);
                }
            }

            return response()->json(['success' => 'File imported successfully'], 200);
        } catch (\Exception $e) {
            // Captura cualquier error y regístralo
            return response()->json(['error' => 'File upload failed: ' . $e->getMessage()], 500);
        }
    }

    public function obtenerCampos()
    {
        $campos = Schema::getColumnListing('postulantes');
        return response()->json($campos);
    }

    public function generaCodigo()
    {
        //

    }
}
