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

require_once 'funciones/buscarCurso.php';
$CursoElegido = array();
require_once 'funciones/buscarEstudiante.php';
$EstudElegido = array();

//Declaro variables
$mensaje='';
?>
<!DOCTYPE html>
<html lang="en">

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
                <div class="col-lg-10">
                    <h2 class="tile-title" ><font color="#85C1E9"><center><b>Asistencias Diarias </b></font></h2>
                </div>
                <!-- /.col-lg-12 -->
            </div><!-- /.row titulo -->
            
            
<?php
    //Listo los cursos
require_once 'funciones/listaCursos.php';
	$ListadoCursos=array();
	$ListadoCursos = ListarCursos($MiConexion);
	$CantidadCursos = count($ListadoCursos);
require_once 'funciones/listarEstudiantes.php';
    $ListadoEstudiantes=array();
    $ListadoEstudiantes = Listar_Estudiantes($MiConexion);
    $CantidadEstudiantes = count($ListadoEstudiantes);
require_once 'funciones/listarInstancias.php';
    $ListadoInstancias=array();
    $ListadoInstancias = Listar_Instancias($MiConexion);
    $CantidadInstancias = count($ListadoInstancias);
require_once 'funciones/listarSituaciones.php';
    $ListadoSituaciones=array();
    $ListadoSituaciones = Listar_Situaciones($MiConexion);
    $CantidadSituaciones = count($ListadoSituaciones);
?>
			<div class="row">
			  <div class="col-lg-10">
				<div class="panel panel-primary">
					<div class="panel-heading">
						Seleccione los datos
					</div>
				<div class="panel-body">
					<div class="row">
                      <div class="col-lg-12">
                        <form role="form" method="post">
						
