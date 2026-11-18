<?php
require_once __DIR__ . "/../../../configure/bootstrap.php";

use app\controllers\config\ControllersConfig;
use app\controllers\exceptions\DatosInvalidos;

$controller = ControllersConfig::get_reservas_controller();

try {
    $controller->update_reserva($_POST);
    header("Location: ../inicio.php?tipo=ok&mensaje=" . urlencode("Reserva actualizada correctamente."));
} catch (DatosInvalidos $ex) {
    $id = (int) ($_POST["id"] ?? 0);
    header("Location: ../form.php?id=" . $id . "&mensaje=" . urlencode($ex->getMessage()));
} catch (Throwable $ex) {
    $id = (int) ($_POST["id"] ?? 0);
    header("Location: ../form.php?id=" . $id . "&mensaje=" . urlencode("No se pudo actualizar: " . $ex->getMessage()));
}
exit;
