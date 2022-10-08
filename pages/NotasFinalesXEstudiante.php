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
require_once 'funciones/baseDeDatos.php';

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
    require_once 'menuDerecho.php';
    require_once 'funciones/DatosUsuario.php';
    require_once 'menuLateral.php';
?>

 <div id="page-wrapper">

<?php
$IdEstudiante=$_REQUEST['Cx'];
$IdEspCurr=$_REQUEST['Cn'];
$EC=consultaEspaciosCurricularesxID($_REQUEST['Cn']);
$Fila=mysqli_fetch_array($EC);
$EspacioCurricular=$Fila['NombreEspacCurric'];
$Fila1=consultaEstudiante($IdEstudiante);
$AprendizajesLogrados=0;
$AprendizajesPendientes=0;
$T='';
$EC_Docente=consultaEspaciosCurricularesxDocente($_SESSION['Id']);
$cambio=0;
while ($Fila2=mysqli_fetch_array($EC_Docente))
	  { if ($Fila2['NombreEspacCurric']==$EspacioCurricular){
		 $cambio=1; 
	    }
	  }

 $SqlNota=consultaNotaFinal($IdEstudiante,$IdEspCurr);
?>
   <div class="row">
    <div class="col-lg-12">
      <h2 class="tile-title" ><font color="#85C1E9"><center><b>Calificación final de <?php echo $EspacioCurricular;?> </b></font></h2>
     </div>
   </div><!--Hasta aquí el título--><br>
 <div class="panel panel-primary">   
    <div class="panel-heading"><center>Estudiante<b> <?php echo $Fila1['apellido'].", ".$Fila1['nombre'];?></b></center></div>
   <br>
	<div class="panel-body">
	  <div class="row">
		<div class="col-lg-4"><h4><center>Calificaciones</h4></div>
		<div class="col-lg-8"><h4><center>Aprendizajes</center></h4></div>
	  </div>
	  <div class="row">
<?php 
 $Listado=array();
 $Listado = consultaAlumnoEvaluacionAprendizajeCalificacion($_REQUEST['Cx']);
 $Cantidad = count($Listado);
 $Suma=0;
 if($Cantidad=="0")
	{
	 echo'<div class="col-lg-4">
         <div class="panel panel-info">
		  <div class="panel-body"><center>Estudiante sin calificaciones</center></div></div></div>';
	
	
	}
 else
	{ 
?>	  
	  <div class="col-lg-4">
         <div class="panel panel-info">
		  <div class="panel-body">
		<div class="col-lg-124">
			<div class="table-responsive">
              <table class="table table table-bordered ">
				<thead>
					<tr class="bg-primary">
					<td>N°Evaluación</td>
					<!--<th>Instancia</th>-->
					<td>Fecha</td>
					<td> Nota</td>
					</tr>
				</thead>
				<tbody>
<?php

     $b=0;
	 for($i=0; $i < $Cantidad; $i++)
		{ 
		if($Listado[$i]['IdEspCurr']==$_REQUEST['Cn'])
		  {         
			echo "<tr><td>".$Listado[$i]['IdEv']."</td>";
			//echo"<td>".$Listado[$i]['Instancia']."</td>";
			echo"<td>".$Listado[$i]['Fecha']."</td>"; 
			$ConsultaCalificacionEvaluaciones=consultaEvaluacionxEstudiante($Listado[$i]['IdEvEst']);
			while($Sql=mysqli_fetch_array($ConsultaCalificacionEvaluaciones))
			  { 
				$calificacion=$Sql['calificacion'];
			    $Suma+=$calificacion;
			  }
			echo "<th scope='row'>".$calificacion."</th></tr>";
			$b=1;
		  }
	}
	 if($b==0){ echo "No HAY calificaciones para ese estudiante"; }
   //cierre if validacion si no hay calificaciones
  ?> 
		</tbody><!--fin body tabla-->
		</table><!--fin tabla notas-->
		</div><!--class table-->
		</div><!--col-lg-4-->
		</div></div></div>
		
<?php
	}
	$Listado2=consultaAprendizajes($_REQUEST['Cx'],$_REQUEST['Cn']);
	$Cantidad2 = count($Listado2);
if ($Cantidad=="0")
   {
	   echo '<div class="col-lg-8">
         <div class="panel panel-info">
		  <div class="panel-body"><center>No existen aprendizajes para ese estudiante</center></div></div></div>'; 
   }
  else
   {	
?>      <div class="col-lg-8">
         <div class="panel panel-info">
		  <div class="panel-body">
		<div class="col-lg-6">
		  <div class="table-responsive">
			<table class="table table table-bordered">
			 <thead><tr class="bg-primary"><td>Logrados</td></tr>
			 </thead>
			<tbody>

<?php
    		
	for ($i=0; $i < $Cantidad2; $i++) { 
	    if ($Listado2[$i]['Estado']=='Logrado') 
		   {
			 $AprendizajesLogrados++;
             echo "<tr><td>".$Listado2[$i]['Aprendizajes']."</td></tr>"; 
            }
							
		}
		
		 
 ?>
			</tbody><!--fin body tabla-->
		  </table><!--fin tabla logrados-->
		 </div><!--class table-->
		</div><!--col-lg-4-->
		<div class="col-lg-6">
			<div class="table-responsive">
            <table class="table table table-bordered">
			<thead><tr class="bg-primary"><td> Pendientes</td></tr></thead>
			<tbody>
<?php
		//$Suma=0;
			
	for ($i=0; $i < $Cantidad2; $i++) { 
		if ($Listado2[$i]['Estado']=='Pendiente') {
		 $AprendizajesPendientes++;
         echo"<tr><td>".$Listado2[$i]['Aprendizajes']."</td></tr>";
         }
		}
		


?>
			</tbody><!--fin body tabla-->
			</table><!--fin tabla pendientes-->
			</div><!--class table-->
			</div><!--col-lg-4-->
	
	</div></div></div>
<?php 
}//cierre if validacion de si hay aprendizajes
?></div><!--cierre row2--><br>
<?php 
if ($AprendizajesLogrados!==0)
	{
		$PorcentajeLogrado=round($AprendizajesLogrados/$Cantidad2*100);
		$NotaSugerida=$AprendizajesLogrados/$Cantidad2*10;
  
	}
	else{
		$PorcentajeLogrado=0;
		$NotaSugerida=0;
	}
		
