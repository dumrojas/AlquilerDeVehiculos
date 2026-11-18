<?php
require_once __DIR__ . "/../../../configure/bootstrap.php";

use app\controllers\config\ControllersConfig;
use app\controllers\exceptions\DatosInvalidos;

$controller = ControllersConfig::get_vehiculos_controller();

try {
    $controller->save_vehiculo($_POST);
    header("Location: ../inicio.php?tipo=ok&mensaje=" . urlencode("Vehículo registrado correctamente."));
} catch (DatosInvalidos $ex) {
    header("Location: ../form.php?mensaje=" . urlencode($ex->getMessage()));
} catch (Throwable $ex) {
    header("Location: ../form.php?mensaje=" . urlencode("No se pudo registrar el vehículo: " . $ex->getMessage()));
}
exit;
