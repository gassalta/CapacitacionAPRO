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

require_once 'funciones/listaCursos.php';
$ListadoCursos=array();
$ListadoCursos = ListarCursos($MiConexion);
$CantidadCursos = count($ListadoCursos);
$Emitidas = 0;
require_once 'funciones/listarEstudiantes.php';
require_once 'funciones/emitirPDFracs.php';
$Listo1 = 0;
$Listo2 = 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
 <?php
   require_once 'encabezado.php';
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
    <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Registros Anuales de Calificaciones</b></font></h2>  </div></div>
  </div>  <!-- /.row titulo --><br>
  <div class="row">
   <div class="col-lg-10">
     <div class="panel panel-primary">
        <div class="panel-heading"><center>¿Qué registro desea emitir?</center></div><br>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <form role="form" method="post">
<?php 
                //Si cancela vuelvo a administrarEspaciosCursos
                if(!empty($_POST['Cancelar'])) 
				   {
                    header('Location: index.php');
                    }          
?>
			</div><!--Cierra col errores-->
		</div><!--Cierra Row errores-->
		<div class="row" align="center">
		    <div class="col-lg-2"></div>
			<div class="col-lg-2"><label>Curso</label></DIV>
			<div class="col-lg-5">
      <select class="form-control" name="Curso" id="Curso">
        <option value="">Seleccione un Curso</option>
        <?php 
          $selectedC='';
          for ($i=0 ; $i < $CantidadCursos ; $i++) {
            if (!empty($_POST['Curso']) && $_POST['Curso'] ==  $ListadoCursos[$i]['ID']) {
              $selectedC = 'selected';
            }else {
              $selectedC='';
            }
            if ($ListadoCursos[$i]['DIVISION'] != "" && $ListadoCursos[$i]['DIVISION'] != "Ninguna") {
        ?>
            <option value="<?php echo $ListadoCursos[$i]['ID']; ?>" <?php echo $selectedC; ?>  >
              Año:  <?php echo $ListadoCursos[$i]['ANIO']." - Division: ".$ListadoCursos[$i]['DIVISION']; ?>
            </option>
<?php   }
          } 
		  ?>
      </select></div>
    </div><!-- row busqueda--><br><br>
   
		
		<div class="row" align="center">
		  <div class="col-lg-12"><button type="submit" class="btn btn-primary" value="EmitirRACs" name="EmitirRACs"><box-icon  type='solid' name='user-detail'  size="sm" color="white" animation="tada-hover"></box-icon> Emitir Registros Anuales de Calificaciones de los Estudiantes del Curso</button></div>
		</div><!-- row boton emitir--> <br>
<?php
         if(!empty($_POST['EmitirRACs'])) 
          {
           if(empty($_POST['Curso'])) 
			{
?>
			 <br><div class="row" align="center">
			   <div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong><center>Debe seleccionar un curso</center></strong></div></div>
			 </div>
<?php
            }
		   else 
		    {
              
             $files = glob('funciones/RACs/*'); //obtenemos todos los nombres de los ficheros
             foreach($files as $file)
			   {
                if(is_file($file))
                unlink($file); //elimino el fichero
				}            
			 $_SESSION['idCurso'] = $_POST['Curso'];
             $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$_SESSION['idCurso']);
             $CantidadEstudiantes = count($ListadoEstudiantes);
              if($CantidadEstudiantes == 0)
				{
?>
				  <br><div class="row" align="center">
				   <div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong><center>El curso seleccionado no tiene estudiantes asignados</center></strong></div></div>
                   </div>
<?php
                }
			  else 
			    {
                 if($CantidadEstudiantes>10)
				   {
                    $_SESSION['tercioCantEst'] = round($CantidadEstudiantes/3);
                    for($i=0; $i < $_SESSION['tercioCantEst']; $i++)
					  { 
                       generate($_SESSION['idCurso'],$ListadoEstudiantes[$i]['ID']);
                      }
                    $Listo1 = 1;
?>
                    <br><div class="row" align="center">
				    <div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-success">
                   <strong><center>Algunos RACs listos. Presione "Continuar"</center></strong></div></div></div>
				   </div>
<?php
                   } 
				 else
				   {
					for($i=0; $i < $CantidadEstudiantes; $i++)
					  { 
                       generate($_SESSION['idCurso'],$ListadoEstudiantes[$i]['ID']);
                      }
?>
                      <br><div class="row" align="center">
				     <div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-success">
                    <strong><center>Todos los RACs se generaron y están listos para su descarga</center></strong></div></div> </div>
				    </div>
<?php
                    $Emitidas = 1;
                   }
                }
            }
           }//cierra if si se presiono emitir rac
            if ($Listo1 ==1) 
			  { 
?>
                <br><div class="row" align="center">
				  <div class="col-lg-12"><button type="submit" class="btn btn-primary" value="Continuar1" name="Continuar1" ><box-icon  type='solid' name='user-detail'  size="sm" color="white" animation="tada"></box-icon> Continuar</button></div></div><br><br>
<?php 
			  }
         if (!empty($_POST['Continuar1'])) 
		    {
			 $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$_SESSION['idCurso']);
             $CantidadEstudiantes = count($ListadoEstudiantes);
             $_SESSION['dostercios'] = $_SESSION['tercioCantEst']*2;
             for($i=$_SESSION['tercioCantEst']; $i < $_SESSION['dostercios']; $i++)
				{ 
                  generate($_SESSION['idCurso'],$ListadoEstudiantes[$i]['ID']);
                }
                $Listo2=1;
 ?>
                 <br><div class="row" align="center">
				  <div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-success">
                  <strong><center>Se generaron algunos RACs más. Vuelva a presionar "Continuar"</center></strong> </div></div></div>
				  </div>
<?php
            }
            if($Listo2 ==1)
			  {
?>
                  <div class="row" align="center">
				    <div class="col-lg-12"><button type="submit" class="btn btn-primary" value="Continuar2" name="Continuar2" ><box-icon  type='solid' name='user-detail'  size="sm" color="white" animation="tada"></box-icon> Continuar</button></div>
				  </div><br><br>
<?php 
	          }
            if (!empty($_POST['Continuar2']))
			  {
				$ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$_SESSION['idCurso']);
				$CantidadEstudiantes = count($ListadoEstudiantes);
				for($i=$_SESSION['dostercios']; $i < $CantidadEstudiantes; $i++)
				   { 
                    generate($_SESSION['idCurso'],$ListadoEstudiantes[$i]['ID']);
                   }
?>
                     <div class="row" align="center">
						<div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>Todos los RACs se generaron y están listos para su descarga</center></strong></div></div> </div> </div>
<?php
                 $Emitidas = 1;
             }
              if ($Emitidas == 1)
			    {
?>
                 <div class="row" align="center"><div class="col-lg-12"><button type="submit" class="btn btn-primary" value="DescargarLibretas" name="DescargarLibretas" ><box-icon  type='solid' name='download'  size="sm" color="white" animation="tada-hover"></box-icon> Descargar Registros Anuales de Calificaciones</button></div></div>
                  <br>
<?php
                }

              if(!empty($_POST['DescargarLibretas']))
				{
                  header('Location: funciones/generarZipConRACs.php');

           /* foreach (glob("*.pdf") as $filename) {
   echo "$filename size " . filesize($filename) . "\n";
   unlink($filename);
}  */
                } 
?>
        <div class="row" align="center">
		 <div class="col-lg-12"> <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon  name="arrow-back"  size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div>
		</div>
                                       
   
         </div> <!-- /.panel-body -->   
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
