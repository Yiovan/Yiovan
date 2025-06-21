<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Luego el resto:
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        $stmt = $pdo->query("SELECT * FROM tareas ORDER BY id DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $titulo = $data['titulo'] ?? '';
        if ($titulo !== '') {
            $stmt = $pdo->prepare("INSERT INTO tareas (titulo) VALUES (:titulo)");
            $stmt->execute([':titulo' => $titulo]);
            echo json_encode(["mensaje" => "Tarea agregada"]);
        }
        break;

    case 'PUT':
        $id = $data['id'] ?? null;
        $completado = $data['completado'] ?? null;
        if ($id !== null) {
            $stmt = $pdo->prepare("UPDATE tareas SET completado = :completado WHERE id = :id");
            $stmt->execute([':completado' => $completado, ':id' => $id]);
            echo json_encode(["mensaje" => "Tarea actualizada"]);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $params);
        $id = $params['id'] ?? null;
        if ($id !== null) {
            $stmt = $pdo->prepare("DELETE FROM tareas WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(["mensaje" => "Tarea eliminada"]);
        }
        break;
}
?>
