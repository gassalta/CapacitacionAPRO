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

require_once 'funciones/buscarEspacioCurricular.php';
$EspacCurric = array();
$EspacCurric = buscarEspacCurric($MiConexion,$_SESSION['IdEspCurrSeleccionado']);
$CantEspCurricular = count($EspacCurric);
if ($CantEspCurricular == 0) {
    $EspacCurric = buscarEspacCurricSimple($MiConexion,$_SESSION['IdEspCurrSeleccionado']);
    $CantEspCurricular = count($EspacCurric);
    if ($CantEspCurricular == 0) {
        header('Location: buscarUnEspacioCurricular.php');
    }
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
                    <h1 class="page-header">Espacio Curricular: <?php echo $EspacCurric['NOMBREESPACCURRIC']; ?></h1>
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
                                                
                                                header('Location: buscarUnEspacioCurricular.php');
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                               require_once 'funciones/guardarEspacioCurricular.php';
                                                if (!empty($_POST['curso'])) {
                                                    if ($_POST['curso'] != $EspacCurric['CURSO']) {
                                                        if (guardarEspacCurricXCurso($MiConexion,$EspacCurric['ID'],$_POST['curso'])) {
                                                            ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Se registró correctamente el curso en el que se dicta el Espacio Curricular</strong>
                </div>
              </div>
                                                    <?php
                                                    $EspacCurric = buscarEspacCurric($MiConexion,$_SESSION['IdEspCurrSeleccionado']);
                                                    $CantEspCurricular = count($EspacCurric);
                                                    if ($CantEspCurricular != 0) {
                                                        $_POST['curso'] = $EspacCurric['CURSO'];
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
                                                    <input type="radio" name="curso" id="<?php echo $Listado[$i]['ID']; ?>" value="<?php echo $Listado[$i]['ID']; ?>" <?php echo ($Listado[$i]['ID'] == $EspacCurric['CURSO']) ? 'checked' : ''; ?>>Año:  <?php echo $Listado[$i]['ANIO']." - Division: ".$Listado[$i]['DIVISION']; ?>

                                                </label>
                                                </div>
                                     <?php  }
                                            ?>
                                            
                                          
                                      
                                        <button type="submit" class="btn btn-default" value="Confirmar" name="Confirmar" onClick="return confirm ('Seguro que desea el curso en que se dicta el Espacio Curricular?');"><box-icon  name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon>Confirmar</button>
                                        <button class="btn btn-danger" type="submit" name="Cancelar" ><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar </button> </div>
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
