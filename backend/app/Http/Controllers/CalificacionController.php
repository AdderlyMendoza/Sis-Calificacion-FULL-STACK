<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\FichasIdentificacion;
use App\Models\FichasRespuestas;
use App\Models\Postulante;
use App\Models\ProgramaEstudios;
use App\Models\Vacantes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception; // Asegúrate de importar la clase Exception
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class CalificacionController extends Controller
{

    public function traerDatos()
    {
        try {
            // Log::info("Iniciando la recolección de datos");
            $resultados = [];
            $idenIdenticacion = FichasIdentificacion::pluck('litho'); # Todos los datos del campo litho de FichasIdentificacion

            // Log::info("Identificaciones obtenidas: ", ['litho_count' => $idenIdenticacion->count()]);

            foreach ($idenIdenticacion as $litho) { # recorre listo de lithos

                $fichaIdentificacion = FichasIdentificacion::where('litho', $litho)->first();

                if ($fichaIdentificacion) {

                    $dni = $fichaIdentificacion->dni; # Obtenemos el DNI de cada litho

                    $postulante = Postulante::where('dni', $dni)->first(); # buscamos al postulante con ese DNI

                    if ($postulante) {  # Si se encuentra al postulante extraemos sus datos
                        $resultados[] = [
                            'dni' => $dni,
                            'nombre' => $postulante->nombre,
                            'carrera' => $postulante->colegio,
                            'apPaterno' => $postulante->paterno,
                            'apMaterno' => $postulante->materno,
                            'puntaje' => optional(FichasRespuestas::where('litho', $litho)->first())->puntaje,
                        ];
                    } else {
                        Log::warning("Postulante no encontrado: ", ['dni' => $dni, 'litho' => $litho]);
                    }
                } else {
                    Log::warning("Ficha de identificación no encontrada para litho: ", ['litho' => $litho]);
                }
            }

            // Ordenar el array $resultados por el campo 'puntaje' de forma descendente
            usort($resultados, function ($a, $b) {
                return $b['puntaje'] <=> $a['puntaje'];  // Comparación inversa para orden descendente
            });

            return $resultados; // Devuelve los resultados recolectados

        } catch (\Exception $e) {
            Log::error('Error al recolectar datos: ' . $e->getMessage());
            return response()->json(['error' => 'Error al recolectar datos'], 500);
        }
    }

    public function exportarResultados($tipo)
    {
        Log::warning("Tipo de ARCHIVO A EXPORTAR: ", ['tipo' => $tipo]);

        $resultados = $this->traerDatos();
        // $resultados = $this->CalificarPorProgramaEstudios();


        switch ($tipo) {
            case 'excel':
                // Lógica para exportar a Excel
                return $this->exportarExcel($resultados);
            case 'pdf':
                // Lógica para exportar a PDF
                return $this->exportarPdfporArea($resultados);
                // return $this->exportarPdfporPrograma($resultados);
            case 'txt':
                // Lógica para exportar a TXT
                return $this->exportarTxt($resultados);
            default:
                return response()->json(['error' => 'Tipo de exportación no soportado'], 400);
        }
    }

    public function exportarTxt($resultados)
    {
        $filename = 'resultados.txt';

        return response()->stream(function () use ($resultados) {
            $handle = fopen('php://output', 'w');

            // Escribir encabezados
            fwrite($handle, sprintf("%-10s %-12s %-40s %15s\n", "PUESTO", "DNI", "APELLIDOS Y NOMBRES", "PUNTAJE"));
            fwrite($handle, str_repeat("=", 80) . PHP_EOL);

            // Escribir los resultados
            foreach ($resultados as $index => $resultado) {
                $puesto = $index + 1; // Poner el puesto comenzando desde 1
                $nombreCompleto = trim($resultado['apPaterno'] . ' ' . $resultado['apMaterno'] . ', ' . $resultado['nombre']);
                $puntaje = number_format($resultado['puntaje'], 3, '.', '');

                fwrite($handle, sprintf("%-10d %-12s %-40s %14s\n", $puesto, $resultado['dni'], $nombreCompleto, $puntaje));
            }

            fwrite($handle, str_repeat("=", 80) . PHP_EOL);
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////// EXPORTACIONES POR AREAS/SIMULACROS /////////////////////////////////////////////////////////////////
    public function exportarPdfporArea($resultados)
    {
        // Generar el HTML que queremos convertir a PDF
        $html = '
        <h1 style="text-align:center;">Resultados del Examen Simulacro Colegio de Alto Rendimiento COAR</h1>
        <hr>
        <table width="100%" cellspacing="0" cellpadding="10" border="1">
            <thead>
                <tr>
                    <th style="text-align:left;">Puesto</th>
                    <th style="text-align:left;">DNI</th>
                    <th style="text-align:left;">Apellidos y Nombres</th>
                    <th style="text-align:right;">Puntaje</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($resultados as $index => $resultado) {
            $puesto = $index + 1; // Poner el puesto comenzando desde 1
            $nombreCompleto = trim($resultado['apPaterno'] . ' ' . $resultado['apMaterno'] . ', ' . $resultado['nombre']);
            $puntaje = number_format($resultado['puntaje'], 3, '.', '');

            $html .= '
            <tr>
                <td>' . $puesto . '</td>
                <td>' . $resultado['dni'] . '</td>
                <td>' . $nombreCompleto . '</td>
                <td style="text-align:right;">' . $puntaje . '</td>
            </tr>';
        }

        $html .= '
            </tbody>
        </table>';

        // Generar el PDF con Dompdf
        $pdf = Pdf::loadHTML($html);

        // Descargar el PDF
        return $pdf->download('resultados.pdf');
    }

    public function exportarExcel($resultados)
    {
        try {
            // Crear una nueva hoja de cálculo
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Resultados Examen Simulacro');

            // Agregar encabezado
            $sheet->setCellValue('A1', 'Puesto');
            $sheet->setCellValue('B1', 'DNI');
            $sheet->setCellValue('C1', 'Apellidos y Nombres');
            $sheet->setCellValue('D1', 'Puntaje');

            // Establecer encabezado en negrita
            $sheet->getStyle('A1:D1')->getFont()->setBold(true);

            // Agregar datos
            foreach ($resultados as $index => $resultado) {
                $puesto = $index + 1;
                $nombreCompleto = trim($resultado['apPaterno'] . ' ' . $resultado['apMaterno'] . ', ' . $resultado['nombre']);
                $puntaje = number_format($resultado['puntaje'], 3, '.', '');

                $row = $index + 2; // La fila comienza en 2 para evitar el encabezado
                $sheet->setCellValue('A' . $row, $puesto);
                $sheet->setCellValue('B' . $row, $resultado['dni']);
                $sheet->setCellValue('C' . $row, $nombreCompleto);
                $sheet->setCellValue('D' . $row, $puntaje);
            }

            // Autoajustar el ancho de las columnas
            foreach (range('A', 'D') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Guardar el archivo en el almacenamiento temporal
            $tempFile = storage_path('app/temp_resultados.xlsx');  // Guardar en storage temporal
            Log::info("Guardando archivo en: " . $tempFile);

            // Intentamos guardar el archivo Excel
            try {
                $writer = new Xlsx($spreadsheet);
                $writer->save($tempFile);  // Guardamos el archivo
                Log::info("Archivo guardado correctamente: " . $tempFile);
            } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
                Log::error("Error al guardar el archivo Excel: " . $e->getMessage());
                return response()->json(['error' => 'Hubo un problema al guardar el archivo Excel.'], 500);
            }

            // Verificar si el archivo fue guardado correctamente
            if (!file_exists($tempFile)) {
                Log::error("El archivo no se guardó en la ruta esperada: " . $tempFile);
                return response()->json(['error' => 'El archivo no se guardó correctamente.'], 500);
            }

            // Forzar nombre y extensión correctos para la descarga
            return response()->download($tempFile, 'resultados.xlsx');
        } catch (Exception $e) {
            Log::error("Error al generar el archivo Excel: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un problema al generar el archivo Excel.'], 500);
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////// EXPORTACIONES POR PROGRAMAS ////////////////////////////////////////////////////////////////////////
    public function exportarPdfPorPrograma($resultados)
    {
        // Consultar el número de vacantes por carrera en el orden de la tabla vacantes
        $vacantesPorCarrera = DB::table('vacantes')
            ->join('programa_estudios', 'vacantes.programaEstudios_id', '=', 'programa_estudios.id')
            ->select('programa_estudios.nombreProgramaEstudio as carrera', 'vacantes.numeroVacantes')
            ->orderBy('vacantes.id')
            ->get()
            ->toArray();

        // Generar el HTML para el PDF
        $html = '<h1 style="text-align:center;">Resultados Examen QUIMICA - 23/11/2024</h1><hr>';

        // Recorrer la lista de carreras en el orden definido por la tabla 'vacantes'
        foreach ($vacantesPorCarrera as $vacante) {
            $carrera = $vacante->carrera;
            $limite = $vacante->numeroVacantes;

            // Filtrar los resultados por carrera
            $postulantesCarrera = array_filter($resultados, function ($resultado) use ($carrera) {
                return $resultado['carrera'] === $carrera;
            });

            // Ordenar por puntaje descendente (si no está ya ordenado)
            usort($postulantesCarrera, function ($a, $b) {
                return $b['puntaje'] <=> $a['puntaje'];
            });

            // Limitar el número de resultados según el número de vacantes
            $postulantesCarrera = array_slice($postulantesCarrera, 0, $limite);

            // Generar la tabla solo si hay postulantes para esa carrera
            if (count($postulantesCarrera) > 0) {
                $html .= '<h2>' . $carrera . ':</h2>';
                $html .= '
            <table width="100%" cellspacing="0" cellpadding="10" border="1">
                <thead>
                    <tr>
                        <th style="text-align:left;">Puesto</th>
                        <th style="text-align:left;">DNI</th>
                        <th style="text-align:left;">Apellidos y Nombres</th>
                        <th style="text-align:right;">Puntaje</th>
                    </tr>
                </thead>
                <tbody>';

                // Agregar filas para cada postulante
                foreach ($postulantesCarrera as $index => $postulante) {
                    $puesto = $index + 1;
                    $nombreCompleto = trim($postulante['apPaterno'] . ' ' . $postulante['apMaterno'] . ', ' . $postulante['nombre']);
                    $puntaje = number_format($postulante['puntaje'], 3, '.', '');

                    $html .= '
                <tr>
                    <td>' . $puesto . '</td>
                    <td>' . $postulante['dni'] . '</td>
                    <td>' . $nombreCompleto . '</td>
                    <td style="text-align:right;">' . $puntaje . '</td>
                </tr>';
                }

                $html .= '</tbody></table><br>';
            }
        }

        // Generar el PDF con Dompdf
        $pdf = Pdf::loadHTML($html);

        // Descargar el PDF
        return $pdf->download('resultados_por_carrera.pdf');
    }

    public function exportarExcelPorPrograma($resultados)
    {
        try {
            // Crear una nueva hoja de cálculo
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Resultados Examen Simulacro');

            // Agregar encabezado
            $sheet->setCellValue('A1', 'Puesto');
            $sheet->setCellValue('B1', 'DNI');
            $sheet->setCellValue('C1', 'Apellidos y Nombres');
            $sheet->setCellValue('D1', 'Carrera');
            $sheet->setCellValue('E1', 'Puntaje');

            // Establecer encabezado en negrita
            $sheet->getStyle('A1:E1')->getFont()->setBold(true);

            // Consultar el número de vacantes por carrera en el orden de la tabla 'vacantes'
            $vacantesPorCarrera = DB::table('vacantes')
                ->join('programa_estudios', 'vacantes.programaEstudios_id', '=', 'programa_estudios.id')
                ->select('programa_estudios.nombreProgramaEstudio as carrera', 'vacantes.numeroVacantes')
                ->orderBy('vacantes.id')
                ->get()
                ->toArray();


            // Inicializar la fila donde empezar a agregar datos
            $currentRow = 2;

            // Recorrer cada carrera en el orden especificado por la tabla 'vacantes'
            foreach ($vacantesPorCarrera as $vacante) {
                $carrera = $vacante->carrera;
                $limite = $vacante->numeroVacantes;

                // Agregar una fila con el nombre de la carrera
                $sheet->setCellValue('A' . $currentRow, $carrera);
                $sheet->mergeCells("A{$currentRow}:E{$currentRow}"); // Unir las celdas para el encabezado de la carrera
                $sheet->getStyle("A{$currentRow}")->getFont()->setBold(true);
                $currentRow++;

                // Filtrar los resultados por la carrera actual
                $postulantesCarrera = array_filter($resultados, function ($resultado) use ($carrera) {
                    return $resultado['carrera'] === $carrera;
                });

                // Ordenar por puntaje descendente
                usort($postulantesCarrera, function ($a, $b) {
                    return $b['puntaje'] <=> $a['puntaje'];
                });

                // Limitar el número de resultados según el número de vacantes
                $postulantesCarrera = array_slice($postulantesCarrera, 0, $limite);

                // Agregar filas al Excel para cada postulante en la carrera
                foreach ($postulantesCarrera as $index => $postulante) {
                    $puesto = $index + 1;
                    $nombreCompleto = trim($postulante['apPaterno'] . ' ' . $postulante['apMaterno'] . ', ' . $postulante['nombre']);
                    $puntaje = number_format($postulante['puntaje'], 3, '.', '');

                    // Agregar datos a la hoja
                    $sheet->setCellValue('A' . $currentRow, $puesto);
                    $sheet->setCellValue('B' . $currentRow, $postulante['dni']);
                    $sheet->setCellValue('C' . $currentRow, $nombreCompleto);
                    $sheet->setCellValue('D' . $currentRow, $carrera);
                    $sheet->setCellValue('E' . $currentRow, $puntaje);

                    $currentRow++; // Incrementar la fila para el siguiente registro
                }

                // Agregar una fila vacía después de cada carrera para separarlas
                $currentRow++;
            }

            // Autoajustar el ancho de las columnas
            foreach (range('A', 'E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // Guardar el archivo en el almacenamiento temporal
            $tempFile = storage_path('app/temp_resultados1.xlsx');

            try {
                $writer = new Xlsx($spreadsheet);
                $writer->save($tempFile);
            } catch (\PhpOffice\PhpSpreadsheet\Writer\Exception $e) {
                Log::error("Error al guardar el archivo Excel: " . $e->getMessage());
                return response()->json(['error' => 'Hubo un problema al guardar el archivo Excel.'], 500);
            }

            // Verificar si el archivo fue guardado correctamente
            if (!file_exists($tempFile)) {
                Log::error("El archivo no se guardó en la ruta esperada: " . $tempFile);
                return response()->json(['error' => 'El archivo no se guardó correctamente.'], 500);
            }

            // Descargar el archivo Excel generado
            return response()->download($tempFile, 'resultados.xlsx');
        } catch (Exception $e) {
            Log::error("Error al generar el archivo Excel: " . $e->getMessage());
            return response()->json(['error' => 'Hubo un problema al generar el archivo Excel.'], 500);
        }
    }

}


// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use Illuminate\Support\Facades\Response;

// public function exportarExcel($resultados)
// {
//     // Crear una nueva hoja de cálculo
//     $spreadsheet = new Spreadsheet();
//     $sheet = $spreadsheet->getActiveSheet();
//     $sheet->setTitle('Resultados Examen Simulacro');

//     // Agregar encabezado
//     $sheet->setCellValue('A1', 'Puesto');
//     $sheet->setCellValue('B1', 'DNI');
//     $sheet->setCellValue('C1', 'Apellidos y Nombres');
//     $sheet->setCellValue('D1', 'Puntaje');

//     // Establecer encabezado en negrita
//     $sheet->getStyle('A1:D1')->getFont()->setBold(true);

//     // Agregar datos
//     foreach ($resultados as $index => $resultado) {
//         $puesto = $index + 1;
//         $nombreCompleto = trim($resultado['apPaterno'] . ' ' . $resultado['apMaterno'] . ', ' . $resultado['nombre']);
//         $puntaje = number_format($resultado['puntaje'], 3, '.', '');

//         $row = $index + 2; // La fila comienza en 2 para evitar el encabezado
//         $sheet->setCellValue('A' . $row, $puesto);
//         $sheet->setCellValue('B' . $row, $resultado['dni']);
//         $sheet->setCellValue('C' . $row, $nombreCompleto);
//         $sheet->setCellValue('D' . $row, $puntaje);
//     }

//     // Autoajustar el ancho de las columnas
//     foreach (range('A', 'D') as $col) {
//         $sheet->getColumnDimension($col)->setAutoSize(true);
//     }

//     // Crear el escritor para guardar el archivo
//     $writer = new Xlsx($spreadsheet);

//     // Generar el archivo en formato de salida
//     $excelFile = \php://output;

//     // Devolver el archivo Excel como una respuesta
//     return Response::stream(
//         function () use ($writer) {
//             $writer->save('php://output');
//         },
//         200,
//         [
//             'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
//             'Content-Disposition' => 'attachment;filename="resultados.xlsx"',
//             'Cache-Control' => 'max-age=0',
//         ]
//     );
// }