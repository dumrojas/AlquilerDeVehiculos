<?php

require_once __DIR__ . "/../models/entities/vehiculo.php";
require_once __DIR__ . "/../models/entities/cliente.php";
require_once __DIR__ . "/../models/entities/reserva.php";

require_once __DIR__ . "/../models/mappers/vehiculo_mappers.php";
require_once __DIR__ . "/../models/mappers/cliente_mappers.php";
require_once __DIR__ . "/../models/mappers/reserva_mappers.php";

require_once __DIR__ . "/../models/database/database_interface.php";
require_once __DIR__ . "/../models/database/database.php";

require_once __DIR__ . "/../models/repositories/vehiculos_repository/vehiculos_repository_interface.php";
require_once __DIR__ . "/../models/repositories/vehiculos_repository/vehiculos_repository.php";
require_once __DIR__ . "/../models/repositories/clientes_repository/clientes_repository_interface.php";
require_once __DIR__ . "/../models/repositories/clientes_repository/clientes_repository.php";
require_once __DIR__ . "/../models/repositories/reservas_repository/reservas_repository_interface.php";
require_once __DIR__ . "/../models/repositories/reservas_repository/reservas_repository.php";

require_once __DIR__ . "/../models/config/models_config.php";

require_once __DIR__ . "/../controllers/exceptions/exceptions.php";
require_once __DIR__ . "/../controllers/mappers/vehiculos_mapper_controller.php";
require_once __DIR__ . "/../controllers/mappers/clientes_mapper_controller.php";
require_once __DIR__ . "/../controllers/mappers/reservas_mapper_controller.php";
require_once __DIR__ . "/../controllers/vehiculos/vehiculos_controller_interface.php";
require_once __DIR__ . "/../controllers/vehiculos/vehiculos_controller.php";
require_once __DIR__ . "/../controllers/clientes/clientes_controller_interface.php";
require_once __DIR__ . "/../controllers/clientes/clientes_controller.php";
require_once __DIR__ . "/../controllers/reservas/reservas_controller_interface.php";
require_once __DIR__ . "/../controllers/reservas/reservas_controller.php";
require_once __DIR__ . "/../controllers/config/controllers_config.php";
