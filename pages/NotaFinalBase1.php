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
require_once 'funciones/baseDeDatos.php';
$IdEstudiante=$_REQUEST['Cx'];
$IdEspCurr=$_REQUEST['Cn'];
$EC=consultaEspaciosCurricularesxID($IdEspCurr);
$Fila=mysqli_fetch_array($EC);
$EspacioCurricular=$Fila['NombreEspacCurric'];
$Fila1=consultaEstudiante($IdEstudiante);
$Nota=$_REQUEST['Nota'];
$T=$_REQUEST['T'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
<?php
    require_once 'encabezado.php';
?>
<link href="estilos.css" rel="stylesheet"  type="text/css" />
</head>
<body>
<div id="wrapper">
<?php
    require_once 'top.php';
    //require_once 'menuDerecho.php';
    require_once 'funciones/DatosUsuario.php';
    require_once 'menuLateral.php';
?>
 <div id="page-wrapper">


   <div class="row">

 <div class="panel panel-primary">   
    <div class="panel-heading">Calificación final de <?php echo $EspacioCurricular;?> </div>
    <div class="clearfix"></div>
	<div class="panel-body">
	 <div class="row"><div class="col-lg-12">
<?php	 
	 if($T=='G'){
	 InsertNotaFinal($IdEstudiante,$IdEspCurr,$Nota);
	echo '<div class="well well-sm">La calificación se registró con éxito<br></div>';
	 }
	 else{
	UpdateNotaFinal($IdEstudiante,$IdEspCurr,$Nota);
	echo '<div class="well well-sm">El registro se modificó con éxito<br></div>';
	}
?>
	 
	 
	 </div></div>
	  <div class="row">
<?php 
$Consulta=consultaCalificacionFinal($IdEstudiante,$IdEspCurr);

	  while ($Fila=mysqli_fetch_array($Consulta))
	  {
$Apellido=$Fila['apellido'];

$Nombre=$Fila['nombre'];

$Espcurr=$Fila['NombreEspacCurric'];

$Calificacion=$Fila['calificacion'];
	
}
?>
		<div class="col-lg-12"><h4><center><?php echo "El/ La estudiante <font color='blue'>".$Apellido.", ".$Nombre." </font>obtuvo una <font color='blue'>calificación de ".$Calificacion." </font>en el espacio curricular <font color='blue'> ".$Espcurr?></h4></div>
		
	  </div>
	  
<br><br>
    <div class="row">
        
       <div class="col-lg-4"></diV>
		 <div class="col-lg-2"><Form Action="administrarNotasFinales.php" Method="Post">
	     <button class="btn btn-primary" type="submit" name="Continuar"><box-icon name="edit" size="sm"type="solid" color="white" animation="tada"></box-icon> Continuar Calificando</button></form></div>
		 <div class="col-lg-1"></diV>
		 <div class="col-lg-2"><Form Action="index.php" Method="Post">
	     <button class="btn btn-danger" type="submit" name="Cancelar"><box-icon name="home" size="sm"type="solid" color="white" animation="tada-hover"></box-icon> Retornar al menú</button></form></div>
	</div><!--Cierre rows4-->

 
 
</div><!--fin panel-body-->
</div><!--fin panel-primary-->
</div><!--fin page-wrapper-->
</div><!--fin wrapper-->
</body>
</html>