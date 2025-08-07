<?php
function getSucursalesByBodega(PDO $pdo, int $bodega_id): array {
    $stmt = $pdo->prepare("SELECT id, nombre FROM sucursales WHERE bodega_id = :bodega_id ORDER BY nombre");
    $stmt->execute(['bodega_id' => $bodega_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
