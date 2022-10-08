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
                    <h1 class="page-header">Cursos</h1>
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
                                                        $_SESSION['IdCursoElegido'] = $_POST['Id'];
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
                                            }
                                            //Si cancela vuelvo a administrarCursos
                                            if (!empty($_POST['Cancelar'])) {
                                                header('Location: administrarCursos.php');
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                                $mensaje = '';
                                                if (empty($_POST['AnioCurso'])) {
                                                    $mensaje = 'Debe completar el año correspondiente al curso';
                                                } 
                                                require_once 'funciones/listaCursos.php';
                                                $Listado=array();
                                                $Listado = ListarCursos($MiConexion);
                                                $CantidadCursos = count($Listado);
                                                $Existe = 0;
                                                for ($i=0; $i < $CantidadCursos; $i++) { 
                                                    if ($_POST['AnioCurso'] == $Listado[$i]['ANIO'] && $_POST['DivisionCurso'] == $Listado[$i]['DIVISION'] && $_SESSION['IdCursoElegido'] != $Listado[$i]['ID']) {
                                                        $Existe = 1;
                                                    }
                                                }
                                                if ($Existe==1) {
                                                    $mensaje = "El curso ingresado ya existe";
                                                } 
                                                if($mensaje==''){
                                                //Si está todo bien modifico el espacio curricular en base de datos, sino, muestro mensaje
                                                    require_once 'funciones/guardarCurso.php';
                                                        if (modificarCurso($MiConexion,$_SESSION['IdCursoElegido'],$_POST['AnioCurso'],$_POST['DivisionCurso'])) {
                                                                $_POST['Id'] = $_SESSION['IdCursoElegido'];
                                                            ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Curso número <?php echo $_SESSION['IdCursoElegido']; ?> modificado correctamente!</strong>
                </div>
              </div>
                                                    <?php    }
                                                        
                                                    } else {
                                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong><?php echo $mensaje; ?></strong>
                </div>
              </div>
             <?php
                                                } 

 
                                       } ?>

                                        <label >Nro Curso</label>
                                            <input class="form-control" name="Id" value="<?php echo !empty($_POST['Id']) ? $_POST['Id'] : ''; ?>">
                                            <button class="btn btn-default" type="submit" value="Buscar" name="Buscar">Buscar
                                                </button>
                                        
                                        <div class="form-group">
                                            <label>Año*</label>
                                            <input class="form-control" name="AnioCurso" value="<?php echo !empty($_POST['AnioCurso']) ? $_POST['AnioCurso'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Division</label>
                                            <input class="form-control" name="DivisionCurso" value="<?php echo !empty($_POST['DivisionCurso']) ? $_POST['DivisionCurso'] : ''; ?>">
                                        </div>
                                        
                                        <label>* Campos Obligatorios</label>
                                        <button type="submit" class="btn btn-default" value="Confirmar" name="Confirmar" style="background-color: #7b16b6; color: white;"
                                            onClick="return confirm ('Seguro que desea guardar los cambios del Curso?');"
                                       >Confirmar</button>
                                        <button type="submit" class="btn btn-default" value="Cancelar" name="Cancelar" style="background-color: #fb0000; color: white;" >Cancelar</button>
                                       
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
