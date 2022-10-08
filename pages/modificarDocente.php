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
$DocenteBuscado=array();
$DocenteEncontrado = 0; //0-No - 1-Sí

 $IdDocente=$_REQUEST['Cx'];
 $_POST['Id']= $IdDocente;

require_once 'funciones/buscarDocente.php';

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
      <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Modificar informaci&oacuten del docente</b></font></h2>  </div></div><br>
    </div>  <!-- /.row titulo --><br>
<?php
    //Listo los docentes
        require_once 'funciones/listarDocentes.php';
        $Listado=array();
        $Listado = Listar_Docentes($MiConexion);
        $CantidadDocentes = count($Listado);
?>
    <div class="row">
     <div class="col-lg-10">
      <div class="panel panel-primary">
        <div class="panel-heading"><center>Docente N° <?php echo $IdDocente?></center></div>
        <div class="panel-body">
         <form role="form" method="post">
<?php 
           //Si cancela vuelvo a administrarDocentes
          if(!empty($_POST['Cancelar']))
			{
             header('Location: administrarDocentes.php');
            }
          if(!empty($_POST['EspCurr']))
			{
             $_SESSION['DNIDocenteElegido'] = $_POST['DNI'];
             $_SESSION['Envia'] = 'modificarDocente.php';
             header('Location: EspCurrXDocente.php?Cx='.$IdDocente);
            }
          //Si confirma verifico los campos
          if(!empty($_POST['Confirmar']))
			{
             require_once 'funciones/verificarCamposDocente.php';
             $mensaje = '';
             $mensaje = verificarCamposDoc();
             if(!empty($mensaje))
              {
               $mensaje = $mensaje." - ";
              }
              $id= $_SESSION['IdDElegido'];
             $mensaje = $mensaje.docenteExisteOtro($MiConexion,$_POST['DNI'],$id);
             if($mensaje == '')
			   {
                require_once 'funciones/guardarDocente.php';
                
                if (modificarDocente($MiConexion,$id,$_POST['Apellido'],$_POST['Nombre'],$_POST['DNI'],$_POST['FechaNacim'],$_POST['NroLegajoJunta'],$_POST['Titulo'],$_POST['FechaEscalafon'],$_POST['categorias'],$_POST['Mail']))
				 {
                    $_POST['Id'] = $_SESSION['IdDElegido'];
?>
					<div class="row">
						<div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡ Docente número <?php echo $_SESSION['IdDElegido']; ?> modificado correctamente !</center></strong> </div></div></div>
					</div><!--fin row Exito de modicicación-->
<?php   
				 }
                   $Listado = Listar_Docentes($MiConexion);
                    $CantidadDocentes = count($Listado);
               } 
			 else 
			    {
?>         
				  <div class="row">
				   <div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong><center><?php echo $mensaje; ?></center></strong></div></div>
				 </div>
 <?php
                }
			}
            $DocenteBuscado = buscarDocente($MiConexion,$_POST['Id']);
            $Cant = count($DocenteBuscado);
            $_POST['Apellido'] = $DocenteBuscado['APELLIDO'];
            $_POST['Nombre'] = $DocenteBuscado['NOMBRE'];
            $_POST['DNI'] = $DocenteBuscado['DNI'];
            $_POST['FechaNacim'] = $DocenteBuscado['FECHANACIM'];
            $_POST['NroLegajoJunta'] = $DocenteBuscado['NROLEGAJOJUNTA'];
            $_POST['Titulo'] = $DocenteBuscado['TITULO'];
            $_POST['FechaEscalafon'] = $DocenteBuscado['FECHAESCALAFON'];
            $_POST['categorias'] = $DocenteBuscado['CATEGORIA'];
            $_POST['Mail'] = $DocenteBuscado['MAIL'];
            $_POST['UltIngreso'] = $DocenteBuscado['ULTINGRESO'];
            $_SESSION['IdDElegido'] = $_POST['Id'];
