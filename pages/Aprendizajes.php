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
<link href="estilos.css" rel="stylesheet"  type="text/css"  />
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
	  <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Listado Aprendizajes de <?php echo '"'.$NContenido.'"'; ?></b></font></h2>  </div></div>
	  </div><br>
     <div class="row">
        <div class="col-lg-10">
         <div class="table-responsive">
              <table class="table table-striped table-bordered table-hover bg-info">
                <thead>
                  <tr class="bg-primary">
                    <th>N°</th>
                    <th>Aprendizaje</th>
					<th>Modificar</th>
					<th>Eliminar</th>
				  </tr>
                </thead>
                <tbody>
<?php
				if(mysqli_num_rows($SqlContenido)==0)
				  {
				   echo"<tr align='center'><td colspan='12'>No existen Aprendizajes para ese contenido </td></tr>";
				   echo'<tr>
					 <td colspan="6"><b><a href="ModificarAprendizaje.php?Tx=A&Cx='.$Contenido.'&Ec='.$EspacioCurricular.'"><box-icon  name="plus-circle" type="solid" size="md"  color="#005eff" animation="tada-hover"></box-icon> Registrar un nuevo Apendizaje</b></a></td></tr>';
				  }
				else 
				  {
				   while($Fila=mysqli_fetch_array($SqlContenido))
		  			{
						$Id=$Fila['id'];
						$NombreAprendizaje=$Fila['nombreAprendizaje'];	
					    $EstadoAprendizaje=$Fila['estadoAprendizaje'];
						if($EstadoAprendizaje==0)
						 {
							echo"<tr>";
							echo"<td><font color='#005eff'>".$Id;
							echo"<td><font color='#005eff'>".$NombreAprendizaje;
							echo'<td>
								 <a href="modificarAprendizaje.php?Tx=M&Cx='.$Id.'&Ec='.$EspacioCurricular.'"><box-icon name="edit-alt" type="solid" size="md" color="#005eff" animation="tada-hover"></box-icon></a>';
				?>
						    <td>
							
							<form name="eliminar" method="post"<?php echo'action="modificarAprendizaje.php?Tx=E&Cx='.$Id.'&Ec='.$EspacioCurricular.'">'?>
						       <button class="btn btn-danger btn-circle" type="submit"  name="eliminar"  onclick="return confirm ('¿Seguro que desea eliminarlo?')"><box-icon  name="trash"  size="md" color="white" animation="tada-hover"></box-icon></button></form></td>
							</tr>
							<?php
						}
					}
?>
					 <tr>
					 <td colspan="6"><b><a <?php echo'href="ModificarAprendizaje.php?Tx=A&Cx='.$Contenido.'&Ec='.$EspacioCurricular.'"><box-icon  name="plus-circle" type="solid" size="md"  color="#005eff" animation="tada-hover"></box-icon> Registrar un nuevo Aprendizaje</b></a></td></tr>';
				}
					  
?>
              </tbody>
              </table>
            </div>
			<div class="row" align="center">
			 <div class="col-lg-12">
			 <form name="contact"  action="administrarContenidosYAprendizajes.php?Cx=<?php echo $EspacioCurricular;?> "method="post">
						<button type="submit" class="btn btn-danger"><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada"></box-icon> Retornar</button><br></form><br>
						</div></div>
		
         
    </div><!--fin col principal-->
   </div><!--fin row principal-->
     </div><!--fin page wrapper-->
    </div><!--fin wrapper-->

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