if ($AprendizajesPendientes!==0)
    {	
	$PorcentajePendiente=round($AprendizajesPendientes/$Cantidad2*100);
	}
	else{
		 $PorcentajePendiente=0;
	}

if($AprendizajesPendientes!==0 || $AprendizajesLogrados!==0)
   {
 ?> 
    <?php echo '<form name="submit" method="post" action="NotaFinalBase.php?Cx='.$IdEstudiante.'&Cn='.$IdEspCurr.'">';?>
	<div class="row"><div class="col-lg-4"><div class="row"valign='center'><div class="col-lg-3"></div>
		 <div class="col-lg-6 bg-info" ><div class="form-group"><center><label><?php if(mysqli_num_rows($SqlNota)!==0) {echo'Nota Final';} else {echo 'Nota Sugerida';}?></label></center></diV></div></div> </div> </div>
	
     <div class="row"align="center">
	   <div class="col-lg-4">
		 <div class="row">
		  <div class="col-lg-3"></div>
		  <div class="col-lg-6 bg-info"><div class="form-group"><input class="form-control" name="Nota" value="<?php if(mysqli_num_rows($SqlNota)!==0) {echo $_REQUEST['Ca']; }else {echo round($NotaSugerida,0,PHP_ROUND_HALF_UP);}?>" <?php if($cambio==0){echo 'readonly';}?> required></div></div>
		 </div>
	   </div> 
    	
	       <div class="col-lg-4"><center><?php  echo'<div class="well well-sm"><b>Aprendizajes aprobados '.$PorcentajeLogrado.' % </div>';?></div>
		  
		   <div class="col-lg-4"><center><?php echo'<b><div class="well well-sm">Aprendizaje Pendientes '.$PorcentajePendiente.' % </div>';?></div>
				
	
				
	</div><!--Cierre rows3--><br><bR>
	 <?php 
  

	if(mysqli_num_rows($SqlNota)!==0)
	  {
		  
		echo'<div class="row"> <div class="col-lg-12"><div class="alert alert-dismissible alert-success"><strong><center>El estudiante ya ha sido calificado</center></strong></div></div></div><br>';
		}
		?>
		
    <div class="row" align="center">
      
<?php 
if($cambio!==0)
   {
     if(mysqli_num_rows($SqlNota)==0)
	   {
	    $T='G';
?>
		<div class="col-lg-2"></div>
        <div class="col-lg-4"><button class="btn btn-primary" type="submit" name="Guardar"onClick="return confirm ('¿Desea guardar la calificación?');"><box-icon name="save" type="solid" size="sm" color="white" animation="tada" ></box-icon> Guardar Calificación</button></div>
<?php
	  }
	else
	  {
?>	
		<div class="col-lg-2"></div>
		<div class="col-lg-4"><button class="btn btn-primary" type="submit" name="Modificar"onClick="return confirm ('¿Desea modificar la calificación?');"><box-icon name="edit-alt" type="solid" size="sm" color="white" animation="tada"></box-icon> Modificar Calificación</button></div>
<?php
			$T='M';
	 }
   }
?>

<input type="Hidden"name="T" value=<?php echo"$T"?>>
</form>
    
	<div class="col-lg-4"><Form Action="<?php echo 'administrarNotasFinales.php?Cx='.$EspacioCurricular; ?>" Method="Post">
	     <button class="btn btn-danger" type="submit" name="Cancelar"><box-icon name="arrow-back	" size="sm"type="solid" color="white" animation="tada-hover"></box-icon> Retornar</button></form></div>
 <?php
   }//finaliza if de aprendizajes logrados y pendientes dif de 0
   else
   {
?>
        
	<div class="col-lg-5"></div>
	<div class="col-lg-2"><Form Action="<?php echo 'administrarNotasFinales.php?Cx='.$EspacioCurricular; ?>" Method="Post">
	     <button class="btn btn-danger" type="submit" name="Cancelar"><box-icon name="arrow-back" size="sm"type="solid" color="white" animation="tada"></box-icon> Retornar</button></form></div>
<?php
    }//si no hay aprendizajes 

?>
	</div><!--Cierre rows4-->

 
 
</div><!--fin panel-body-->
</div><!--fin panel-primary-->
</div><!--fin page-wrapper-->
</div><!--fin wrapper-->
<!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>
</body>
</html>