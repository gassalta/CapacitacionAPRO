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
require_once 'funciones/baseDeDatos.php';
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


    $page = isset($_GET['tipo']) ? $_GET['tipo'] : null;

    switch ($page) {
      case 'aprobado':
        $sql = "select count(aprobados) as aprobados,sum(reprobados>3) as reprobados from situacionestudiantes";
        $registros = mysqli_query($MiConexion, $sql) or die("Error en el select SqlArea");

        $Fila = mysqli_fetch_array($registros);


        $estudiante[] = 'Aprobados (' . ($Fila['aprobados'] - $Fila['reprobados']) . ')';
        $estudiante[] = 'Reprobados (' . $Fila['reprobados'] . ')';

        $total[] = ($Fila['aprobados'] - $Fila['reprobados']);
        $total[] = $Fila['reprobados'];
        $cant[] = 'Total: ' . $Fila['aprobados'];
        break;

      case 'reprobado':
        # code...
        break;

      default:
        # code...
        break;
    }

    // Verificamos que el get no este vacio
    if (!empty($page)) {
      $datosEstudiante = json_encode($estudiante);
      $datosTotal = json_encode($total);
      $datosCant = json_encode($cant);
    }


    ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-12">
          <h2 class="tile-title">
            <font color="#85C1E9">
              <center><b>Gráfico de Estudiantes <?= $page ?></b>
            </font>
          </h2>
        </div>
      </div><br> <!-- fin row  titulo-->

      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <div class="panel-body">

                <form role="form" method="post">
                  <?php
                  //Si cancela vuelvo a administrarAsistencias
                  if (!empty($_POST['Cancelar'])) {
                    header('Location: index.php');
                  }
                  ?>



                  <div class="row" align="center">
                    <div class="col-lg-1"></div>
                    <div class="col-lg-10">
                      <!-- Grafico torta -->
                      <div id="graficaTorta"></div>

                      <script type="text/javascript">
                        function crearCadenaBarras(json) {
                          var parsed = JSON.parse(json);
                          var arr = [];
                          for (var x in parsed) {
                            arr.push(parsed[x]);
                          }
                          return arr;
                        }
                      </script>

                      <script type="text/javascript">
                        datosEstudiante = crearCadenaBarras('<?php echo $datosEstudiante ?>');
                        datosCant = crearCadenaBarras('<?php echo $datosCant ?>')
                        datosTotal = crearCadenaBarras('<?php echo $datosTotal ?>');

                        //configuramos los datos del grafico// 
                        var A = {
                          labels: datosEstudiante,
                          values: datosTotal,

                          name: "Estados",
                          type: 'pie', //lines,histogram,
                          //orientation: 'h',..si quisieramos hacer barras horizontales
                          marker: {
                            color: 'grey',
                            line: { //color relleno
                              width: 0.5, //ancho linea
                              dash: 'solid',
                              color: 'grey', //color borde line
                            },
                          },

                          automargin: true,
                          textinfo: "label+percent", //"label+percent" si queremos que ademas muestre la etiqueta
                          insidetextorientation: "radial" //, si queremeos que siga con la orientacion del grafico

                        };



                        var data = [A];

                        //configuramos el título//
                        var layout = {
                          bargap: 0.05,
                          bargroupgap: 0.2,
                          title: 'Estudiantes Aprobados',
                          font: {
                            size: 12,
                            color: '#0d47a1'
                          },
                        };
                        //lo hacemos responsivo// 
                        var config = {
                          responsive: true,

                          toImageButtonOptions: {
                            format: 'jpeg', // one of png, svg, jpeg, webp
                            filename: 'Estudiantes_Aprobados',
                            height: 400,
                            width: 600,
                            scale: 1, // Multiply title/legend/axis/canvas sizes by this factor
                          },
                        };

                        Plotly.newPlot('graficaTorta', data, layout, config);
                      </script>

                    </div>
                  </div>
                  <!--fin row grafico -->
              </div><!-- /.panel body-->

            </div> <!-- /.panel primary-->
          </div>


          <div class="row" align="center">
            <div class="col-lg-12"><!-- <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" onclick="return confirm ('Seguro que desea cancelar?')">
                <box-icon name="arrow-back" size="sm" color="white" animation="tada"></box-icon> Retornar
              </button> -->
              <a class="btn btn-primary" href="estudiantesPdf.php" target="_new" name="Imprimir">Imprimir</a>
            </div>
          </div><!-- /.row botones -->
        </div>
      </div> <!-- /FIN row primary -->
    </div> <!-- /#page-wrapper -->
  </div><!-- /#wrapper -->


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
      //$('#cargaGrafico').load('graficobarras.php');
      $('#cargaTorta').load('graficoTortaEstudiantesReprobados.php');
      //$('#prueba').load('estadisticasAprendizajes.php');
    });
  </script>


</body>

</html>