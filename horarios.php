<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Allow, Access-Control-Allow-Origin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD");
header("Allow: GET, POST, PUT, DELETE, OPTIONS, HEAD");
require_once "conexion.php";
require_once "librerias/jwt.php";

if ($_SERVER["REQUEST_METHOD"] == "OPTIONS") {
    exit();
}

$metodo = $_SERVER["REQUEST_METHOD"];

$header = apache_request_headers();
if (isset($header['Authorization'])) {
    $jwt = $header['Authorization'];
    if (JWT::verify($jwt, Config::SECRET) != 0) {
        header("HTTP/1.1 401 Unauthorized");
        exit();
    }
} else {
    header("HTTP/1.1 401 Unauthorized");
    exit();
}
$data = JWT::get_data($jwt, Config::SECRET);



switch ($metodo) {

    case "GET":

        if (isset($_GET['mac'])) {
            $c = conexion();

            $stm = $c->prepare("SELECT horarios.id, horarios.minuto, horarios.gramos FROM horarios INNER JOIN dispositivos ON horarios.mac = dispositivos.mac INNER JOIN mascotas ON dispositivos.mascota = mascotas.id WHERE mascotas.usuario = :usuario AND dispositivos.mac = :mac;");
            $stm->bindValue(":usuario", $data['id']);
            $stm->bindValue(":mac", $_GET['mac']);
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
        } else {
            echo (json_encode(
                array(
                    "estado" => "false",
                    "datos" => "Faltan datos"
                )
            ));
        }
        break;

    case "POST":

        if (isset($_GET['mac_token']) && isset($_GET['minuto']) && isset($_GET['gramos']) && JWT::verify($_GET['mac_token'], Config::SECRET) == 0) {
            $c = conexion();


            $tokenData = JWT::get_data($_GET['mac_token'], Config::SECRET);
            $mac = $tokenData['mac'];

            $stm = $c->prepare("INSERT INTO horarios (mac, minuto,gramos) VALUES (:mac,:minuto,:gramos);");
            $stm->bindValue(":minuto", $_GET['minuto']);
            $stm->bindValue(":mac", $mac);
            $stm->bindValue(":gramos", $_GET['gramos']);

            $stm->execute();
            $id = $c->lastInsertId();

            if ($stm->rowCount()) {
                $r = array("registrado" => "true", "id" => $id);
            } else {
                $r = array("registrado" => "false");
            }

            header("HTTP/1.1 200 OK");
            echo json_encode($r);
        } else {
            echo (json_encode(array("registrado" => "false", "error" => "Faltan datos")));
        }
        break;

    case "PUT":
        parse_str(file_get_contents("php://input"), $_GET);
        if (isset($_GET['id']) && isset($_GET['mac_token']) && isset($_GET['minuto']) && isset($_GET['gramos'])) {
            $c = conexion();

            $tokenData = JWT::get_data($_GET['mac_token'], Config::SECRET);
            $mac = $tokenData['mac'];

            $stm = $c->prepare("UPDATE horarios SET minuto = :minuto, gramos = :gramos WHERE mac = :mac AND id = :id");
            $stm->bindValue(":minuto", $_GET['minuto']);
            $stm->bindValue(":mac", $mac);
            $stm->bindValue(":gramos", $_GET['gramos']);
            $stm->bindValue(":id", $_GET['id']);

            $stm->execute();

            if ($stm->rowCount()) {
                $r = array("registrado" => "true");
            } else {
                $r = array("registrado" => "false");
            }

            header("HTTP/1.1 200 OK");
            echo json_encode($r);
        } else {
            echo (json_encode(array("registrado" => "false", "error" => "Faltan datos")));
        }
        break;

    case "DELETE":
        if (isset($_GET['id']) && isset($_GET['mac_token'])&& JWT::verify($_GET['mac_token'],Config::SECRET)==0) {

            $tokenData = JWT::get_data($_GET['mac_token'], Config::SECRET);
            $mac = $tokenData['mac'];

            $c = conexion();
            $s = $c->prepare("DELETE FROM horarios WHERE id = :id AND mac = :mac");
            $s->bindValue(":id", $_GET['id']);
            $s->bindValue(":mac", $mac);

            echo (json_encode(array('estado' => $s->execute())));
        } else {
            header("HTTP/1.1 400 Bad Request");
        }
        break;

    default:
        header("HTTP/1.1 400 Bad Request");
        break;
}
