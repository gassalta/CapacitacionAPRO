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
<html lang="en">

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
<?php
    //Listo los docentes
        require_once 'funciones/listarDocentes.php';
        $Listado=array();
        $Listado = Listar_Docentes($MiConexion);
        $CantidadDocentes = count($Listado);
?>
    <div class="row">
      <div class="col-md-12">
        <div class="tile">
            <h2 class="tile-title" ><font color="#85C1E9"><center><b>Listado  de Docentes </b> (<?php echo $CantidadDocentes; ?>)</font></h2>
		</div>
		 <div class="clearfix"></div>
            <div class="table-responsive">
              <table class="table-sm table-striped table-bordered bg-info">
                <thead>
                  <tr class="bg-primary">
                    <th> N°</th>
                    <th> Apellido</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Nro Legajo</th>
                    <th>Título</th>
                    <th>Fecha Escalafón</th>
                    <th>Categoría</th>
                    <th>E-mail</th>
                    <th>Último Ingreso</th>

                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de los docentes
                            for ($i=0; $i < $CantidadDocentes; $i++) { ?>
                                <tr class="table-info">
                                    <th scope="row"><?php echo $Listado[$i]['ID']; ?></th>
                                    <td><?php echo $Listado[$i]['APELLIDO']; ?></td>
                                    <td><?php echo $Listado[$i]['NOMBRE']; ?></td>
                                    <td><?php echo $Listado[$i]['DNI']; ?></td>
                                    <td><?php echo $Listado[$i]['FECHANACIM']; ?></td>
                                    <td><?php echo $Listado[$i]['NROLEGAJOJUNTA']; ?></td>
                                    <td><?php echo $Listado[$i]['TITULO']; ?></td>
                                    <td><?php echo $Listado[$i]['FECHAESCALAFON']; ?></td>
                                    <td><?php echo $Listado[$i]['CATEGORIA']; ?></td>
                                    <td><?php echo $Listado[$i]['MAIL']; ?></td>
                                    <td><?php echo $Listado[$i]['ULTINGRESO']; ?></td>
                                </tr> 
                         <?php   }
                        ?>
                        
                        
                     
                 
                </tbody>
              </table>
            </div>
         
        </div>
        <div class="clearfix"></div>
        
      </div>
      <div class="row">
        <div class="col-lg-12">
          <div class="panel panel-default">
            <div class="panel-body">
             <form role="form" method="post" align="center">
			 <div class="col-lg-2"></div>
             <div class="col-lg-2">
				<button class="btn btn-primary" type="submit" name="BuscarDocente" formaction="buscarUnDocente.php"><box-icon name="search-alt" type="solid" size="sm" color="white" animation="tada"></box-icon> Buscar</button></div>
			 <div class="col-lg-2">
				<button class="btn btn-primary" type="submit" name="ModificarDocente" formaction="modificarDocente.php"><box-icon  name="edit-alt" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Modificar</button></div>
			  <div class="col-lg-2">
				<button class="btn btn-primary btn" type="submit" name="DocenteNuevo" formaction="DocenteNuevo.php"><box-icon name="user-plus" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Nuevo</button></div>
			<div class="col-lg-2">
				<button class="btn btn-primary" type="submit" name="EliminarDocente" formaction="eliminarDocente.php"><box-icon  name="trash" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Eliminar</button></div>
			</form>
			</div>
           </div>
        </div>
      </div>
    </div>
    <!-- /#wrapper -->

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
