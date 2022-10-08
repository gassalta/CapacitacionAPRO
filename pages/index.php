<?php
//Configuro la zona horaria local
date_default_timezone_set("America/Argentina/Buenos_Aires");
//Verifico que haya una sesión abierta
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
$_SESSION['DNIEstudianteElegido'] = "";
$_SESSION['IdEstudianteSeleccionado'] = "";
$_SESSION['IdEElegido'] = "";
$_SESSION['IdEspCurrSeleccionado'] = "";
$_SESSION['IdECElegido'] = "";
$_SESSION['DNIDocenteElegido'] = "";
$_SESSION['Envia'] = "";
$_SESSION['IdDElegido'] = "";
$_SESSION['IdCursoSeleccionado'] = "";
$_SESSION['AnioCursoSeleccionado'] = "";
$_SESSION['DivisionCursoSeleccionado'] = "";
$_SESSION['IdCursoElegido'] = "";
$_SESSION['CursoEleg'] = "";
$_SESSION['EstudEleg'] = "";
$_SESSION['CursoEle'] = "";
$_SESSION['EstudEle'] = "";
$_SESSION['InstanciaEle'] = "";
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <?php
    //llamo al encabezado
        require_once 'encabezado.php';
    ?>
   <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
    <script src="includes/jquery-3.3.1.min.js"></script>
	<script src="includes/plotly-latest.min.js"></script>
</head>

<body>

    <div id="wrapper">

                <?php
                //Llamo a los menúes y funciones necesarias
                require_once 'top.php';
                require_once 'menuDerecho.php';
                require_once 'funciones/DatosUsuario.php';
                require_once 'menuLateral.php';
            ?>

        <div id="page-wrapper">
         <div class="row">
			<div class="col-lg-1"></div>
			<div class="col-lg-1">
			   <box-icon class="border border-secondary border-3 rounded-circle" name="bell" type="solid" size="lg" color="#3498DB" animation="tada"></box-icon>
			</div>
            <div class="col-lg-8">
              <h1 class="page-header"align="center"><font color="#3498DB"><strong>Novedades PRoA</strong></font></h1>
			</div>
			<div class="col-lg-1">
				<box-icon class="border border-secondary border-3 rounded-circle" name="bell" type="solid" size="lg" color="#3498DB" animation="tada"></box-icon>
			</div>
			<div class="col-lg-1"></div>
           </div>
         <div class="row">
             <div class="col-lg-12">
                <div class="panel panel-primary">
                  <div class="panel-heading">
						<p>Se informa que el receso de invierno será desde el día 11 al 22 de Julio, retomando las actividades escolares el día lunes 25 de Julio. Mientras dure el receso, la Escuela permanecerá cerrada y es posible que se realicen tareas de mantenimiento en los servidores.</p>
						<p><center>Solicitamos por favor no cargar nueva información en el sistema durante esos días.</p><br>
						<p><b><center>¡Muchas gracias y Felices Vacaciones!</center></p><br>
<?php /*
 if($_SESSION ['Categoria']=='Coordinador/a'){
?>						
	<div class="row" align="center">
		<div class="col-lg-6">
			<div id="cargaTorta"></div></div>
		<div class="col-lg-6">
			<div id="cargaLineal"></div></div>
	</div>
					</div>	
 <?php }	*/?>					
				</div>		
					</div>
                </div>
			  </div>
          </div>
		</div>
	</div>

    <!-- jQuery ** permite colapsar y despleagar los menú** -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript ** permite colapsar y despleagar los menú** -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript *** afecta a la lista lateral-->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript ***GRAFICOS????***
    <script src="../vendor/raphael/raphael.min.js"></script>
    <script src="../vendor/morrisjs/morris.min.js"></script>
    <script src="../data/morris-data.js"></script>-->

    <!-- Custom Theme JavaScript ***afecta a la lista lateral-->
    <script src="../dist/js/sb-admin-2.js"></script>
   <script type="text/javascript">
	$(document).ready(function(){
		$('#cargaLineal').load('estadisticasAprendizajes.php');
		$('#cargaTorta').load('graficoTortaEstudiantesReprobados.php');
	});
</script>
</body>

</html>
