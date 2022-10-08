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
                <div class="col-lg-12">
                    <h1 class="page-header">Asistencias</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
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
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Registrar Asistencia Diaria
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a administrarAsistencias
                                            if (!empty($_POST['Cancelar'])) {
                                                
                                                header('Location: administrarAsistencias.php');
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                                $mensaje = '';
                                                if(empty($_POST['Fecha'])|| empty($_POST['Situacion'])){
                                                    ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>Debe completar los campos obligatorios</strong>
                </div>
              </div>
             <?php
                                                } else {
                                                    //Si está todo bien, veo que no esté el detalle guardado anteriormente
                                                    $fecha =strtotime($_POST['Fecha']);
                                                    $anio = date("Y",$fecha);
                                                    require_once 'funciones/buscarAsistencia.php';
                                                    $Asist = array();
                                                    $Asist = buscarAsistencia($MiConexion,$_SESSION['EstudEle'],$anio,$_SESSION['InstanciaEle']);
                                                    if (!empty($Asist)) {
                                                        $detalleExiste = array();
                                                    $detalleExiste = buscarDetalleAsistencia ($MiConexion,$Asist['ID'],$_POST['Fecha']);
                                                    if (!empty($detalleExiste)) {
                                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>La asistencia ya está registrada con anterioridad</strong>
                </div>
              </div>
             <?php
                                                    } else {
                                                        //Si está todo bien, creo la asistencia y/o el detalle, según corresponda
                                                    require_once 'funciones/guardarAsistencia.php';
                                                    
                                                    if (guardarAsistenciaYDetalle($MiConexion,$_SESSION['EstudEle'],$anio,$_SESSION['InstanciaEle'],$_POST['Fecha'],$_POST['Situacion'],$_POST['Justificacion'])) {
                                                        ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Asistencia guardada!</strong>
                </div>
              </div>
                                                    <?php
                                                    }
                                                    }
                                                    }
                                                }
                                            } ?>

                                    <div class="form-group">
                                            <label>Curso</label>
                                            <select class="form-control" name="Curso" id="Curso">
                                                <option value=""></option>
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
                                            </select>
                                            <button type="submit" class="btn btn-default" value="ElegirCurso" name="ElegirCurso"> Elegir</button>
                           <?php   if (!empty($_POST['ElegirCurso'])) {
                                    $CursoElegido = buscarCurso($MiConexion,$_POST['Curso']);
                                    $_SESSION['CursoEle'] = $_POST['Curso'];
                                    if (!empty($CursoElegido)) {
                                        echo "Año: ".$CursoElegido['ANIO']." - Division: ".$CursoElegido['DIVISION'];
                                    }
                                    require_once 'funciones/listarEstudiantes.php';
                                    $ListadoEstudiantes=array();
                                    $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$_POST['Curso']);
                                    $CantidadEstudiantes = count($ListadoEstudiantes);
                                    if ($CantidadEstudiantes == 0) {
                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>El curso seleccionado no tiene estudiantes asignados</strong>
                </div>
              </div>
             <?php
                                    }
                           } ?>
                                        </div>    
                                        <div class="form-group">
                                            <label>Estudiante</label>
                                            <select class="form-control" name="Estudiante" id="Estudiante">
                                                <option value=""></option>
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
                                            </select>
                                            <button type="submit" class="btn btn-default" value="ElegirEstudiante" name="ElegirEstudiante"> Elegir</button>
                               <?php   if (!empty($_POST['ElegirEstudiante'])) {
                                $CursoElegido = buscarCurso($MiConexion,$_SESSION['CursoEle']);
                                $EstudElegido = buscarEstudiante($MiConexion,$_POST['Estudiante']);
                                        $_SESSION['EstudEle'] = $_POST['Estudiante'];
                                        echo "Año: ".$CursoElegido['ANIO']." - Division: ".$CursoElegido['DIVISION']." // ";
                                    echo "Legajo Nro ".$EstudElegido['NROLEGAJO']." - ".$EstudElegido['APELLIDO']." ".$EstudElegido['NOMBRE']." - DNI ".$EstudElegido['DNI'];
                               } ?>
                                        </div>
                                        <div class="form-group">
                                            <label>Instancia</label>
                                            <select class="form-control" name="Instancia" id="Instancia">
                                                <option value=""></option>
                                                <?php 
                                                $selectedI='';
                                                for ($i=0 ; $i < $CantidadInstancias ; $i++) {
                                                    if (!empty($_POST['Instancia']) && $_POST['Instancia'] ==  $ListadoInstancias[$i]['ID']) {
                                                        $selectedI = 'selected';
                                                    }else {
                                                        $selectedI='';
                                                    } 
                                                    ?>
                                                    <option value="<?php echo $ListadoInstancias[$i]['ID']; ?>" <?php echo $selectedI; ?>  >
                                                        <?php echo $ListadoInstancias[$i]['DENOMINACION']; ?>
                                                    </option>
                                                <?php }  ?>
                                            </select>
                                            <button type="submit" class="btn btn-default" value="ElegirInstancia" name="ElegirInstancia"> Elegir</button>
                              <?php   if (!empty($_POST['ElegirInstancia'])) {
                                        $_SESSION['InstanciaEle'] = $_POST['Instancia'];
                                        $CursoElegido = buscarCurso($MiConexion,$_SESSION['CursoEle']);
                                $EstudElegido = buscarEstudiante($MiConexion,$_SESSION['EstudEle']);
                                echo "Año: ".$CursoElegido['ANIO']." - Division: ".$CursoElegido['DIVISION']." // ";
                                    echo "Legajo Nro ".$EstudElegido['NROLEGAJO']." - ".$EstudElegido['APELLIDO']." ".$EstudElegido['NOMBRE']." - DNI ".$EstudElegido['DNI'];
                               } ?>
                                        </div>

                                        <div class="form-group">
                                            <label>Fecha*</label>
                                            <input id="date" type="date" name="Fecha" value="<?php echo !empty($_POST['Fecha']) ? $_POST['Fecha'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Situación*</label>
                                            <select class="form-control" name="Situacion" id="Situacion">
                                                <option value=""></option>
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
                                            </select>
                                            <button type="submit" class="btn btn-default" value="SituacionNueva" name="SituacionNueva" formaction="NuevaSituacion.php">Nueva Situación</button>
                          <!-- Acá va la acción del botón SituacionNueva-->
                                        </div>

                                        <div class="form-group">
                                            <label>Justificación</label>
                                            <textarea class="form-control" rows="3" name="Justificacion"><?php echo !empty($_POST['Justificacion']) ? $_POST['Justificacion'] : ''; ?></textarea>
                                        </div>
                                        
                                        <label>* Campos Obligatorios</label>
                                        <button type="submit" class="btn btn-default" value="Confirmar" name="Confirmar" style="background-color: #7b16b6; color: white;"
                                            onClick="return confirm ('Seguro que desea guardar la asistencia?');"
                                       >Confirmar</button>
                                        <button type="submit" class="btn btn-default" value="Cancelar" name="Cancelar" style="background-color: #fb0000; color: white;" onclick="return confirm ('Seguro que desea cancelar? - No se guardarán los datos que no haya guardado')">Cancelar</button>
                                        <br>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

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
