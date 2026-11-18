# Alquiler de Vehículos

Aplicación web MVC en PHP + MySQL para gestionar el alquiler de vehículos:
vehículos, clientes, reservas, devoluciones, disponibilidad e historial.

Proyecto construido siguiendo la arquitectura por capas del proyecto
`programacion_g1_s2_2026-mvc`, adaptada a las entidades de la base de datos
`alquiler_vehiculos_db`.

## Requisitos

- XAMPP con Apache y MySQL/MariaDB iniciados
- PHP 8.x (incluido en XAMPP)
- Base de datos `alquiler_vehiculos_db` importada

## Instalación

1. Copiar el proyecto en `C:\xampp\htdocs\alquiler_vehiculos` (ya está).
2. Iniciar Apache y MySQL desde el panel de XAMPP.
3. Importar la base de datos:

```powershell
Get-Content .\database\alquiler_vehiculos_db.sql -Raw | & "C:\xampp\mysql\bin\mysql.exe" -u root
```

4. Abrir <http://localhost/alquiler_vehiculos/> en el navegador.

Las credenciales de conexión están en `configure/data_base/conexion.php`
(`localhost`, usuario `root`, sin contraseña, puerto `3306`), que son las de
XAMPP por defecto.

## Funcionalidades

| Requisito del documento | Dónde está |
| --- | --- |
| Registrar vehículos (marca, modelo, año, categoría, estado) | `views/vehiculos/` |
| Registrar clientes (nombre, contacto, licencia) | `views/clientes/` |
| Reservas por periodo con fechas de inicio y fin | `views/reservas/` |
| Devolución de vehículos actualizando su estado | `views/reservas/operaciones/devolver.php` |
| Historial de alquileres por vehículo y por cliente | `views/reportes/inicio.php` |
| Consulta de vehículos disponibles | `views/reportes/inicio.php` |
| Cambio automático de estado del vehículo | `controllers/reservas/reservas_controller.php` |
| Evitar conflictos de reservas (solapamiento) | `ReservasRepository::hay_solapamiento()` |

Reglas de negocio implementadas:

- Un vehículo en `mantenimiento` no se puede reservar.
- No se permiten dos reservas `activas` solapadas para el mismo vehículo.
- Al crear una reserva vigente para hoy, el vehículo pasa a `alquilado`.
- Al devolver o cancelar, el vehículo vuelve a `disponible` (salvo que esté en
  mantenimiento o tenga otra reserva vigente).
- No se pueden eliminar vehículos ni clientes con reservas registradas.

## Arquitectura

```
index.php                  Redirección a views/inicio.php
configure/
  data_base/conexion.php   Conexión mysqli (DB::conectar)
  bootstrap.php            Carga todas las capas
controllers/
  config/                  Fábrica de controladores
  mappers/                 Request -> entidad
  exceptions/              DatosInvalidos
  vehiculos/ clientes/ reservas/
models/
  entities/                Vehiculo, Cliente, Reserva
  mappers/                 Fila de BD -> entidad
  database/                Acceso a datos con sentencias preparadas
  repositories/             Consultas SQL por agregado
  config/                  Fábrica de repositorios
views/                     Listados, formularios y operaciones
public/css/style.css       Estilos
database/                  Script SQL de la base de datos
docs/instrucciones.md      Definición funcional del proyecto
```

Capas: `views` -> `controllers` -> `models/repositories` -> `models/database` -> MySQL.

## Diferencias respecto del proyecto de referencia

- Se usa un único archivo de arranque (`configure/bootstrap.php`) en lugar de
  repetir los `require` en cada vista.
- Las consultas usan sentencias preparadas (`mysqli_prepare`) en vez de
  concatenar SQL, para evitar inyección SQL.
- La conexión se comparte (una sola instancia) para poder usar transacciones al
  registrar reservas y actualizar el estado del vehículo en la misma operación.
