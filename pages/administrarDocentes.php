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
$_SESSION['DNIDocenteElegido'] = "";
$_SESSION['Envia'] = "";
$_SESSION['IdDElegido'] = "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
<?php
    require_once 'encabezado.php';
?>
   <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
   <link href="estilos.css" rel="stylesheet"  type="text/css"  />
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
		<div class="col-lg-12"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Nómina de Docentes</b></font></h2>  </div></div>
	</div> <!-- /.row  titulo--><br>
  
<?php
   //Listo los docentes
 require_once 'funciones/listarDocentes.php';
 $Listado=array();
 $Listado = Listar_Docentes($MiConexion);
 $CantidadDocentes = count($Listado);
?>

  <div class="row">
    <div class="col-lg-12">
     <div class="table-responsive">
       <table class="table table-striped table-bordered bg-info">
         <thead>
          <tr class="bg-primary">
           <th>N°</th>
           <th>Apellido y Nombre</th>
           <th>DNI</th>
           <th>Legajo</th>
           <th>Título</th>
           <th>Categoría</th>
           <th>Ver</th>
		   <th>Editar</th>
		   <th>Eliminar</th>
		  </tr>
         </thead>
         <tbody>
<?php
        //Cargo a la tabla el listado de los docentes
         for($i=0; $i < $CantidadDocentes; $i++)
		    { 
?>
             <tr class="table-info">
             <td><?php echo $Listado[$i]['ID']; ?></td>
             <td><?php echo $Listado[$i]['APELLIDO'].', '.$Listado[$i]['NOMBRE']; ?></td>
             <td><?php echo $Listado[$i]['DNI']; ?></td>
             <td><?php echo $Listado[$i]['NROLEGAJOJUNTA']; ?></td>
             <td><?php echo $Listado[$i]['TITULO']; ?></td>
             <td><?php echo $Listado[$i]['CATEGORIA']; ?></td>
<?php
			echo'<td><a href="buscarUnDocente.php?Cy=0&Cx='.$Listado[$i]['ID'].'"><box-icon  name="show-alt"  size="md" color="#005eff" animation="tada-hover"></box-icon><b></a></td> ';
				echo'<td><a href="modificarDocente.php?Cx='.$Listado[$i]['ID'].'"><box-icon  name="edit-alt" size="md" color="#005eff" animation="tada-hover"></box-icon><b></a></td> ';
				echo'<td><form name="eliminar" method="post" action="eliminarDocente.php?Cx='.$Listado[$i]['ID'].'">'?><button class="btn btn-danger btn-circle" type="submit"  name="eliminar"  onclick="return confirm ('¿Seguro que desea eliminarlo?')"><box-icon  name="trash"  size="md" color="white" animation="tada-hover"></box-icon></button></form></td>                      
             </tr> 
<?php 
			}
?>
			<tr>
			 <td colspan="9"><b><a href="DocenteNuevo.php"><box-icon  name="plus-circle" type="solid" size="md""  color="#005eff" animation="tada-hover"></box-icon> Registrar un nuevo Docente</b></a></td>
			</tr>
                        
                        
                     
                 
                </tbody>
              </table>
            </div><!-- fin tabla-->
         
        </div><!-- fin col tabla-->
       
        
      </div> <!-- fin row tabla-->

  </div> <!-- page- wrapper -->
</div> <!-- /#wrapper -->
   

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
