<?php

/**
 * Archivo que contiene el modelo de las guitarras.
 * Este archivo se encarga de realizar las operaciones
 * de base de datos para las guitarras, como son:
 *      - obtener todas las guitarras
 *      - obtener una guitarra por su id
 *      - insertar una guitarra
 *      - eliminar una guitarra
 *      - actualizar una guitarra
 */

# Importo la conexión a la base de datos
require_once '../db/dbConnection.php';

# Inicio de la clase de las guitarras
Class guitarraModel{
  public $id;
  public $nombre;
  public $descripcion;
  public $precio;
  public $stock;

  function __construct($id=null, $nombre=null, $descripcion=null, $precio=null, $stock=null){
    $this->id = $id;
    $this->nombre = $nombre;
    $this->descripcion = $descripcion;
    $this->precio = $precio;
    $this->stock = $stock;
  }

  
  #Getters
  public function getId(){
    return $this->id;
  }
  public function getNombre(){
    return $this->nombre;
  }
  public function getDescripcion(){
    return $this->descripcion;
  }
  public function getPrecio(){
    return $this->precio;
  }
  public function getStock(){
    return $this->stock;
  }

  #Setters
  public function setId($id){
    $this->id = $id;
  }
  public function setNombre($nombre){
    $this->nombre = $nombre;
  }
  public function setDescripcion($descripcion){
    $this->descripcion = $descripcion;
  }
  public function setPrecio($precio){
    $this->precio = $precio;
  }
  public function setStock($stock){
    $this->stock = $stock;
  }

  #Funcion para obtener todas las guitarras
  public function getGuitarras($id=null){
    global $mysqli;
    $condicionBuscarUnaGuitarra = $id==null ? "" : "AND id=".$id;
    $guitarras = [];
    $query = "SELECT id, nombre, descripcion, precio, stock FROM guitarras WHERE is_active=true ".$condicionBuscarUnaGuitarra;
    $result = $mysqli->query($query);
    while($row = $result->fetch_assoc()){
      array_push($guitarras, $row);
    }
    return $guitarras;
  }
  #Funcion para insertar una guitarra
  public function insertGuitarra($nombre, $descripcion, $precio, $stock){
    global $mysqli;
    $validation = $this->validateGuitarra($nombre, $descripcion, $precio, $stock);
    $response = ['error','Guitarra ya existente'];
    if(empty($validation)){
      $query = "INSERT INTO guitarras(nombre, descripcion, precio, stock) VALUES('$nombre', '$descripcion', $precio, $stock)";    
      $mysqli->query($query);
      $response = ['success','Guitarra insertada correctamente'];  
    }
    return $response;
  }

  #Funcion para actualizar una guitarra
  public function updateGuitarra($id, $nombre, $descripcion, $precio, $stock){
    global $mysqli;
    $itExist = $this->getGuitarras($id);
    $response = ['error','Guitarra no existente'];
    if(!empty($itExist)){
      $validation = $this->validateGuitarra($nombre, $descripcion, $precio, $stock);
      $response = ['error','Guitarra ya existente'];
      if(empty($validation)){
        $query = "UPDATE guitarras SET nombre='$nombre', descripcion='$descripcion', precio=$precio, stock=$stock WHERE id=$id";
        $mysqli->query($query);
        $response = ['success','Guitarra actualizada correctamente'];  
      }
    }
    return $response;  
  }

  #Funcion para eliminar una guitarra
  public function deleteGuitarra($id){
    global $mysqli;
    $validacion = $this->getGuitarras($id);
    $response = ['error','Guitarra no encontrada (id='.$id.')'];
    if(!empty($validacion)){
      $query = "UPDATE guitarras SET is_active=FALSE WHERE id=$id";
      $mysqli->query($query);
      $response = ['success','Guitarra eliminada correctamente'];
    }
    return $response;
  }

  public function validateGuitarra($nombre, $descripcion, $precio, $stock){
    global $mysqli;
    $guitarras=[];
    $query = "SELECT * FROM guitarras WHERE nombre='$nombre' AND descripcion='$descripcion' AND precio=$precio AND stock=$stock";
    $result = $mysqli->query($query);
    while($row = $result->fetch_assoc()){
      array_push($guitarras, $row);
    }
    return $guitarras;
  }

}
?>
