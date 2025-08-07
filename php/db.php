<?php
// Cargar configuracion
$config = require __DIR__ . '/../config.php';

$host = $config['host'];
$port = $config['port'];
$dbname = $config['dbname'];
$user = $config['user'];
$password = $config['password'];

$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexion exitosa a la base de datos PostgreSQL con PDO.";
} catch (PDOException $e) {
    die("Error en la conexion: " . $e->getMessage());
}
