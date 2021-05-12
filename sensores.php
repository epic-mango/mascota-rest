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
} 
$data = JWT::get_data($jwt, Config::SECRET);

switch ($metodo){
    case "POST":

        if (isset($_POST['mac']) && isset($_POST['tipo'])&& isset($_POST['valor'])){
            $c=conexion();
            $s = $c->prepare("INSERT INTO sensores (mac, tipo, valor, fecha) VALUES (:mac, :tipo,:valor,:fecha);");
            $s->bindValue(":mac", $_POST['mac']);
            $s->bindValue(":tipo", $_POST['tipo']);
            $s->bindValue(":valor", $_POST['valor']);
            $s->bindValue(":fecha", date("Y-m-d H:i:s"));
            $s->execute();

            if($s->rowCount()){
                $id = $c -> lastInsertId();
                $r = array("agregado"=>"true");
            } else {
                $r = array("agregado"=>"false");
            }

            header("HTTP/1.1 200 OK");
            echo json_encode($r);
        } else {
            header("HTTP/1.1 400 Bad Request");
        }

        break;
}