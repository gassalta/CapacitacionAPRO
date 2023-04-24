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

require_once 'funciones/buscarCurso.php';
$CursoElegido = array();
require_once 'funciones/buscarEstudiante.php';
$EstudElegido = array();
require_once 'funciones/informeAsistencia.php';
$TotalesAsistencias = array();
require_once 'graficobarras.php';
//Declaro variables
$mensaje = '';
$ListoEmitir = 0;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <?php
  require_once 'encabezado.php';
  ?>
  <script src="includes/jquery-3.3.1.min.js"></script>
  <script src="includes/plotly-latest.min.js"></script>
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
          <h2 class="tile-title">
            <font color="#85C1E9">
              <center><b>Trayectorias Escolares</b>
            </font>
          </h2>
        </div>
      </div><br> <!-- fin row  titulo-->
      <?php
      //Listo los cursos
      require_once 'funciones/listaCursos.php';
      $ListadoCursos = array();
      $ListadoCursos = ListarCursos($MiConexion);
      $CantidadCursos = count($ListadoCursos);
      require_once 'funciones/listarEstudiantes.php';
      $ListadoEstudiantes = array();
      $ListadoEstudiantes = Listar_Estudiantes($MiConexion);
      $CantidadEstudiantes = count($ListadoEstudiantes);
      ?>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <center>¿Qué trayectoria desea ver?</center>
            </div><br>
            <div class="panel-body">

              <form role="form" method="post">
                <?php
                //Si cancela vuelvo a administrarAsistencias
                if (!empty($_POST['Cancelar'])) {
                  header('Location: index.php');
                }
                ?>
                <div class="form-group">
                  <div class="row">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-2"><label>Curso</label></div>
                    <div class="col-lg-6">
                      <select class="form-control" name="Curso" id="Curso">
                        <option value="">Seleccione un curso</option>
                        <?php
                        $selectedC = '';
                        for ($i = 0; $i < $CantidadCursos; $i++) {
                          if (!empty($_POST['Curso']) && $_POST['Curso'] ==  $ListadoCursos[$i]['ID']) {
                            $selectedC = 'selected';
                          } else {
                            $selectedC = '';
                          }
                        ?>
                          <option value="<?php echo $ListadoCursos[$i]['ID']; ?>" <?php echo $selectedC; ?>>Año: <?php echo $ListadoCursos[$i]['ANIO'] . " - Division: " . $ListadoCursos[$i]['DIVISION']; ?></option>
                        <?php
                        } //fin for select curso
                        ?>
                      </select>
                    </diV><!--fin col select curso-->
                    <div class="col-lg-2"><button type="submit" class="btn btn-primary" value="ElegirCurso" name="ElegirCurso"><box-icon name="show-alt" size="sm" color="white" animation="tada-hover"></box-icon> Ver</button></diV>
                  </div><br><!--fin row seleccion de curso-->
                  <?php
                  if (!empty($_POST['ElegirCurso'])) {
                    $CursoElegido = buscarCurso($MiConexion, $_POST['Curso']);
                    $_SESSION['CursoEleg'] = $_POST['Curso'];
                    require_once 'funciones/listarEstudiantes.php';
                    $ListadoEstudiantes = array();
                    $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion, $_POST['Curso']);
                    $CantidadEstudiantes = count($ListadoEstudiantes);
                    if ($CantidadEstudiantes == 0) {
                  ?>
                      <div class="row">
                        <div class="col-lg-12">
                          <div class="alert alert-dismissible alert-danger"><strong>
                              <center>El curso seleccionado no tiene estudiantes asignados</center>
                            </strong></div>
                        </div>
                      </div><!--fin row alert no estudiantes-->
                </div><!--fin form goup-->
            <?php
                    } //fin if sin estudiantes en el curso 
                  } //fin if seleccion curso 
            ?>

            <div class="form-group">
              <div class="row">
                <div class="col-lg-1"></div>
                <div class="col-lg-2"><label>Estudiante</label></div>
                <div class="col-lg-6">
                  <select class="form-control" name="Estudiante" id="Estudiante">
                    <option value="0">Seleccione un estudiante</option>
                    <?php
                    $selectedE = '';
                    for ($i = 0; $i < $CantidadEstudiantes; $i++) {
                      if (!empty($_POST['Estudiante']) && $_POST['Estudiante'] ==  $ListadoEstudiantes[$i]['ID']) {
                        $selectedE = 'selected';
                      } else {
                        $selectedE = '';
                      }
                    ?>
                      <option value="<?php echo $ListadoEstudiantes[$i]['ID']; ?>" <?php echo $selectedE; ?>><?php echo $ListadoEstudiantes[$i]['ID'] . "- " . $ListadoEstudiantes[$i]['APELLIDO'] . " " . $ListadoEstudiantes[$i]['NOMBRE']; ?>
                      </option>
                    <?php
                    } //fin for 
                    ?>
                  </select>
                </div>
                <div class="col-lg-2"><button type="submit" class="btn btn-primary" value="ElegirEstudiante" name="ElegirEstudiante"><box-icon name="show-alt" size="sm" color="white" animation="tada-hover"></box-icon> Ver</button></diV>
              </div><br><br><!-- fin row seleccion estudiante-->


              <?php
              if (!empty($_POST['ElegirEstudiante'])) {
                if (!empty($_POST['Curso'])) {
                  if ($_POST['Estudiante'] != 0) {

              ?> <div class="row">
                      <div class="col-lg-12">
                        <div class="panel panel-primary">
                          <div class="panel-heading">
                            <div class="panel-body">

                              <?php

                              $Estudiante = $_POST['Estudiante'];
                              graficoBarras($Estudiante);
                              ?>

                              <div class="row" align="center">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-10">
                                  <div id="cargaGrafico"></div>
                                </div>
                              </div>
                            </div><!--col asistencias-->
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php

                  } else {
                  ?>
                    <div class="row">
                      <div class="col-lg-12">
                        <div class="alert alert-dismissible alert-danger"><strong>
                            <center>Por favor seleccione un estudiante.</center>
                          </strong></div>
                      </div>
                    </div><!-- fin row error estudiante-->
            </div><!-- fin form group-->
          <?php
                  }
                } //fin if empty group
                else {
          ?>
          <div class="row">
            <div class="col-lg-12">
              <div class="alert alert-dismissible alert-danger"><strong>
                  <center>Por favor seleccione un curso</center>
                </strong></div>
            </div>
          </div><!-- fin row curso-->
      <?php
                }
              } //fin elegir estudiante
      ?>


      <div class="row" align="center">


        <div class="col-lg-12">
          <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" onclick="return confirm ('Seguro que desea cancelar?')"><box-icon name="arrow-back" size="sm" color="white" animation="tada"></box-icon> Retornar</button>
        </div>

      </div><!-- /.row botones -->



            </div> <!-- /.panel body-->
          </div><!-- /.panel primary-->
        </div> <!-- fin col principal -->
      </div><!-- fin row principal -->
    </div> <!--fin page-wrapper -->
  </div><!-- fin wrapper -->


  <!-- jQuery -->
  <script src="../vendor/jquery/jquery.min.js"></script>

  <!-- Bootstrap Core JavaScript -->
  <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

  <!-- Metis Menu Plugin JavaScript -->
  <script src="../vendor/metisMenu/metisMenu.min.js"></script>

  <!-- Custom Theme JavaScript -->
  <script src="../dist/js/sb-admin-2.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      $('#cargaGrafico').load('graficobarras.php');
      //$('#cargaTorta').load('graficoTortaEstudiantesReprobados.php');
      //$('#prueba').load('estadisticasAprendizajes.php');
    });
  </script>


</body>

</html>