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

require_once 'funciones/buscarEspacioCurricular.php';
$EspCurrElegido = array();

require_once 'funciones/listarAprendizajesXEspCurr.php';
$ListadoAprendizajes = array();

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
                    <h1 class="page-header">Aprendizajes</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
    <?php
    //Listo los espacios curriculares de acuerdo al docente
    $ListadoEspaciosCurriculares=array();
    if ($_SESSION['Categoria']=='Coordinador/a') {
        require_once 'funciones/listarEspaciosCurriculares.php';
        $ListadoEspaciosCurriculares = Listar_EspCurr($MiConexion);
    } else {
        require_once 'funciones/listarEspaciosCurricularesXDocente.php';
        $ListadoEspaciosCurriculares = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
    }
        
$CantidadEC = count($ListadoEspaciosCurriculares);
?>
            <div class="row">
                <div class="col-lg-18">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Aprendizajes por Espacio Curricular
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-15">
                                    <form role="form" method="post">
                                    <div class="form-group">
                                            <label>Espacio Curricular</label>
                                            <select class="form-control" name="EspaCurri" id="EspaCurri">
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantidadEC ; $i++) {
                                                    if (!empty($_POST['EspaCurri']) && $_POST['EspaCurri'] ==  $ListadoEspaciosCurriculares[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoEspaciosCurriculares[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" class="btn btn-default" value="Ver" name="Ver" style="background-color: #337ab7; color: white;"
                                       ><box-icon  name="show" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>Ver</button>
                           <?php   if (!empty($_POST['Ver'])) {
                                    $EspCurrElegido = buscarEspacCurric($MiConexion,$_POST['EspaCurri']);
                                    $_SESSION['EspCurrEleg'] = $_POST['EspaCurri'];
                                    if (!empty($EspCurrElegido)) {
                                        echo $EspCurrElegido['NOMBREESPACCURRIC'];
                                    }
                                   
                                    $ListadoAprendizajes = Listar_AprendizajesXEC($MiConexion,$_POST['EspaCurri']);
                                    $CantidadAprendizajes = count($ListadoAprendizajes);
                                    if ($CantidadAprendizajes == 0) {
                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>El espacio curricular seleccionado no tiene aprendizajes registrados</strong>
                </div>
              </div>
             <?php
                                    } 
                           } ?>
                                        </div>    
                                        
                                        </div>
                                        <hr style="color: #888ffc"/>
                                        <?php       if (!empty($ListadoAprendizajes)) { ?>
                                        <font size="5" face="Verdana, Arial, Helvetica, sans-serif">Espacio Curricular: <?php echo $EspCurrElegido['NOMBREESPACCURRIC']."    -     Año: ".$EspCurrElegido['ANIO']; ?></font><br>
                                        
                                    <center><font size="7" face="Verdana, Arial, Helvetica, sans-serif">Aprendizajes</font></center>    
                                        <div class="table-responsive">
              <table class="table-sm table-striped  bg-info">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Contenido</th>
                    <th>Aprendizaje</th>
                    
                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de los aprendizajes
                            for ($i=0; $i < $CantidadAprendizajes; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $ListadoAprendizajes[$i]['ID'], "- "; ?></td>
                                    <td><?php echo $ListadoAprendizajes[$i]['CONTENIDO'], "- "; ?></td>
                                    <td><?php echo $ListadoAprendizajes[$i]['DENOMINACION']; ?></td>
                                </tr> 
                         <?php   }

                        ?>
                        
                        
                     
                 
                </tbody>
              </table> 
            </div>
                                        <?php  }
                                               ?> 
                                        <hr>
                                        <br>
                                        <button type="submit" class="btn btn-default" value="Emitir" name="Emitir" style="background-color: #337ab7; color: white;" formaction="funciones/emitirPDFListadoAprendizajesXEspCurr.php"
                                            onClick="return confirm ('Seguro que desea emitir el informe?');"
                                       ><box-icon  name="downvote" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>Emitir</button>
                                        <button class="btn btn-primary" type="submit" name="Cancelar" formaction="index.php" onclick="return confirm ('Seguro que desea cancelar?')"><box-icon  name="task-x" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Cancelar
                                                </button>
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
