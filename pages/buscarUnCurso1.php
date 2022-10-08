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
                    <h1 class="page-header">Consulta de Cursos</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos Curso
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a administrarEspaciosCurriculares
                                            if (!empty($_POST['Cancelar'])) {
                                                header('Location: administrarCursos.php');
                                            }

                                           if (!empty($_POST['EstudiantesXCurso'])) {
                                               if (empty($_POST['AnioCurso'])) { ?>
                                                    <div class="alert alert-dismissible alert-danger">
                  <strong>Primero debe buscar un Curso</strong>
                </div>
                                             <?php   } else { 
                                                    $_SESSION['IdCursoSeleccionado'] = $_POST['Id'];
                                                    $_SESSION['AnioCursoSeleccionado'] = $_POST['AnioCurso'];
                                                    $_SESSION['DivisionCursoSeleccionado'] = $_POST['DivisionCurso'];
                                                    header('Location: listadoEstudiantesXCurso.php');
                                                } 
                                            } 
                                            if (!empty($_POST['EspCurrXCurso'])) {
                                               if (empty($_POST['AnioCurso'])) { ?>
                                                    <div class="alert alert-dismissible alert-danger">
                  <strong>Primero debe buscar un Curso</strong>
                </div>
                                             <?php   } else { 
                                                if ($_POST['AnioCurso'] != '1ro' && $_POST['AnioCurso'] != '2do' && $_POST['AnioCurso'] != '3ro' && $_POST['AnioCurso'] != '4to' && $_POST['AnioCurso'] != '5to' && $_POST['AnioCurso'] != '6to' && $_POST['AnioCurso'] != '7mo') {
                                                     ?>
                                                    <div class="alert alert-dismissible alert-danger">
                  <strong>El curso elegido no posee Espacios Curriculares</strong>
                </div>
                                             <?php
                                                } else {
                                                    $_SESSION['IdCursoSeleccionado'] = $_POST['Id'];
                                                    $_SESSION['AnioCursoSeleccionado'] = $_POST['AnioCurso'];
                                                    header('Location: listadoEspCurrXCurso.php');
                                                }
                                                } 
                                            } 

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Buscar'])) {
                                                if (empty($_POST['Id'])) {
                                                    ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>Debe ingresar un número identificador de Curso</strong>
                </div>
              </div>
                                                        
             <?php  
                                                } else {
                                                    $CursoEncontrado = array();
                                                    $CursoEncontrado = buscarCurso($MiConexion,$_POST['Id']);
                                                    $Cont = 0;
                                                    $Cont = count($CursoEncontrado);
                                                    if ($Cont != 0) {
                                                        $_POST['AnioCurso'] = $CursoEncontrado['ANIO'];
                                                        $_POST['DivisionCurso'] = $CursoEncontrado['DIVISION'];
                                                    } else {
                                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>Número identificador de Espacio Curricular no válido</strong>
                </div>
              </div>
             <?php
                                                        $_POST['AnioCurso'] = '';
                                                        $_POST['DivisionCurso'] = '';
                                                    }
                                       }
                                       } ?>

                                        <label >Nro Curso</label>
                                            <input class="form-control" name="Id" value="<?php echo !empty($_POST['Id']) ? $_POST['Id'] : ''; ?>">
                                            <button class="btn btn-default" type="submit" value="Buscar" name="Buscar">Buscar
                                                </button>
                                        
                                        <div class="form-group">
                                            <label>Año</label>
                                            <input class="form-control" name="AnioCurso" value="<?php echo !empty($_POST['AnioCurso']) ? $_POST['AnioCurso'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Division</label>
                                            <input class="form-control" name="DivisionCurso" value="<?php echo !empty($_POST['DivisionCurso']) ? $_POST['DivisionCurso'] : ''; ?>" readonly>
                                        </div>
                                        <button type="submit" class="btn btn-default" value="Cancelar" name="Cancelar" style="background-color: #fb0000; color: white;" >Cancelar</button>
                                       <center>
                                            <div>
                                                <button type="submit" class="btn btn-default" value="EstudiantesXCurso" name="EstudiantesXCurso" style="background-color: #888ffc">Listado de Estudiantes por Curso</button>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-default" value="EspCurrXCurso" name="EspCurrXCurso" style="background-color: #888ffc">Listado de Espacios Curriculares por Curso</button>
                                            </div>
                                        </center>
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
