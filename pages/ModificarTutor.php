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

//Declaro variables
$mensaje='';
$TutorBuscado=array();
$TutorEncontrado = 0; //0-No - 1-Sí

require_once 'funciones/listarNacionalidades.php';
$ListadoNacionalidades = array();
$ListadoNacionalidades=Listar_Nacionalidades($MiConexion);
$CantNacionalidades = count($ListadoNacionalidades);
$idTutor = $_REQUEST['Cx'];
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
?>
<div id="page-wrapper">
 <div class="row">
	<div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Listado Padres, Madres, Tutores/as </b></font></h2>  </div></div>
  </div> <!-- /.row  titulo--><br>
<?php
//Listo los estudiantes
  require_once 'funciones/listarTutores.php';
  $Listado=array();
  $Listado = ListarTutores($MiConexion);
  $CantidadTutores = count($Listado);
?>
 <div class="row">
  <div class="col-lg-10">
  <div class="table-responsive">
   <table class="table table-striped table-bordered bg-info">
   <thead>
    <tr class="bg-primary">
     <th>N°</th>
     <th>Apellido y Nombre</th>
     <th>DNI</th>
     <th>Teléfono</th>
     <th>E-mail</th>
     <th>Teléfono Laboral</th>
     <th>Editar</th>
    </tr>
   </thead>
   <tbody>
<?php
   //Cargo a la tabla el listado de los estudiantes
   for($i=0; $i < $CantidadTutores; $i++)
	  { 
?>
        <tr class="table-info">
          <td><?php echo $Listado[$i]['ID']; ?></td>
          <td><?php echo $Listado[$i]['APELLIDO'].', '.$Listado[$i]['NOMBRE']; ?></td>
          <td><?php echo $Listado[$i]['DNI']; ?></td>
          <td><?php echo $Listado[$i]['TELEFONO']; ?></td>
          <td><?php echo $Listado[$i]['MAIL']; ?></td>
          <td><?php echo $Listado[$i]['TELTRABAJO']; ?></td>
<?php 
		  echo'<td><a href="ModificarTutorBase.php?Cx='.$Listado[$i]['ID'].'"><box-icon  name="edit-alt" size="md" color="#005eff" animation="tada-hover"></box-icon><b></a></td> ';
          echo '</tr>'; 
       }
?>
    </tbody>
   </table>
   </div><!-- fin tabla-->
   <div class="row"align="center">
   <div class="col-lg-4"></div>
  <form action="<?php echo 'ModificarEstudiante.php?Cx='.$idEstud; ?>" method="post">
   
     <div class="col-lg-4"><button formaction="<?php echo 'ModificarEstudiante.php?Cx='.$idEstud; ?>" type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"  ><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div> </form>    
     </div>   
 
           
            
    
   </div>   <!-- fin col principal-->  
 </div><!-- fin row principal-->
    </div> <!-- fin page-wrapper -->
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
