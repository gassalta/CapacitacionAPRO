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
                    <h1 class="page-header"><font color="#85C1E9"><?php echo $Listado['APELLIDO']." ".$Listado['NOMBRE']; ?></font></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                          <font color="white" size="4px">Seleccione los Espacios Curriculares</font>
                        </div>
                        <div class="panel-body">
                          
                              
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a la pagina que mandó
                                            if (!empty($_POST['Cancelar'])) {
                                                
                                                header('Location: '.$_SESSION['Envia']);
                                            }

                                            //Si confirma incluyo las funciones para guardar espacios curriculares
                                            if (!empty($_POST['Confirmar'])) {
                                               require_once 'funciones/guardarEspacioCurricular.php';
                                               //Recorro el listado de Espacios Curriculares
                                                for ($i=0; $i < $CantEspCurricular; $i++) {
                                                    $EsDeDocente = 0; 
                                                    //Reviso si es de los que tiene el docente a cargo con anterioridad
                                                    for ($j=0; $j < $CantEspCurrXDoc; $j++) { 
                                                        if ($ListadoEspCurricular[$i]['ID'] == $ListadoEspCurrXDoc[$j]['ID']) {
                                                            $EsDeDocente = 1;
                                                        }
                                                    }
                                                    //Reviso si el checkbox correspondiente está checked
                                                    if(!empty($_POST[$ListadoEspCurricular[$i]['ID']]) && $_POST[$ListadoEspCurricular[$i]['ID']] == 'SI') {
                                                        //Si está checked verifico si no es de los que ya tenía a cargo el docente
                                                        if ($EsDeDocente==0) {
                                                            //Modifico el docente a cargo del espacio curricular
                                                            if (guardarEspacCurricXDocente($MiConexion,$ListadoEspCurricular[$i]['ID'],$Listado['ID'])) {
                                                                ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong><?php echo $ListadoEspCurricular[$i]['NOMBREESPACCURRIC']; ?> guardado a cargo del docente!</strong>
                </div>
              </div>
                                                    <?php
                                                            }
                                                        }
                                                    }else {
                                                        //Si no está checked verifico si es de los que ya tenía a cargo el docente
                                                        if ($EsDeDocente == 1) {
                                                            //Pongo en 0 el código del docente a cargo del espacio curricular
                                                            if (guardarEspacCurricXDocente($MiConexion,$ListadoEspCurricular[$i]['ID'],0)) {
                                                                ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Ya no hay docente a cargo de <?php echo $ListadoEspCurricular[$i]['NOMBREESPACCURRIC']; ?></strong>
                </div>
              </div>
                                                    <?php
                                                            }
                                                        }
                                                    }
                                                }
                                                $ListadoEspCurrXDoc = ListarEspCurrXDocente($MiConexion,$Listado['ID']);
                                                $CantEspCurrXDoc = count($ListadoEspCurrXDoc);
                                            }
                                                    
                                             ?>  
                                      <div class="form-group">
                                          <div class="checkbox">
                                            <?php
                                            for ($i=0; $i < $CantEspCurricular; $i++) { 
                                                $EsDeDocente = 0;
                                                for ($j=0; $j < $CantEspCurrXDoc; $j++) { 
                                                    if ($ListadoEspCurricular[$i]['ID'] == $ListadoEspCurrXDoc[$j]['ID']) {
                                                        $EsDeDocente = 1;
                                                    }
                                                } ?>
                                                <label>
                                                    <input type="checkbox" name="<?php echo $ListadoEspCurricular[$i]['ID']; ?>" value="SI" <?php echo ($EsDeDocente == 1) ? 'checked' : ''; ?>> <?php echo $ListadoEspCurricular[$i]['NOMBREESPACCURRIC']; ?>

                                                </label><br><br>
                                     <?php  }
                                            ?>
                                            
                                          </div>
                                         <div class="row">
								<div class="col-lg-12">
								<div class="col-lg-2"></div>
								<div class="col-lg-4">
                                        <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('Seguro que desea guardar los Espacios Curriculares a cargo del Docente?');"
                                       ><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
									  
								<div class="col-lg-4">
                                        <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon name="x" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Cancelar</button><div>
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
