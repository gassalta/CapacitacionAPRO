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
        <!-- <div class="panel-heading"><h3 class="panel-title"><center>Cambiar Contraseña</center></h3></div>-->
         <div class="panel-body">
           <form role="form" method="post">
             <fieldset>
<?php
             if(empty($_POST['Aceptar'])) 
			   { 
?>
                <div class="row">
				<div class="col-lg-12">
                <div class="bs-component"><div class="alert alert-dismissible alert-info"><strong><center>Complete los campos</strong></div></div>
				</div></div>
				
<?php 
			   } 
			 else {
                    //Si el usuario presiono el botón Aceptar tomo las contraseñas ingresadas
                    $contraseniaAnterior = $_POST['passwordAct'];
                    $contraseniaNueva = $_POST['passwordNueva'];
                    //Verifico que realmente haya cambiado la contraseña, que haya repetido correctamente la contraseña nueva y que no haya dejado campos vacíos
                   if($contraseniaAnterior!=$contraseniaNueva && $contraseniaNueva==$_POST['passwordNueva1'] && strlen($contraseniaNueva)>=8 && $_POST['passwordAct']!= null && $_POST['passwordNueva'] =! null && $_POST['passwordNueva1'] != null)
				    {
                      //Actualizo la base de datos
                      $id = $_SESSION['Id'];
                      $SQL = "UPDATE dbcalificacionesproa.docentes SET Contrasenia='$contraseniaNueva', Ingreso=1, FechaCambioContras=NOW() WHERE docentes.Id='$id'";
                      $rs = mysqli_query($MiConexion, $SQL);
                      header('Location: InformacionPersonal.php');
                      exit;
                    } 
				   else
					  {
?>
                        <div class="row">
				        <div class="col-lg-12">
                       <div class="bs-component"><div class="alert alert-dismissible alert-danger"><strong><center>Datos incorrectos </center></strong></div></div></div></div>
<?php
                      } 
                   }
?>
						<div class="row">
				        <div class="col-lg-12">
                                <div class="form-group">
                                    <input class="form-control" placeholder="Contraseña actual" name="passwordAct" type="password" autofocus>
                                </div> </div> </div>
								<div class="row">
				        <div class="col-lg-12">
                                <div class="form-group">
                                    <input class="form-control" placeholder="Contraseña nueva" name="passwordNueva" type="password" value="">
                                </div> </div> </div>
								<div class="row">
				        <div class="col-lg-12">
                                <div class="form-group">
                                    <input class="form-control" placeholder="Repetir contraseña nueva" name="passwordNueva1" type="password" value="">
                                </div> </div> </div>	<br>
                                <center>
										<div class="row" align="center">
							
				        <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary" value="Login" name="Aceptar" ><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button>
								</div>
							 </form>	
					    
						      <form action="InformacionPersonal.php" method="post">
							  <div class="col-lg-6">
                                        <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"  ></box-icon> Retornar</button></div></form>
                             
																</div>	
                                </center>
                            </fieldset>
                       
                    </div><!-- fin panel body-->
                </div><!-- fin panel primary-->
            </div><!-- fin col principal-->
        </div><!-- fin row principal-->
    </div><!-- fin conteiner-->

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