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
$jwt = $header['Authorization'];
if(JWT::verify($jwt,"qwertyuiop")!=0){
    header("HTT/1.1 401 Unauthorized");
    exit();
}
$data = JWT::get_data($jwt, Config::SECRET);

$metodo = $_SERVER["REQUEST_METHOD"];

