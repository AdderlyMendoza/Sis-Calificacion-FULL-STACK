<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;


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
                        'codigo' => $row[9],
                        // 'tipo' => $row[10],
                        'tipo' => "TIPO",
                        // 'aula' => $row[11],
                        'aula' => "AULA",
                    ]);
                }else {
                    Postulante::create([
                        'dni'     => "vacio",
                        'nombre'  => "vacio",
                        'paterno' => "vacio",
                        'materno' => "vacio",
                        'ubigeo'  => "vacio",
                        'colegio' => "vacio",
                        'celular' => "vacio",
                        'email'   => "vacio",
                        'carrera' => "vacio",
                        'codigo' => "vacio",
                        // 'tipo' => $row[10],
                        'tipo' => "TIPO",
                        // 'aula' => $row[11],
                        'aula' => "AULA",
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

    public function frCamposSeleccionados(Request $request)
    {
        // Validar la entrada
        $frDatosDisAulSeleccionadas = $request->validate([
            '*.campo' => 'required|string',
            '*.desde' => 'nullable|string',
            '*.hasta' => 'nullable|string',
        ]);

        Log::info('XXXXXXXXXXXXXXXXXXXXXXXXXXX NUEVO XXXXXXXXXXXXXXXXXXXXXXXXX');
        // Log::info('Tipo de dato:', ['tipo' => gettype($frDatosDisAulSeleccionadas)]);
        Log::info('Datos recibidos:', $frDatosDisAulSeleccionadas);

        // Inicializar un array para almacenar los códigos generados
        $codigosGenerados = [];

        // Obtener todos los postulantes
        $postulantes = Postulante::all();

        // Recorrer cada postulante
        foreach ($postulantes as $postulante) {
            // Inicializar un array para almacenar los valores extraídos para este postulante
            $valoresExtraidosPostulante = [];

            // Recorrer los campos definidos
            foreach ($frDatosDisAulSeleccionadas as $aux) {
                $campo = $aux['campo'];
                $longitud = (int)$aux['hasta'];
                $desde = $aux['desde'];

                // Extraer el valor del campo específico para este postulante
                $valor = $postulante[$campo]; // Asegúrate de que $campo es válido
                // $valor = preg_replace('/[^\x20-\x7E]/', '', $valor); // Esto elimina caracteres no imprimibles

                if (is_array($valor) && isset($valor['message']) && $valor['message'] === "Malformed UTF-8 characters, possibly incorrectly encoded") {
                    Log::info('Datos problemáticos:', $valor);
                }
                
                // Lógica para extraer caracteres según "Desde" y "Hasta"
                $caracteres = '';
                switch ($desde) {
                    case 'INICIO':
                        $caracteres = substr($valor, 0, $longitud);
                        break;
                    case 'FINAL':
                        $caracteres = substr($valor, -$longitud);
                        break;
                    case 'MEDIO':
                        $medioIndex = (strlen($valor) / 2) - (int)($longitud / 2);
                        $caracteres = substr($valor, max(0, $medioIndex), $longitud);
                        break;
                    default:
                        Log::warning('Desde no reconocido', ['desde' => $desde]);
                        break;
                }

                // Manejo especial para los que tienen menos datos
                if (strlen($caracteres) < $longitud) {
                    $caracteres .= str_repeat(substr($caracteres, -1), $longitud - strlen($caracteres));
                }

                // Agregar el resultado a la lista
                $valoresExtraidosPostulante[] = $caracteres; // Usar caracteres extraídos
                Log::info("Campo: $campo", ['valor' => $valor]);
            }

            // Generar el código concatenando los valores extraídos
            $codigoGenerado = implode('', $valoresExtraidosPostulante); // Concatenar los valores

            // Asignar el código generado al postulante
            $postulante->codigo = $codigoGenerado;

            // Guardar el postulante en la base de datos
            $postulante->save();

            $codigosGenerados[] = $codigoGenerado; // Almacenar el código generado
        }

        // Log de todos los códigos generados
        Log::info('Códigos generados:', $codigosGenerados);

        // Devuelve una respuesta adecuada
        return response()->json([
            'message' => 'Datos recibidos y codigos generados correctamente',
            'data' => $frDatosDisAulSeleccionadas,
            'codigosGenerados' => $codigosGenerados,
        ], 200);
    }


    
}
