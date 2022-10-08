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

require_once 'funciones/listaCursos.php';
$Listado=array();
$Listado = ListarCursosComp($MiConexion);
$CantidadCursos = count($Listado);

require_once 'funciones/buscarEstudiante.php';
$Estudiante = array();
$Estudiante = buscarEstudiante($MiConexion,$_SESSION['IdEstudianteSeleccionado']);
$CantEstudiante = count($Estudiante);
 $IdEstudiante=$_REQUEST['Cx'];
if ($CantEstudiante == 0) {
        header('Location: buscarUnEstudiante.php?Cx='.$IdEstudiante);
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
<?php
   require_once 'encabezado.php';
?>
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
   <div class="row" align="center">
    <div class="col-lg-8"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Estudiante <?php echo $Estudiante['APELLIDO'];echo ", ";  echo $Estudiante['NOMBRE']; ?></b></font></h2>  </div></div>
   </div>  <!-- /.row titulo --><br>
   <div class="row">
    <div class="col-lg-1"></diV>
    <div class="col-lg-6">
     <div class="panel panel-primary">
       <div class="panel-heading"><center>Seleccione el curso actual</center></div>
       <div class="panel-body">
         <div class="row">
          <div class="col-lg-12">
            <form role="form" method="post">
<?php 
            //Si cancela vuelvo a la pagina que mandó
            if(!empty($_POST['Cancelar']))
			  {
                header('Location: buscarUnEstudiante.php?Cx='. $IdEstudiante);
              }
			//Si confirma verifico los campos
            if(!empty($_POST['Confirmar']))
			  {
               require_once 'funciones/guardarEstudiante.php';
               if(!empty($_POST['curso']))
				 {
                  if($_POST['curso'] != $Estudiante['CURSO'])
					{
                     if(guardarCursoActualEstudiante($MiConexion,$Estudiante['ID'],$_POST['curso']))
					   {
?>
                        <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>Se registró correctamente el curso actual del estudiante</center></strong></div></div>
 <?php
                        $Estudiante = buscarEstudiante($MiConexion,$_SESSION['IdEstudianteSeleccionado']);
                        $CantEstudiante = count($Estudiante);
                        if($CantEstudiante != 0)
						  {
                           $_POST['curso'] = $Estudiante['CURSO'];
                          }
                       }
                    }
                 else 
				    {
?>
					 <br><div class="alert alert-dismissible alert-danger"><strong><center>El curso seleccionado es el que ya se encuentra registrado</center></strong></div>
         
<?php
                    }
                  } 
				 else 
				  {
 ?>
                    <br><div class="alert alert-dismissible alert-danger"><strong><center>Debe seleccionar un curso</center></strong></div>
			  
<?php
                   }
              }
?>
			  </div>		
             </div><!--fin row errores-->
			 <div class="row" align="left">
			  <div class="col-lg-3"></div>
              <div class="col-lg-7">
			 
               <div class="form-group">
<?php
				for($i=0; $i < $CantidadCursos; $i++)
				   { 
 ?>
                     <div class="radio"><label> <input type="radio" name="curso" id="<?php echo $Listado[$i]['ID']; ?>" value="<?php echo $Listado[$i]['ID']; ?>" <?php echo ($Listado[$i]['ID'] == $Estudiante['CURSO']) ? 'checked' : ''; ?>>  Año:  <?php echo $Listado[$i]['ANIO']." - Division: ".$Listado[$i]['DIVISION']; ?></label></div>	
 <?php  
					}
?>
                </div>
		      </div>
			</div>  <!--fin row radios-->   <br>
        <div class="row"align="center">
		  <div class="col-lg-2"> </div>
          <div class="col-lg-4"><button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('¿Desea guardar el curso actual del estudiante?');"> <box-icon  name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
          <div class="col-lg-4"> <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div>
		</div>  <!-- /.row botones --><br>
    </div>     <!-- /.panel-body -->
    </div>   <!-- /.panel primary -->
    </div>  <!-- /.col-lg-primary -->
    </div> <!-- /.row primary -->
    </div>  <!-- fin page-wrapper -->
    </div>   <!-- fin wrapper -->
 

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
