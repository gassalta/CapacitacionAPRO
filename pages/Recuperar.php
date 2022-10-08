<?php
session_start();
date_default_timezone_set("America/Argentina/Buenos_Aires");


 require("/funciones/baseDeDatos.php");
 require("/includes/e_mail.php");

// $Mail=$_POST['mail'];
 //$Dni=$_REQUEST['dni'];
 //$U=$_REQUEST['radio1'];
 $codigo=rand(1000,9999);

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
    <meta name="author" content="Berigozzi-Benicio-Bradaschia">
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
                    <!--<div class="panel-heading">
                        <h3 class="panel-title">Ingreso</h3>
                    </div>-->
                    <div class="panel-body">
                        <form role="form" method="post">
                            <fieldset>
 <?php
            if (!empty($_POST['Cancelar']))
			{
				 header('Location: login.php');
				 exit;
				
			}
                            //Verifico si el usuario presiono el boton login
            if (empty($_POST['Recuperar']))
				{ 
?>
					<div class="bs-component"><div class="alert alert-dismissible alert-info"> <strong><center>RECUPERAR CLAVE</strong></div></div>
 <?php  
				} 
			else 
				{ 
				 $usu = $_POST['email'];
                 $dni = $_POST['DNI'];
				 //Verifico si los campos están completos
				if($usu != "" && $_POST['DNI'] != null)
				  {
				   $resultado=mysqli_num_rows(selectDocenteMail($dni,$usu));
					if ($resultado==0)
					{
?>
					<div class="bs-component"><div class="alert alert-dismissible alert-danger"><strong><center>¡ Datos incorrectos !</strong> </div></div>

<?php
					}
					else
					{
					 clave_mail($usu,$codigo);	
                     UpdatePasword($codigo,$dni);	
					 echo'<div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>Verifique su e-mail para restablecer su cuenta</strong><br><br>
					 </div></div>';
					 	
					}
          


                 } else
					 { 
?>
					<div class="bs-component"><div class="alert alert-dismissible alert-danger"> <strong><center>Debe completar ambos campos</strong></div></div>
 <?php  
				} 
					 
				}
?> 
                <div class="row">
				 <div class="col-lg-12"><div class="form-group"><input class="form-control" placeholder="***@escuelasproa.edu.ar" name="email" type="email"  autofocus ></div></div>
				</div>
				<div class="row">
				 <div class="col-lg-12"><div class="form-group"><input class="form-control" placeholder="DNI" name="DNI" type="text"  autofocus ></div></div>
				 </div>
			
                <div class="row" align="center">
				 <div class="col-lg-6"><button type="post" class="btn btn-primary" value="Recuperar" name="Recuperar" ><box-icon class="border border-secondary border-3 rounded-circle" name="user-check" type="solid" size="md" color="white" animation="tada"></box-icon> Recuperar</button>
                  </div>            
                <div class="col-lg-6"><button type="post" class="btn btn-danger" value="Cancelar" name="Cancelar" ><box-icon name="arrow-back" type="solid" size="md" color="white" animation="tada-hover"></box-icon>Retornar</button></div>
				</div>
						     </fieldset>
                        </form>
                </div> <!-- fin panel body -->
            </div> <!-- fin panel primary -->
        </div> <!-- fin col principal -->
    </div> <!-- fin row principal-->
	</div> <!-- fin container-->

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
