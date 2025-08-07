<?php
function getBodegas(PDO $pdo): array {
    $stmt = $pdo->query("SELECT id, nombre FROM bodegas ORDER BY nombre");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
