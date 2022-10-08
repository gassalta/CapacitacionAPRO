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
$_SESSION['DNIEstudianteElegido'] = "";
$_SESSION['IdEstudianteSeleccionado'] = "";
$_SESSION['IdEElegido'] = "";
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
		<div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Listado de Estudiantes</b></font></h2>  </div></div>
	</div> <!-- /.row  titulo--><br>
            
    <?php
    //Listo los estudiantes
        require_once 'funciones/listarEstudiantes.php';
        $Listado=array();
        $Listado = Listar_Estudiantes($MiConexion);
        $CantidadEstudiantes = count($Listado);
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
                    
                    
                    <th>Curso</th>
					<th>Ver</th>
                    <th>Editar</th>

                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de los estudiantes
                            for ($i=0; $i < $CantidadEstudiantes; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $Listado[$i]['ID']; ?></td>
                                  
                                    <td><?php echo $Listado[$i]['APELLIDO']; echo', '; echo $Listado[$i]['NOMBRE']; ?></td>
                                
                                    <td><?php echo $Listado[$i]['DNI']; ?></td>
                                   
                                   
                                    <td><?php echo $Listado[$i]['ANIO']; ?> <?php echo $Listado[$i]['DIVISION']; ?></td>
<?php
									echo'<td><a href="buscarUnEstudiante.php?Cx='.$Listado[$i]['ID'].'"><box-icon  name="show-alt"  size="md" color="#005eff" animation="tada-hover"></box-icon><b></a></td> ';
									echo'<td><a href="ModificarEstudiante.php?Cx='.$Listado[$i]['ID'].'"><box-icon  name="edit-alt" size="md" color="#005eff" animation="tada-hover"></box-icon><b></a></td></tr> ';
                          }
?>	
					<tr>
			 <td colspan="6"><b><a href="EstudianteNuevo.php"><box-icon  name="plus-circle" type="solid" size="md""  color="#005eff" animation="tada-hover"></box-icon> Registrar un Nuevo Estudiante</b></a></td>
			</tr>
				</tbody>
              </table>
            </div>
          </div>
        </div><!--row tabla-->
      
              
               
        </div><!-- /#page-wrapper -->
            
    </div>   <!-- /#wrapper -->
        


 

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
