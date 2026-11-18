<?php
require_once __DIR__ . "/../../../configure/bootstrap.php";

use app\controllers\config\ControllersConfig;
use app\controllers\exceptions\DatosInvalidos;

$controller = ControllersConfig::get_clientes_controller();

try {
    $controller->delete_cliente((int) ($_GET["id"] ?? 0));
    header("Location: ../inicio.php?tipo=ok&mensaje=" . urlencode("Cliente eliminado."));
} catch (DatosInvalidos $ex) {
    header("Location: ../inicio.php?mensaje=" . urlencode($ex->getMessage()));
} catch (Throwable $ex) {
    header("Location: ../inicio.php?mensaje=" . urlencode("No se pudo eliminar: " . $ex->getMessage()));
}
exit;
