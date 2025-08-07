<?php
header('Content-Type: application/json');

// Mostrar errores solo para depuracion
//ini_set('display_errors', 1); error_reporting(E_ALL);

require_once 'db.php';

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data) {
        echo json_encode(['success' => false, 'error' => 'Datos inválidos.']);
        exit;
    }

    // Sanitizar y validar los datos
    $codigo = $data['codigo'] ?? '';
    $nombre = $data['nombre'] ?? '';
    $bodega = $data['bodega'] ?? '';
    $sucursal = $data['sucursal'] ?? '';
    $moneda = $data['moneda'] ?? '';
    $precio = $data['precio'] ?? '';
    $materiales = $data['materiales'] ?? [];
    $descripcion = $data['descripcion'] ?? '';

    // Insertar en base de datos
    $stmt = $pdo->prepare("INSERT INTO productos (codigo_producto, nombre_producto, bodega_id, sucursal_id, moneda_id, precio, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$codigo, $nombre, $bodega, $sucursal, $moneda, $precio, $descripcion]);

    // Insertar materiales (relacion)
    $producto_id = $pdo->lastInsertId();
    $stmtMaterial = $pdo->prepare("INSERT INTO producto_material (producto_id, material_id) VALUES (?, ?)");
    foreach ($materiales as $mat) {
        $stmtMaterial->execute([$producto_id, $mat]);
    }

    echo json_encode(['success' => true]);
    exit;

} catch (PDOException $e) {
    // Manejar error de llave duplicada (codigo SQLSTATE 23505 en PostgreSQL)
    if ($e->getCode() === '23505') {
        echo json_encode(['success' => false, 'error' => "El código '$codigo' ya está registrado."]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error en el servidor: ' . $e->getMessage()]);
    }
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error inesperado: ' . $e->getMessage()]);
    exit;
}
