<?php

/**
 * Archivo que se encarga de recibir las peticiones del servidor para 
 * interactuar con las guitarras, a través del modelo guitarraModel
 * 
 */

# Configuración CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Allow: GET, POST, PUT, DELETE');
header('content-Type: application/json; charset=utf-8');

# Incluyo el modelo
require_once '../models/guitarraModel.php';
$guitarraModel = new guitarraModel();

# Switch en base a la respuesta del servidor 
switch ($_SERVER['REQUEST_METHOD']) {
  case 'GET':
    # Obtengo todas las guitarras y las devuelvo en un json
    $response = $guitarraModel->getGuitarras();
    if (isset($_GET['id'])) {
      $response = $guitarraModel->getGuitarras($_GET['id']);
    }
    echo json_encode($response);
    break;
  case 'POST':
    # Obtengo los datos de la guitarra y los valido
    $_POST = json_decode(file_get_contents('php://input'), true);
    # Validaciones
    if (!isset($_POST['nombre']) || is_null($_POST['nombre']) || empty(trim($_POST['nombre'])) || strlen($_POST['nombre']) > 80) {
      $response = ['error' => 'El nombre es requerido (maximo 80 caracteres)'];
    }
    if (!isset($_POST['descripcion']) || is_null($_POST['descripcion']) || empty(trim($_POST['descripcion'])) || strlen($_POST['descripcion']) > 150) {
      $response = ['error' => 'La descripcion es requerida  (maximo 150 caracteres)'];
    }
    if (!isset($_POST['precio']) || is_null($_POST['precio']) || empty(trim($_POST['precio'])) || !is_numeric($_POST['precio']) || strlen($_POST['precio']) > 10) {
      $response = ['error' => 'El precio es requerido (maximo 7 dígitos, con 2 decimales ejemplo: 1500000.99)'];
    }
    if (!isset($_POST['stock']) || is_null($_POST['stock']) || empty(trim($_POST['stock'])) || !is_numeric($_POST['stock']) || strlen($_POST['stock']) > 5) {
      $response = ['error' => 'El stock es requerido (maximo 5 dígitos)'];
    } else {
      $response = $guitarraModel->insertGuitarra($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['stock']);
    }
    echo json_encode($response);
    break;
  case 'PUT':
    # Obtengo los datos de la guitarra y los valido
    $_PUT = json_decode(file_get_contents('php://input'), true);
    # Validaciones
    if (!isset($_PUT['id']) || is_null($_PUT['id']) || empty($_PUT['id'])) {
      $response = ['error' => 'El id es requerido'];
    }
    if (!isset($_PUT['nombre']) || is_null($_PUT['nombre']) || empty(trim($_PUT['nombre'])) || strlen($_PUT['nombre']) > 80) {
      $response = ['error' => 'El nombre es requerido (maximo 80 caracteres)'];
    }
    if (!isset($_PUT['descripcion']) || is_null($_PUT['descripcion']) || empty(trim($_PUT['descripcion'])) || strlen($_PUT['descripcion']) > 150) {
      $response = ['error' => 'La descripcion es requerida (maximo 150 caracteres)'];
    }
    if (!isset($_PUT['precio']) || is_null($_PUT['precio']) || empty(trim($_PUT['precio'])) || !is_numeric($_PUT['precio']) || strlen($_PUT['precio']) > 10) {
      $response = ['error' => 'El precio es requerido | El precio debe ser numerico | Maximo 7 dígitos, con 2 decimales ejemplo: 1500000.99'];
    }
    if (!isset($_PUT['stock']) || is_null($_PUT['stock']) || empty(trim($_PUT['stock'])) || !is_numeric($_PUT['stock']) || strlen($_PUT['stock']) > 5) {
      $response = ['error' => 'El stock es requerido | El stock debe ser numerico | Maximo 5 dígitos'];
    } else {
      $response = $guitarraModel->updateGuitarra($_PUT['id'], $_PUT['nombre'], $_PUT['descripcion'], $_PUT['precio'], $_PUT['stock']);
    }
    echo json_encode($response);
    break;
  case 'DELETE':
    # Obtengo el id de la guitarra y lo valido
    $_DELETE = json_decode(file_get_contents('php://input'), true);
    if (!isset($_DELETE['id']) || is_null($_DELETE['id']) || empty($_DELETE['id'])) {
      $response = ['error' => 'El id es requerido'];
    }
    $response = $guitarraModel->deleteGuitarra($_DELETE['id']);
    echo json_encode($response);
    break;
}
