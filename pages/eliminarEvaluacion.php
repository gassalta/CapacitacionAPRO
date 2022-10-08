<?php
//Verifico si está abierta la sesion
session_start();
if(empty($_SESSION['Nombre'])) {
  header('Location: cerrarSesion.php');
  exit;
}
//Verifico tiempo de sesion
require_once 'funciones/controlTiempoSesion.php';
if (tiempoCumplido()) {
    header('Location: cerrarSesion.php');
    exit;
}
//Conecto con la base de datos
require_once 'funciones/conexion.php';
$MiConexion=ConexionBD();

//Declaro variables
$mensaje='';
$EvaluacionBuscada=array();

require_once 'funciones/buscarEvaluacion.php';

$EC=$_REQUEST['Ec'];
$IdEval = $_REQUEST['Cx'];
eliminarLaEvaluacion($MiConexion,$IdEval);
header('Location: administrarEvaluaciones.php?Cx='.$EC);
?>

