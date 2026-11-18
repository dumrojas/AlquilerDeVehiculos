<?php
require_once __DIR__ . "/../../configure/bootstrap.php";

use app\controllers\config\ControllersConfig;

$vehiculos = ControllersConfig::get_vehiculos_controller()->get_vehiculos();

$titulo = "Vehículos";
$raiz = "../../";
require __DIR__ . "/../partials/header.php";
?>

<div class="cabecera-pagina">
    <div>
        <h2>Vehículos</h2>
        <p>Flota completa con el estado de cada unidad en tiempo real.</p>
    </div>
    <a class="boton" href="form.php">Nuevo vehículo</a>
</div>

<?php if (count($vehiculos) === 0) : ?>
    <p class="vacio">No hay vehículos registrados todavía. Empieza agregando el primero.</p>
<?php else : ?>
    <section class="grid-vehiculos">
        <?php foreach ($vehiculos as $vehiculo) : ?>
            <article class="vehiculo-card">
                <div class="vehiculo-card-top">
                    <div>
                        <h3><?php echo htmlspecialchars($vehiculo->get_marca() . " " . $vehiculo->get_modelo()); ?></h3>
                        <p class="vehiculo-sub"><?php echo $vehiculo->get_anio(); ?></p>
                    </div>
                    <span class="etiqueta estado-<?php echo htmlspecialchars($vehiculo->get_estado()); ?>">
                        <?php echo htmlspecialchars($vehiculo->get_estado()); ?>
                    </span>
                </div>

                <div class="vehiculo-datos">
                    <span class="chip">
                        <?php echo $vehiculo->get_categoria() !== "" ? htmlspecialchars($vehiculo->get_categoria()) : "Sin categoría"; ?>
                    </span>
                    <span class="chip">Unidad #<?php echo $vehiculo->get_id(); ?></span>
                </div>

                <div class="vehiculo-card-acciones">
                    <a class="boton boton-mini"
                        href="form.php?id=<?php echo $vehiculo->get_id(); ?>">Editar</a>
                    <a class="boton boton-mini boton-peligro"
                        href="operaciones/borrar.php?id=<?php echo $vehiculo->get_id(); ?>"
                        onclick="return confirm('¿Eliminar este vehículo?');">Eliminar</a>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php endif; ?>

<?php require __DIR__ . "/../partials/footer.php"; ?>
