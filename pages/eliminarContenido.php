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

require_once 'funciones/buscarContenido.php';
    require_once 'funciones/listarEspaciosCurricularesXDocente.php';
        $ListadoEC=array();
        $ListadoEC = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
        $CantidadEspCurr = count($ListadoEC);

        $IdContenido = $_REQUEST['Cx'];
        require_once 'funciones/buscarEspacioCurricular.php';
        $NEC=$_REQUEST['Ec'];
;

        require_once 'funciones/buscarContenido.php';
eliminarElContenido($MiConexion,$IdContenido);

header('Location: administrarContenidosYAprendizajes.php?Cx='.$NEC);






?>
