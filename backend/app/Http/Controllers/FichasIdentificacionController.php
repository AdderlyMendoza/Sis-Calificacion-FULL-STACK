<?php

namespace App\Http\Controllers;

use App\Models\FichasIdentificacion;
use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Barryvdh\DomPDF\Facade\Pdf;


class FichasIdentificacionController extends Controller
{

    ///////////////////////////////////////////////////////// SUBIDA DE ARCHIVOS Y GUARDADO DE DATOS EN LA DB ///////////////////////////////////////////
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

    /////////////////////////////////////////////////////////////////// LISTADO DE DATOS Y ARCHIVOS ///////////////////////////////////////////////////////
    public function datosIdentificacion(Request $request)
    {
        // Número de elementos por página
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

    ///////////////////////////////////////////////////////////////////// EXPORTACIÓN DE ERRORES ///////////////////////////////////////////////////////////
    public function exportarErroresFichIdenANTERIOR()
    {
        // Obtener los datos de FichasIdentificacion
        $allDatos = FichasIdentificacion::all();

        // Obtener todos los DNI, aula y tipo en un solo paso
        $dnis = $allDatos->pluck('dni')->unique();
        $aulas = $allDatos->pluck('aula')->unique();
        $tipos = $allDatos->pluck('tipo')->unique();

        // Cargar los postulantes que coinciden con los DNI -> FALTA AULA Y TIPO EN MI BASE DE DATOS XD
        $postulantes = Postulante::whereIn('dni', $dnis)
            // ->orWhereIn('aula', $aulas)
            // ->orWhereIn('tipo', $tipos)
            ->get()
            ->keyBy('dni'); // Puedes usar el campo que necesites como clave

        // Agregar los datos del postulante a cada dato
        foreach ($allDatos as $item) {
            $postulante = $postulantes->get($item->dni);
            $item->dni_postulante = $postulante ? $postulante->dni : null;
            // $item->aula_postulante = $postulante ? $postulante->aula : null;
            // $item->tipo_postulante = $postulante ? $postulante->tipo : null;
        }

        $datos = [];
        Log::info('exportarErroresFichResp');
        Log::info('name complete:', [$allDatos]);

        foreach ($allDatos as $dato) {
            // Log::info('Datos de errores:', [$dato]);
            // if ($dato->dni != $dato->dni_postulante || $dato->aula != $dato->tipo_aula || $dato->tipo != $dato->tipo_identificacion) {
            if ($dato->dni != $dato->dni_postulante) {

                $datos[] = $dato;
            }
        }

        // Generar el HTML que queremos convertir a PDF
        $html = '
        <h1 style="text-align:center;">Errores Examen Simulacro MACUSANI</h1>
        <hr>
        <table width="100%" cellspacing="0" cellpadding="10" border="1">
            <thead>
                <tr>
                    <th style="text-align:left;">N</th>
                    <th style="text-align:left;">DNI</th>
                    <th style="text-align:left;">Apellidos y Nombres</th>
                    <th style="text-align:left;">Carrera</th>
                    <th style="text-align:left;">Errores</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($datos as $index => $resultado) {
            // ERROR EN EL NOMBRE COMPLETO
            $puesto = $index + 1; // Poner el puesto comenzando desde 1
            $nombreCompleto = trim($resultado->apPaterno . ' ' . $resultado->apMaterno . ', ' . $resultado->nombre);
            Log::info('name complete:', [$nombreCompleto]);

            $html .= '
                <tr>
                    <td>' . $puesto . '</td>
                    <td>' . $resultado->dni . '</td>
                    <td>' . $nombreCompleto . '</td>
                    <td>' . $resultado->carrera . '</td>
                    <td>';

            // Mostrar los errores de acuerdo con las discrepancias
            if ($resultado->dni != $resultado->dni_postulante) {
                $html .= 'ERROR DNI ';
            }

            // if ($resultado->aula != $resultado->aula_postulante) {
            //     $html .= 'AULA ';
            // }

            // if ($resultado->tipo != $resultado->tipo_postulante) {
            //     $html .= 'TIPO';
            // }

            $html .= '</td></tr>';
        }


        $html .= '
            </tbody>
        </table>';

        // Generar el PDF con Dompdf
        $pdf = Pdf::loadHTML($html);

        // Descargar el PDF
        return $pdf->download('erroresFichaIdentificacion.pdf');
    }

    public function exportarErroresFichIden()
    {
        // Obtener todos los datos de FichasIdentificacion
        $fichasDatos = FichasIdentificacion::all();

        // Obtener todos los DNI únicos de las fichas
        $dnisFichas = $fichasDatos->pluck('dni')->unique();

        // Cargar los postulantes que NO tienen ficha de identificación usando los DNI
        $postulantesSinFicha = Postulante::whereNotIn('dni', $dnisFichas)
            ->get(['dni', 'nombre', 'paterno', 'materno', 'carrera']); // Obtener también nombres y apellidos

        // Verificar si hay datos para exportar
        if ($postulantesSinFicha->isEmpty()) {
            return response()->json(['message' => 'No se encontraron errores'], 200);
        }

        // Generar el HTML que queremos convertir a PDF
        $html = '
        <h1 style="text-align:center;">Errores de las Fichas de Identificacion</h1>
        <hr>
        <table width="100%" cellspacing="0" cellpadding="10" border="1">
            <thead>
                <tr>
                    <th style="text-align:left;">N</th>
                    <th style="text-align:left;">DNI</th>
                    <th style="text-align:left;">Apellidos y Nombres</th>
                    <th style="text-align:left;">Carrera</th>
                    <th style="text-align:left;">Errores</th>
                </tr>
            </thead>
            <tbody>';

        // Generar la tabla con los datos de los postulantes que no tienen ficha
        foreach ($postulantesSinFicha as $index => $postulante) {
            $puesto = $index + 1;
            $nombreCompleto = trim($postulante->paterno . ' ' . $postulante->materno . ', ' . $postulante->nombre);

            $html .= '
            <tr>
                <td>' . $puesto . '</td>
                <td>' . $postulante->dni . '</td>
                <td>' . $nombreCompleto . '</td>
                <td>' . $postulante->carrera . '</td>
                <td>DNI</td>
            </tr>';
        }

        $html .= '
            </tbody>
        </table>';

        // Generar el PDF con Dompdf
        $pdf = Pdf::loadHTML($html);

        // Descargar el PDF
        return $pdf->download('erroresPostulantesSinFicha.pdf');
    }
}
