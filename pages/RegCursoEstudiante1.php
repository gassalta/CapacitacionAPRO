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
$Listado = ListarCursos($MiConexion);
$CantidadCursos = count($Listado);

require_once 'funciones/buscarEstudiante.php';
$Estudiante = array();
$Estudiante = buscarEstudiante($MiConexion,$_SESSION['IdEstudianteSeleccionado']);
$CantEstudiante = count($Estudiante);
if ($CantEstudiante == 0) {
        header('Location: buscarUnEstudiante.php');
}
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
                    <h1 class="page-header">Estudiante: <?php echo $Estudiante['APELLIDO']; ?> <?php echo $Estudiante['NOMBRE']; ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Cursos
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a la pagina que mandó
                                            if (!empty($_POST['Cancelar'])) {
                                                
                                                header('Location: buscarUnEstudiante.php');
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                               require_once 'funciones/guardarEstudiante.php';
                                                if (!empty($_POST['curso'])) {
                                                    if ($_POST['curso'] != $Estudiante['CURSO']) {
                                                        if (guardarCursoActualEstudiante($MiConexion,$Estudiante['ID'],$_POST['curso'])) {
                                                            ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Se registró correctamente el curso actual del estudiante</strong>
                </div>
              </div>
                                                    <?php
                                                    $Estudiante = buscarEstudiante($MiConexion,$_SESSION['IdEstudianteSeleccionado']);
                                                    $CantEstudiante = count($Estudiante);
                                                    if ($CantEstudiante != 0) {
                                                        $_POST['curso'] = $Estudiante['CURSO'];
                                                    }
                                                    }
                                                    }
                                                    else {
                                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>El curso seleccionado es el que ya se encuentra registrado</strong>
                </div>
              </div>
             <?php
                                                    }
                                                } else {
                                                
                                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>Debe seleccionar un curso</strong>
                </div>
              </div>
             <?php
                                                }
                                            }

 
                                       ?>
                                      <div class="form-group">
                                          
                                            <?php

                                            for ($i=0; $i < $CantidadCursos; $i++) { 
                                                 ?>
                                                 <div class="radio">
                                                <label>
                                                    <input type="radio" name="curso" id="<?php echo $Listado[$i]['ID']; ?>" value="<?php echo $Listado[$i]['ID']; ?>" <?php echo ($Listado[$i]['ID'] == $Estudiante['CURSO']) ? 'checked' : ''; ?>>Año:  <?php echo $Listado[$i]['ANIO']." - Division: ".$Listado[$i]['DIVISION']; ?>

                                                </label>
                                                </div>
                                     <?php  }
                                            ?>
                                            
                                          
                                      
                                        <button type="submit" class="btn btn-default" value="Confirmar" name="Confirmar" style="background-color: #7b16b6; color: white;"
                                            onClick="return confirm ('Seguro que desea guardar el curso actual del estudiante?');"
                                       >Confirmar</button>
                                        <button type="submit" class="btn btn-default" value="Cancelar" name="Cancelar" style="background-color: #fb0000; color: white;" >Cancelar</button>
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
