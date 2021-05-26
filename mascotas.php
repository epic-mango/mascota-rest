<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Allow, Access-Control-Allow-Origin");
header("Access-Control-Allow-Methods: GET, PUT, PUT, DELETE, OPTIONS, HEAD");
header("Allow: GET, PUT, PUT, DELETE, OPTIONS, HEAD");
require_once "conexion.php";
require_once "librerias/jwt.php";

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    exit();
}

$header = apache_request_headers();
$jwt = $header['Authorization'];
if (JWT::verify($jwt, Config::SECRET) != 0) {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}
$data = JWT::get_data($jwt, Config::SECRET);

$metodo = $_SERVER["REQUEST_METHOD"];

switch ($metodo) {
    case  "POST":
        if (isset($_POST['nombre']) && isset($_POST['especie']) && isset($_POST['raza']) && isset($_POST['nacimiento'])) {
            $c = conexion();

            $stm = $c->prepare("INSERT INTO Mascotas (nombre, especie, raza, nacimiento, usuario) 
            VALUES (:nombre, :especie, :raza, :nacimiento, :usuario);");

            $stm->bindValue(":nombre", $_POST['nombre']);
            $stm->bindValue(":especie", $_POST['especie']);
            $stm->bindValue(":raza", $_POST['raza']);
            $stm->bindValue(":nacimiento", $_POST['nacimiento']);
            $stm->bindValue(":usuario", $data['id']);

            try {
                $r = $stm->execute();
                $r = array("estado" => "true", "id" => $c->lastInsertId());
            } catch (PDOException $e) {
                //No se insertó la mascota
                $r = array("estado" => "false", "id" => $e->getCode());
            }

            header("HTTP/1.1 200 OK");
            echo (json_encode($r));
        } else {
            header("HTTP/1.1 400 Bad Request");
        }
        break;

    case "GET":
        $c = conexion();

        $stm = $c->prepare("SELECT * FROM mascotas WHERE usuario = :id");
        $stm->bindValue(":id", $data['id']);
        $stm->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stm->execute();

        if ($result) {
            header("HTTP/1.1 200 OK");
            echo (json_encode(
                array(
                    "estado" => "true",
                    "datos" => $stm->fetchAll()
                )
            ));
        } else {
            header("HTTP/1.1 204 No Content");
        }
        break;

    case "PUT":
        parse_str(file_get_contents("php://input"), $PUT);

        if (isset($PUT['id']) && isset($PUT['nombre']) && isset($PUT['especie']) && isset($PUT['raza']) && isset($PUT['nacimiento'])) {
            $c = conexion();
            $s = $c->prepare("UPDATE mascotas SET nombre=:nom, especie = :esp, raza = :raz, nacimiento=:nac WHERE id = :id AND usuario = :usu");
            $s->bindValue(":nom", $PUT['nombre']);
            $s->bindValue(":esp", $PUT['especie']);
            $s->bindValue(":raz", $PUT['raza']);
            $s->bindValue(":nac", $PUT['nacimiento']);
            $s->bindValue(":id", $PUT['id']);
            $s->bindValue(":usu", $data['id']);

            echo (json_encode(array('estado' => $s->execute())));
        } else {
            header("HTTP/1.1 400 Bad Request");
        }
        break;

    case "DELETE":
        if (isset($_GET['id'])) {
            $c = conexion();
            $s = $c->prepare("DELETE FROM mascotas WHERE id = :id AND usuario = :usu");
            $s->bindValue(":id", $_GET['id']);
            $s->bindValue(":usu", $data['id']);

            echo (json_encode(array('estado' => $s->execute())));
        } else {
            header("HTTP/1.1 400 Bad Request");
        }
        break;

    default:
        header("HTTP/1.1 400 Bad Request");
        break;
}
