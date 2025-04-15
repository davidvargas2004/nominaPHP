<?php
/**
 * Clase Empleado
 * Representa a un empleado individual con todos sus datos de nómina
 */
class Empleado {
    private $nombre;
    private $salario;
    private $diasLaborados;
    private $totalMensual;
    private $horasExtras;
    private $valorHorasExtras;
    private $comisiones;
    private $totalDevengado;
    private $libranza;
    private $salud;
    private $pension;
    private $sindicatos;
    private $totalDeducido;
    private $netoAPagar;
    
    // Constructor
    public function __construct($datos = []) {
        $this->nombre = $datos['nombre'] ?? '';
        $this->salario = $datos['salario'] ?? 0;
        $this->diasLaborados = $datos['diasLaborados'] ?? 0;
        $this->totalMensual = $datos['totalMensual'] ?? 0;
        $this->horasExtras = $datos['horasExtras'] ?? 0;
        $this->valorHorasExtras = $datos['valorHorasExtras'] ?? 0;
        $this->comisiones = $datos['comisiones'] ?? 0;
        $this->totalDevengado = $datos['totalDevengado'] ?? 0;
        $this->libranza = $datos['libranza'] ?? 0;
        $this->salud = $datos['salud'] ?? 0;
        $this->pension = $datos['pension'] ?? 0;
        $this->sindicatos = $datos['sindicatos'] ?? 0;
        $this->totalDeducido = $datos['totalDeducido'] ?? 0;
        $this->netoAPagar = $datos['netoAPagar'] ?? 0;
    }
    
    // Getters y Setters
    public function getNombre() {
        return $this->nombre;
    }
    
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    
    public function getSalario() {
        return $this->salario;
    }
    
    public function setSalario($salario) {
        $this->salario = $salario;
    }
    
    public function getDiasLaborados() {
        return $this->diasLaborados;
    }
    
    public function setDiasLaborados($diasLaborados) {
        $this->diasLaborados = $diasLaborados;
    }
    
    public function getNetoAPagar() {
        return $this->netoAPagar;
    }
    
    public function setNetoAPagar($netoAPagar) {
        $this->netoAPagar = $netoAPagar;
    }
    
    public function getHorasExtras() {
        return $this->horasExtras;
    }
    
    public function setHorasExtras($horasExtras) {
        $this->horasExtras = $horasExtras;
    }
    
    public function getValorHorasExtras() {
        return $this->valorHorasExtras;
    }
    
    public function setValorHorasExtras($valorHorasExtras) {
        $this->valorHorasExtras = $valorHorasExtras;
    }
    
    public function getComisiones() {
        return $this->comisiones;
    }
    
    public function setComisiones($comisiones) {
        $this->comisiones = $comisiones;
    }
    
    public function getTotalDevengado() {
        return $this->totalDevengado;
    }
    
    public function setTotalDevengado($totalDevengado) {
        $this->totalDevengado = $totalDevengado;
    }
    
    public function getLibranza() {
        return $this->libranza;
    }
    
    public function setLibranza($libranza) {
        $this->libranza = $libranza;
    }
    
    public function getSalud() {
        return $this->salud;
    }
    
    public function setSalud($salud) {
        $this->salud = $salud;
    }
    
    public function getPension() {
        return $this->pension;
    }
    
    public function setPension($pension) {
        $this->pension = $pension;
    }
    
    public function getSindicatos() {
        return $this->sindicatos;
    }
    
    public function setSindicatos($sindicatos) {
        $this->sindicatos = $sindicatos;
    }
    
    public function getTotalDeducido() {
        return $this->totalDeducido;
    }
    
    public function setTotalDeducido($totalDeducido) {
        $this->totalDeducido = $totalDeducido;
    }
    
    public function getTotalMensual() {
        return $this->totalMensual;
    }
    
    public function setTotalMensual($totalMensual) {
        $this->totalMensual = $totalMensual;
    }
    
    // Método para obtener todos los datos como array
    public function toArray() {
        return [
            'nombre' => $this->nombre,
            'salario' => $this->salario,
            'diasLaborados' => $this->diasLaborados,
            'totalMensual' => $this->totalMensual,
            'horasExtras' => $this->horasExtras,
            'valorHorasExtras' => $this->valorHorasExtras,
            'comisiones' => $this->comisiones,
            'totalDevengado' => $this->totalDevengado,
            'libranza' => $this->libranza,
            'salud' => $this->salud,
            'pension' => $this->pension,
            'sindicatos' => $this->sindicatos,
            'totalDeducido' => $this->totalDeducido,
            'netoAPagar' => $this->netoAPagar
        ];
    }
}
?>