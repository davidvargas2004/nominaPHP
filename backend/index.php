<?php


// Cargar clases
require_once 'Empleado.php';
require_once 'Nomina.php';
require_once 'ImportadorExcel.php';

// Iniciar sesión para mensajes flash
session_start();

// Definir constantes
define('APP_PATH', dirname(__FILE__));
define('DATA_PATH', APP_PATH . '/data');

// Verificar si existe el directorio de datos y crearlo si no
if (!file_exists(DATA_PATH)) {
    mkdir(DATA_PATH, 0777, true);
}

// Procesar formularios
$mensaje = null;
$tipoMensaje = null;
$nomina = new Nomina();
$archivoNomina = DATA_PATH . '/nomina.json';

// Si ya existe una nómina guardada, cargarla
if (file_exists($archivoNomina)) {
    $nomina->cargarJSON($archivoNomina);
}

// Procesar la subida de archivos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'importar':
                if (isset($_FILES['archivo_excel']) && $_FILES['archivo_excel']['error'] === UPLOAD_ERR_OK) {
                    $archivoTemporal = $_FILES['archivo_excel']['tmp_name'];
                    $archivoDestino = DATA_PATH . '/' . $_FILES['archivo_excel']['name'];
                    
                    // Mover el archivo subido al directorio de datos
                    if (move_uploaded_file($archivoTemporal, $archivoDestino)) {
                        try {
                            $importador = new ImportadorExcel($archivoDestino);
                            $nomina = $importador->importar();
                            $nomina->guardarJSON($archivoNomina);
                            $mensaje = "Datos importados correctamente.";
                            $tipoMensaje = "success";
                        } catch (Exception $e) {
                            $mensaje = "Error al importar datos: " . $e->getMessage();
                            $tipoMensaje = "danger";
                        }
                    } else {
                        $mensaje = "Error al subir el archivo.";
                        $tipoMensaje = "danger";
                    }
                } else {
                    $mensaje = "Por favor seleccione un archivo Excel.";
                    $tipoMensaje = "warning";
                }
                break;
                
            case 'agregar_empleado':
                if (isset($_POST['nombre']) && !empty($_POST['nombre'])) {
                    $empleado = new Empleado([
                        'nombre' => $_POST['nombre'],
                        'salario' => (float)$_POST['salario'],
                        'diasLaborados' => (int)$_POST['dias_laborados'],
                        'totalMensual' => (float)$_POST['total_mensual'],
                        'horasExtras' => (int)$_POST['horas_extras'],
                        'valorHorasExtras' => (float)$_POST['valor_horas_extras'],
                        'comisiones' => (float)$_POST['comisiones'],
                        'totalDevengado' => (float)$_POST['total_devengado'],
                        'libranza' => (float)$_POST['libranza'],
                        'salud' => (float)$_POST['salud'],
                        'pension' => (float)$_POST['pension'],
                        'sindicatos' => (float)$_POST['sindicatos'],
                        'totalDeducido' => (float)$_POST['total_deducido'],
                        'netoAPagar' => (float)$_POST['neto_a_pagar']
                    ]);
                    
                    $nomina->agregarEmpleado($empleado);
                    $nomina->guardarJSON($archivoNomina);
                    $mensaje = "Empleado agregado correctamente.";
                    $tipoMensaje = "success";
                } else {
                    $mensaje = "Por favor ingrese al menos el nombre del empleado.";
                    $tipoMensaje = "warning";
                }
                break;

                case 'limpiar_datos':
                    // Eliminar el archivo de nómina
                    if (file_exists($archivoNomina)) {
                        unlink($archivoNomina);
                    }
                    
                    // Eliminar todos los archivos de Excel importados
                    $archivos = glob(DATA_PATH . '/*.{xls,xlsx}', GLOB_BRACE);
                    foreach ($archivos as $archivo) {
                        unlink($archivo);
                    }
                    
                    // Reiniciar la nómina
                    $nomina = new Nomina();
                    
                    $mensaje = "Todos los datos han sido eliminados correctamente.";
                    $tipoMensaje = "success";
                    break;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Nómina - Importación de Excel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../navbar.css"></link>
    <style>
        .container { max-width: 1200px; }
        .table-responsive { overflow-x: auto; }
        .custom-file-input:lang(es)~.custom-file-label::after { content: "Buscar"; }
    </style>
