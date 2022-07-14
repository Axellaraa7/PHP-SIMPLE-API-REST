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
    if(isset($_GET["id"])) $registros = $tabla->getById($_GET["id"]);
    else if(isset($_GET["marca"])) $registros = $tabla->getByMarca($_GET["marca"]);
    else if(isset($_GET["tipo_prenda"])) $registros = $tabla->getByTipoPrenda($_GET["tipo_prenda"]);
    else if(isset($_GET["anio"])) $registros = $tabla->getByAnio($_GET["anio"]);
    else if(isset($_GET["withid"]) && $_GET["withid"] == true) $registros = $tabla->getAll();
    else $registros = $tabla->getAllNoId();
    if($registros == null) $registros = setMessageResponse(404,"No existen registros");
    echo $registros;
    break;
  case "POST":
    //The flag true is to set the array into an associative;
    //The instruction is to collect from a JSON, if u want to collect the data from a form u just need to use the supoerglobal variable $_POST.
    $_POST = json_decode(file_get_contents("php://input"),true);

    if(empty($_POST)) echo setMessageResponse(204,"No se encuentra la información del cliente");
    else{
      $registros = $tabla->insert($_POST["marca"],$_POST["tipo_prenda"],$_POST["anio"]);
      echo ($registros) ? setMessageResponse(200,"Se ha realizado la operación exitosamente") : setMessageResponse(202,"No se ha realizado la operación");
    }
    break;
  case "PUT":
    $_POST = json_decode(file_get_contents("php://input"),true);
    if(empty($_POST) || empty($_GET)) echo setMessageResponse(204,"No se encuentra la información del cliente");
    else{
      $registros = $tabla->update($_GET["id"],$_POST["marca"],$_POST["tipo_prenda"],$_POST["anio"]);
      echo ($registros) ? setMessageResponse(200,"Se ha realizado la operación exitosamente") : setMessageResponse(202,"No se ha realizado la operación");
    }
    break;
  case "DELETE":
    $_POST = json_decode(file_get_contents("php://input"),true);
    if(empty($_POST)) echo setMessageResponse(204, "No se encuentra la información del cliente");
    else{
      $registros = $tabla->delete($_POST["id"]);
      echo ($registros) ? setMessageResponse(200,"Se ha eliminado el registro: ".$_POST["id"]) : setMessageResponse(202,"No se ha realizado la operación");
    }
    break;
  default:
    echo setMessageResponse(405,"Método HTTP no permitido");
    break;
    

}

?>