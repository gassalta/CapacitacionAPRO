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
<html lang="es">

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
    <div class="col-lg-10"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Nuevo Estudiante </b></font></h2></div>
  </div> <!-- row titulo --><br>
  <div class="row">
    <div class="col-lg-10">
     <div class="panel panel-primary">
      <div class="panel-heading"><center>Complete los datos</center></div><br>
      <div class="panel-body">
       <div class="row">
        <div class="col-lg-12">
         <form role="form" method="post">
<?php 
            //Si cancela vuelvo a administrarEstudiantes
          if(!empty($_POST['Cancelar']))
			{
              header('Location: administrarEstudiantes.php');
            }
			//Si confirma verifico los campos
          if(!empty($_POST['Confirmar']))
			{
             require_once 'funciones/verificarCamposEstudiante.php';
             require_once 'funciones/buscarEstudiante.php';
             $mensaje = '';
             $mensaje = verificarCamposEstud();
             if(!empty($mensaje))
			   {
                $mensaje = $mensaje." - ";
               }
             $mensaje = $mensaje.estudianteExiste($MiConexion,$_POST['DNI']);
            //Si está todo bien creo estudiante nuevo en base de datos, sino, muestro mensaje
            if($mensaje == '')
			  {
               require_once 'funciones/guardarEstudiante.php';
               if(estudianteNuevo($MiConexion,$_POST['nroLegajo'],$_POST['nroLibroMatriz'],$_POST['nroFolio'],$_POST['Apellido'],$_POST['Nombre'],$_POST['DNI'],$_POST['telefono'],$_POST['Mail'],$_POST['Nacionalidad'],$_POST['escDeProcedencia'],$_POST['lugarNacim'],$_POST['fechaNacim'],$_POST['domicilio'],$_POST['Barrio'],$_POST['fechaPreinscripcion'],$_POST['Padre'],$_POST['Madre'],$_POST['Tutor']))
				 {
?>
                  <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡Estudiante nuevo guardado!</center></strong></div></div>
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
									   
			</div> 
		   </div> <!--fin row errores-->  
		   <div class="row"><div class="form-group">
			  <div class="col-lg-4"><label>Legajo</label><input class="form-control" name="nroLegajo" value="<?php echo !empty($_POST['nroLegajo']) ? $_POST['nroLegajo'] : ''; ?>"></div>
			  <div class="col-lg-4"><label>Libro Matriz</label><input class="form-control" name="nroLibroMatriz" value="<?php echo !empty($_POST['nroLibroMatriz']) ? $_POST['nroLibroMatriz'] : ''; ?>"></div>
             <div class="col-lg-4"><label>Folio</label><input class="form-control" name="nroFolio" value="<?php echo !empty($_POST['nroFolio']) ? $_POST['nroFolio'] : ''; ?>"></div>
		   </div></div><br><!--fin row 1--> 
		   <div class="row"><div class="form-group">
				<div class="col-lg-6"><label>Apellido *</label><input class="form-control" name="Apellido" value="<?php echo !empty($_POST['Apellido']) ? $_POST['Apellido'] : ''; ?>"></div>
                <div class="col-lg-6"><label>Nombre *</label><input class="form-control"  name="Nombre" value="<?php echo !empty($_POST['Nombre']) ? $_POST['Nombre'] : ''; ?>"></div>
           </div></div><br><!--fin row 2--> 
            <div class="row"><div class="form-group">
              <div class="col-lg-6"><label>DNI *</label><input class="form-control"  name="DNI" value="<?php echo !empty($_POST['DNI']) ? $_POST['DNI'] : ''; ?>"></div>
              <div class="col-lg-6"><label>Telefono *</label><input class="form-control" name="telefono" value="<?php echo !empty($_POST['telefono']) ? $_POST['telefono'] : ''; ?>"></div>
			</div></div><br><!--fin row 3--> 
			<div class="row"><div class="form-group">
              <div class="col-lg-6"><label>E-mail *</label><input class="form-control"  name="Mail" value="<?php echo !empty($_POST['Mail']) ? $_POST['Mail'] : ''; ?>" placeholder="...@escuelasproa.edu.ar"></div>
                    <div class="col-lg-5	"><label>Nacionalidad *</label>
						<select class="form-control"  name="Nacionalidad" id="Nacionalidad">
                          <option value="">Seleccione Nacionalidad</option>
<?php 
                           $selected='';
                           for($i=0 ; $i < $CantNacionalidades ; $i++)
							{
                              if(!empty($_POST['Nacionalidad']) && $_POST['Nacionalidad'] ==  $ListadoNacionalidades[$i]['ID'])
								{
                                  $selected = 'selected';
                                }
							  else
								{
                                 $selected='';
                                }
?>
                              <option value="<?php echo $ListadoNacionalidades[$i]['ID']; ?>" <?php echo $selected; ?>><?php echo $ListadoNacionalidades[$i]['NACION']; ?></option>
<?php
							} 
?>

                        </select>
					</div>
					<div class="col-lg-1" align="center"><label>Otra</label><abbr title="Aquí usted puede añadir una nacionalidad que no se encuentre en el listado"><button type="submit" class="btn btn-primary btn-circle" value="NacionalidadNueva" name="NacionalidadNueva" formaction="NuevaNacionalidad.php"><box-icon  name="plus-circle" type="solid" size="sm" color="white" animation="tada-hover"></box-icon></button></abbr>
                    </div>
					</div></div><br><!--fin row 4--> 
				<div class="row"><div class="form-group">
					<div class="col-lg-6"><label>Escuela de Procedencia</label><select class="form-control"  name="escDeProcedencia" id="escDeProcedencia">
            <option value="">Seleccione Escuela</option>
            <option value="Eva Duarte" <?php echo !empty($_POST['escDeProcedencia']) && $_POST['escDeProcedencia']=="Eva Duarte" ? 'selected' : ''; ?>>Eva Duarte</option>
            <option value="San Martin" <?php echo !empty($_POST['escDeProcedencia']) && $_POST['escDeProcedencia']=="San Martin" ? 'selected' : ''; ?>>San Martín</option>
            <option value="Otra" <?php echo !empty($_POST['escDeProcedencia']) && $_POST['escDeProcedencia']=="Otra" ? 'selected' : ''; ?>>Otra</option>
          </select></div>
                    <div class="col-lg-6"><label>Lugar de Nacimiento * </label><select class="form-control"  name="lugarNacim" id="lugarNacim">
            <option value="">Seleccione Lugar</option>
            <option value="Buenos Aires" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Buenos Aires" ? 'selected' : ''; ?>>Buenos Aires</option>
            <option value="CABA" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="CABA" ? 'selected' : ''; ?>>CABA</option>
            <option value="Catamarca" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Catamarca" ? 'selected' : ''; ?>>Catamarca</option>
            <option value="Chaco" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Chaco" ? 'selected' : ''; ?>>Chaco</option>
            <option value="Chubut" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Chubut" ? 'selected' : ''; ?>>Chubut</option>
            <option value="Cordoba" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Cordoba" ? 'selected' : ''; ?>>Cordoba</option>
            <option value="Corrientes" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Corrientes" ? 'selected' : ''; ?>>Corrientes</option>
            <option value="Entre Rios" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Entre Rios" ? 'selected' : ''; ?>>Entre Ríos</option>
            <option value="Formosa" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Formosa" ? 'selected' : ''; ?>>Formosa</option>
            <option value="Jujuy" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Jujuy" ? 'selected' : ''; ?>>Jujuy</option>
            <option value="La Pampa" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="La Pampa" ? 'selected' : ''; ?>>La Pampa</option>
            <option value="La Rioja" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="La Rioja" ? 'selected' : ''; ?>>La Rioja</option>
            <option value="Mendoza" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Mendoza" ? 'selected' : ''; ?>>Mendoza</option>
            <option value="Misiones" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Misiones" ? 'selected' : ''; ?>>Misiones</option>
            <option value="Neuquen" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Neuquen" ? 'selected' : ''; ?>>Neuquén</option>
            <option value="Rio Negro" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Rio Negro" ? 'selected' : ''; ?>>Río Negro</option>
            <option value="Salta" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Salta" ? 'selected' : ''; ?>>Salta</option>
            <option value="San Juan" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="San Juan" ? 'selected' : ''; ?>>San Juan</option>
            <option value="San Luis" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="San Luis" ? 'selected' : ''; ?>>San Luis</option>
            <option value="Santa Cruz" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Santa Cruz" ? 'selected' : ''; ?>>Santa Cruz</option>
            <option value="Santa Fe" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Santa Fe" ? 'selected' : ''; ?>>Santa Fe</option>
            <option value="Santiago del Estero" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Santiago del Estero" ? 'selected' : ''; ?>>Santiago del Estero</option>
            <option value="Tierra del Fuego" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Tierra del Fuego" ? 'selected' : ''; ?>>Tierra del Fuego</option>
            <option value="Tucuman" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Tucuman" ? 'selected' : ''; ?>>Tucumán</option>
            <option value="Otra" <?php echo !empty($_POST['lugarNacim']) && $_POST['lugarNacim']=="Otra" ? 'selected' : ''; ?>>Otra</option>
          </select></div>
				</div></div><br><!--fin row 5--> 
				<div class="row">
				   <div class="col-lg-3"><label>Fecha de Preinscripción</label></div>
				    <div class="col-lg-3"><label>Fecha de Nacimiento * </label></div>
					 <div class="col-lg-5"><label>Barrio *</label></div>
					  <div class="col-lg-1"><label>Otro </label></div>
					</div><!--fin row 6--> 
				<div class="row"><div class="form-group">
				   <div class="col-lg-3"><input id="date" type="date" name="fechaPreinscripcion" value="<?php echo !empty($_POST['fechaPreinscripcion']) ? $_POST['fechaPreinscripcion'] : ''; ?>"></div>
                    <div class="col-lg-3"><input id="date" type="date" name="fechaNacim" value="<?php echo !empty($_POST['fechaNacim']) ? $_POST['fechaNacim'] : ''; ?>"></div>
					<div class="col-lg-5"><select class="form-control"  name="Barrio" id="Barrio">
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
						   
						 </div>
						 <div class="col-lg-1" align="center"><abbr title="Aquí usted puede añadir un barrio que no se encuentre en el listado"><button type="submit" class="btn btn-primary btn-circle" value="NacionalidadNueva" name="NacionalidadNueva" formaction="NuevoBarrio.php"><box-icon  name="plus-circle" type="solid" size="sm" color="white" animation="tada-hover"></box-icon></button></abbr>
                    </div>
				 </div></div><br><!--fin row 7--> 
				<div class="row">
                   <div class="form-group">
                     <div class="col-lg-12"><label>Domicilio *</label><input class="form-control" name="domicilio" value="<?php echo !empty($_POST['domicilio']) ? $_POST['domicilio'] : ''; ?>"></div>
                    
					</div>
				</div><br><hr><!--fin row 8--> 
				<div class="form-group">
                <div class="row">
                  <div class="col-lg-4">
					<label>Padre</label>
                    <select class="form-control" name="Padre" id="Padre">
                       <option value="">Seleccione el Padre</option>
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
                     <option value="">Seleccione la Madre</option>
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
                       <option value="">Seleccione Tutor/a</option>
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
                    </select>
					</div></div><br><!--fin row 9--> 
			 <div class="row" align="center">
		<!--	<div class="col-lg-4"></div>
                <div class="col-lg-4"><abbr title="Aquí usted puede añadir un padre,madre o tutor que no se encuentre registrado"><button type="submit" class="btn btn-primary" value="ModificarTutor" name="ModificarTutor" formaction="NuevoPadre.php"><box-icon  name="group" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Agregar Padre, Madre o Tutor</button></abbr></div>
				-->
             </div><hr><br><!--fin row 10--> 
                              
			 <div class="row" align="center">
				<div class="col-lg-12"> <div class="alert alert-dismissible alert-info"><center><b>Los campos marcados con  * son obligatorios</b></div></div>
			 </div><br><!--fin row aviso--> 
			 <div class="row"align="center">
			<div class="col-lg-2"></div>
			    <div class="col-lg-4">
                <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('¿Desea guardar el nuevo estudiante?');"><box-icon  name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
				<div class="col-lg-4">
				</form>
				 <form name="cancelar" method="post" action="administrarEstudiantes.php">
                <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"  onclick="return confirm ('¿ Desea retornar? - No se guardarán los datos')"><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button>
                  </form>
				  </div>                     
			</div>
									   
						
                                    
                          
                       
                     
                   
                   
               
                
         </div>   <!-- fin .panel-body -->
         </div>   <!-- fin .panel primary -->
      </div><!-- fin col-primary-->
       </div>   <!--fin.row primary --> 
    </div><!-- fin page-wrapper -->
    </div>  <!-- fin wrapper -->
  

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
