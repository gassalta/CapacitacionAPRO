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
	<div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Modificaci&oacuten de datos </b></font></h2>  </div></div>
  </div> <!-- /.row  titulo--><br>
<?php

 $_POST['IdTutor']=$_REQUEST['Cx'];
?>

  <div class="row">
   <div class="col-lg-10">
     <div class="panel panel-primary">
      <div class="panel-heading">Madre,Padre o Tutor/a N° <?php echo $_POST['IdTutor']?></div>
       <div class="panel-body">
        <div class="row">
         <div class="col-lg-12">
           <form role="form" method="post">
<?php 
            //Si cancela vuelvo a Modificar Tutor
            if(!empty($_POST['Cancelar'])) 
			  {
                header('Location: administrarTutores.php');
              }

            //Si confirma verifico los campos
            if(!empty($_POST['Confirmar']))
			  {
               require_once 'funciones/verificarCamposTutor.php';
               require_once 'funciones/buscarTutor.php';
               $mensaje = '';
               $mensaje = verificarCamposTutor();
                //Si está todo bien actualizo tutor en base de datos, sino, muestro mensaje
                if($mensaje == '')
				  {
                   require_once 'funciones/guardarTutor.php';
                   $id = $_SESSION['IdTElegido'];
                   if(modificarTutor($MiConexion,$id,$_POST['ApellidoTutor'],$_POST['NombreTutor'],$_POST['DNITutor'],$_POST['telefonoTutor'],$_POST['MailTutor'],$_POST['ocupacionTutor'],$_POST['telTrabajoTutor'],$_POST['NacionalidadTutor']))
					   {
                        $_POST['IdTutor'] = $_SESSION['IdTElegido'];
 ?>
                          <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡Padre, madre, tutor/a N° <?php echo $_SESSION['IdTElegido']; ?> modificado/a correctamente!</center></strong></div></div>
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
		 </div><!--fin col errores --><br>
<?php                        
        require_once 'funciones/buscarTutor.php';
        $TutorBuscado = buscarTutor($MiConexion,$_POST['IdTutor']);
		$_POST['ApellidoTutor'] = $TutorBuscado['APELLIDO'];
        $_POST['NombreTutor'] = $TutorBuscado['NOMBRE'];
        $_POST['DNITutor'] = $TutorBuscado['DNI'];
        $_POST['telefonoTutor'] = $TutorBuscado['TELEFONO'];
        $_POST['MailTutor'] = $TutorBuscado['MAIL'];
        $_POST['ocupacionTutor'] = $TutorBuscado['OCUPACION'];
        $_POST['telTrabajoTutor'] = $TutorBuscado['TELTRABAJO'];
        $_POST['NacionalidadTutor'] = $TutorBuscado['NACIONALIDAD'];
        $_SESSION['IdTElegido'] = $_POST['IdTutor'];
?>
        <div class="row">
          <div class="col-lg-6"><div class="form-group"><label>Apellido *</label><input class="form-control" name="ApellidoTutor" value="<?php echo !empty($_POST['ApellidoTutor']) ? $_POST['ApellidoTutor'] : ''; ?>"></div></div>
		  <div class="col-lg-6"><div class="form-group"><label>Nombre *</label><input class="form-control" name="NombreTutor" value="<?php echo !empty($_POST['NombreTutor']) ? $_POST['NombreTutor'] : ''; ?>"></div></div>
		</div><!-- /.fin row 1--><br>
        <div class="row">
          <div class="col-lg-4"><div class="form-group"><label>DNI *</label><input class="form-control" name="DNITutor" value="<?php echo !empty($_POST['DNITutor']) ? $_POST['DNITutor'] : ''; ?>"></div></div>
		  <div class="col-lg-4"><div class="form-group"><label>N° de Tel&eacutefono *</label><input class="form-control" name="telefonoTutor" value="<?php echo !empty($_POST['telefonoTutor']) ? $_POST['telefonoTutor'] : ''; ?>"></div></div>
		  <div class="col-lg-4"><div class="form-group"><label>Teléfono del Trabajo</label><input class="form-control" name="telTrabajoTutor" value="<?php echo !empty($_POST['telTrabajoTutor']) ? $_POST['telTrabajoTutor'] : ''; ?>"></div></div>
		</div><!-- /.fin row 2--><br>
        <div class="row">
          <div class="col-lg-12"><div class="form-group"><label>e-mail*</label><input class="form-control" name="MailTutor" value="<?php echo !empty($_POST['MailTutor']) ? $_POST['MailTutor'] : ''; ?>"></div></div>
		</div><!-- /.fin row 3--><br>
         	<div class="row">
          <div class="col-lg-6"><div class="form-group"><label>Nacionalidad *</label>   <select class="form-control" name="NacionalidadTutor" id="NacionalidadTutor">
                                                
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantNacionalidades ; $i++) {
                                                    if (!empty($_POST['NacionalidadTutor']) && $_POST['NacionalidadTutor'] ==  $ListadoNacionalidades[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoNacionalidades[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoNacionalidades[$i]['NACION']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select></div></div>
		  <div class="col-lg-6"><div class="form-group"><label>Ocupación</label><input class="form-control" name="ocupacionTutor" value="<?php echo !empty($_POST['ocupacionTutor']) ? $_POST['ocupacionTutor'] : ''; ?>"></div></div>
		  </div><!-- /.fin row 4--><br>                              
            <div class="row">
            <div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-info"><strong><center>Los campos marcados con * son obligatorios</center></strong></div></div></div>
		  </div>   <!-- /.fin row 5--><br>                               
             <div class="row"align="center">
            <div class="col-lg-2"> </div>                        
              <div class="col-lg-4"><button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('¿Desea guardar los cambios?');"><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
			  <div class="col-lg-4"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"  onclick="return confirm ('¿Desea cancelar? - No se guardarán los cambios')"><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div>
		  </div><!-- /.fin row botones-->                         
     
      </div> <!-- fin panel-body -->         
     </div><!-- fin panel primary -->
   </div> <!-- fin  col primary -->				
  </div> <!-- fin  row primary -->
  </div>	<!-- fin page wrapper -->			  
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