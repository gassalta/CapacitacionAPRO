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

$EsDocente = 0;
?>
<!DOCTYPE html>
<html lang="es">

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
      <?php
      //Listo los espacios curriculares
      require_once 'funciones/listarEspaciosCurricularesXDocente.php';
      $ListadoEC = array();
      $ListadoEC = ListarEspCurrXDocente($MiConexion, $_SESSION['Id']);
      $CantidadEspCurr = count($ListadoEC);
      $NEC = $_REQUEST['Cx'];
      $IdEC = 0;
      for ($i = 0; $i < $CantidadEspCurr; $i++) {
        if ($NEC == $ListadoEC[$i]['NOMBREESPACCURRIC']) {
          $IdEC = $ListadoEC[$i]['ID'];
        }
      }
      ?>
      <div class="row" align="center">
        <div class="col-lg-10">
          <div class="tile">
            <h2 class="tile-title">
              <font color="#85C1E9">
                <center><b>Contenidos y Aprendizajes de <?php echo $NEC ?></b>
              </font>
            </h2>
          </div>
        </div>
      </div> <!-- /.row titulo --><br>
      <div class="row">
        <div class="col-lg-10">
          <form role="form" method="post">

            <?php
            require_once 'funciones/listarContenidos.php';
            $ListadoContenidos = array();
            $ListadoContenidos = Listar_Contenidos($MiConexion, $IdEC);
            $CantidadContenidos = count($ListadoContenidos);
            if ($CantidadContenidos != 0) {
            ?>
              <div class="row" align="center">
                <div class="col-lg-12">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered bg-info">
                      <thead>
                        <tr class="bg-primary">
                          <th>N°</th>
                          <th>Contenido</th>
                          <th>Aprendizajes</th>
                          <th>Modificar</th>
                          <th>Eliminar</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        //Cargo a la tabla el listado de los contenidos
                        for ($i = 0; $i < $CantidadContenidos; $i++) {
                          $NContenido = $ListadoContenidos[$i]['DENOMINACION'];
                          $CoContenido = $ListadoContenidos[$i]['ID'];
                          if ($ListadoContenidos[$i]['ESTADO'] == '0') {
                        ?>
                            <tr>
                              <th>
                                <font color="#005eff"><?php echo $ListadoContenidos[$i]['ID']; ?></font>
                              </th>
                              <?php
                              echo '<td><font color="#005eff">' . $NContenido . '</font></td>';
                              echo '<td><a href="Aprendizajes.php?Cx=' . $CoContenido . '&Cn=' . $NContenido . '&Ec=' . $NEC . '"><box-icon name="grid" " size="md" color="#005eff" animation="tada-hover"></box-icon></a></td>';

                              echo '<td><a href="modificarContenidos.php?Tx=M&Cx=' . $ListadoContenidos[$i]['ID'] . '&Ec=' . $NEC . '"><box-icon name="edit-alt" type="solid" size="md" color="#005eff" animation="tada-hover"></box-icon></a></td>';
                              echo '<td><form name="eliminar" method="post" action="eliminarContenido.php?Cx=' . $ListadoContenidos[$i]['ID'] . '&Ec=' . $NEC . '">'; ?>
                              <button class="btn btn-danger btn-circle" type="submit" name="eliminar" onclick="return confirm ('¿Seguro que desea eliminarlo?')"><box-icon name="trash" size="md" color="white" animation="tada-hover"></box-icon></button>
          </form>
          </td>
          </tr>



          <!--echo'<td><a href="eliminarContenido.php?Tx=M&Cx='.$ListadoContenidos[$i]['ID'].'&Ec='.$NEC.'"><box-icon name="trash" " size="md" color="red" animation="tada-hover"></box-icon></a></td>';
						 echo"</tr>"; -->
      <?php
                          }
                        }
      ?>
      <tr>
        <td colspan="6"><b><a href="contenidoNuevo.php?Cx=<?php echo $NEC; ?>"><box-icon name="plus-circle" type="solid" size="md" color="#005eff" animation="tada-hover"></box-icon> Registrar un nuevo contenido</b></a></td>
      </tr>
      </tbody>
      </table>
        </div>
      </div>
    </div><!-- /.row tabla --><br><br>
  <?php
            } else {
  ?><!--<br><br><div class="row" align="center">
          <div class="col-lg-12">	
			<div class="alert alert-dismissible alert-danger">
                  <strong><center>El espacio curricular aún no tiene registrado ningún contenido.</center></strong>
            </div>
         </div>
		 </div><br><br>--><br><br>
    <div class="row" align="center">
      <div class="col-lg-12">
        <div class="table-responsive">
          <table class="table table-striped table-bordered bg-info">

            <tbody>
              <tr>
                <td colspan="6"><b><label><box-icon name="plus-circle" type="solid" size="sm" color="#005eff" animation="tada"></box-icon><a href="contenidoNuevo.php?Cx=<?php echo $NEC; ?>"><abbr title="Aquí podrá cargar por primera vez un contenido en éste espacio curricular"> Éste espacio curricular aún no tiene registrado ningún contenido. ¿Desea Registrar uno? </b></abbr></a><box-icon name="plus-circle" type="solid" size="sm" color="#005eff" animation="tada"></box-icon></td>
              </tr>
              </b></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div><br><br><br>
  <?php
            }
            $_SESSION['EspCurrEleg'] = $IdEC;
  ?>

  <div class="row" align="center">
    <div class="col-lg-2"> </div>
    <div class="col-lg-6">
      <button class="btn btn-primary" type="submit" name="Aprendizajes" formaction="funciones/emitirPDFListadoAprendizajesXEspCurr.php" onClick="return confirm ('Seguro que desea emitir el informe?');"><box-icon name="download" type="solid" size="sm" color="white" animation="fade-down"></box-icon> Emitir listado de Aprendizajes por Espacio Curricular</button>
    </div>
    <div class="col-lg-2">
      <button class="btn btn-danger" type="submit" name="Cancelar" formaction="index.php"><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar </button>
    </div>
  </div><!-- /.row botones-->
  </div> <!-- /.col principal -->
  </div><!-- /.row principal-->
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