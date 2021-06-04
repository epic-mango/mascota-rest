<?php
require_once "config.php";
function conexion(){
    try{
        $c=new PDO("mysql:host=".Config::HOST.";dbname=".Config::BD, 
        Config::USER, Config::PASS);
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = 'CST'");
        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $c;
    }catch(PDOException $e){
        exit($e->getMessage());
    }
}