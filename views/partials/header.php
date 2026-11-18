<?php
$titulo = $titulo ?? "Alquiler de Vehículos";
$raiz = $raiz ?? "";
$mensaje = $_GET["mensaje"] ?? "";
$tipo_mensaje = ($_GET["tipo"] ?? "error") === "ok" ? "alerta-ok" : "alerta-error";

$ruta_actual = str_replace("\\", "/", $_SERVER["REQUEST_URI"] ?? "");
$seccion = "inicio";
foreach (["vehiculos", "clientes", "reservas", "reportes"] as $clave) {
    if (strpos($ruta_actual, "/" . $clave . "/") !== false) {
        $seccion = $clave;
    }
}

$enlaces = [
    "inicio" => ["Inicio", "views/inicio.php"],
    "vehiculos" => ["Vehículos", "views/vehiculos/inicio.php"],
    "clientes" => ["Clientes", "views/clientes/inicio.php"],
    "reservas" => ["Reservas", "views/reservas/inicio.php"],
    "reportes" => ["Reportes", "views/reportes/inicio.php"],
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($titulo); ?> | AutoRent</title>
    <link rel="stylesheet" href="<?php echo $raiz; ?>public/css/style.css?v=3">
</head>

<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a class="marca" href="<?php echo $raiz; ?>views/inicio.php">
                <span class="marca-icono">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 17h14" />
                        <path d="M4 17v-4l1.8-4.2A2 2 0 0 1 7.6 7.5h8.8a2 2 0 0 1 1.8 1.3L20 13v4" />
                        <circle cx="7" cy="17" r="1.5" />
                        <circle cx="17" cy="17" r="1.5" />
                    </svg>
                </span>
                <span class="marca-texto">
                    <strong>AutoRent</strong>
                    <small>Alquiler de vehículos</small>
                </span>
            </a>

            <nav class="menu">
                <?php foreach ($enlaces as $clave => $enlace) : ?>
                    <a href="<?php echo $raiz . $enlace[1]; ?>"
                        class="<?php echo $seccion === $clave ? "activo" : ""; ?>">
                        <?php echo $enlace[0]; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>

    <main class="contenido">
        <?php if ($mensaje !== "") : ?>
            <p class="alerta <?php echo $tipo_mensaje; ?>"><?php echo htmlspecialchars($mensaje); ?></p>
        <?php endif; ?>
