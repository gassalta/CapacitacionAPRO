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

//Listo las áreas
require_once 'funciones/listarAreas.php';
$ListadoAreas = Listar_Areas($MiConexion);
$CantAreas = count($ListadoAreas);

//Declaro variables
$mensaje='';
require_once 'funciones/buscarEspacioCurricular.php';
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
                    <h1 class="page-header">Espacios Curriculares</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos Espacio Curricular
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
                  <strong>Debe ingresar un número identificador de Espacio Curricular</strong>
                </div>
              </div>
                                                        
             <?php  
                                                } else {
                                                    $EspCurrEncontrado = array();
                                                    $EspCurrEncontrado = buscarEspacCurric($MiConexion,$_POST['Id']);
                                                    $Cont = 0;
                                                    $Cont = count($EspCurrEncontrado);
                                                    if ($Cont != 0) {
                                                        $_POST['NombreEspCurr'] = $EspCurrEncontrado['NOMBREESPACCURRIC'];
                                                        $_POST['Area'] = $EspCurrEncontrado['AREA'];
                                                    } else {
                                                        $EspCurrEncontrado = buscarEspacCurricSimple($MiConexion,$_POST['Id']);
                                                    $Cont = 0;
                                                    $Cont = count($EspCurrEncontrado);
                                                    if ($Cont != 0) {
                                                        $_POST['NombreEspCurr'] = $EspCurrEncontrado['NOMBREESPACCURRIC'];
                                                        $_POST['Area'] = $EspCurrEncontrado['AREA'];
                                                    } else {
                                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>Número identificador de Espacio Curricular no válido</strong>
                </div>
              </div>
             <?php
                                                        $_POST['NombreEspCurr'] = '';
                                                        $_POST['Area'] = '';
                                                    }
                                                } 

 
                                       }

                                            }
                                            //Si cancela vuelvo a administrarEspaciosCurriculares
                                            if (!empty($_POST['Cancelar'])) {
                                                //$accion = 0;
                                                header('Location: administrarEspaciosCurriculares.php');
                                            }

                                            
                                            if (!empty($_POST['Confirmar'])) {
                                             $id= $_POST['Id'];
                                                $mensaje = eliminarElEspCurr($MiConexion,$id);
                                                    ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong><?php echo $mensaje; ?></strong>
                </div>
              </div>
             <?php
                                            $_POST['NombreEspCurr'] = '';
                                            $_POST['Area'] = '';
                                            $_POST['Id'] = '';
 
                                       } ?>

                                        <label >Nro Espacio Curricular a Eliminar</label>
                                            <input class="form-control" name="Id" value="<?php echo !empty($_POST['Id']) ? $_POST['Id'] : ''; ?>">
                                            <button class="btn btn-default" type="submit" value="Buscar" name="Buscar">Buscar
                                                </button>
                                        
                                        <div class="form-group">
                                            <label>Nombre Espacio Curricular</label>
                                            <input class="form-control" name="NombreEspCurr" value="<?php echo !empty($_POST['NombreEspCurr']) ? $_POST['NombreEspCurr'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Área</label>
                                            <select class="form-control" name="Area" id="Area" readonly>
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantAreas ; $i++) {
                                                    if (!empty($_POST['Area']) && $_POST['Area'] ==  $ListadoAreas[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoAreas[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoAreas[$i]['DENOMINACION']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            
                                        <button type="submit" class="btn btn-default" value="Confirmar" name="Confirmar" style="background-color: #7b16b6; color: white;"
                                            onClick="return confirm ('Seguro que desea eliminar el Espacio Curricular? - Si confirma no podrá recuperar los datos');"
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
