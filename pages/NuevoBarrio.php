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

//Listo las áreas
require_once 'funciones/listarBarrios.php';
$ListadoBarrios = array();
$ListadoBarrios=Listar_Barrios($MiConexion);
$CantBarrios = count($ListadoBarrios);

//Declaro variables
$mensaje='';
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
?>
<div id="page-wrapper">
 <div class="row" align="center">
  <div class="col-lg-8"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Registrar un Barrio</b></font></h2>  </div></div>
 </div> <!-- /.row titulo --><br>
 <div class="row">
  <div class="col-lg-8">
   <div class="panel panel-primary">
     <div class="panel-heading"></div><br>
     <div class="panel-body">
       <div class="row">
         <div class="col-lg-12">
          <form role="form" method="post">
<?php 
           //Si cancela vuelvo a EstudianteNuevo
           if(!empty($_POST['Cancelar']))
			{
             header('Location: EstudianteNuevo.php');
            }
			//Si confirma verifico los campos
           if(!empty($_POST['Confirmar'])) 
		    {
             $mensaje = '';
             if(empty($_POST['Nombre']))
			   {
                $mensaje = 'Debe completar el nombre del barrio';
               } 
            if($mensaje=='')
			  {
               //Si está todo bien creo area nuevo en base de datos, sino, muestro mensaje
               require_once 'funciones/guardarBarrio.php';
               if(barrioNuevo($MiConexion,$_POST['Nombre']))
				{
?>
                  <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡ Barrio guardado exitosamente !</center></strong></div></div>
<?php  
				}
                                                        
               }
			 else 
			   {
?>
                 <div class="alert alert-dismissible alert-danger"><strong><center><?php echo $mensaje;?></center></strong></div>
<?php 
                }

			} 
?>
            </div>
			</div><!--fin row errores--><br>
			 <div class="row" align="center">
              <div class="form-group">
			    <div class="col-lg-1"></div>
				<div class="col-lg-2"><label>Nombre del Barrio </label></div>
                <div class="col-lg-8"><input class="form-control" name="Nombre" value="<?php echo !empty($_POST['Nombre']) ? $_POST['Nombre'] : ''; ?>"></div>
			  </div>
			 </div><!--fin row input--><br><br>
              <div class="row" align="center">                          
                 <div class="col-lg-2"></div>                      
                  <div class="col-lg-4"><button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar"onClick="return confirm ('¿Desea guardar el nuevo barrio?');"><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
                  <div class="col-lg-4"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div>
			  </div><!--fin row botones-->
     
           </div>    <!--  fin panel-body -->
         </div>  <!-- fin panel primary -->
       </div>  <!-- fin col primary -->
      </div>  <!-- fin row primary --> 
  </div><!-- fin page-wrapper -->
    </div> <!-- fin wrapper -->
 
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