</head>
<body>

    <ul class="nav justify-content-center my-3">
        <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="../index.html">Inicio ⬅️</a>
        </li>
        
        
    </ul>

    <div class="container mt-4">
        <h1 class="text-center mb-4">Sistema de Nómina</h1>
        
        <?php if ($mensaje): ?>
        <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show" role="alert">
            <?php echo $mensaje; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Importar Nómina desde Excel</h5>
            </div>
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="importar">
                    <div class="mb-3">
                        <label for="archivo_excel" class="form-label">Seleccione el archivo Excel:</label>
                        <input type="file" class="form-control" id="archivo_excel" name="archivo_excel" accept=".xlsx,.xls">
                        <div class="form-text">Seleccione un archivo Excel con el formato de nómina adecuado.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Importar Datos</button>
                </form>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Datos de la Nómina</h5>
                <form action="" method="post" onsubmit="return confirm('¿Está seguro de que desea limpiar todos los datos?');">
                    <input type="hidden" name="action" value="limpiar_datos">
                    <button type="submit" class="btn btn-danger btn-sm">Limpiar Todos los Datos</button>
                </form>
            </div>
            <div class="card-body">
                <?php if (count($nomina->getEmpleados()) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Salario</th>
                                    <th>Días Lab.</th>
                                    <th>Total Mensual</th>
                                    <th>Horas Extras</th>
                                    <th>Valor H.E.</th>
                                    <th>Comisiones</th>
                                    <th>Total Devengado</th>
                                    <th>Libranza</th>
                                    <th>Salud</th>
                                    <th>Pensión</th>
                                    <th>Sindicatos</th>
                                    <th>Total Deducido</th>
                                    <th>Neto a Pagar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($nomina->getEmpleados() as $empleado): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($empleado->getNombre()); ?></td>
                                    <td><?php echo number_format($empleado->getSalario(), 0, ',', '.'); ?></td>
                                    <td><?php echo $empleado->getDiasLaborados(); ?></td>
                                    <td><?php echo number_format($empleado->getTotalMensual(), 0, ',', '.'); ?></td>
                                    <td><?php echo $empleado->getHorasExtras(); ?></td>
                                    <td><?php echo number_format($empleado->getValorHorasExtras(), 0, ',', '.'); ?></td>
                                    <td><?php echo number_format($empleado->getComisiones(), 0, ',', '.'); ?></td>
                                    <td><?php echo number_format($empleado->getTotalDevengado(), 0, ',', '.'); ?></td>
                                    <td><?php echo number_format($empleado->getLibranza(), 0, ',', '.'); ?></td>
                                    <td><?php echo number_format($empleado->getSalud(), 0, ',', '.'); ?></td>
                                    <td><?php echo number_format($empleado->getPension(), 0, ',', '.'); ?></td>
                                    <td><?php echo number_format($empleado->getSindicatos(), 0, ',', '.'); ?></td>
                                    <td><?php echo number_format($empleado->getTotalDeducido(), 0, ',', '.'); ?></td>
                                    <td><?php echo number_format($empleado->getNetoAPagar(), 0, ',', '.'); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-info">
                                <tr>
                                    <td colspan="13" class="text-end"><strong>Total Nómina:</strong></td>
                                    <td><strong><?php echo number_format($nomina->calcularTotalNomina(), 0, ',', '.'); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <h5>Valores de Horas:</h5>
                        <div class="row">
                            <?php $valoresHora = $nomina->getValoresHora(); ?>
                            <div class="col-md-4">
                                <p><strong>Hora Diurna:</strong> $<?php echo number_format($valoresHora['diurna'], 0, ',', '.'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Hora Nocturna:</strong> $<?php echo number_format($valoresHora['nocturna'], 0, ',', '.'); ?></p>
                            </div>
                            <div class="col-md-4">
                                <p><strong>Hora Dominical:</strong> $<?php echo number_format($valoresHora['dominical'], 0, ',', '.'); ?></p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No hay datos de nómina disponibles. Por favor, importe un archivo Excel.
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Formulario para agregar empleado manualmente -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Agregar Empleado Manualmente</h5>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <input type="hidden" name="action" value="agregar_empleado">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="nombre" class="form-label">Nombre:</label>
                            <input type="text" 
                            class="form-control" 
                            id="nombre" 
                            name="nombre" 
                            pattern="[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+" 
                            title="Solo se permiten letras"
                            required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="salario" class="form-label">Salario:</label>
                            <input type="number" class="form-control" id="salario" name="salario" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="dias_laborados" class="form-label">Días Laborados:</label>
                            <input type="number" class="form-control" id="dias_laborados" name="dias_laborados" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="total_mensual" class="form-label">Total Mensual:</label>
                            <input type="number" class="form-control" id="total_mensual" name="total_mensual" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="horas_extras" class="form-label">Horas Extras:</label>
                            <input type="number" class="form-control" id="horas_extras" name="horas_extras">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="valor_horas_extras" class="form-label">Valor Horas Extras:</label>
                            <input type="number" class="form-control" id="valor_horas_extras" name="valor_horas_extras">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="comisiones" class="form-label">Comisiones:</label>
                            <input type="number" class="form-control" id="comisiones" name="comisiones">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="total_devengado" class="form-label">Total Devengado:</label>
                            <input type="number" class="form-control" id="total_devengado" name="total_devengado" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="libranza" class="form-label">Libranza:</label>
                            <input type="number" class="form-control" id="libranza" name="libranza">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="salud" class="form-label">Salud:</label>
                            <input type="number" class="form-control" id="salud" name="salud" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="pension" class="form-label">Pensión:</label>
                            <input type="number" class="form-control" id="pension" name="pension" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="sindicatos" class="form-label">Sindicatos:</label>
                            <input type="number" class="form-control" id="sindicatos" name="sindicatos">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="total_deducido" class="form-label">Total Deducido:</label>
                            <input type="number" class="form-control" id="total_deducido" name="total_deducido" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="neto_a_pagar" class="form-label">Neto a Pagar:</label>
                        <input type="number" class="form-control" id="neto_a_pagar" name="neto_a_pagar" required>
                    </div>
                    
                    <button type="submit" class="btn btn-info">Agregar Empleado</button>
                    </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
