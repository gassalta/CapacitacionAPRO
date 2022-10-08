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

$EsCu=$_REQUEST['Cx'];

require_once 'funciones/buscarEvaluacion.php';
$eval=array();
$eval = buscarEvalXFecha($MiConexion,$_SESSION['FechaEvalElegida'],$_SESSION['EspaCurriEleg']);
$CantidadEval = count($eval);
if ($CantidadEval == 0) {
  if($_SESSION['EnviaEval'] == 'evaluacionNueva.php'){
        header('Location: '.$_SESSION['EnviaEval'].'?Cx='.$EsCu);
    } else {
        $eval = buscarEvaluacion($MiConexion,$_SESSION['NroEval']);
        $CantidadEval = count($eval);
        if ($CantidadEval == 0) {
            header('Location: '.$_SESSION['EnviaEval'].'?Tx=M&Cx='.$eval['ID'].'&Ec='.$EsCu);
        }
    }
}

require_once 'funciones/listarAprendizajesXEspCurr.php';
$ListadoAprendizajesXEC = array();
$ListadoAprendizajesXEC = Listar_AprendizajesXECPEval($MiConexion,$eval['IDESPACURRI'],$eval['ID']);
$CantAXEC = count($ListadoAprendizajesXEC);

$ListadoAprendizajesXECEvaluados = array();
$ListadoAprendizajesXECEvaluados = Listar_AprendizajesXECYaEvaluados($MiConexion,$eval['IDESPACURRI'],$eval['ID']);
$CantAXECEvaluados = count($ListadoAprendizajesXECEvaluados);

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
                    <h1 class="page-header"><font color="#85C1E9"><?php echo $EsCu; ?></font></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                          <font color="white" size="4px">Seleccione los Aprendizajes a evaluar el día <?php echo $_SESSION['FechaEvalElegida']; ?></font>
                        </div>
                        <div class="panel-body">
                          
                              
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a la pagina que mandó
                                            if (!empty($_POST['Cancelar'])) {
                                                if($_SESSION['EnviaEval'] == 'evaluacionNueva.php'){
                                                header('Location: '.$_SESSION['EnviaEval'].'?Cx='.$EsCu);
                                            } else {
                                                header('Location: '.$_SESSION['EnviaEval'].'?Tx=M&Cx='.$eval['ID'].'&Ec='.$EsCu);
                                            }
                                            }

                                            //Si confirma incluyo las funciones para guardar espacios curriculares
                                            if (!empty($_POST['Confirmar'])) {
                                               require_once 'funciones/guardarEvaluacion.php';
                                               //Recorro el listado de aprendizajes
                                                for ($i=0; $i < $CantAXEC; $i++) {
                                                    $EsDeEval = 0; 
                                                    //Reviso si es de los que tiene asignados la evaluacion
                                                    if ($ListadoAprendizajesXEC[$i]['EVALUACION'] == $eval['ID']) {
                                                        $EsDeEval = 1;
                                                    }
                                                    //Reviso si el checkbox correspondiente está checked
                                                    if(!empty($_POST[$ListadoAprendizajesXEC[$i]['ID']]) && $_POST[$ListadoAprendizajesXEC[$i]['ID']] == 'SI') {
                                                        //Si está checked verifico si no es de los que ya tenía asignados la evaluacion
                                                        if ($EsDeEval==0) {
                                                            //Lo asigno a la evaluación
                                                            if (guardarAprendizajesXEvaluacion($MiConexion,$ListadoAprendizajesXEC[$i]['ID'],$eval['ID'])) {
                                                                ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>El aprendizaje fue asignado a la evaluación!</strong>
                </div>
              </div>
                                                    <?php
                                                            }
                                                        }
                                                    }else {
                                                        //Si no está checked verifico si es de los que ya estaban asignados a la evaluacion
                                                        if ($EsDeEval == 1) {
                                                            //Elimino la asignación del aprendizaje a la evaluacion
                                                            $mensaje = eliminarElAprendizajeXEvaluacion($MiConexion,$ListadoAprendizajesXEC[$i]['ID'],$eval['ID'])
                                                                ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong><?php echo $mensaje; ?></strong>
                </div>
              </div>
                                                    <?php
                                                            }
                                                        }
                                                    }
                                                    $ListadoAprendizajesXEC = Listar_AprendizajesXECPEval($MiConexion,$eval['IDESPACURRI'],$eval['ID']);
                                                $CantAXEC = count($ListadoAprendizajesXEC);
                                                }
                                            
                                                    
                                             ?>  
                                      <div class="form-group">
                                          <div class="checkbox">
                                            <?php 
                                            for ($i=0; $i < $CantAXEC; $i++) { 
                                                $EsDeEval = 0;
                                                    if ($ListadoAprendizajesXEC[$i]['EVALUACION'] == $eval['ID']) {
                                                        $EsDeEval = 1;
                                                    } 
                                                $Evaluado = 0;
                                                for ($j=0; $j < $CantAXECEvaluados; $j++) { 
                                                    if ($ListadoAprendizajesXEC[$i]['ID'] == $ListadoAprendizajesXECEvaluados[$j]['ID']) {
                                                        $Evaluado = 1;
                                                    }
                                                }
                                                $txtEvaluado = "";
                                                if ($Evaluado == 1) {
                                                    $txtEvaluado = " - APRENDIZAJE EVALUADO EN OTRO EXAMEN";
                                                    ?>
                                                    <font color="red">
                                                <?php } ?>
                                                <label>
                                                    <input type="checkbox" name="<?php echo $ListadoAprendizajesXEC[$i]['ID']; ?>" value="SI" <?php echo ($EsDeEval == 1) ? 'checked' : ''; ?>> <?php echo $ListadoAprendizajesXEC[$i]['DENOMINACION'].$txtEvaluado; ?>

                                                </label><br><br>
                                     <?php      if ($Evaluado == 1) { ?>
                                                    </font>
                                                    <?php
                                                }

                                                }
                                            
                                            ?>
                                            
                                          </div>
                                         <div class="row">
								<div class="col-lg-12">
								<div class="col-lg-2"></div>
								<div class="col-lg-4">
                                        <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('Seguro que desea guardar los Aprendizajes señalados en la evaluación?');"
                                       ><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
									  
								<div class="col-lg-4">
                                        
                                            <button class="btn btn-danger" type="submit" name="Cancelar"><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar </button> </div>
                                        <br>  
</div></div>										
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                            
                            <!-- /.row (nested) -->
                        
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
