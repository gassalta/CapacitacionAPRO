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
     <div class="col-lg-12"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Gráfico de Estudiantes en Riesgo</b></font></h2></div>
   </div><br>  <!-- fin row  titulo-->

	<div class="row">
     <div class="col-lg-12">
      <div class="panel panel-primary">
        <div class="panel-heading"> </div><br>
        <div class="panel-body">
          
              <form role="form" method="post">
<?php 
               //Si cancela vuelvo a administrarAsistencias
               if(!empty($_POST['Cancelar']))
				{
                  header('Location: index.php');
                } 
?>
			  <div class="form-group">
				<div class="row">
                
					  <div class="row"align="center">
					  	
			
			<div class="col-lg-12">
				<?php
require_once 'graficoTortaEstudiantesReprobados.php';

					  	?>
				<div id="cargaTorta"></div>
			</div><!--col asistencias-->
			
			</div>
			
         </div><!--fin panel body--><br><br>

	  
 					<div class="row" align="center">
						

						<div class="col-lg-12">
                          <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"  onclick="return confirm ('Seguro que desea cancelar?')"><box-icon  name="arrow-back"  size="sm" color="white" animation="tada"></box-icon> Retornar</button>
                         </div>
   
                          </div><!-- /.row botones -->
                          
                      
                   
           </div><!-- /.panel body--> 
          
        </div> <!-- /.panel primary-->
        </div>
		</div>   <!-- /FIN row primary -->    
        </div>  <!-- /#page-wrapper -->
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
	$(document).ready(function(){
		//$('#cargaGrafico').load('graficobarras.php');
		$('#cargaTorta').load('graficoTortaEstudiantesReprobados.php');
		//$('#prueba').load('estadisticasAprendizajes.php');
	});
</script>


</body>

</html>
