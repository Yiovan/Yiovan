<?php
require 'db.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        $stmt = $pdo->query("SELECT * FROM tareas ORDER BY id DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        break;

    case 'POST':
        $titulo = $input['titulo'] ?? '';
        if ($titulo !== '') {
            $stmt = $pdo->prepare("INSERT INTO tareas (titulo) VALUES (:titulo)");
            $stmt->execute([':titulo' => $titulo]);
            echo json_encode(["mensaje" => "Tarea creada"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Título es requerido"]);
        }
        break;

    case 'PUT':
        $id = $input['id'] ?? null;
        $completado = $input['completado'] ?? null;
        if ($id !== null && $completado !== null) {
            $stmt = $pdo->prepare("UPDATE tareas SET completado = :completado WHERE id = :id");
            $stmt->execute([':completado' => $completado, ':id' => $id]);
            echo json_encode(["mensaje" => "Tarea actualizada"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "ID y estado requeridos"]);
        }
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $params);
        $id = $params['id'] ?? null;
        if ($id !== null) {
            $stmt = $pdo->prepare("DELETE FROM tareas WHERE id = :id");
            $stmt->execute([':id' => $id]);
            echo json_encode(["mensaje" => "Tarea eliminada"]);
        } else {
            http_response_code(400);
            echo json_encode(["error" => "ID requerido para eliminar"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        break;
}
?>
