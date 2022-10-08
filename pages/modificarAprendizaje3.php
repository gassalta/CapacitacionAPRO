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
?>
<!DOCTYPE html>
<html lang="es">
<head>
<?php
    require_once 'encabezado.php';
?>
<script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
</head>
<body>
	<div id="wrapper">
<?php
    require_once 'top.php';
    require_once 'menuDerecho.php';
    require_once 'funciones/DatosUsuario.php';
    require_once 'menuLateral.php';
	$Tipo=$_REQUEST['Tx'];
	$Aprendizaje=$_REQUEST['Cx'];
	$EspacioCurricular=$_REQUEST['Ec'];
	$Cont=0;

	if ($Tipo!='A')
	{
		$ConsultaAprendizaje=consultaEspacioContenidosAprendizajesA($Aprendizaje);
		if($Fila=mysqli_fetch_array($ConsultaAprendizaje)){
			$Id=$Fila['id'];
			$Contenido=$Fila['nombreContenido'];
			$DenominacionAprendizaje=$Fila['nombreAprendizaje'];	
			$ECurricular=$Fila['espacioCurricular'];
			$Cont=$Fila['contenido'];
			}
		if($Tipo=='E')
		{
		 UpdateEliminarAprendizaje($Aprendizaje);
		 header('Location:Aprendizajes.php?Cx='.$Cont.'&Cn='.$Contenido.'&Ec='.$ECurricular);
		}
     }
	  else
	  {

		$ConsultaContenido=consultaContenidoId($Aprendizaje);
		if($Fila=mysqli_fetch_array($ConsultaContenido)){
			$Id=$Fila['id'];
			$Contenido=$Fila['denominacion'];
			$Cont=$Id;
			}
	 }
	
?>
	<div id="page-wrapper">
	 <div class="row">
        <div class="col-md-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center> <?php if($Tipo=='E'){echo 'Eliminar Aprendizaje';} elseif($Tipo=='M') {echo'Modificar Aprendizaje';}else{echo'Nuevo Aprendizaje';}?></h3></center></font></div></div>
		</div><br>
     <div class="row">
        <div class="col-md-10">
		    <div class="panel panel-primary">
					<div class="panel-heading">Por favor complete los datos</div><br>
                        <div class="panel-body">
         <!-- <div class="tile">
            <h3 class="tile-title"><?php if($Tipo=='E'){echo 'Eliminar aprendizaje';} elseif($Tipo=='M') {echo'Modificar Aprendizaje';}else{echo'Cargar un nuevo aprendizaje';}?></h3>	-->
			<form name="contact" method="post" action="modificarAprendizajeBase.php">
<?php 
			if($Tipo!='A'){
?>
                <div class="row">
				<div class="col-lg-6">
				<div class="form-group">
					<label>N&uacutemero de Apendizaje </label><input class="form-control" name="Id" value="<?php echo $Aprendizaje; ?>" readonly></div>
				</div>
				<div class="col-lg-6">
				<div class="form-group">
					<label>Nombre de Espacio Curricular </label><input class="form-control" name="NombreEspCurr" value="<?php echo $ECurricular; ?>"readonly></div></div>
				</div>	<hr>
<?php 
			}
?>
				 <div class="row">
				<div class="col-lg-6">
				<div class="form-group">
					<label> Nombre del Contenido</label><input class="form-control" name="NombreContenido" value="<?php echo $Contenido; ?>"readonly></div></div>
				<div class="col-lg-6  bg-info">
				<div class="form-group">
					<label>Nombre del Aprendizaje</label><input  class="form-control bg-primary" name="NombreAprendizaje" value="<?php if($Tipo!='A'){ echo $DenominacionAprendizaje;} ?>" <?php if($Tipo=='E')echo'readonly';?>> 
                </div></div>
				</div><hr>
				
            <input type="Hidden"name="Tipo" value=<?php echo"$Tipo"?>>	
			<input type="Hidden"name="IdContenido" value=<?php echo"$Cont"?>>				
			 <div class="row" align="center">
			 <div class="col-lg-2"></div>
			<div class="col-lg-4">
							
               <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('Seguro que desea guardar los cambios del Aprendizaje?');"
               ><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon><?php if($Tipo=='E')echo'Eliminar'; elseif($Tipo=='M') echo'Modificar'; else echo'Agregar' ?></button>
			  
			</form>
			 </div>
			 <div class="col-lg-4">
			<Form Action="Aprendizajes.php?Cx=<?php echo $Cont; ?>&Cn=<?php echo $Contenido; ?>&Ec=<?php echo $EspacioCurricular; ?>" Method="Post"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" ><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>Retornar</button>
       
         </div>
		 </div>
    </div>
    </div>
    </div>
</div>
	</div>

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