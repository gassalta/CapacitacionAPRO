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

require_once 'funciones/listarInstancias.php';
$ListadoInstancias = array();
$ListadoInstancias=Listar_Instancias($MiConexion);
$CantInstancias = count($ListadoInstancias);

//Declaro variables
$mensaje='';

$EC=$_REQUEST['Cx'];
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
                    <h1 class="page-header">Instancias</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
             
    <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Listado Instancias (<?php echo $CantInstancias; ?>)</h3>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Instancia</th>
                
                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de las instacias
                            for ($i=0; $i < $CantInstancias; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $ListadoInstancias[$i]['ID']; ?></td>
                                    <td><?php echo $ListadoInstancias[$i]['DENOMINACION']; ?></td>
                                </tr> 
                         <?php   }
                        ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        
      </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos Nueva Instancia
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a evaluacionNueva
                                            if (!empty($_POST['Cancelar'])) {
                                                //$accion = 0;
                                                header('Location: evaluacionNueva.php?Cx='.$EC);
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                             
                                                $mensaje = '';
                                                if (empty($_POST['Denominacion'])) {
                                                    $mensaje = 'Debe completar la denominación de la instancia';
                                                } 
                                                if($mensaje==''){
                                                //Si está todo bien creo la nueva instancia en base de datos, sino, muestro mensaje
                                                    require_once 'funciones/guardarInstancia.php';
                                                        if (instanciaNueva($MiConexion,$_POST['Denominacion'])) {?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Nueva instancia guardada!</strong>
                </div>
              </div>
                                                    <?php   
                                                    $ListadoInstancias=Listar_Instancias($MiConexion);
                                                    $CantInstancias = count($ListadoInstancias);
                                                     }
                                                        
                                                    } else {
                                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong><?php echo $mensaje; ?></strong>
                </div>
              </div>
             <?php 
                                                }

 
                                       } ?>
                                        
                                        <div class="form-group">
                                            <label>Instancia</label>
                                            <input class="form-control" name="Denominacion" value="<?php echo !empty($_POST['Denominacion']) ? $_POST['Denominacion'] : ''; ?>">
                                        </div>
                                        
                                        <div class="row">  
                                        <div class="col-lg-2"></div>
                                            <div class="col-lg-4" align="center">
                                        <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('Seguro que desea guardar la nueva instancia?');"
                                       ><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
                                       <div class="col-lg-4" align="center">
                                        <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" onclick="return confirm ('Seguro que desea cancelar? - No se guardarán los datos que no haya guardado')"><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>   Retornar</button></div></div>
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
