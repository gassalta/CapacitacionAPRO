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

require_once 'funciones/buscarDocente.php';
$Listado=array();
$Listado = buscarDocenteXDNI($MiConexion,$_SESSION['DNIDocenteElegido']);
$CantidadDocentes = count($Listado);
if ($CantidadDocentes == 0) {
  header('Location: '.$_SESSION['Envia']);
}

require_once 'funciones/listarEspaciosCurricularesXDocente.php';
$ListadoEspCurrXDoc = array();
$ListadoEspCurrXDoc = ListarEspCurrXDocente($MiConexion,$Listado['ID']);
$CantEspCurrXDoc = count($ListadoEspCurrXDoc);

require_once 'funciones/listarEspaciosCurriculares.php';
$ListadoEspCurricular = array();
$ListadoEspCurricular = Listar_EspCurr($MiConexion);
$CantEspCurricular = count($ListadoEspCurricular);
$IdDocente=$_REQUEST['Cx'];
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
      <div class="col-lg-8"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>
	  <?php echo $Listado['APELLIDO']." ".$Listado['NOMBRE']; ?></b></font></h2>  </div></div><br>
    </div> <!-- /.row titulo --><br>
    <div class="row">
	 <div class="col-lg-1"></div>
     <div class="col-lg-6">
       <div class="panel panel-primary">
         <div class="panel-heading"><center>Seleccione los Espacios Curriculares </center></div>
          <div class="panel-body">
            <form role="form" method="post">
			<div class="row">
	          <div class="col-lg-12">
<?php 
              //Si cancela vuelvo a la pagina que mandó
              if(!empty($_POST['Cancelar']))
				{
                 if($IdDocente==0)
				   {
					header('Location:administrarDocentes.php');
					} 
				 else
				   {
                    header('Location: '.$_SESSION['Envia'].'?Cx='.$IdDocente);
					}
                 }

              //Si confirma incluyo las funciones para guardar espacios curriculares
              if(!empty($_POST['Confirmar']))
				{
                 require_once 'funciones/guardarEspacioCurricular.php';
                 //Recorro el listado de Espacios Curriculares
                 for($i=0; $i < $CantEspCurricular; $i++)
					{
                     $EsDeDocente = 0; 
                     //Reviso si es de los que tiene el docente a cargo con anterioridad
                     for($j=0; $j < $CantEspCurrXDoc; $j++)
					   { 
                        if($ListadoEspCurricular[$i]['ID'] == $ListadoEspCurrXDoc[$j]['ID'])
						{
                         $EsDeDocente = 1;
                        }
                       }
                    //Reviso si el checkbox correspondiente está checked
                 if(!empty($_POST[$ListadoEspCurricular[$i]['ID']]) && $_POST[$ListadoEspCurricular[$i]['ID']] == 'SI')
				   {
                    //Si está checked verifico si no es de los que ya tenía a cargo el docente
                    if($EsDeDocente==0)
					  {
                        //Modifico el docente a cargo del espacio curricular
                        if(guardarEspacCurricXDocente($MiConexion,$ListadoEspCurricular[$i]['ID'],$Listado['ID'])) 
						 {
?>
                          <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡ <?php echo $ListadoEspCurricular[$i]['NOMBREESPACCURRIC']; ?> guardado a cargo del docente!<center></strong></div></div>
<?php
                         }
                      }
                    }
				  else
					{
                     //Si no está checked verifico si es de los que ya tenía a cargo el docente
                     if($EsDeDocente == 1)
					   {
                        //Pongo en 0 el código del docente a cargo del espacio curricular
                         if(guardarEspacCurricXDocente($MiConexion,$ListadoEspCurricular[$i]['ID'],0))
						  {
?>
                            <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>Ya no hay docente a cargo de <?php echo $ListadoEspCurricular[$i]['NOMBREESPACCURRIC']; ?></center></strong></div></div>
<?php
                           }
                        }
                    }
                   }//cierra el for
                    $ListadoEspCurrXDoc = ListarEspCurrXDocente($MiConexion,$Listado['ID']);
                   $CantEspCurrXDoc = count($ListadoEspCurrXDoc);
                }//cierra el confirmar
?>  
			</div>
			</div><!--fin row errores--><br>
			 <div class="form-group">
			<div class="row">
	          <div class="col-lg-12">
                <div class="checkbox">
 <?php
                  for($i=0; $i < $CantEspCurricular; $i++)
					{ 
                      $EsDeDocente = 0;
                      for($j=0; $j < $CantEspCurrXDoc; $j++)
						{ 
                         if($ListadoEspCurricular[$i]['ID'] == $ListadoEspCurrXDoc[$j]['ID'])
						   {
                            $EsDeDocente = 1;
                           }
                        } 
?>
                        <label><input type="checkbox" name="<?php echo $ListadoEspCurricular[$i]['ID']; ?>" value="SI" <?php echo ($EsDeDocente == 1) ? 'checked' : ''; ?>> <?php echo $ListadoEspCurricular[$i]['NOMBREESPACCURRIC']; ?></label><br><br>
<?php  
					}//cierra for
 ?>
                </div><!-- fin checkbox -->
				</div></div><!-- fin row checkbox --><br>
                <div class="row"align="center">
				  <div class="col-lg-2"></div>
				  <div class="col-lg-4"><button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('¿Desea guardar los Espacios Curriculares a cargo del Docente?');"><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Modificar</button></div>
				  <div class="col-lg-4"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div>
                </div><!-- fin row botones -->
     
      </div>  <!-- fin panel-body -->  
	</div> <!--FIN .panel PRIMARY-->
    </div> <!-- fin col primary-->
	</div> <!-- fin row primary-->
	</div> <!--fin page-wrapper -->
	</div>  <!-- fin wrapper -->
  

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
