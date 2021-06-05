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

$header = apache_request_headers();

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    //Identificarse
    if (isset($header['Authorization'])) {

        $jwt = $header['Authorization'];
        if (($valor = JWT::verify($jwt, Config::SECRET)) == 0) {
            //Tenía un Token válido, renovarlo.
            $data = JWT::get_data($jwt, Config::SECRET);
            $jwt = JWT::create(array("id" => $data['id']), Config::SECRET);
            $r = array("estado" => "true", "token" => $jwt);
        } else
            $r = array("estado" => "false", "token" => "Token inválido");


        header("HTTP/1.1 200 OK");
        echo json_encode($r);
    } else if (isset($_GET['id']) && isset($_GET['pass'])) {
        $c = conexion();
        $s = $c->prepare("SELECT * FROM usuarios WHERE id=:u AND pass=MD5(:p)");
        $s->bindValue(":u", $_GET['id']);
        $s->bindValue(":p", $_GET['pass']);
        $s->execute();
        $s->setFetchMode(PDO::FETCH_ASSOC);
        $r = $s->fetch();
        if ($r) {
            $jwt = JWT::create(array("id" => $_GET['id']), Config::SECRET);
            $r = array("estado" => "true", "token" => $jwt);
        } else {
            $r = array("estado" => "false", "token" => "Error de usuario y contrasena");
        }
        header("HTTP/1.1 200 OK");
        echo json_encode($r);
    } else {
        //Parámetros incompletos
        header("HTTP/1.1 400 Bad Request");
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Crear cuenta
    $c = conexion();
    if (isset($_POST["id"]) && isset($_POST["pass"]) && isset($_POST["apodo"])) {
        $insert = $c->prepare("INSERT INTO Usuarios VALUES (:id, MD5(:pass), :nombres, :apellidos, :apodo)");
        $insert->bindValue(":id", $_POST["id"]);
        $insert->bindValue(":pass", $_POST["pass"]);
        $insert->bindValue(":apodo", $_POST["apodo"]);
        if (isset($_POST["nombres"]))
            $insert->bindValue(":nombres", $_POST["nombres"]);
        else
            $insert->bindValue(":nombres", "");
        if (isset($_POST["apellidos"]))
            $insert->bindValue(":apellidos", $_POST["apellidos"]);
        else
            $insert->bindValue(":apellidos", "");

        try {
            $r = $insert->execute();

            $jwt = JWT::create(array("id" => $_POST['id']), Config::SECRET);
            $r = array("estado" => "true", "token" => $jwt);
        } catch (PDOException $e) {
            //No se insertó el usuario
            $r = array("estado" => "false", "token" => $e->getCode());
        }

        header("HTTP/1.1 200 OK");
        echo (json_encode($r));
    } else {
        //No están todos los campos
        header("HTTP/1.1 400 Bad Request");
    }
} else if ($_SERVER['REQUEST_METHOD'] = "PUT") {
} else {
    //No se puede manejar ese método
    header("HTTP/1.1 400 Bad Request");
}
