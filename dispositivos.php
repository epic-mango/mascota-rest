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
    if ($metodo == "GET" && isset($_GET['mac']) && isset($_GET['pass'])) {
        $c = conexion();

        $stm = $c->prepare("SELECT * FROM dispositivos WHERE mac = :mac AND pass = MD5(:pass);");
        $stm->bindValue(":mac", $_GET['mac']);
        $stm->bindValue(":pass", $_GET['pass']);
        $stm->execute();
        $stm->setFetchMode(PDO::FETCH_ASSOC);

        $res = $stm->fetch();

        if ($res) {
            $jwt = JWT::create(array("mac" => $_GET['mac']), Config::SECRET);
            $r = array("login" => "true", "token" => $jwt);
        } else {
            $r = array("login" => "false", "token" => "Error de usuario/contrasena");
        }

        header("HTTP/1.1 200 OK");
        echo json_encode($r);
        exit();
    }
}
$data = JWT::get_data($jwt, Config::SECRET);



switch ($metodo) {

    case "GET":

        if (isset($_GET['id'])) {
            $c = conexion();

            $stm = $c->prepare("SELECT mac, alimento, serie FROM dispositivos INNER JOIN mascotas ON dispositivos.mascota = mascotas.id WHERE mascotas.usuario = :usuario AND mascotas.id = :id;");
            $stm->bindValue(":usuario", $data['id']);
            $stm->bindValue(":id", $_GET['id']);
            $stm->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stm->execute();

            if ($result) {

                $datos = $stm->fetchAll();
                $mac_tokens = array();

                foreach ($datos as $dato) {
                    $jwt = JWT::create(array("mac" => $dato['mac']), Config::SECRET);
                    $mac_tokens[$dato['mac']] = $jwt;
                }

                header("HTTP/1.1 200 OK");
                echo (json_encode(
                    array(
                        "estado" => "true",
                        "datos" => $datos,
                        "mac_tokens" => $mac_tokens
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
        if (isset($_GET['mac']) && isset($_GET['pass']) && isset($_GET['mascota']) && isset($_GET['alimento'])) {
            $c = conexion();

            $stm = $c->prepare("UPDATE dispositivos SET mascota = :mascota, alimento = :alimento WHERE mac = :mac AND pass = md5(:pass);");
            $stm->bindValue(":mascota", $_GET['mascota']);
            $stm->bindValue(":mac", $_GET['mac']);
            $stm->bindValue(":pass", $_GET['pass']);
            $stm->bindValue(":alimento", $_GET['alimento']);

            $stm->execute();

            if ($stm->rowCount()) {
                $jwt = JWT::create(array("mac" => $_GET['mac']), Config::SECRET);
                $r = array("registrado" => "true", "mac_token" => $jwt);
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
        if (isset($_GET['mac']) && isset($_GET['pass']) && isset($_GET['mascota']) && isset($_GET['alimento'])) {
            $c = conexion();

            $stm = $c->prepare("UPDATE dispositivos SET mascota = :mascota, alimento = :alimento WHERE mac = :mac AND pass = md5(:pass);");
            $stm->bindValue(":mascota", $_GET['mascota']);
            $stm->bindValue(":mac", $_GET['mac']);
            $stm->bindValue(":pass", $_GET['pass']);
            $stm->bindValue(":alimento", $_GET['alimento']);

            $stm->execute();

            if ($stm->rowCount()) {
                $jwt = JWT::create(array("mac" => $_GET['mac']), Config::SECRET);
                $r = array("registrado" => "true", "mac_token" => $jwt);
            } else {
                $r = array("registrado" => "false");
            }

            header("HTTP/1.1 200 OK");
            echo json_encode($r);
        } else {
            echo (json_encode(array("registrado" => "false", "error" => "Faltan datos")));
        }
        break;

    default:
        header("HTTP/1.1 400 Bad Request");
        break;
}
