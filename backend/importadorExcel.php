<?php

use Shuchkin\SimpleXLSX;
/**
 * Clase ImportadorExcel
 * Maneja la importación de datos desde archivos Excel usando SimpleXLSX
 */
class ImportadorExcel {
    private $archivo;
    
    // Constructor
    public function __construct($archivo = null) {
        $this->archivo = $archivo;
    }
    
    // Establecer archivo
    public function setArchivo($archivo) {
        $this->archivo = $archivo;
    }
    
    // Importar datos a un objeto Nómina
    public function importar() {
        // Verificar que el archivo existe
        if (!file_exists($this->archivo)) {
            throw new Exception("El archivo no existe: " . $this->archivo);
        }
        
        // Cargar la librería SimpleXLSX
        require_once 'simplexlsx/SimpleXLSX.php';

       

        if (!class_exists('Shuchkin\SimpleXLSX')) {
            throw new Exception("La clase SimpleXLSX no está disponible.");
        }


        // Crear una nueva nómina
        $nomina = new Nomina();
        
        // Leer el archivo Excel
        if ($xlsx = SimpleXLSX::parse($this->archivo)) {
            $filas = $xlsx->rows();
            
            // La primera fila contiene el encabezado de la nómina
            // Las filas de datos comienzan en la fila 3 (índice 2)
            // Buscamos las filas que tienen datos de empleados
            for ($i = 2; $i < count($filas); $i++) {
                $fila = $filas[$i];
                
                // Si el nombre del empleado está vacío, no procesar
                if (empty($fila[0])) {
                    continue;
                }
                
                // Crear un nuevo empleado con los datos de la fila
                $empleado = new Empleado([
                    'nombre' => $fila[0],
                    'salario' => (float)str_replace(['$', ',', ' '], '', $fila[1]),
                    'diasLaborados' => (int)$fila[2],
                    'totalMensual' => (float)str_replace(['$', ',', ' '], '', $fila[3]),
                    'horasExtras' => (int)$fila[4],
                    'valorHorasExtras' => (float)str_replace(['$', ',', ' '], '', $fila[5]),
                    'comisiones' => (float)str_replace(['$', ',', ' '], '', $fila[6]),
                    'totalDevengado' => (float)str_replace(['$', ',', ' '], '', $fila[7]),
                    'libranza' => (float)str_replace(['$', ',', ' '], '', $fila[8]),
                    'salud' => (float)str_replace(['$', ',', ' '], '', $fila[9]),
                    'pension' => (float)str_replace(['$', ',', ' '], '', $fila[10]),
                    'sindicatos' => (float)str_replace(['$', ',', ' '], '', $fila[11]),
                    'totalDeducido' => (float)str_replace(['$', ',', ' '], '', $fila[12]),
                    'netoAPagar' => (float)str_replace(['$', ',', ' '], '', $fila[13])
                ]);
                
                // Agregar el empleado a la nómina
                $nomina->agregarEmpleado($empleado);
            }
            
                /// Buscar los valores de hora en las filas inferiores
                $valoresHora = ['diurna' => 0, 'nocturna' => 0, 'dominical' => 0];

                for ($i = count($filas) - 5; $i < count($filas); $i++) {
                    $fila = $filas[$i];
                    if (isset($fila[0]) && strpos(strtoupper($fila[0]), 'HORA DIURNA') !== false) {
                        $valoresHora['diurna'] = (float)str_replace(['$', ',', ' '], '', $fila[1]);
                    }
                    if (isset($fila[0]) && strpos(strtoupper($fila[0]), 'HORA NOCTURNA') !== false) {
                        $valoresHora['nocturna'] = (float)str_replace(['$', ',', ' '], '', $fila[1]);
                    }
                    if (isset($fila[0]) && strpos(strtoupper($fila[0]), 'HORA DOMINICAL') !== false) {
                        $valoresHora['dominical'] = (float)str_replace(['$', ',', ' '], '', $fila[1]);
                    }
                }

                $nomina->setValoresHora($valoresHora['diurna'], $valoresHora['nocturna'], $valoresHora['dominical']);

            
            return $nomina;
        } else {
            throw new Exception("Error al leer el archivo Excel: " . SimpleXLSX::parseError());
        }
    }
}
?>