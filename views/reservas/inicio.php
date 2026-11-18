<?php
require_once __DIR__ . "/../../configure/bootstrap.php";

use app\controllers\config\ControllersConfig;

$controller = ControllersConfig::get_reservas_controller();

$reservas = $controller->get_reservas();
$filtro = $_GET["estado"] ?? "";

if (in_array($filtro, ["activa", "completada", "cancelada"], true)) {
    $reservas = array_values(array_filter(
        $reservas,
        fn($reserva): bool => $reserva->get_estado() === $filtro
    ));
}

$titulo = "Reservas";
$raiz = "../../";
require __DIR__ . "/../partials/header.php";
?>

<div class="cabecera-pagina">
    <div>
        <h2>Reservas</h2>
        <p>Controla alquileres, devoluciones y cancelaciones.</p>
    </div>
    <a class="boton" href="form.php">Nueva reserva</a>
</div>

<div class="acciones">
    <a class="boton boton-mini <?php echo $filtro === "" ? "" : "boton-secundario"; ?>" href="inicio.php">Todas</a>
    <a class="boton boton-mini <?php echo $filtro === "activa" ? "" : "boton-secundario"; ?>"
        href="inicio.php?estado=activa">Activas</a>
    <a class="boton boton-mini <?php echo $filtro === "completada" ? "" : "boton-secundario"; ?>"
        href="inicio.php?estado=completada">Completadas</a>
    <a class="boton boton-mini <?php echo $filtro === "cancelada" ? "" : "boton-secundario"; ?>"
        href="inicio.php?estado=cancelada">Canceladas</a>
</div>

<?php if (count($reservas) === 0) : ?>
    <p class="vacio">No hay reservas para mostrar.</p>
<?php else : ?>
    <div class="tabla-envoltura">
        <table class="tabla-acciones">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Días</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservas as $reserva) : ?>
                    <tr>
                        <td><?php echo $reserva->get_id(); ?></td>
                        <td><?php echo htmlspecialchars($reserva->get_cliente_nombre()); ?></td>
                        <td><?php echo htmlspecialchars($reserva->get_vehiculo_descripcion()); ?></td>
                        <td><?php echo htmlspecialchars($reserva->get_fecha_inicio()); ?></td>
                        <td><?php echo htmlspecialchars($reserva->get_fecha_fin()); ?></td>
                        <td><?php echo $reserva->get_dias(); ?></td>
                        <td>
                            <span class="etiqueta estado-<?php echo htmlspecialchars($reserva->get_estado()); ?>">
                                <?php echo htmlspecialchars($reserva->get_estado()); ?>
                            </span>
                        </td>
                        <td>
                            <div class="celda-acciones">
                                <?php if ($reserva->get_estado() === "activa") : ?>
                                    <a class="accion-link"
                                        href="operaciones/devolver.php?id=<?php echo $reserva->get_id(); ?>"
                                        onclick="return confirm('¿Registrar la devolución del vehículo?');">Devolver</a>
                                    <a class="accion-link"
                                        href="operaciones/cancelar.php?id=<?php echo $reserva->get_id(); ?>"
                                        onclick="return confirm('¿Cancelar esta reserva?');">Cancelar</a>
                                <?php endif; ?>
                                <a class="accion-link"
                                    href="form.php?id=<?php echo $reserva->get_id(); ?>">Editar</a>
                                <a class="accion-link peligro"
                                    href="operaciones/borrar.php?id=<?php echo $reserva->get_id(); ?>"
                                    onclick="return confirm('¿Eliminar el registro de esta reserva?');">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . "/../partials/footer.php"; ?>
