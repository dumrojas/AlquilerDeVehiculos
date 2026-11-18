<?php
require_once __DIR__ . "/../../configure/bootstrap.php";

use app\controllers\config\ControllersConfig;

$titulo = "Crear cliente";
$action = "operaciones/registrar.php";
$id = 0;
$nombre = "";
$telefono = "";
$correo = "";
$numero_licencia = "";

if (!empty($_GET["id"])) {
    $titulo = "Modificar cliente";
    $action = "operaciones/modificar.php";
    $id = (int) $_GET["id"];

    $cliente = ControllersConfig::get_clientes_controller()->get_cliente($id);
    $nombre = $cliente->get_nombre();
    $telefono = $cliente->get_telefono();
    $correo = $cliente->get_correo();
    $numero_licencia = $cliente->get_numero_licencia();
}

$raiz = "../../";
require __DIR__ . "/../partials/header.php";
?>

<div class="cabecera-pagina">
    <div>
        <h2><?php echo htmlspecialchars($titulo); ?></h2>
        <p>Datos de contacto y licencia del cliente.</p>
    </div>
</div>

<form class="tarjeta" action="<?php echo $action; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <div class="campo">
        <label for="nombre">Nombre completo:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>" required>
    </div>

    <div class="campo">
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($telefono); ?>">
    </div>

    <div class="campo">
        <label for="correo">Correo:</label>
        <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($correo); ?>">
    </div>

    <div class="campo">
        <label for="numero_licencia">Número de licencia:</label>
        <input type="text" id="numero_licencia" name="numero_licencia"
            value="<?php echo htmlspecialchars($numero_licencia); ?>">
    </div>

    <div class="acciones">
        <button type="submit">Guardar</button>
        <a class="boton boton-secundario" href="inicio.php">Cancelar</a>
    </div>
</form>

<?php require __DIR__ . "/../partials/footer.php"; ?>
