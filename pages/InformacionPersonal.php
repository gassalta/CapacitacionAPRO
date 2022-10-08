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

//Declaro variables
$mensaje = '';
$DocenteBuscado = array();
$DocenteEncontrado = 0; //0-No - 1-Sí

$IdDocente = $_SESSION['Id'];
$_POST['Id'] = $IdDocente;

?>
<!DOCTYPE html>
<html lang="es">

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
    require_once 'funciones/listarEspaciosCurricularesXDocente.php';
    $ListadoEspCurrXDoc = array();
    $ListadoEspCurrXDoc = ListarEspCurrXDocente($MiConexion, $_SESSION['Id']);
    $CantEspCurrXDoc = count($ListadoEspCurrXDoc);
    ?>
    <div id="page-wrapper">
      <div class="row">
        <div class="col-lg-10">
          <div class="tile">
            <h2 class="tile-title">
              <font color="#85C1E9">
                <center><b>Mi informaci&oacuten</b>
              </font>
            </h2>
          </div>
        </div><br>
      </div> <!-- /.row titulo --><br>

      <div class="row">
        <div class="col-lg-10">
          <div class="panel panel-primary">
            <div class="panel-heading"></div>
            <div class="panel-body">


              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group"><label for="disabledSelect">Apellido y Nombre</label><input class="form-control" name="Apellido" value="<?php echo $_SESSION['Apellido'] . ', ' . $_SESSION['Nombre'] ?>" readonly></div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group"><label for="disabledSelect">DNI</label><input class="form-control" name="DNI" value="<?php echo $_SESSION['DNI'] ?>" readonly> </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group"><label for="disabledSelect">Legajo en Junta</label><input class="form-control" name="NroLegajoJunta" value="<?php echo $_SESSION['NroLegajoJunta'] ?>" readonly></div>
                </div>
              </div><!-- fin primera row--><br>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group"><label for="disabledSelect">E-mail</label><input class="form-control" name="Mail" value="<?php echo $_SESSION['Email'] ?>" readonly></div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group"><label for="disabledSelect">Título</label><textarea class="form-control" rows="1" name="Titulo" readonly><?php echo $_SESSION['Titulo'] ?></textarea></div>
                </div>
              </div><!-- fin segunda row--><br>
              <div class="row" align="center">
                <div class="col-lg-3" align="left">
                  <div class="form-group"><label for="disabledSelect">Categoría</label><input class="form-control" name="Categoria" value="<?php echo $_SESSION['Categoria']  ?>" readonly></div>
                </div>


                <!--<div class="col-lg-1"></div>-->
                <div class="col-lg-3">
                  <div class="form-group"><label for="disabledSelect">Fecha de Nacimiento </label><input id="date" type="date" name="FechaNacim" value="<?php echo $_SESSION['FechaNacim'] ?>" readonly></div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group"><label for="disabledSelect">Fecha de Escalafón </label><input id="date" type="date" name="FechaEscalafon" value="<?php echo $_SESSION['FechaEscalafon'] ?>" readonly></div>
                </div>
                <div class="form-group">
                  <div class="col-lg-3"><label for="disabledSelect">Fecha Último Ingreso</label><input class="form-control" id="disabledInput" type="text" name="UltIngreso" value="<?php echo $_SESSION['UltIngreso'] ?>" readonly></div>
                </div>


              </div><!-- fin tercera row--><br>
              <div class="row" align="center">
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                  <div class="panel panel-info">
                    <div class="panel-heading"><b>Espacios curriculares a cargo</b> </div>
                    <div class="panel-body">
                      <ol>
                        <?php
                        for ($i = 0; $i < $CantEspCurrXDoc; $i++) {
                          echo "<li>" . $ListadoEspCurrXDoc[$i]['NOMBREESPACCURRIC'] . "</li>";
                        }
                        ?>
                      </ol>
                    </div>
                  </div>
                </div>
              </div><br>
              <hr>

              <div class="row" align="center">
                <div class="col-lg-2"></div>
                <div class="col-lg-4">
                  <form action="cambiarClave.php" method="post"><button type="submit" class="btn btn-primary" value="EspCurr" name="EspCurr">
                      <box-icon name="key" size="sm" color="white" animation="tada-hover"></box-icon> Modificar contraseña
                    </button></form>
                </div>
                <div class="col-lg-4">
                  <form action="index.php" method="post"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar">
                      <box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada"></box-icon> Retornar
                    </button></form>
                </div>
              </div>

            </div> <!-- /.panel-body -->
          </div> <!-- fin panel primary -->
        </div> <!-- fin col primary -->
      </div><!-- /fin row primaryl -->
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