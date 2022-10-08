<?php
//Verifico si está abierta la sesion
session_start();
if (empty($_SESSION['Nombre'])) {
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
$MiConexion = ConexionBD();

require_once 'funciones/buscarEspacioCurricular.php';
$EspCurrElegido = array();

require_once 'funciones/listarAprendizajesXEspCurr.php';
$ListadoAprendizajes = array();

//Declaro variables
$mensaje = '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php
  require_once 'encabezado.php';
  ?>
  <link href="estilos.css" rel="stylesheet" type="text/css" />
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
        <div class="col-lg-10">
          <div class="tile">
            <h2 class="tile-title">
              <font color="#85C1E9">
                <center><b>Consulta de Aprendizajes</b>
              </font>
            </h2>
          </div>
        </div>
      </div><!-- /.row titulo--><br>

      <?php
      //Listo los espacios curriculares de acuerdo al docente
      $ListadoEspaciosCurriculares = array();
      if ($_SESSION['Categoria'] == 'Coordinador/a') {
        require_once 'funciones/listarEspaciosCurriculares.php';
        $ListadoEspaciosCurriculares = Listar_EspCurr($MiConexion);
      } else {
        require_once 'funciones/listarEspaciosCurricularesXDocente.php';
        $ListadoEspaciosCurriculares = ListarEspCurrXDocente($MiConexion, $_SESSION['Id']);
      }

      $CantidadEC = count($ListadoEspaciosCurriculares);
      ?>
      <div class="row">
        <div class="col-lg-10">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <center>Aprendizajes por Espacio Curricular</center>
            </div><br>
            <div class="panel-body">
              <form role="form" method="post">
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-3"><label>Espacio Curricular</label></diV>
                    <div class="col-lg-5">
                      <select class="form-control" name="EspaCurri" id="EspaCurri">
                        <option value="">Seleccione una opción</option>
                        <?php
                        $selected = '';
                        for ($i = 0; $i < $CantidadEC; $i++) {
                          if (!empty($_POST['EspaCurri']) && $_POST['EspaCurri'] ==  $ListadoEspaciosCurriculares[$i]['ID']) {
                            $selected = 'selected';
                          } else {
                            $selected = '';
                          }
                        ?>
                          <option value="<?php echo $ListadoEspaciosCurriculares[$i]['ID']; ?>" <?php echo $selected; ?>><?php echo $ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']; ?></option>
                        <?php
                        } ?>
                      </select>
                    </diV>
                    <div class="col-lg-2">
                      <button type="submit" class="btn btn-default" value="Ver" name="Ver" style="background-color: #337ab7; color: white;">
                        <box-icon name="show-alt" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Ver
                      </button>
                    </div>
                  </div><br><br>

                  <?php

                  if (!empty($_POST['Ver'])) {
                    $EspCurrElegido = buscarEspacCurric($MiConexion, $_POST['EspaCurri']);
                    $_SESSION['EspCurrEleg'] = $_POST['EspaCurri'];
                    //if (!empty($EspCurrElegido)) 
                    // {
                    //     echo $EspCurrElegido['NOMBREESPACCURRIC'];
                    //   }
                    $ListadoAprendizajes = Listar_AprendizajesXEC($MiConexion, $_POST['EspaCurri']);
                    $CantidadAprendizajes = count($ListadoAprendizajes);
                    if ($CantidadAprendizajes == 0) {
                  ?>
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="alert alert-dismissible alert-danger"><strong>
                              <center>El espacio curricular seleccionado no tiene aprendizajes registrados</center>
                            </strong></div>
                        </diV>
                      </div>
                  <?php
                    }
                  }
                  ?>

                </div>
                <!--Cierra el form group-->
                <?php
                if (!empty($ListadoAprendizajes)) { ?>
                  <div class="row" align="center">
                    <div class="col-lg-12">
                      <label>
                        <font color="#85C1E9" size="3px">
                          <center><b><?php echo $EspCurrElegido['NOMBREESPACCURRIC'] . "    -     Año: " . $EspCurrElegido['ANIO']; ?></b></center>
                        </font>
                      </label>
                    </div>
                  </div>
                  <!--row titulo-->

                  <div class="row">
                    <div class="col-lg-12">

                      <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                          <thead>
                            <tr class="bg-info">
                              <th>N°</th>
                              <th>Contenido</th>
                              <th>Aprendizaje</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            //Cargo a la tabla el listado de los aprendizajes
                            for ($i = 0; $i < $CantidadAprendizajes; $i++) { ?>
                              <tr class="table-info">
                                <td><?php echo $ListadoAprendizajes[$i]['ID']; ?></td>
                                <td><?php echo $ListadoAprendizajes[$i]['CONTENIDO']; ?></td>
                                <td><?php echo $ListadoAprendizajes[$i]['DENOMINACION']; ?></td>
                              </tr>
                            <?php
                            }
                            ?>
                          </tbody>
                        </table>
                      </div>
                      <!--row tabla-->
                    </div>
                  </div>
                  <hr>
                <?php
                }
                ?>

                <div class="row" align="center">
                  <div class="col-lg-2"></div>
                  <div class="col-lg-4">
                    <button type="submit" class="btn btn-default" value="Emitir" name="Emitir" style="background-color: #337ab7; color: white;" formaction="funciones/emitirPDFListadoAprendizajesXEspCurr.php" onClick="return confirm ('Seguro que desea emitir el informe?');">
                      <box-icon name="downvote" type="solid" size="sm" color="white" animation="fade-down"></box-icon> Emitir listado
                    </button>
                  </div>
                  <div class="col-lg-4">
                    <button class="btn btn-danger" type="submit" name="Cancelar" formaction="index.php" onclick="return confirm ('¿Seguro que desea cancelar?')">
                      <box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar
                    </button>
                  </div>
                </div>
                <!--row botones--><br>


                <!-- /.col-lg-6 (nested) -->

                <!-- /.row (nested) -->






            </div> <!-- /.panel-body -->
          </div> <!-- /.panel -->
        </div> <!-- /.col-principal-->
      </div> <!-- /.row principal -->
    </div> <!-- /#page-wrapper -->
  </div> <!-- /#wrapper -->

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