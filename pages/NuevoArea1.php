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
                    <h1 class="page-header">Áreas</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
             
    <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Listado Áreas (<?php echo $CantAreas; ?>)</h3>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Área</th>
                
                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de los docentes
                            for ($i=0; $i < $CantAreas; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $ListadoAreas[$i]['ID']; ?></td>
                                    <td><?php echo $ListadoAreas[$i]['DENOMINACION']; ?></td>
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
                            Datos Nuevo Área
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a NuevoEspacioCurricular
                                            if (!empty($_POST['Cancelar'])) {
                                                //$accion = 0;
                                                header('Location: NuevoEspacioCurricular.php');
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                             
                                                $mensaje = '';
                                                if (empty($_POST['Denominacion'])) {
                                                    $mensaje = 'Debe completar el nombre del área';
                                                } 
                                                if($mensaje==''){
                                                //Si está todo bien creo area nuevo en base de datos, sino, muestro mensaje
                                                    require_once 'funciones/guardarArea.php';
                                                        if (areaNuevo($MiConexion,$_POST['Denominacion'])) {?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Nuevo Área guardado!</strong>
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
                                        
                                        <div class="form-group">
                                            <label>Nombre Área*</label>
                                            <input class="form-control" name="Denominacion" value="<?php echo !empty($_POST['Denominacion']) ? $_POST['Denominacion'] : ''; ?>">
                                        </div>
                                        
                                        <label>* Campos Obligatorios</label>
                                        <button type="submit" class="btn btn-default" value="Confirmar" name="Confirmar" style="background-color: #7b16b6; color: white;"
                                            onClick="return confirm ('Seguro que desea guardar el nuevo Área?');"
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
