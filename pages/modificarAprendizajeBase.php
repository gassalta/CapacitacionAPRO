<?php
session_start();
if(empty($_SESSION['Nombre'])) {
  header('Location: cerrarSesion.php');
  exit;
}
require_once 'funciones/controlTiempoSesion.php';
if (tiempoCumplido()) {
    header('Location: cerrarSesion.php');
    exit;
}
require_once 'funciones/conexion.php';
$MiConexion=ConexionBD();
require_once 'funciones/baseDeDatos.php';
$Tipo=$_REQUEST['Tipo'];
$Id=$_REQUEST['Id'];
$Aprendizaje=$_REQUEST['NombreAprendizaje'];
$IdContenido=$_REQUEST['IdContenido'];
require_once 'funciones/buscarContenido.php';
$Apr=array();
$Apr=buscarAprendizaje($MiConexion,$IdContenido);
$Cont = array();
$Cont=buscarContenido($MiConexion,$IdContenido);
$Denom = $Cont['DENOMINACION'];
$EC = $Cont['ESPACCURRIC'];
if ($Tipo=='M')
  {
   
   UpdateAprendizaje($Id,$Aprendizaje);
  
   
  }	  elseif($Tipo=='E')
  {
	 UpdateEliminarAprendizaje($Id);
  }else{
	  
	  InsertAprendizaje($IdContenido,$Aprendizaje);
  }
   header('Location: Aprendizajes.php?Cx='.$IdContenido.'&Cn='.$Denom.'&Ec='.$EC);
   exit;




?>
