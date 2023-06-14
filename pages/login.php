<?php
session_start();
require_once 'funciones/conexion.php';
if (empty($_SESSION['Nombre'])) {
  date_default_timezone_set("America/Argentina/Buenos_Aires");
  //Creo la conexion con la base de datos
  $MiConexion = ConexionBD();
?>
  <!DOCTYPE html>
  <html lang="es">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistema de Gestión de Calificaciones">
    <meta name="author" content="Berigozzi-Benicio-Bradaschia">
    <link rel="icon" href="images/Logo sin fondo.png" type="image/png" />
    <title>CalificacionesPRoA</title>

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
  </head>

  <body style="background-color:#63b4cf;">
    <img class="center-block" src="images/Logo CalificacionesPRoA SF.png" />
    <br />
    <div class="container">
      <div class="row">
        <div class="col-md-4 col-md-offset-4">
          <div class="login-panel panel panel-default">
            <!--<div class="panel-heading">
                        <h3 class="panel-title">Ingreso</h3>
                    </div>-->
            <div class="panel-body">
              <form role="form" method="post">
                <fieldset>

                  <?php
                  //Verifico si el usuario presiono el boton login
                  if (empty($_POST['BotonLogin'])) {
                  ?>
                    <div class="bs-component">
                      <div class="alert alert-dismissible alert-info">
                        <strong>
                          <center>Ingrese sus datos
                        </strong>
                      </div>
                    </div>
                    <?php  } else {
                    $usu = $_POST['email'];
                    $cla = ($_POST['password']);
                    //Verifico si los campos están completos
                    if ($usu != "" && $_POST['password'] != null) {
                      //Busco el usuario en la base de datos
                      $SQL = "SELECT D.Id, D.Apellido, D.Nombre, D.DNI, D.FechaNacim, D.NroLegajoJunta, D.Titulo, D.FechaEscalafon, C.Denominacion AS Categoria, D.Contrasenia, D.Mail, D.UltIngreso, D.Ingreso, D.FechaCambioContras
               FROM docentes D, categorias C
                WHERE D.Mail='$usu' AND D.Contrasenia='$cla' AND D.Categoria=C.Id";
                      $rs = mysqli_query($MiConexion, $SQL) or die(mysqli_error($MiConexion));
                      //Si existe abro la sesion 
                      if ($data = mysqli_fetch_array($rs)) {

                        $fechaActual = date('Y-m-d H:i:s');
                        $IdUsuario = $data['Id'];

                        $_SESSION['Id'] = $data['Id'];
                        $_SESSION['Apellido'] = $data['Apellido'];
                        $_SESSION['Nombre'] = $data['Nombre'];
                        $_SESSION['DNI'] = $data['DNI'];
                        $_SESSION['FechaNacim'] = $data['FechaNacim'];
                        $_SESSION['NroLegajoJunta'] = $data['NroLegajoJunta'];
                        $_SESSION['Titulo'] = $data['Titulo'];
                        $_SESSION['FechaEscalafon'] = $data['FechaEscalafon'];
                        $_SESSION['Categoria'] = $data['Categoria'];
                        $_SESSION['Contrasenia'] = $data['Contrasenia'];
                        $_SESSION['Email'] = $data['Mail'];
                        $_SESSION['UltIngreso'] = $fechaActual;
                        $_SESSION['Ingreso'] = $data['Ingreso'];
                        $_SESSION['FechaCambioContras'] = $data['FechaCambioContras'];

                        //Actualizo la fecha del último ingreso
                        require_once 'funciones/guardarDocente.php';
                        $rs = guardarUltConexionDocente($MiConexion, $IdUsuario);
                        //Verifico cuánto tiempo pasó desde el último cambio de contraseña
                        $fechaAct = date('Y-m-d');
                        $contador = date_diff($_SESSION['FechaCambioContras'], $fechaAct);

                        //Si nunca ingresó o superó los 200 días desde que cambió por ultima vez su contraseña, lo envío a cambiarla, sino, lo envio al index
                        if ($_SESSION['Ingreso'] == 0 || $contador > 200) {
                          header('Location: cambiarContrasenia.php');
                        } else {
                          header('Location: index.php');
                        }

                        exit;
                      } else {
                        //Si los datos que ingresó no se encuentran en la base de datos, le informo que son incorrectos

                    ?>
                        <div class="bs-component">
                          <div class="alert alert-dismissible alert-danger">
                            <strong>
                              <center>¡ Datos incorrectos !
                            </strong>
                          </div>
                        </div>
                  <?php }
                    }
                  }
                  ?>

                  <div class="form-group">

                    <input class="form-control" placeholder="***@escuelasproa.edu.ar" name="email" type="email" required="requerido" autofocus>
                  </div>
                  <div class="form-group">
                    <input class="form-control" placeholder="Contraseña" name="password" type="password" required="requerido" value="">
                  </div>

                  <center>
                    <button type="submit" class="btn btn-primary" value="Login" name="BotonLogin" "><box-icon class=" border border-secondary border-3 rounded-circle" name="user-check" type="solid" size="md" color="white" animation="tada"></box-icon> Ingresar</button>
                  </center>
                </fieldset>
              </form>
              <br>
              <center><a href="Recuperar.php">&#191Olvid&oacute su contrase&ntildea?</a></center>
            </div><!--fin panel body-->
          </div>
        </div>
      </div>
    </div>

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
<?php } else {
  header("Location: {$URL}");
  exit;
} ?>