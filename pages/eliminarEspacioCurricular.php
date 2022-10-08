
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
require_once 'funciones/buscarEspacioCurricular.php';

$idEC=$_REQUEST['Cx'];  
eliminarElEspCurr($MiConexion,$idEC);

header('Location: administrarEspaciosCurriculares.php');
    
?>
