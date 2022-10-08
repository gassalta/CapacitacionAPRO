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

?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php
    require_once 'encabezado.php';
	
?>
   <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
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
            
    <?php
    //Listo los espacios curriculares
        require_once 'funciones/listarEspaciosCurricularesXDocente.php';
        $ListadoEC=array();
        $ListadoEC = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
        $CantidadEspCurr = count($ListadoEC);
    ?>
    <div class="row">
        <div class="col-md-12">
         <h1 class="page-header">Evaluaciones</h1>
        </div>
        <div class="clearfix"></div>
        
      </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post">
                                        
                                        <div class="form-group">
                                            <label>Espacio Curricular</label>
                                            <select class="form-control" name="EspaCurri" id="EspaCurri">
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantidadEspCurr ; $i++) {
                                                    if (!empty($_POST['EspaCurri']) && $_POST['EspaCurri'] ==  $ListadoEC[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                    /*    if ($selected != 'selected' && $_SESSION['EspCurrElegido']!= 0 && $_SESSION['EspCurrElegido'] == $ListadoEC[$i]['ID']) {
                                                            $selected = 'selected';
                                                        } else { */
                                                            $selected='';
                                                     //   }
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoEC[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoEC[$i]['NOMBREESPACCURRIC']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" class="btn btn-default" value="Ver" name="Ver" style="background-color: #337ab7; color: white;"
                                       ><box-icon  name="show" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>Ver</button>
                                        </div>
                                        <?php
                                        if (!empty($_POST['Ver'])) {
                                            $_SESSION['EspCurrElegido'] = 0;
                                        }
                                            //si hay seleccionado algún contenido...
                                            if (!empty($_POST['EspaCurri'])) {
                                                //listo los contenidos del espacio curricular
                                                require_once 'funciones/listarEvaluaciones.php';
                                                $ListadoEvaluaciones = array();

                                                if ($_SESSION['EspCurrElegido'] == 0) {
                                                    $ListadoEvaluaciones = Listar_Evaluaciones($MiConexion,$_POST['EspaCurri']);
                                                } else {
                                                    $ListadoEvaluaciones = Listar_Evaluaciones($MiConexion,$_SESSION['EspCurrElegido']);
                                                }
                                                $CantidadEvaluaciones = count($ListadoEvaluaciones); 

                                                 if ($CantidadEvaluaciones != 0) {
                                                ?>
                                                

                                                <h2 class="tile-title"><font color="#85C1E9"><center>Listado  de Evaluaciones (<?php echo $CantidadEvaluaciones; ?>)</font></h2>
            <div class="table-responsive">
              <table class="table-sm table-striped  bg-info">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    
                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de las evaluaciones
                            for ($i=0; $i < $CantidadEvaluaciones; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $ListadoEvaluaciones[$i]['ID'], "- "; ?></td>
                                    <td><?php echo $ListadoEvaluaciones[$i]['FECHA']; ?></td>
                                </tr> 
                         <?php   }

                        ?>
                        
                        
                     
                 
                </tbody>
              </table> 
            </div>
        <?php
        } else { ?>
                <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>El espacio curricular no tiene registrada ninguna evaluación</strong>
                </div>
              </div>
      <?php }

         } else { ?>
                <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Seleccione un espacio curricular</strong>
                </div>
              </div>
      <?php  }?>
				<div class="form-group"></div>
                								<button class="btn btn-primary" type="submit" name="BuscarDocente" formaction="buscarUnaEvaluacion.php"><box-icon name="search-alt" type="solid" size="sm" color="white" animation="tada"></box-icon> Buscar</button>
                                                <button class="btn btn-primary" type="submit" name="ModificarContenidos" formaction="modificarEvaluacion.php"><box-icon  name="edit-alt" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Modificar
                                                </button>
												<button class="btn btn-primary" type="submit" name="ContenidoNuevo" formaction="evaluacionNueva.php"><box-icon name="plus-circle" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Nueva
                                                </button>
												<button class="btn btn-primary" type="submit" name="EliminarContenido" formaction="eliminarEvaluacion.php"><box-icon  name="trash" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Eliminar
                                                </button>
                                                <div class="form-group"></div>
                                               <!-- <center>
                                                <div class="form-group">
                                                    <button class="btn btn-primary" type="submit" name="Aprendizajes" formaction="consultaAprendizajes.php"><box-icon name="spreadsheet" type="solid" size="sm" color="white" animation="tada"></box-icon> Aprendizajes
                                                </button>
                                                </div>
                                                </center> -->
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
