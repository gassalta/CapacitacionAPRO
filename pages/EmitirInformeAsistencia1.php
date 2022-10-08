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
require_once 'funciones/informeAsistencia.php';
$TotalesAsistencias = array();

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
?>
            <div class="row">
                <div class="col-lg-18">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Informe de Asistencia por Estudiante
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-15">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a administrarAsistencias
                                            if (!empty($_POST['Cancelar'])) {
                                                
                                                header('Location: administrarAsistencias.php');
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
                                    $_SESSION['CursoEleg'] = $_POST['Curso'];
                                    if (!empty($CursoElegido)) {
                                        echo $CursoElegido['ANIO']." - ".$CursoElegido['DIVISION']." Division";
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
                                if (!empty($_POST['Curso'])) {
                                    $CursoElegido = buscarCurso($MiConexion,$_SESSION['CursoEleg']);
                                $EstudElegido = buscarEstudiante($MiConexion,$_POST['Estudiante']);
                                        $_SESSION['EstudEleg'] = $_POST['Estudiante'];
                                        echo $CursoElegido['ANIO']." - ".$CursoElegido['DIVISION']." Division    //     ";
                                    echo "Legajo Nro ".$EstudElegido['NROLEGAJO']." - ".$EstudElegido['APELLIDO']." ".$EstudElegido['NOMBRE']." - DNI ".$EstudElegido['DNI'];
                               $anioActual = date("Y");
                               $TotalesAsistencias = contarTotalesAsistencia($MiConexion,$EstudElegido['ID'],$anioActual);
                                } else {
                                    ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>Debe seleccionar un curso primero</strong>
                </div>
              </div>
             <?php
                                }
                               } ?>
                                        </div>
                                        <hr style="color: #888ffc"/>
                                        <?php       if (!empty($TotalesAsistencias)) { ?>
                                        <font size="5" face="Verdana, Arial, Helvetica, sans-serif">Estudiante: <?php echo $EstudElegido['APELLIDO']." ".$EstudElegido['NOMBRE']."    -     DNI: ".$EstudElegido['DNI']."   -         Legajo: ".$EstudElegido['NROLEGAJO']; ?></font><br>
                                        <font size="5" face="Verdana, Arial, Helvetica, sans-serif">Curso: <?php echo $CursoElegido['ANIO']." - ".$CursoElegido['DIVISION']." Division        - -             "; ?><?php echo ($CursoElegido['ANIO']=='1ro'||$CursoElegido['ANIO']=='2do'||$CursoElegido['ANIO']=='3ro') ? 'Ciclo Basico' : 'Ciclo Orientado'; ?></font>
                                    <center><font size="7" face="Verdana, Arial, Helvetica, sans-serif">Asistencia</font></center>    
                                        <font size="4" face="Verdana, Arial, Helvetica, sans-serif">Total: <?php echo !empty($TotalesAsistencias) ? $TotalesAsistencias['TOTAL'] : ''; ?></font><br>
                                        <font size="4" face="Verdana, Arial, Helvetica, sans-serif">Presente: <?php echo !empty($TotalesAsistencias) ? $TotalesAsistencias['PRESENTES'] : ''; ?></font><br>
                                        <center><font size="5" face="Verdana, Arial, Helvetica, sans-serif">Inasistencias</font></center><br>
                                        <font size="4" face="Verdana, Arial, Helvetica, sans-serif">Justificadas: <?php echo !empty($TotalesAsistencias) ? $TotalesAsistencias['JUSTIFICADAS'].'     -     ' : '     -     '; ?> No justificadas: <?php echo !empty($TotalesAsistencias) ? $TotalesAsistencias['INJUSTIFICADAS'] : ''; ?></font><br>
                                        <font size="4" face="Verdana, Arial, Helvetica, sans-serif">Total Inasistencias: <?php echo !empty($TotalesAsistencias) ? $TotalesAsistencias['INASISTENCIAS'] : ''; ?></font><br>
                                        <?php  }
                                               ?> 
                                        <hr>
                                        <br>
                                        <button type="submit" class="btn btn-default" value="Emitir" name="Emitir" style="background-color: #7b16b6; color: white;" formaction="funciones/emitirPDFInformeAsistencia.php" 
                                            onClick="return confirm ('Seguro que desea emitir el informe?');"
                                       >Emitir</button>
                                       <?php if ($_SESSION['Categoria'] != 'Coordinador/a') {
                                            ?>
                                        <button type="submit" class="btn btn-default" value="Cancelar" name="Cancelar" style="background-color: #fb0000; color: white;" onclick="return confirm ('Seguro que desea cancelar?')">Cancelar</button>
                                        <br>
                                    <?php } ?>
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
