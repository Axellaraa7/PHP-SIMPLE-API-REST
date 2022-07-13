<?php 
class Conexion{
  private static $instance;
  private $conexion;

  private function __construct($dbname){
    try{
      $this->conexion = new PDO("mysql:host=localhost;dbname=$dbname;charset=utf8mb4","root","");
      $this->conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $ex){
      echo "Existió algún error al conectar a la bd".$ex->getMessage()."linea: ".$ex->getLine();
    }
  }
  
  public static function getInstance($dbname){
    if(!isset(self::$instance)){
      self::$instance = new Conexion($dbname);
    }
    return self::$instance;
  }

  public function getConexion(){ return $this->conexion; }

  public function __clone(){
    trigger_error("No se puede clonar este objeto".E_USER_ERROR);
  }
}
?>