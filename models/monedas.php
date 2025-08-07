<?php
function getMonedas(PDO $pdo): array {
    $stmt = $pdo->query("SELECT id, nombre FROM monedas ORDER BY nombre");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
