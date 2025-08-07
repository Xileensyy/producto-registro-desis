<?php
require_once 'db.php';

// Verificamos que se haya enviado el parametro 'codigo' por GET
if (isset($_GET['codigo'])) {  
    $codigo = $_GET['codigo']; // Se obtiene el codigo desde la URL

    try {
         // Preparamos la consulta para verificar si el codigo ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM productos WHERE codigo = :codigo");
        $stmt->bindParam(':codigo', $codigo);
        $stmt->execute();
        $existe = $stmt->fetchColumn();

        //true o false
        echo json_encode(['existe' => $existe > 0]);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al verificar el codigo en la base de datos.']);
    }
} else {
    echo json_encode(['error' => 'No se recibio el codigo.']);
}
