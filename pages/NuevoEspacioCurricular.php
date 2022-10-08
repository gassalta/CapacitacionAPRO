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
require_once 'funciones/listarAreas.php';
$ListadoAreas = Listar_Areas($MiConexion);
$CantAreas = count($ListadoAreas);

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
    <div class="row">
      <div class="col-lg-12"><div class="col-lg-12"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Nuevo Espacio Curricular</b></font></h2></div></div>
    </div><!-- row titulo --><br>
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-primary">
           <div class="panel-heading"><center>Complete los datos</center></div>
             <div class="panel-body">
               <div class="row">
                 <div class="col-lg-12">
                  <form role="form" method="post">
<?php 
                    //Si cancela vuelvo a administrarEspaciosCurriculares
                    if(!empty($_POST['Cancelar']))
					  {
                        //$accion = 0;
                        header('Location: administrarEspaciosCurriculares.php');
                       }
						//Si confirma verifico los campos
                    if(!empty($_POST['Confirmar']))
					  {
                        $mensaje = '';
                       if(empty($_POST['NombreEspCurr']) || empty($_POST['Area']))
						{
                         $mensaje = 'Debe completar los campos obligatorios';
                        } 
                       if($mensaje=='')
					    {
                         //Si está todo bien creo espacio curricular nuevo en base de datos, sino, muestro mensaje
                         require_once 'funciones/guardarEspacioCurricular.php';
                         if(espCurricNuevo($MiConexion,$_POST['NombreEspCurr'],$_POST['Area']))
						    {
?>
                             <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡Nuevo Espacio Curricular guardado!</center></strong></div></div>
<?php   
							}
                        }
      					else
						  {
?>
							<div class="alert alert-dismissible alert-danger"><strong><center><?php echo $mensaje; ?></center></strong></div>
<?php
                          }
						} 
?>
					</div><!--col errores-->				   
				</div><!--row errores--><br>
					<div class="row">
					
					  <div class="col-lg-6"> <label>Nombre Espacio Curricular *</label></div>
                  <div class="col-lg-5"> <label>Area *</label>   </div>
				    
					  <div class="col-lg-1"align="center"><label>Otra </label> </div>
					  </div>
				<div class="row" align="center">
			
                  <div class="col-lg-6">  
                    <div class="form-group">
                       <input class="form-control" name="NombreEspCurr" value="<?php echo !empty($_POST['NombreEspCurr']) ? $_POST['NombreEspCurr'] : ''; ?>">
                    </div>
				   </div>
				  <div class="col-lg-5">  					
                    <div class="form-group">
                       
					   <select class="form-control" name="Area" id="Area">
                         <option value="">Seleccione el área</option>
<?php 
                            $selected='';
                            for($i=0 ; $i < $CantAreas ; $i++)
							   {
                                if(!empty($_POST['Area']) && $_POST['Area'] ==  $ListadoAreas[$i]['ID'])
								  {
                                   $selected = 'selected';
                                   }
								else
								   {
                                    $selected='';
                                    }
?>
                          <option value="<?php echo $ListadoAreas[$i]['ID']; ?>" <?php echo $selected;?>><?php echo $ListadoAreas[$i]['DENOMINACION']; ?></option>
<?php 
							} 
?>
                       </select>
					    </div> </div>
					    <div class="col-lg-1" align="center"><abbr title="Aquí usted puede añadir un área que no se encuentre en el listado"><button type="submit" class="btn btn-primary btn-circle" value="NacionalidadNueva" name="NacionalidadNueva" formaction="NuevoArea.php"><box-icon  name="plus-circle" type="solid" size="sm" color="white" animation="tada-hover"></box-icon></button></abbr> </div>
					 
                   
				  
				</div><!-- /.row input --><br>
				<div class="row" align="center">
				  <div class="col-lg-12"> <div class="alert alert-dismissible alert-info"><center><b>Los campos marcados con  * son obligatorios</b></div></div>
			    </div><!-- /.row aviso --><br>
				<div class="row" align="center">
				  <div class="col-lg-3"></div> 
				  <div class="col-lg-3"> <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('¿Seguro desea guardar el nuevo Espacio Curricular?');"><box-icon  name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
				  <div class="col-lg-3"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" ><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div>
				 </div><!-- /.row botones -->
				
	       
        </div>        <!-- /.panel-body --> 
      </div>  <!-- /.panel principal-->
     </div>  <!-- /.col-principal -->
    </div>  <!-- /.row principal -->  
  </div><!-- /#page-wrapper -->
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
