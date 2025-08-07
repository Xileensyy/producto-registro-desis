<?php
require_once 'db.php';
require_once 'modelos/bodegas.php';
require_once 'modelos/sucursales.php';
require_once 'modelos/monedas.php';

$bodegas = getBodegas($pdo);
$monedas = getMonedas($pdo);

$sucursales = []; 

