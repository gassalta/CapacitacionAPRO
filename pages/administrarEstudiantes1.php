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
$_SESSION['DNIEstudianteElegido'] = "";
$_SESSION['IdEstudianteSeleccionado'] = "";
$_SESSION['IdEElegido'] = "";
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
                    <h1 class="page-header">Estudiantes</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
    <?php
    //Listo los estudiantes
        require_once 'funciones/listarEstudiantes.php';
        $Listado=array();
        $Listado = Listar_Estudiantes($MiConexion);
        $CantidadEstudiantes = count($Listado);
    ?>
    <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Listado Estudiantes (<?php echo $CantidadEstudiantes; ?>)</h3>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nro Legajo</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>e-mail</th>
                    <th>Curso</th>
                    <th>Fecha de Nacimiento</th>

                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de los estudiantes
                            for ($i=0; $i < $CantidadEstudiantes; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $Listado[$i]['ID']; ?></td>
                                    <td><?php echo $Listado[$i]['NROLEGAJO']; ?></td>
                                    <td><?php echo $Listado[$i]['APELLIDO']; ?></td>
                                    <td><?php echo $Listado[$i]['NOMBRE']; ?></td>
                                    <td><?php echo $Listado[$i]['DNI']; ?></td>
                                    <td><?php echo $Listado[$i]['TELEFONO']; ?></td>
                                    <td><?php echo $Listado[$i]['MAIL']; ?></td>
                                    <td><?php echo $Listado[$i]['ANIO']; ?> <?php echo $Listado[$i]['DIVISION']; ?></td>
                                    <td><?php echo $Listado[$i]['FECHANACIM']; ?></td>
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
                            Acciones
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post">
                                                <button class="btn btn-default" type="submit" name="EstudianteNuevo" formaction="EstudianteNuevo.php">Nuevo
                                                </button>
                                                <label>    </label>
                                                <button class="btn btn-default" type="submit" name="ModificarEstudiante" formaction="modificarEstudiante.php">Modificar
                                                </button>
                                                <label>    </label>
                                                <button class="btn btn-default" type="submit" name="BuscarEstudiante" formaction="buscarUnEstudiante.php">Buscar
                                                </button>
                                            
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
