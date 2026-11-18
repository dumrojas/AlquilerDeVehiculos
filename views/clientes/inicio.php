<?php
require_once __DIR__ . "/../../configure/bootstrap.php";

use app\controllers\config\ControllersConfig;

$clientes = ControllersConfig::get_clientes_controller()->get_clientes();

$titulo = "Clientes";
$raiz = "../../";
require __DIR__ . "/../partials/header.php";
?>

<div class="cabecera-pagina">
    <div>
        <h2>Clientes</h2>
        <p>Personas registradas para realizar reservas.</p>
    </div>
    <a class="boton" href="form.php">Nuevo cliente</a>
</div>

<?php if (count($clientes) === 0) : ?>
    <p class="vacio">No hay clientes registrados todavía.</p>
<?php else : ?>
    <div class="tabla-envoltura">
        <table class="tabla-acciones">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Licencia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente) : ?>
                    <tr>
                        <td><?php echo $cliente->get_id(); ?></td>
                        <td><?php echo htmlspecialchars($cliente->get_nombre()); ?></td>
                        <td><?php echo htmlspecialchars($cliente->get_telefono()); ?></td>
                        <td><?php echo htmlspecialchars($cliente->get_correo()); ?></td>
                        <td><?php echo htmlspecialchars($cliente->get_numero_licencia()); ?></td>
                        <td>
                            <div class="celda-acciones">
                                <a class="accion-link"
                                    href="../reportes/inicio.php?cliente_id=<?php echo $cliente->get_id(); ?>">Historial</a>
                                <a class="accion-link"
                                    href="form.php?id=<?php echo $cliente->get_id(); ?>">Editar</a>
                                <a class="accion-link peligro"
                                    href="operaciones/borrar.php?id=<?php echo $cliente->get_id(); ?>"
                                    onclick="return confirm('¿Eliminar este cliente?');">Eliminar</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require __DIR__ . "/../partials/footer.php"; ?>