<?php                       
                            if (!empty($_POST['Cancelar']))
								{
                                  header('Location: index.php');
                                }
							//Si confirma verifico los campos
                            if (!empty($_POST['Confirmar'])) 
								{
                                 $mensaje = '';
                                 if(empty($_POST['Fecha'])|| empty($_POST['Situacion']))
									{
?>
										<div class="alert alert-dismissible alert-danger"><strong><center>Debe completar los campos obligatorios</center></strong></div>
					  
             <?php
                                    } 
								else {
                                       //Si está todo bien, veo que no esté el detalle guardado anteriormente
                                      $fecha =strtotime($_POST['Fecha']);
                                      $anio = date("Y",$fecha);
                                      require_once 'funciones/buscarAsistencia.php';
                                      $Asist = array();
                                      $Asist = buscarAsistencia($MiConexion,$_SESSION['EstudEle'],$anio,$_SESSION['InstanciaEle']);
                                      if (!empty($Asist)) 
										{
                                          $detalleExiste = array();
                                          $detalleExiste = buscarDetalleAsistencia ($MiConexion,$Asist['ID'],$_POST['Fecha']);
                                          if (!empty($detalleExiste)) 
											{
 ?>
												<div class="alert alert-dismissible alert-danger"><strong>La asistencia ya está registrada con anterioridad</strong></div>
					</div><!-- Cierra Row carteles de errores-->
             <?php
                                            }
										  else {
                                                //Si está todo bien, creo la asistencia y/o el detalle, según corresponda
                                               if ($_POST['Situacion']!=2) {
                                                    $_POST['Justificacion'] = '';
                                               }
                                                require_once 'funciones/guardarAsistencia.php';
                                                  if (guardarAsistenciaYDetalle($MiConexion,$_SESSION['EstudEle'],$anio,$_SESSION['InstanciaEle'],$_POST['Fecha'],$_POST['Situacion'],$_POST['Justificacion'])) 
													{
?>
                                                     <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong>¡Asistencia guardada!</strong></div></div>
                                                    <?php
                                                    $Lugar=0;
                                                    $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$_SESSION['CursoEle']);
                                        $CantidadEstudiantes = count($ListadoEstudiantes);
                                                    for ($i=0; $i < $CantidadEstudiantes; $i++) { 
                                                        if ($_SESSION['EstudEle'] == $ListadoEstudiantes[$i]['ID']) {
                                                            $Lugar=$i;
                                                        }
                                                    }

                                                    if ($Lugar < $CantidadEstudiantes-1) {
                                                        $_POST['Estudiante'] = $ListadoEstudiantes[$Lugar+1]['ID'];
                                                        $_SESSION['EstudEle'] = $ListadoEstudiantes[$Lugar+1]['ID'];
                                                    } else {
                                                        $_POST['Estudiante'] = '';
                                                        $_SESSION['EstudEle'] = '';
                                                    }
                                                    $_POST['Instancia'] = '';
                                                    $_POST['Fecha']='';
                                                    $_POST['Situacion'] = '';
                                                    $_POST['Justificacion'] = '';
                                                    }
                                                }
                                        } else {
                                                //Si está todo bien, creo la asistencia y/o el detalle, según corresponda
                                               if ($_POST['Situacion']!=2) {
                                                    $_POST['Justificacion'] = '';
                                               }
                                                require_once 'funciones/guardarAsistencia.php';
                                                  if (guardarAsistenciaYDetalle($MiConexion,$_SESSION['EstudEle'],$anio,$_SESSION['InstanciaEle'],$_POST['Fecha'],$_POST['Situacion'],$_POST['Justificacion'])) 
                                                    {
?>
                                                     <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong>¡Asistencia guardada!</strong></div></div>
                                                    <?php
                                                    $Lugar=0;
                                                    $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$_SESSION['CursoEle']);
                                        $CantidadEstudiantes = count($ListadoEstudiantes);
                                                    for ($i=0; $i < $CantidadEstudiantes; $i++) { 
                                                        if ($_SESSION['EstudEle'] == $ListadoEstudiantes[$i]['ID']) {
                                                            $Lugar=$i;
                                                        }
                                                    }

                                                    if ($Lugar < $CantidadEstudiantes-1) {
                                                        $_POST['Estudiante'] = $ListadoEstudiantes[$Lugar+1]['ID'];
                                                    $_SESSION['EstudEle'] = $ListadoEstudiantes[$Lugar+1]['ID'];
                                                    } else {
                                                        $_POST['Estudiante'] = '';
                                                        $_SESSION['EstudEle'] = '';
                                                    }
                                                    $_POST['Instancia'] = '';
                                                    $_POST['Fecha']='';
                                                    $_POST['Situacion'] = '';
                                                    $_POST['Justificacion'] = '';
                                                    } 

                                        }
                                      }
                                } ?>
                                    </div>
						</div>
                                    <div class="form-group">
									<div class="row">
									   <div class="col-lg-2"><label>Curso</label></div>
                                       <div class="col-lg-6">
										 <select class="form-control" name="Curso" id="Curso">
                                                <option value="">Seleccione un curso</option>
                                                <?php 
                                                $selectedC='';
                                                for ($i=0 ; $i < $CantidadCursos ; $i++) {
                                                    if (!empty($_POST['Curso']) && $_POST['Curso'] ==  $ListadoCursos[$i]['ID']) {
                                                        $selectedC = 'selected';
                                                    }else {
                                                        $selectedC='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoCursos[$i]['ID']; ?>" <?php echo $selectedC; ?>  >
                                                        Año:  <?php echo $ListadoCursos[$i]['ANIO']." - Division: ".$ListadoCursos[$i]['DIVISION']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select></div>
                                           <div class="col-lg-2"><button type="submit" class="btn btn-primary" value="ElegirCurso" name="ElegirCurso"><box-icon  name="show"  size="sm" color="white" animation="tada-hover"></box-icon> Ver</button></div>
									</div><!--Cierro row Curso-->		
                           <?php   if (!empty($_POST['ElegirCurso']))
									  {
									   $CursoElegido = buscarCurso($MiConexion,$_POST['Curso']);
									   $_SESSION['CursoEle'] = $_POST['Curso'];
									   if (!empty($CursoElegido)) 
										 {
                                        //echo "Año: ".$CursoElegido['ANIO']." - Division: ".$CursoElegido['DIVISION'];
										}
										require_once 'funciones/listarEstudiantes.php';
										$ListadoEstudiantes=array();
										$ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$_POST['Curso']);
										$CantidadEstudiantes = count($ListadoEstudiantes);
										if ($CantidadEstudiantes == 0) 
										 {
      ?>
										<div class="row">
											<div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong>El curso seleccionado no tiene estudiantes asignados</strong> </div></div>
										</div><!--Cierro row Error-->
										</div><!--Cierro form-group-->
             <?php
                                    }
                           } ?>
                                      <hr>    
                                    <div class="form-group">
									  <div class="row">
									   <div class="col-lg-2"><label>Estudiante</label></div>
                                         <div class="col-lg-6">  
										 <select class="form-control" name="Estudiante" id="Estudiante">
                                                <option value="">Seleccione un estudiante</option>
                                                <?php 
                                                $selectedE='';
                                                for ($i=0 ; $i < $CantidadEstudiantes ; $i++) {
                                                    if (!empty($_POST['Estudiante']) && $_POST['Estudiante'] ==  $ListadoEstudiantes[$i]['ID']) {
                                                        $selectedE = 'selected';
                                                    }else {
                                                        $selectedE='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoEstudiantes[$i]['ID']; ?>" <?php echo $selectedE; ?>  >
                                                        <?php echo $ListadoEstudiantes[$i]['ID']."- ".$ListadoEstudiantes[$i]['APELLIDO']." ".$ListadoEstudiantes[$i]['NOMBRE']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select></div>
											 <div class="col-lg-2">
                                            <button type="submit" class="btn btn-primary" value="ElegirEstudiante" name="ElegirEstudiante"><box-icon  name="show"  size="sm" color="white" animation="tada-hover"></box-icon> Ver</button></div>
										</div><!--Cierra row Estudiante-->
<?php  
							   if (!empty($_POST['ElegirEstudiante'])) 
									{
									 $CursoElegido = buscarCurso($MiConexion,$_SESSION['CursoEle']);
									 $EstudElegido = buscarEstudiante($MiConexion,$_POST['Estudiante']);
                                     $_SESSION['EstudEle'] = $_POST['Estudiante'];
                                      //echo "Año: ".$CursoElegido['ANIO']." - Division: ".$CursoElegido['DIVISION']." // ";
                                    //echo "Legajo Nro ".$EstudElegido['NROLEGAJO']." - ".$EstudElegido['APELLIDO']." ".$EstudElegido['NOMBRE']." - DNI ".$EstudElegido['DNI'];
                               } ?>
                                        </div><hr>
										
                                        <div class="form-group">
										 <div class="row">
											<div class="col-lg-2"><label>Instancia</label></div>
											<div class="col-lg-6">
												<select class="form-control" name="Instancia" id="Instancia">
                                                <option value="">Seleccione una Instancia</option>
<?php 
                                                 $selectedI='';
                                                 for ($i=0 ; $i < $CantidadInstancias ; $i++) 
													{
                                                     if (!empty($_POST['Instancia']) && $_POST['Instancia'] ==  $ListadoInstancias[$i]['ID']) 
													   {
                                                        $selectedI = 'selected';
														}
													else 
														{
                                                         $selectedI='';
                                                        } 
?>
                                                    <option value="<?php echo $ListadoInstancias[$i]['ID']; ?>" <?php echo $selectedI; ?>  >
                                                        <?php echo $ListadoInstancias[$i]['DENOMINACION']; ?>
                                                    </option>
                                                <?php }  ?>
                                            </select></div>
											<div class="col-lg-2"><button type="submit" class="btn btn-primary" value="ElegirInstancia" name="ElegirInstancia"><box-icon  name="show"  size="sm" color="white" animation="tada-hover"></box-icon> Ver</button></div>
											</div><!--Cierra row Instancia-->
                              <?php   if (!empty($_POST['ElegirInstancia'])) {
                                        $_SESSION['InstanciaEle'] = $_POST['Instancia'];
                                        $CursoElegido = buscarCurso($MiConexion,$_SESSION['CursoEle']);
                                $EstudElegido = buscarEstudiante($MiConexion,$_SESSION['EstudEle']);
                                //echo "Año: ".$CursoElegido['ANIO']." - Division: ".$CursoElegido['DIVISION']." // ";
                                   // echo "Legajo Nro ".$EstudElegido['NROLEGAJO']." - ".$EstudElegido['APELLIDO']." ".$EstudElegido['NOMBRE']." - DNI ".$EstudElegido['DNI'];
                               } ?>
                                        </div><hr>
                                       <div class="row">
                                        <div class="form-group">
										  <div class="col-lg-2"><label>Fecha*</label></div>
                                           <div class="col-lg-3"> <input id="date" type="date" name="Fecha" value="<?php echo !empty($_POST['Fecha']) ? $_POST['Fecha'] : ''; ?>">
                                          </div>
										
										  <div class="col-lg-2"><label>Situación*</label></div>
                                           <div class="col-lg-5"> <select class="form-control" name="Situacion" id="Situacion">
                                                <option value="">Seleccione la Situación</option>
                                                <?php 
                                                $selectedS='';
                                                for ($i=0 ; $i < $CantidadSituaciones ; $i++) {
                                                    if (!empty($_POST['Situacion']) && $_POST['Situacion'] ==  $ListadoSituaciones[$i]['ID']) {
                                                        $selectedS = 'selected';
                                                    }else {
                                                        $selectedS='';
                                                    } 
                                                    ?>
                                                    <option value="<?php echo $ListadoSituaciones[$i]['ID']; ?>" <?php echo $selectedS; ?>  >
                                                        <?php echo $ListadoSituaciones[$i]['DENOMINACION']; ?>
                                                    </option>
                                                <?php }  ?>
                                            </select></div></div>
												<!-- <button type="submit" class="btn btn-default" value="SituacionNueva" name="SituacionNueva" formaction="NuevaSituacion.php">Nueva Situación</button>
												Acá va la acción del botón SituacionNueva-->
                                        </div><!--Cierra row Fecha-Situacion--><hr>
                                
								<div class="row" ><div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Justificación</label>
                                            <textarea class="form-control" rows="3" name="Justificacion"><?php echo !empty($_POST['Justificacion']) ? $_POST['Justificacion'] : ''; ?></textarea></div></div>
                                        </div>
                                         <div class="row" align="center">
				<div class="col-lg-12"> <div class="alert alert-dismissible alert-info"><center><b>Los campos marcados con  * son obligatorios</b></div></div>
			 </div>
                                        <div class="row">
										<div class="col-lg-3"></div>
										<div class="col-lg-4">
                                        <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('Seguro que desea guardar la asistencia?');"><box-icon  name="check-double"  size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
									   <div class="col-lg-4">
									   
                                        <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" onclick="return confirm ('Seguro que desea cancelar? - No se guardarán los datos que no haya guardado')"><box-icon  name="arrow-back"  size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button>
                                        </div></div>
					    </form>
										 </div><!--panel body --> 
                                </div><!--panel principal -->
                                <!-- /.col-lg-6 (nested) -->
                            </div><!-- columnaprincipal principal -->
                            
                        </div><!-- /.row principal -->
                      
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
