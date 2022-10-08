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
require_once 'funciones/buscarCurso.php';
//Conecto con la base de datos
require_once 'funciones/conexion.php';
$MiConexion=ConexionBD();

//Declaro variables
$mensaje='';
?>
<!DOCTYPE html>
<html lang="es">
<head>
 <?php
   require_once 'encabezado.php';
   $idCurso=$_REQUEST['Cx'];   
?>
<link href="estilos.css" rel="stylesheet"  type="text/css"  />
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
  <div class="row">
    <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Datos del Curso</b></font></h2>  </div></div>
  </div>  <!-- /.row titulo --><br>
  <div class="row">
   <div class="col-lg-10">
    <div class="panel panel-primary">
     <div class="panel-heading"><center>Curso N° <?php echo $idCurso?></center></div>
      <div class="panel-body">
        <div class="row">
        <div class="col-lg-12">
          <form role="form" method="post">
<?php 
          //Si cancela vuelvo a administrarEspaciosCursos
          if(!empty($_POST['Cancelar'])) 
			{
              header('Location: administrarCursos.php');
            }
		  if(!empty($_POST['EstudiantesXCurso'])) 
			{
             require_once 'funciones/listarEstudiantes.php';
             $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$idCurso);
             $CantidadEstudiantes = count($ListadoEstudiantes);
             if($CantidadEstudiantes == 0)
			  {
 ?>
                <div class="alert alert-dismissible alert-danger"><strong><center>El curso seleccionado no tiene estudiantes asignados</center></strong></div>
 <?php
              }
			 else 
			  {
               header('Location: funciones/emitirPDFListadoEstudiantesXCurso.php?Cx='.$idCurso);
			  }
            }			  
             if(!empty($_POST['EspCurrXCurso'])) 
			   {
				header('Location: funciones/emitirPDFListadoEspCurrXCurso.php?Cx='.$idCurso);
				} 
			 $CursoEncontrado = array();
             $CursoEncontrado = buscarCurso($MiConexion,$idCurso);
             $Cont = 0;
             $Cont = count($CursoEncontrado);
             if($Cont != 0)
			   {
                $_POST['AnioCurso'] = $CursoEncontrado['ANIO'];
                $_POST['DivisionCurso'] = $CursoEncontrado['DIVISION'];
               } 
			 else 
			   {
?>
				 <div class="alert alert-dismissible alert-danger"><strong><center>Número identificador de Curso no válido</center></strong></div>
<?php
                $_POST['AnioCurso'] = '';
                $_POST['DivisionCurso'] = '';
               }
			
?>
			</div><!--Cierra col errores-->
		</div><!--Cierra Row errores--><br>
		<div class="row">
			<div class="col-lg-6">  
				<div class="form-group"><label>Año 	</label><input class="form-control" name="AnioCurso" value="<?php echo !empty($_POST['AnioCurso']) ? $_POST['AnioCurso'] : ''; ?>" readonly></div>
			  </div>
			  <div class="col-lg-6">  					
                 <div class="form-group"><label>Divisi&oacuten </label><input class="form-control" name="DivisionCurso" value="<?php echo !empty($_POST['DivisionCurso']) ? $_POST['DivisionCurso'] : ''; ?>" readonly></div>
			  </div>
		</div><!-- /.row input --><br><br>	
	

<div class="panel panel-info">
  <div class="panel-heading"><center><b>Espacios Curriculares del Curso</b></center></div>
  <div class="panel-body">
   <div class="row" align="center">
    
<?php 
    $ListadoEspaciosCurriculares = ListarEspCurrXCurso($MiConexion,$idCurso);
     $CantidadEspaciosCurriculares = count($ListadoEspaciosCurriculares);
     if ($CantidadEspaciosCurriculares == 0)
	   { 
?>       
          <div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong><center>El curso seleccionado no tiene Espacios Curriculares asignados</center></strong></div></div>
<?php
		}
 ?>

<?php if(!empty($ListadoEspaciosCurriculares)) 
		{
?> 
          <div class="col-lg-1"></div>
          <div class="col-lg-10">                              
          <div class="table-responsive">
              <table class="table table-striped table-bordered">
                <thead>
                  <tr class="bg-primary">
                    <td>N°</td>
                    <td>Nombre Espacio Curricular</td>
                    <td>Área</td>
                    </tr>
                </thead>
                <tbody>
<?php
                        //Cargo a la tabla el listado de los estudiantes
                            for ($i=0; $i < $CantidadEspaciosCurriculares; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $ListadoEspaciosCurriculares[$i]['ID']; ?></td>
                                    <td><?php echo $ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']; ?></td>
                                    <td><?php echo $ListadoEspaciosCurriculares[$i]['AREA']; ?></td>
                </tr> 
                         <?php   }
                        ?>
                </tbody>
              </table>
            </div>
			</div>
        

<?php } ?>
 </div><!-- fin row panel-->
  </div><!-- fin panel body info-->
</div><!-- fin panel info--><br>
<div class="row" align="center">
    <div class="col-lg-6"><button type="submit" class="btn btn-primary" value="EspCurrXCurso" name="EspCurrXCurso"><box-icon  name="shopping-bag"  size="sm" color="white" animation="tada-hover"></box-icon> Emitir Listado Espacios Curriculares por Curso</button></div>
	<div class="col-lg-6"><button type="submit" class="btn btn-primary" value="EstudiantesXCurso" name="EstudiantesXCurso" ><box-icon  type='solid' name='user-detail'  size="sm" color="white" animation="tada-hover"></box-icon> Emitir Listado Estudiantes por Curso</button></div>
</div><!-- /.row input -->   <br><br>
<div class="row" align="center">
	<div class="col-lg-12"> <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon  name="arrow-back"  size="sm" color="white" animation="tada"></box-icon> Retornar</button></div>
</div><!--fin row boton --><br>

 </div>      <!-- /.panel-body -->   
    </div>  <!-- /.panel primary -->     
    </div>   <!-- /.col principal-->  
   </div>    <!-- /.row principal -->  
 </div>   <!-- /#page-wrapper -->
</div>    <!-- /#wrapper -->
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
