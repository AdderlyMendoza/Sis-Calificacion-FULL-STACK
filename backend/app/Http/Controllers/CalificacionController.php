<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\FichasIdentificacion;
use App\Models\FichasRespuestas;
use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CalificacionController extends Controller
{
    public function traerDatos()
    {
        try {
            // Log::info("Iniciando la recolección de datos...");
            $resultados = [];
            $idenIdenticacion = FichasIdentificacion::pluck('litho');

            // Log::info("Identificaciones obtenidas: ", ['litho_count' => $idenIdenticacion->count()]);

            foreach ($idenIdenticacion as $litho) {
                // Log::info("Procesando litho: ", ['litho' => $litho]);

                $fichaIdentificacion = FichasIdentificacion::where('litho', $litho)->first();
                if ($fichaIdentificacion) {
                    $dni = $fichaIdentificacion->dni;

                    // Log::info("DNI encontrado: ", ['dni' => $dni]);

                    $postulante = Postulante::where('dni', $dni)->first();
                    if ($postulante) {
                        $resultados[] = [
                            'dni' => $dni,
                            'nombre' => $postulante->nombre,
                            'apPaterno' => $postulante->paterno,
                            'apMaterno' => $postulante->materno,
                            'puntaje' => optional(FichasRespuestas::where('litho', $litho)->first())->puntaje,
                        ];
                    } else {
                        Log::warning("Postulante no encontrado para DNI: ", ['dni' => $dni]);
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
        $resultados = $this->traerDatos();

        switch ($tipo) {
            case 'excel':
                // Lógica para exportar a Excel
                return $this->exportarExcel($resultados);
            case 'pdf':
                // Lógica para exportar a PDF
                return $this->exportarPdf($resultados);
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

    public function exportarPdf($resultados) {}

    public function exportarExcel($resultados) {}
}
