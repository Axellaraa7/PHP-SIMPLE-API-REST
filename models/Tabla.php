<?php 
class Tabla{
  private $conexion,$table,$id,$marca,$tipo_prenda,$anio;
  private $selectNoId;

  public function getId(){ return $this->id; }
  public function getMarca(){ return $this->marca; }
  public function getTipoPrenda(){ return $this->tipo_prenda; }
  public function getAnio(){ return $this->anio; }

  public function __construct($conexion){
    $this->conexion = $conexion;
    $this->table = "conexionphp";
    $this->selectNoId = "SELECT marca,tipo_prenda,anio FROM $this->table ";
  }

  public function getAll(){
    $results = $this->conexion->query("SELECT * FROM $this->table order by id;");
    return ($results->rowCount() < 1) ? null : json_encode($results->fetchAll(PDO::FETCH_ASSOC));
  }

  public function getAllNoId(){
    $results = $this->conexion->query($this->selectNoId."order by id;");
    return ($results->rowCount() < 1) ? null : json_encode($results->fetchAll(PDO::FETCH_ASSOC));
  }

  public function getById($id){
    $results = $this->conexion->query($this->selectNoId."order by id;")->fetchAll(PDO::FETCH_ASSOC);
    $inArray = array_key_exists($id,$results);
    return (!$results || !$inArray) ? null : json_encode($results[$id]);
  }

  public function getByMarca($marca){
    $query = $this->conexion->prepare($this->selectNoId."WHERE marca = :marca");
    $banExec = $query->execute(array(":marca"=>$marca));
    return (!$banExec || ($query->rowCount() < 1)) ? null : json_encode($query->fetchAll(PDO::FETCH_ASSOC));
  }

  public function getByTipoPrenda($tipo_prenda){
    $query = $this->conexion->prepare($this->selectNoId."WHERE tipo_prenda = :tipoPrenda");
    $banExec = $query->execute(array(":tipoPrenda"=>$tipo_prenda));
    return(!$banExec || ($query->rowCount() < 1)) ? null : json_encode($query->fetchAll(PDO::FETCH_ASSOC));
  }

  public function getByAnio($anio){
    $query = $this->conexion->prepare($this->selectNoId."WHERE anio = :anio");
    $banExec = $query->execute(array(":anio"=>$anio));
    return (!$banExec || ($query->rowCount() < 1)) ? null: json_encode($query->fetchAll(PDO::FETCH_ASSOC));
  }

  public function insert($marca,$tipo_prenda,$anio){
    $query = $this->conexion->prepare("INSERT INTO $this->table (marca,tipo_prenda,anio) VALUES (:marca,:tipoPrenda,:anio)");
    $results = $query->execute(array(":marca"=>$marca,":tipoPrenda"=>$tipo_prenda,":anio"=>$anio));
    return $results;
  }

  public function update($id,$marca,$tipo_prenda,$anio){
    $id = $this->changeIdToPK($id);
    if(!$id) return null; 
    $query = $this->conexion->prepare("UPDATE $this->table SET marca = :marca, tipo_prenda = :tipoPrenda, anio = :anio WHERE id = :id");
    $results = $query->execute(array(":marca"=>$marca, ":tipoPrenda"=>$tipo_prenda, ":anio"=>$anio, ":id"=>$id));
    return $results;
  }

  public function delete($id){
    $id = $this->changeIdToPK($id);
    if(!$id) return null;
    $query = $this->conexion->prepare("DELETE FROM $this->table WHERE id = :id");
    $banExec = $query->execute(array(":id"=>$id));
    return $banExec;
  }

  private function changeIdToPK($id){
    $results = $this->conexion->query("SELECT id FROM $this->table order by id")->fetchAll(PDO::FETCH_ASSOC);
    $inArray = array_key_exists($id,$results);
    return (!$results || !$inArray) ? null : $results[$id]["id"];
  }
}
?>