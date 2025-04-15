
<?php
/**
 * Clase Nomina
 * Maneja una colección de empleados y operaciones relacionadas con la nómina
 */
class Nomina {
    private $empleados = [];
    private $totalNomina = 0;
    private $valorHoraDiurna = 0;
    private $valorHoraNocturna = 0;
    private $valorHoraDominical = 0;
    
    // Constructor
    public function __construct() {
        $this->empleados = [];
    }
    
    // Agregar un empleado a la nómina
    public function agregarEmpleado(Empleado $empleado) {
        $this->empleados[] = $empleado;
        $this->calcularTotalNomina();
    }
    
    // Obtener todos los empleados
    public function getEmpleados() {
        return $this->empleados;
    }
    
    // Calcular el total de la nómina
    public function calcularTotalNomina() {
        $this->totalNomina = 0;
        foreach ($this->empleados as $empleado) {
            $this->totalNomina += $empleado->getNetoAPagar();
        }
        return $this->totalNomina;
    }
    
    // Establecer valores de horas
    public function setValoresHora($diurna, $nocturna, $dominical) {
        $this->valorHoraDiurna = $diurna;
        $this->valorHoraNocturna = $nocturna;
        $this->valorHoraDominical = $dominical;
    }
    
    // Obtener valores de horas
    public function getValoresHora() {
        return [
            'diurna' => $this->valorHoraDiurna,
            'nocturna' => $this->valorHoraNocturna,
            'dominical' => $this->valorHoraDominical
        ];
    }
    
    // Guardar nómina en formato JSON
    public function guardarJSON($archivo) {
        $datos = [
            'empleados' => array_map(function($empleado) {
                return $empleado->toArray();
            }, $this->empleados),
            'totalNomina' => $this->totalNomina,
            'valoresHora' => [
                'diurna' => $this->valorHoraDiurna,
                'nocturna' => $this->valorHoraNocturna,
                'dominical' => $this->valorHoraDominical
            ]
        ];
        
        $json = json_encode($datos, JSON_PRETTY_PRINT);
        file_put_contents($archivo, $json);
    }
    
    // Cargar nómina desde formato JSON
    public function cargarJSON($archivo) {
        if (file_exists($archivo)) {
            $json = file_get_contents($archivo);
            $datos = json_decode($json, true);
            
            $this->empleados = [];
            foreach ($datos['empleados'] as $empleadoDatos) {
                $this->empleados[] = new Empleado($empleadoDatos);
            }
            
            $this->totalNomina = $datos['totalNomina'];
            $this->valorHoraDiurna = $datos['valoresHora']['diurna'];
            $this->valorHoraNocturna = $datos['valoresHora']['nocturna'];
            $this->valorHoraDominical = $datos['valoresHora']['dominical'];
            
            return true;
        }
        return false;
    }
}
?>