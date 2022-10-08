<?php
session_start();
if(empty($_SESSION['Nombre'])) {
  header('Location: cerrarSesion.php');
  exit;
}
require_once 'funciones/controlTiempoSesion.php';
if (tiempoCumplido()) {
    header('Location: cerrarSesion.php');
    exit;
}
require_once 'funciones/conexion.php';
require_once 'funciones/baseDeDatos.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<?php
    require_once 'encabezado.php';
?>
<script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
</head>
<body>
	<div id="wrapper">
<?php
    require_once 'top.php';
    require_once 'menuDerecho.php';
    require_once 'funciones/DatosUsuario.php';
    require_once 'menuLateral.php';
	$Contenido=$_REQUEST['Cx'];
	$NContenido=$_REQUEST['Cn'];
	$SqlContenido = consultaEspacioContenidosAprendizajes($Contenido);
	$EspacioCurricular=$_REQUEST['Ec'];
	
?>
	<div id="page-wrapper">
     <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Listado Aprendizajes de <?php echo '"'.$NContenido.'"'; ?></h3>

			<div class="table-responsive">
              <table class="table table-striped  table-hover bg-info">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>APRENDIZAJE</th>
					<th>MODIFICAR</th>
					<th>ELIMINAR</th>
				  </tr>
                </thead>
                <tbody>
<?php
			if (mysqli_num_rows($SqlContenido)==0){
				echo"<tr><td colspan='12'>No existen Aprendizajes para ese contenido <tr>";
					 }
			else {
				  while($Fila=mysqli_fetch_array($SqlContenido))
		  			 {
						$Id=$Fila['id'];
						
						$NombreAprendizaje=$Fila['nombreAprendizaje'];	
					    $EstadoAprendizaje=$Fila['estadoAprendizaje'];
						if($EstadoAprendizaje==0){
						echo"<tr>";
						echo"<td>".$Id;
						echo"<td>".$NombreAprendizaje;
						
						echo'<td><a href="modificarAprendizaje.php?Tx=M&Cx='.$Id.'&Ec='.$EspacioCurricular.'"><box-icon name="edit-alt" type="solid" size="md" color="black" animation="tada-hover"></box-icon></a>';
						
						echo'<td><a href="modificarAprendizaje.php?Tx=E&Cx='.$Id.'&Ec='.$EspacioCurricular.'"><box-icon name="trash" type="solid" size="md" color="#black" animation="tada-hover"></box-icon></a>';
						echo"</tr>";
						}
					 }
				}
					  
?>
              </tbody>
              </table>
            </div>
			<table class="table">
			<tbody ><tr></tr>
			<tr><td colspan="5" align="right">
			<form name="contact" method="post"<?php echo'action="ModificarAprendizaje.php?Tx=A&Cx='.$Contenido.'&Ec='.$EspacioCurricular.'">'?> 
						<button type="submit" class="btn btn-primary"><box-icon name="list-plus" type="solid" size="md" color="white" animation="tada-hover"></box-icon> Nuevo</button></form></td>
					<td colspan="2"></td>
			<td colspan="5" align="left"><form name="contact" method="post" action="administrarAprendizajes.php?Cx=<?php echo $EspacioCurricular; ?>">
						<button type="submit" class="btn btn-primary"><box-icon name="left-arrow-circle" type="solid" size="md" color="white" animation="tada"></box-icon> Otra consulta</button></form></td>
			</tr></tbody>
						
			</table>
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