?>
                                  
			<br><div class="row">
			     <div class="col-lg-3"><div class="form-group"><label>Apellido *</label><input class="form-control" name="Apellido" value="<?php echo !empty($_POST['Apellido']) ? $_POST['Apellido'] : ''; ?>"></div></div>
				  <div class="col-lg-3"><div class="form-group"><label>Nombre *</label><input class="form-control" name="Nombre" value="<?php echo !empty($_POST['Nombre']) ? $_POST['Nombre'] : ''; ?>"></div></div>
				  <div class="col-lg-3"><div class="form-group"><label>D.N.I *</label><input class="form-control" name="DNI" value="<?php echo !empty($_POST['DNI']) ? $_POST['DNI'] : ''; ?>"> </div></div>
				  <div class="col-lg-3"><div class="form-group"><label>Legajo en Junta</label><input class="form-control" name="NroLegajoJunta" value="<?php echo !empty($_POST['NroLegajoJunta']) ? $_POST['NroLegajoJunta'] : ''; ?>"></div></div>
				</div><!-- fin primera row--><br>
				
				<div class="row">
                  <div class="col-lg-6"><div class="form-group"><label>E-mail *</label><input class="form-control" name="Mail" value="<?php echo !empty($_POST['Mail']) ? $_POST['Mail'] : ''; ?>"></div></div>
				   <div class="col-lg-6"><div class="form-group"><label>Título *</label><select class="form-control"  name="Titulo" id="Titulo">
            <option value="">Seleccione Título</option>
            <option value="Profesor de Educacion Tecnologica" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Educacion Tecnologica" ? 'selected' : ''; ?>>Profesor de Educacion Tecnologica</option>
            <option value="Profesor de Matematica" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Matematica" ? 'selected' : ''; ?>>Profesor de Matematica</option>
            <option value="Profesor de Lengua" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Lengua" ? 'selected' : ''; ?>>Profesor de Lengua</option>
            <option value="Profesor de Biologia" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Biologia" ? 'selected' : ''; ?>>Profesor de Biologia</option>
            <option value="Profesor de Fisica" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Fisica" ? 'selected' : ''; ?>>Profesor de Fisica</option>
            <option value="Profesor de Quimica" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Quimica" ? 'selected' : ''; ?>>Profesor de Quimica</option>
            <option value="Profesor de Historia" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Historia" ? 'selected' : ''; ?>>Profesor de Historia</option>
            <option value="Profesor de Geografia" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Geografia" ? 'selected' : ''; ?>>Profesor de Geografia</option>
            <option value="Profesor de Ingles" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Ingles" ? 'selected' : ''; ?>>Profesor de Ingles</option>
            <option value="Profesor de Educacion Fisica" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Educacion Fisica" ? 'selected' : ''; ?>>Profesor de Educacion Fisica</option>
            <option value="Profesor de Artistica" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Artistica" ? 'selected' : ''; ?>>Profesor de Artistica</option>
            <option value="Profesor de Musica" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Musica" ? 'selected' : ''; ?>>Profesor de Musica</option>
            <option value="Profesor de Enseñanza Primaria" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Profesor de Enseñanza Primaria" ? 'selected' : ''; ?>>Profesor de Enseñanza Primaria</option>
            <option value="Analista de Sistemas" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Analista de Sistemas" ? 'selected' : ''; ?>>Analista de Sistemas</option>
            <option value="Analista Programador" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Analista Programador" ? 'selected' : ''; ?>>Analista Programador</option>
            <option value="Ingeniero en Sistemas" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Ingeniero en Sistemas" ? 'selected' : ''; ?>>Ingeniero en Sistemas</option>
            <option value="Tecnico en Programacion" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Tecnico en Programacion" ? 'selected' : ''; ?>>Tecnico en Programacion</option>
            <option value="Licenciado en Psicologia" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Licenciado en Psicologia" ? 'selected' : ''; ?>>Licenciado en Psicologia</option>
            <option value="Licenciado en Pedagogia" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Licenciado en Pedagogia" ? 'selected' : ''; ?>>Licenciado en Pedagogia</option>
            <option value="Otro" <?php echo !empty($_POST['Titulo']) && $_POST['Titulo']=="Otro" ? 'selected' : ''; ?>>Otro</option>
          </select></div></div>
				 </div><!-- fin segunda row--><br>						
			    <div class="row" align="center">
				<div class="col-lg-3" align="left">	
				
                
				  <div class="panel panel-info">
				    <div class="panel-heading"><center><label>Categoría *</center></label></div>
					<div class="row"  align="left">
					 <div class="col-lg-2" ></div>	
				      <div class="col-lg-10" >		
				       <div class="form-group">
                        <div class="radio"><label class="radio-inline"><input type="radio" name="categorias" id="1" value="1" <?php echo (!empty($_POST['categorias'])) && ($_POST['categorias']=='1') ? 'checked':''; ?>>Coordinador/a</label></div>
                        <div class="radio"><label class="radio-inline"><input type="radio" name="categorias" id="2" value="2" <?php echo (!empty($_POST['categorias'])) && ($_POST['categorias']=='2') ? 'checked':''; ?>>Secretario/a</label></div>
                        <div class="radio"><label class="radio-inline"><input type="radio" name="categorias" id="3" value="3" <?php echo (!empty($_POST['categorias'])) && ($_POST['categorias']=='3') ? 'checked':''; ?>>Preceptor/a</label></div>
                        <div class="radio"><label class="radio-inline"><input type="radio" name="categorias" id="4" value="4"  <?php echo (!empty($_POST['categorias'])) && ($_POST['categorias']=='4') ? 'checked':''; ?>>Docente</label></div>
                       </div>
                      </div>
					</div>  <!-- fin catedoria row-->                 
				 </div><!-- fin panel info categoria row--></div>
				<div class="col-lg-3"><div class="form-group"><label>Fecha de Nacimiento *</label><input id="date" type="date" name="FechaNacim" value="<?php echo !empty($_POST['FechaNacim']) ? $_POST['FechaNacim'] : ''; ?>" ></div></div>
					<div class="col-lg-3"> <div class="form-group"><label>Fecha de Escalafón</label><input id="date" type="date" name="FechaEscalafon" value="<?php echo !empty($_POST['FechaEscalafon']) ? $_POST['FechaEscalafon'] : ''; ?>" ></div></div>
					<div class="form-group"><div class="col-lg-3"><label for="disabledSelect">Último Ingreso</label><input class="form-control" id="disabledInput" type="text" disabled name="UltIngreso" value="<?php echo !empty($_POST['UltIngreso']) ? $_POST['UltIngreso'] : ''; ?>"></div></div>
				</div><!-- fin tercera row--><br>
				
	            <div class="row">
					<div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-info"><strong><center>Los campos marcados (*) son obligatorios</center></strong> </div></div></div>
				</div><!--fin row aviso--><br>
				<div class="row" align="center">
				 <div class="col-lg-4"><button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('¿ Desea modificar docente ?');"><box-icon name="check-double"  size="sm" color="white"animation="tada" ></box-icon> Confirmar</button></div> 
				 
				 <div class="col-lg-4"><button type="submit" class="btn btn-primary" value="EspCurr" name="EspCurr"  onclick="return confirm ('¿Desea dirigirse a los Esp.Curriculares? - Si no guarda puede perder los cambios -')"><box-icon name="shopping-bag"  size="sm" color="white"animation="tada-hover" ></box-icon> Espacios Curriculares a Cargo</button></div>
				 
				 <div class="col-lg-4"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"  ></box-icon> Retornar</button></div>
                </div>    <!--fin row botones --><br>
					   
                       
        </div>    <!-- /.panel-body -->     
       </div>           <!-- /.panel primary -->
	  </div>	<!-- /colprimary -->		
	 </div>	<!-- /.row primary -->	
    </div>   <!-- fin page-wrapper -->   
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
