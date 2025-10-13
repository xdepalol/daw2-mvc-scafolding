<?php
require __DIR__ . '/bootstrap.php';

include_once 'database/database.php';
include_once 'model/DAL/equipo-dao.php';
include_once 'model/equipo.php';

use App\Models\Equipo;

$equipo = EquipoDao::GetEquipoByID(20);
var_dump($equipo);

