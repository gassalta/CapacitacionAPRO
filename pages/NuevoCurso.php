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
    <div class="row">
      <div class="col-lg-10"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Nuevo Curso</b></font></h2></div>
    </div><!-- /.row titulo--><br>
    <div class="row">
       <div class="col-lg-10">
          <div class="panel panel-primary">
             <div class="panel-heading"><center>Complete los datos</center></div><br>
             <div class="panel-body">
               <div class="row">
                 <div class="col-lg-12">
                   <form role="form" method="post">
<?php 
                    //Si cancela vuelvo a administrarEspaciosCurriculares
                     if (!empty($_POST['Cancelar']))
						{//$accion = 0;
                         header('Location: administrarCursos.php');
                        }
					//Si confirma verifico los campos
                     if(!empty($_POST['Confirmar'])) 
						{
                         $mensaje = '';
                         if(empty($_POST['AnioCurso']))
						   {
                            $mensaje = 'Debe completar el año correspondiente al curso';
                           } 
                         require_once 'funciones/listaCursos.php';
                         $Listado=array();
                         $Listado = ListarCursos($MiConexion);
                         $CantidadCursos = count($Listado);
                         $Existe = 0;
                          for($i=0; $i < $CantidadCursos; $i++)
							{ 
                             if ($_POST['AnioCurso'] == $Listado[$i]['ANIO'] && $_POST['DivisionCurso'] == $Listado[$i]['DIVISION'])
								{
                                  $Existe = 1;
                                }
                            }
                          if($Existe==1) 
							{
                              $mensaje = "El curso ingresado ya existe";
                            }
                          if($mensaje=='')
							{//Si está todo bien creo espacio curricular nuevo en base de datos, sino, muestro   mensaje
                             require_once 'funciones/guardarCurso.php';
                             if (cursoNuevo($MiConexion,$_POST['AnioCurso'],$_POST['DivisionCurso'])) 
								{
?>
                                  <div class="bs-component"><div class="alert alert-dismissible alert-success"> <strong><center>¡Nuevo Curso guardado!</center></strong></div></div>
<?php 
								}
                                                        
                            }
						  else
							{
?>
								<div class="alert alert-dismissible alert-danger"><strong><center><?php echo $mensaje;?></center></strong></div>
<?php
							}
						}
		
		?>
                 </div>
			   </div> <!-- /.row errores -->
			   <div class="row" align="center">
                  <div class="col-lg-6">  
                    <div class="form-group">
                        <label>Año*</label><input class="form-control" name="AnioCurso" value="<?php echo !empty($_POST['AnioCurso']) ? $_POST['AnioCurso'] : ''; ?>">
                    </div>
				   </div>
				  <div class="col-lg-6">  					
                    <div class="form-group">
                       <label>Divisi&oacuten </label><input class="form-control" name="DivisionCurso" value="<?php echo !empty($_POST['DivisionCurso']) ? $_POST['DivisionCurso'] : ''; ?>">
                    </div>
				   </div>
				</div><!-- /.row input --><br>
                <div class="row" align="center">
				  <div class="col-lg-12"> <div class="alert alert-dismissible alert-info"><center><b>Los campos marcados con  * son obligatorios</b></div></div>
			    </div><!-- /.row aviso --><br>
                 <div class="row" align="center">
				  <div class="col-lg-3"></div> 
				  <div class="col-lg-3"> <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('¿Desea guardar el nuevo Curso?');"><box-icon  name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
				  <div class="col-lg-3"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" ><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div>
				 </div><!-- /.row botones --><br>
				 
              </div><!-- /.panel-body -->
           </div>  <!-- /.panel primary-->
          </div><!-- /.col-lg-12 principal -->
        </div>  <!-- row principal-->
      </div> <!-- /#page-wrapper -->
 </div><!-- /#wrapper -->

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
