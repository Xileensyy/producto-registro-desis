<?php
require_once 'db.php';

if (isset($_GET['codigo'])) {  
    $codigo = $_GET['codigo'];

    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE codigo = :codigo");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        $existe = $stmt->fetchColumn();

        echo json_encode(['existe' => $existe > 0]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al verificar el codigo en la base de datos.']);
    }
} else {
    echo json_encode(['error' => 'No se recibio el codigo.']);
}
