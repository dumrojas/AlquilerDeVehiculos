<?php
require_once __DIR__ . "/../../configure/bootstrap.php";

use app\controllers\config\ControllersConfig;

$titulo = "Crear vehículo";
$action = "operaciones/registrar.php";
$id = 0;
$marca = "";
$modelo = "";
$anio = (int) date("Y");
$categoria = "";
$estado = "disponible";

if (!empty($_GET["id"])) {
    $titulo = "Modificar vehículo";
    $action = "operaciones/modificar.php";
    $id = (int) $_GET["id"];

    $vehiculo = ControllersConfig::get_vehiculos_controller()->get_vehiculo($id);
    $marca = $vehiculo->get_marca();
    $modelo = $vehiculo->get_modelo();
    $anio = $vehiculo->get_anio();
    $categoria = $vehiculo->get_categoria();
    $estado = $vehiculo->get_estado();
}

$raiz = "../../";
require __DIR__ . "/../partials/header.php";
?>

<div class="cabecera-pagina">
    <div>
        <h2><?php echo htmlspecialchars($titulo); ?></h2>
        <p>Completa los datos del vehículo para guardarlo en la flota.</p>
    </div>
</div>

<form class="tarjeta" action="<?php echo $action; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="campo">
        <label for="marca">Marca:</label>
        <input type="text" id="marca" name="marca" value="<?php echo htmlspecialchars($marca); ?>" required>
    </div>

    <div class="campo">
        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" value="<?php echo htmlspecialchars($modelo); ?>" required>
    </div>

    <div class="campo">
        <label for="anio">Año:</label>
        <input type="number" id="anio" name="anio" min="1900" max="<?php echo (int) date('Y') + 1; ?>"
            value="<?php echo $anio; ?>" required>
    </div>

    <div class="campo">
        <label for="categoria">Categoría:</label>
        <input type="text" id="categoria" name="categoria" value="<?php echo htmlspecialchars($categoria); ?>"
            placeholder="Sedán, SUV, Camioneta...">
    </div>

    <div class="campo">
        <label for="estado">Estado:</label>
        <select id="estado" name="estado">
            <?php foreach (["disponible", "alquilado", "mantenimiento"] as $opcion) : ?>
                <option value="<?php echo $opcion; ?>" <?php echo $estado === $opcion ? "selected" : ""; ?>>
                    <?php echo ucfirst($opcion); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="acciones">
        <button type="submit">Guardar</button>
        <a class="boton boton-secundario" href="inicio.php">Cancelar</a>
    </div>
</form>

<?php require __DIR__ . "/../partials/footer.php"; ?>
