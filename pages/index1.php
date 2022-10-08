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
                <div class="panel panel-default">
                   <div class="panel-heading">
						<p>Los días 25 y 26 de Mayo la Escuela permanecerá cerrada, es posible que durante esos días se realicen tareas de mantenimiento en los servidores.</p>
						<p>Solicitamos por favor no cargar nueva información en el sistema durante esos días.</p><br>
						<p><b>¡Muchas gracias!</p>
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

</body>

</html>
