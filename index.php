<?php
require_once("./db/Conexion.php");
require_once("./models/Tabla.php");

function setMessageResponse($status, $msg){
  return json_encode(array("status" => $status, "msg" => $msg));
}

$conexion = Conexion::getInstance("pruebasconexion");
$tabla = new Tabla($conexion->getConexion());

//La salida del script será en formato JSON
header("Content-type: application/json");
//Se limite los metodos HTTP de acceso
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
//Se configura el acceso CORS
header("Access-Control-Allow-Origin: *");


switch($_SERVER["REQUEST_METHOD"]){
  case "GET":
    if(isset($_GET["id"])){
      $registros = $tabla->getById($_GET["id"]);
      if($registros == null) $registros = setMessageResponse(404,"No existen registros");
      echo $registros;
    }else if(isset($_GET["marca"])){
      $registros = $tabla->getByMarca($_GET["marca"]);
      if($registros == null) $registros = setMessageResponse(404,"No existen registros");
      echo $registros;
    }else if(isset($_GET["tipo_prenda"])){
      $registros = $tabla->getByTipoPrenda($_GET["tipo_prenda"]);
      if($registros == null) $registros = setMessageResponse(404,"No existen registros");
      echo $registros;
    }else if(isset($_GET["anio"])){
      $registros = $tabla->getByAnio($_GET["anio"]);
      if($registros == null) $registros = setMessageResponse(404,"No existen registros");
      echo $registros;
    }else{
      $registros = $tabla->getAllNoId();
      echo $registros;
    }
    break;
  case "POST":
    //The flag true is to set the array into an associative;
    $_POST = json_decode(file_get_contents("php://input"),true);

    if(empty($_POST)) echo setMessageResponse(204,"No se encuentra la información del cliente");
    else{
      $registros = $tabla->insert($_POST["marca"],$_POST["tipo_prenda"],$_POST["anio"]);
      echo ($registros) ? setMessageResponse(200,"Se ha realizado la operación exitosamente") : setMessageResponse(202,"No se ha realizado la operación");
    }
    break;
  case "PUT":
    break;
  case "DELETE":
    $_POST = json_decode(file_get_contents("php://input"),true);
    if(empty($_POST)) echo setMessageResponse(204, "No se encuentra la información del cliente");
    else{
      $registros = $tabla->delete($_POST["id"]);
      echo ($registros) ? $registros : setMessageResponse(202,"No se ha realizado la operación");
    }
    break;
  default:
    echo setMessageResponse(405,"Método HTTP no permitido");
    break;
    

}

?>