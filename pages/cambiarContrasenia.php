<?php
session_start();
//Creo la conexion con la base de datos
require_once 'funciones/conexion.php';
$MiConexion=ConexionBD();
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistema de Gestión de Calificaciones">
    <meta name="author" content="Mónica Berigozzi">
   <link rel="icon"   href="images/Logo sin fondo.png" type="image/png" />
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
                <div class="login-panel panel panel-primary">
                    
                    <div class="panel-body">
                        <form role="form" method="post">
                            <fieldset>
                                <?php
                                if (empty($_POST['Aceptar'])) { 
                                  ?>
                                      <div class="bs-component">
                                    <div class="alert alert-dismissible alert-info">
                                      <strong><center>Complete los campos</center></strong>
                                    </div>
                                     </div>
                                <?php  } else {
                                    //Si el usuario presiono el botón Aceptar tomo las contraseñas ingresadas
                                    $contraseniaAnterior = $_POST['passwordAct'];
                                    $contraseniaNueva = $_POST['passwordNueva'];
                                    require_once 'funciones/buscarDocente.php';
                                    $Doc = buscarDocente($MiConexion,$_SESSION['Id']);
                                    //Verifico que realmente haya cambiado la contraseña, que haya repetido correctamente la contraseña nueva y que no haya dejado campos vacíos
                                    if($contraseniaAnterior==$Doc['CONTRASENIA'] && $contraseniaAnterior!=$contraseniaNueva && $contraseniaNueva==$_POST['passwordNueva1'] && strlen($contraseniaNueva)>=8 && $_POST['passwordAct']!= null && $_POST['passwordNueva'] =! null && $_POST['passwordNueva1'] != null){
                                        //Actualizo la base de datos
                                        $id = $_SESSION['Id'];
                                        $SQL = "UPDATE dbcalificacionesproa.docentes SET Contrasenia='$contraseniaNueva', Ingreso=1, FechaCambioContras=NOW() WHERE docentes.Id='$id'";
                                        $rs = mysqli_query($MiConexion, $SQL);
                                        header('Location: index.php');
                                        exit;
                                    } else {
                                        ?>
                  <div class="bs-component">
                    <div class="alert alert-dismissible alert-danger">
                      <strong><center>Datos incorrectos.</center></strong>
                    </div>
                  </div>
                <?php
            } 
        }?>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Contraseña actual" name="passwordAct" type="password" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Contraseña nueva" name="passwordNueva" type="password" value="">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Repetir contraseña nueva" name="passwordNueva1" type="password" value="">
                                </div>
                                <div class="alert alert-dismissible alert-danger">
                  <center>* La contraseña nueva debe tener un mínimo de ocho caracteres y ser diferente a la anterior</center>
                </div>
                <center>
								 <button type="submit" class="btn btn-primary" value="Login" name="Aceptar" ><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon>Confirmar</button>
                                
                                </center>
                                
                            </fieldset>
                        </form>
                    </div>
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