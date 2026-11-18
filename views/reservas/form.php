<?php
require_once __DIR__ . "/../../configure/bootstrap.php";

use app\controllers\config\ControllersConfig;

$clientes = ControllersConfig::get_clientes_controller()->get_clientes();
$vehiculos = ControllersConfig::get_vehiculos_controller()->get_vehiculos();

$titulo = "Crear reserva";
$action = "operaciones/registrar.php";
$id = 0;
$cliente_id = 0;
$vehiculo_id = 0;
$fecha_inicio = date("Y-m-d");
$fecha_fin = date("Y-m-d", strtotime("+1 day"));

if (!empty($_GET["id"])) {
    $titulo = "Modificar reserva";
    $action = "operaciones/modificar.php";
    $id = (int) $_GET["id"];

    $reserva = ControllersConfig::get_reservas_controller()->get_reserva($id);
    $cliente_id = $reserva->get_cliente_id();
    $vehiculo_id = $reserva->get_vehiculo_id();
    $fecha_inicio = $reserva->get_fecha_inicio();
    $fecha_fin = $reserva->get_fecha_fin();
}

$raiz = "../../";
require __DIR__ . "/../partials/header.php";
?>

<div class="cabecera-pagina">
    <div>
        <h2><?php echo htmlspecialchars($titulo); ?></h2>
        <p>El sistema valida que el vehículo no tenga otra reserva activa en esas fechas.</p>
    </div>
</div>

<?php if (count($clientes) === 0 || count($vehiculos) === 0) : ?>
    <p class="alerta alerta-error">
        Para crear una reserva primero debe registrar al menos un cliente y un vehículo.
    </p>
<?php endif; ?>

<form class="tarjeta" action="<?php echo $action; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="campo">
        <label for="cliente_id">Cliente:</label>
        <select id="cliente_id" name="cliente_id" required>
            <option value="">Seleccione un cliente</option>
            <?php foreach ($clientes as $cliente) : ?>
                <option value="<?php echo $cliente->get_id(); ?>"
                    <?php echo $cliente_id === $cliente->get_id() ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($cliente->get_nombre()); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="campo">
        <label for="vehiculo_id">Vehículo:</label>
        <select id="vehiculo_id" name="vehiculo_id" required>
            <option value="">Seleccione un vehículo</option>
            <?php foreach ($vehiculos as $vehiculo) : ?>
                <option value="<?php echo $vehiculo->get_id(); ?>"
                    <?php echo $vehiculo_id === $vehiculo->get_id() ? "selected" : ""; ?>>
                    <?php echo htmlspecialchars($vehiculo->get_descripcion() . " - " . $vehiculo->get_estado()); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="campo">
        <label for="fecha_inicio">Fecha de inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio"
            value="<?php echo htmlspecialchars($fecha_inicio); ?>" required>
    </div>

    <div class="campo">
        <label for="fecha_fin">Fecha de fin:</label>
        <input type="date" id="fecha_fin" name="fecha_fin"
            value="<?php echo htmlspecialchars($fecha_fin); ?>" required>
    </div>

    <div class="acciones">
        <button type="submit">Guardar</button>
        <a class="boton boton-secundario" href="inicio.php">Cancelar</a>
    </div>
</form>

<?php require __DIR__ . "/../partials/footer.php"; ?>
