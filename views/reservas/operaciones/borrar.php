<?php
require_once __DIR__ . "/../../../configure/bootstrap.php";

use app\controllers\config\ControllersConfig;
use app\controllers\exceptions\DatosInvalidos;

$controller = ControllersConfig::get_reservas_controller();

try {
    $controller->delete_reserva((int) ($_GET["id"] ?? 0));
    header("Location: ../inicio.php?tipo=ok&mensaje=" . urlencode("Reserva eliminada."));
} catch (DatosInvalidos $ex) {
    header("Location: ../inicio.php?mensaje=" . urlencode($ex->getMessage()));
} catch (Throwable $ex) {
    header("Location: ../inicio.php?mensaje=" . urlencode("No se pudo eliminar la reserva: " . $ex->getMessage()));
}
exit;
