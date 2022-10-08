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
require_once 'funciones/listarNacionalidades.php';
$ListadoNacionalidades = Listar_Nacionalidades($MiConexion);
$CantNacionalidades = count($ListadoNacionalidades);

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
                    <h1 class="page-header">Nacionalidades</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
             
    <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Listado Nacionalidades (<?php echo $CantNacionalidades; ?>)</h3>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nacionalidad</th>
                
                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de los docentes
                            for ($i=0; $i < $CantNacionalidades; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $ListadoNacionalidades[$i]['ID']; ?></td>
                                    <td><?php echo $ListadoNacionalidades[$i]['NACION']; ?></td>
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
                            Datos Nueva Nacionalidad
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a EstudianteNuevo
                                            if (!empty($_POST['Cancelar'])) {
                                                //$accion = 0;
                                                header('Location: EstudianteNuevo.php');
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                             
                                                $mensaje = '';
                                                if (empty($_POST['Nacion'])) {
                                                    $mensaje = 'Debe completar la nacionalidad';
                                                } 
                                                if($mensaje==''){
                                                //Si está todo bien creo area nuevo en base de datos, sino, muestro mensaje
                                                    require_once 'funciones/guardarNacionalidad.php';
                                                        if (nacionalidadNueva($MiConexion,$_POST['Nacion'])) {?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Nueva nacionalidad guardada!</strong>
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
                                            <label>Nacionalidad*</label>
                                            <input class="form-control" name="Nacion" value="<?php echo !empty($_POST['Nacion']) ? $_POST['Nacion'] : ''; ?>">
                                        </div>
                                        
                                        <label>* Campos Obligatorios</label>
                                        <button type="submit" class="btn btn-default" value="Confirmar" name="Confirmar" style="background-color: #7b16b6; color: white;"
                                            onClick="return confirm ('Seguro que desea guardar la nueva nacionalidad?');"
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
