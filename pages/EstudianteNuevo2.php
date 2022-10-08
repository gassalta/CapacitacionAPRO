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

require_once 'funciones/listarNacionalidades.php';
$ListadoNacionalidades = array();
$ListadoNacionalidades=Listar_Nacionalidades($MiConexion);
$CantNacionalidades = count($ListadoNacionalidades);

require_once 'funciones/listaCursos.php';
$ListadoCursos=array();
$ListadoCursos = ListarCursos($MiConexion);
$CantidadCursos = count($ListadoCursos);

require_once 'funciones/listarBarrios.php';
$ListadoBarrios = array();
$ListadoBarrios=Listar_Barrios($MiConexion);
$CantBarrios = count($ListadoBarrios);

require_once 'funciones/listarTutores.php';
$ListadoTutores = array();
$ListadoTutores=ListarTutores($MiConexion);
$CantTutores = count($ListadoTutores);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php
        require_once 'encabezado.php';
    ?>

   <link href="estilos.css" rel="stylesheet"  type="text/css" />

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
    <div class="col-lg-12"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Nuevo Estudiante </b></font></h2></div>
  </div> <!-- row titulo --><br>
            <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
        <div class="clearfix"></div>
        
      </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Complete los datos del nuevo estudiante 
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a administrarEstudiantes
                                            if (!empty($_POST['Cancelar'])) {
                                                
                                                header('Location: administrarEstudiantes.php');
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                                require_once 'funciones/verificarCamposEstudiante.php';
                                                require_once 'funciones/buscarEstudiante.php';
                                                $mensaje = '';
                                                $mensaje = verificarCamposEstud();
                                                if (!empty($mensaje)) {
                                                    $mensaje = $mensaje." - ";
                                                }
                                                $mensaje = $mensaje.estudianteExiste($MiConexion,$_POST['DNI']);
                                                
                                                //Si está todo bien creo estudiante nuevo en base de datos, sino, muestro mensaje
                                                if ($mensaje == '') {
                                                    require_once 'funciones/guardarEstudiante.php';
                                                        if (estudianteNuevo($MiConexion,$_POST['nroLegajo'],$_POST['nroLibroMatriz'],$_POST['nroFolio'],$_POST['Apellido'],$_POST['Nombre'],$_POST['DNI'],$_POST['telefono'],$_POST['Mail'],$_POST['Nacionalidad'],$_POST['escDeProcedencia'],$_POST['lugarNacim'],$_POST['fechaNacim'],$_POST['domicilio'],$_POST['Barrio'],$_POST['fechaPreinscripcion'],$_POST['Padre'],$_POST['Madre'],$_POST['Tutor'])) {
                                                            ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong><center>¡Estudiante nuevo guardado!</center></strong>
                </div>
              </div>
                                                    <?php    }
                                                        
                                                    } else {
                                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong><center><?php echo $mensaje; ?></center></strong>
                </div>
              </div> </div>                                  

             <?php
             
 }
                                       }  ?>
				<div class="row">
					
                    <div class="form-group">
					 <div class="col-lg-2"><label>Legajo</label></div>
					 <div class="col-lg-2"><input class="form-control" name="nroLegajo" value="<?php echo !empty($_POST['nroLegajo']) ? $_POST['nroLegajo'] : ''; ?>"></div>
				
                    <div class="col-lg-2"><label>Libro Matriz</label></div>
                    <div class="col-lg-2"><input class="form-control" name="nroLibroMatriz" value="<?php echo !empty($_POST['nroLibroMatriz']) ? $_POST['nroLibroMatriz'] : ''; ?>">
                    </div>
                     <div class="col-lg-2"><label>Folio</label></diV>
                     <div class="col-lg-2"><input class="form-control" name="nroFolio" value="<?php echo !empty($_POST['nroFolio']) ? $_POST['nroFolio'] : ''; ?>"></div>
					 </div>
				</div>
				<hr>
				<div class="row">						
                  <div class="form-group">
					<div class="col-lg-2"><label>Apellido*</label></div>
                     <div class="col-lg-4"><input class="form-control" required name="Apellido" value="<?php echo !empty($_POST['Apellido']) ? $_POST['Apellido'] : ''; ?>"></div>
                    <div class="col-lg-2"><label>Nombre*</label></diV>
                      <div class="col-lg-4"><input class="form-control" required name="Nombre" value="<?php echo !empty($_POST['Nombre']) ? $_POST['Nombre'] : ''; ?>"></div>
                   </div>
				</div><hr>
                <div class="row">	                       
				 <div class="form-group">
                  <div class="col-lg-2"><label>DNI*</label></div>
                  <div class="col-lg-4"><input class="form-control" required name="DNI" value="<?php echo !empty($_POST['DNI']) ? $_POST['DNI'] : ''; ?>"></div>
                  <div class="col-lg-2"><label>Telefono*</label></div>
                     <div class="col-lg-4"><input class="form-control" required name="telefono" value="<?php echo !empty($_POST['telefono']) ? $_POST['telefono'] : ''; ?>"></div>
				 </div>
				</div><hr>
				<div class="row">	
                  <div class="form-group">
                    <div class="col-lg-2"><label>E-mail*</label></div>
                    <div class="col-lg-4"><input class="form-control" required name="Mail" value="<?php echo !empty($_POST['Mail']) ? $_POST['Mail'] : ''; ?>"></div>
                    <div class="col-lg-2"><label>Nacionalidad*</label></div>
                    <div class="col-lg-4">
						<select class="form-control" required name="Nacionalidad" id="Nacionalidad">
                                                <option value="">Seleccione una nacionalidad</option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantNacionalidades ; $i++) {
                                                    if (!empty($_POST['Nacionalidad']) && $_POST['Nacionalidad'] ==  $ListadoNacionalidades[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoNacionalidades[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoNacionalidades[$i]['NACION']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" class="btn btn-primary" value="NacionalidadNueva" name="NacionalidadNueva" formaction="NuevaNacionalidad.php"><box-icon  name="flag-alt" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Agregar Nacionalidad</button>
                    </div>
					</div>
				</div><hr>
				<div class="row">
				  <div class="form-group">
					<div class="col-lg-2"><label>Escuela de Procedencia</label></diV>
                    <div class="col-lg-4"><input class="form-control" name="escDeProcedencia" value="<?php echo !empty($_POST['escDeProcedencia']) ? $_POST['escDeProcedencia'] : ''; ?>"></div>
                    <div class="col-lg-2"><label>Lugar de Nacimiento*</label></div>
                    <div class="col-lg-4"><input class="form-control" required name="lugarNacim" value="<?php echo !empty($_POST['lugarNacim']) ? $_POST['lugarNacim'] : ''; ?>"></div>
                   </div>
				</div><hr>
				<div class="row">
				 <div class="form-group">
				   <div class="col-lg-2"><label>Fecha de Preinscripcion</label></div>
                   <div class="col-lg-4"><input id="date" type="date" name="fechaPreinscripcion" value="<?php echo !empty($_POST['fechaPreinscripcion']) ? $_POST['fechaPreinscripcion'] : ''; ?>"></div>
                    <div class="col-lg-2"><label>Fecha de Nacimiento*</label></div>
                    <div class="col-lg-4"><input id="date" type="date" required name="fechaNacim" value="<?php echo !empty($_POST['fechaNacim']) ? $_POST['fechaNacim'] : ''; ?>"></div>
				 </div>
				</div><hr>
				<div class="row">
                   <div class="form-group">
                     <div class="col-lg-2"><label>Domicilio*</label></div>
                     <div class="col-lg-4"><input class="form-control" required name="domicilio" value="<?php echo !empty($_POST['domicilio']) ? $_POST['domicilio'] : ''; ?>"></div>
                     <div class="col-lg-2"><label>Barrio*</label></div>
                     <div class="col-lg-4"><select class="form-control" required name="Barrio" id="Barrio">
                        <option value="">Seleccione un barrio</option>
<?php 
                          $selected='';
                          for ($i=0 ; $i < $CantBarrios ; $i++)
							{
                               if (!empty($_POST['Barrio']) && $_POST['Barrio'] ==  $ListadoBarrios[$i]['ID'])
								  {
                                    $selected = 'selected';
                                  }
							   else 
									{
                                       $selected='';
                                    }
?>
								<option value="<?php echo $ListadoBarrios[$i]['ID']; ?>" <?php echo $selected; ?>  ><?php echo $ListadoBarrios[$i]['NOMBRE']; ?></option>
<?php 
							} ?>
                         </select>
						   <button type="submit" class="btn btn-primary" value="BarrioNuevo" name="BarrioNuevo" formaction="NuevoBarrio.php"><box-icon  name="building-house" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Agregar Barrio</button>
						 </div>
					</div>
				</div><hr>
				<div class="form-group">
                <div class="row">
                  <div class="col-lg-4">
					<label>Padre</label>
                    <select class="form-control" name="Padre" id="Padre">
                       <option value="">Seleccione una opción</option>
<?php 
                          $selected='';
                          for ($i=0 ; $i < $CantTutores ; $i++) 
							  {
								if (!empty($_POST['Padre']) && $_POST['Padre'] ==  $ListadoTutores[$i]['ID'])
									{
                                       $selected = 'selected';
                                    }
								else 
									{
                                      $selected='';
                                    }
?>
                                <option value="<?php echo $ListadoTutores[$i]['ID']; ?>" <?php echo $selected; ?>  ><?php echo $ListadoTutores[$i]['APELLIDO']." ".$ListadoTutores[$i]['NOMBRE']; ?>
                                   </option>
 <?php 
								} ?>
                    </select></div>
				 <div class="col-lg-4">
                    <label>Madre</label>
                    <select class="form-control" name="Madre" id="Madre">
                     <option value="">Seleccione una opción</option>
<?php 
                      $selected='';
                      for ($i=0 ; $i < $CantTutores ; $i++) 
						{
                         if (!empty($_POST['Madre']) && $_POST['Madre'] ==  $ListadoTutores[$i]['ID']) 
							{
                              $selected = 'selected';
                            }
						 else 
							{
                               $selected='';
                            }
?>
                         <option value="<?php echo $ListadoTutores[$i]['ID']; ?>" <?php echo $selected; ?>  ><?php echo $ListadoTutores[$i]['APELLIDO']." ".$ListadoTutores[$i]['NOMBRE']; ?></option>
<?php 
						} ?>
                    </select></div>
				<div class="col-lg-4">
                    <label>Tutor/a</label>
                    <select class="form-control" name="Tutor" id="Tutor">
                       <option value="">Seleccione una opción</option>
<?php 
                        $selected='';
                        for ($i=0 ; $i < $CantTutores ; $i++) 
							{
                               if (!empty($_POST['Tutor']) && $_POST['Tutor'] ==  $ListadoTutores[$i]['ID'])
								  {
                                   $selected = 'selected';
                                  }
							   else 
								  {
                                    $selected='';
                                  }
 ?>
							<option value="<?php echo $ListadoTutores[$i]['ID']; ?>" <?php echo $selected; ?>  ><?php echo $ListadoTutores[$i]['APELLIDO']." ".$ListadoTutores[$i]['NOMBRE']; ?> </option>
<?php
							} ?>
                    </select></div>
			 </div><hr>
			 <div class="row">
				<div class="col-lg-4"></div>
                <div class="col-lg-4"><button type="submit" class="btn btn-primary" value="ModificarTutor" name="ModificarTutor" formaction="NuevoPadre.php"><box-icon  name="group" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Agregar Padre, Madre o Tutor</button></div>
				<div class="col-lg-4"></div>
             </div><hr>
             </div>                       
			 <div class="row" align="center">
				<div class="col-lg-12"> <div class="alert alert-dismissible alert-info"><center><b>Los campos marcados con  * son obligatorios</b></div></div>
			 </div>
			 <div class="row"align="center">
			<div class="col-lg-2"></div>
			    <div class="col-lg-4">
                <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('Seguro que desea guardar el nuevo estudiante?');"><box-icon  name="check-double" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Confirmar</button></div>
				<div class="col-lg-4">
				</form>
				 <form name="cancelar" method="post" action="administrarEstudiantes.php">
                <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"  onclick="return confirm ('Seguro que desea cancelar? - No se guardarán los datos que no haya guardado')"><box-icon  name="x" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>Cancelar</button>
                  </form>
				  </div>                     
			</div>
									   
						
                                    
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

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
