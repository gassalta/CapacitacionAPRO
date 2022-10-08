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
require_once 'funciones/buscarEspacioCurricular.php';
//Conecto con la base de datos
require_once 'funciones/conexion.php';
$MiConexion=ConexionBD();

//Listo las áreas
require_once 'funciones/listarAreas.php';
$ListadoAreas = Listar_Areas($MiConexion);
$CantAreas = count($ListadoAreas);

$idEC=$_REQUEST['Cx'];
$_POST['Id']=$idEC;

require_once 'funciones/listaCursos.php';
$Listado=array();
$Listado = ListarCursos($MiConexion);
$CantidadCursos = count($Listado);

require_once 'funciones/buscarEspacioCurricular.php';
$EspacCurric = array();
$EspacCurric = buscarEspacCurric($MiConexion,$idEC);
$CantEspCurricular = count($EspacCurric);





//Declaro variables
$mensaje='';
?>
<!DOCTYPE html>
<html lang="en">
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
	 $idEC=$_REQUEST['Cx'];
	 $TieneCurso = 0;
?>
<div id="page-wrapper">
    <div class="row">
       <div class="col-lg-10"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Modificar el espacio Curricular</b></font></h2></div>
    </div>  <!-- /.row titulo--><br>
	 <form role="form" method="post">
    <div class="row">
     <div class="col-lg-10">
      <div class="panel panel-primary">
        <div class="panel-heading">Datos del Espacio Curricular N°<?php echo $idEC;?></div>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <form role="form" method="post">
 <?php 
				//if(!empty($_POST['Buscar']))
			      //{
                  
                 // }
					//Si cancela vuelvo a administrarEspaciosCurriculares
                if(!empty($_POST['Cancelar']))
				  {
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
                      //Si está todo bien modifico el espacio curricular en base de datos, sino, muestro mensaje
                      require_once 'funciones/guardarEspacioCurricular.php';
					  guardarEspacCurricXCurso($MiConexion,$idEC,$_POST['curso']);
                      if(modificarEspCurricular($MiConexion,$idEC,$_POST['NombreEspCurr'],$_POST['Area']))
						{
                         $_POST['Id'] = $idEC;
 ?>
                         <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡ Espacio Curricular número <?php echo $_SESSION['IdECElegido']; ?> modificado correctamente!</center></strong></div></div>
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
				  if(empty($_POST['Id']))
					{
?>
						<div class="alert alert-dismissible alert-danger"><strong><center>Debe ingresar un número de Espacio Curricular</center></strong></center></div>
<?php  
                    }
				   else 
				    {
                     $EspCurrEncontrado = array();
                     $EspCurrEncontrado = buscarEspacCurric($MiConexion,$_POST['Id']);
                     $Cont = 0;
                     $Cont = count($EspCurrEncontrado);
                     if($Cont != 0)
					    {
                         $_POST['NombreEspCurr'] = $EspCurrEncontrado['NOMBREESPACCURRIC'];
                         $_POST['Area'] = $EspCurrEncontrado['AREA'];
                         $_SESSION['IdECElegido'] = $_POST['Id'];
                         $TieneCurso = 1;
                        }
					else
						{
                          $EspCurrEncontrado = buscarEspacCurricSimple($MiConexion,$_POST['Id']);
                          $Cont = 0;
                          $Cont = count($EspCurrEncontrado);
                          if($Cont != 0)
							{
                             $_POST['NombreEspCurr'] = $EspCurrEncontrado['NOMBREESPACCURRIC'];
                             $_POST['Area'] = $EspCurrEncontrado['AREA'];
                             $_SESSION['IdECElegido'] = $_POST['Id'];
                             $TieneCurso = 0;
                            }
						  else
							{
?>
								<div class="alert alert-dismissible alert-danger"><center><strong>Número de Espacio Curricular no válido</center></strong></div>
<?php
                                $_POST['NombreEspCurr'] = '';
                                $_POST['Area'] = '';
                            }
                        } 
					}
?>
					</div><!--Cierre col errores-->
				  </div><!--Cierre row errores-->
				  <div class="row">
					<div class="col-lg-3"><label >N° Espacio Curricular</label></DIV>
					<div class="col-lg-3"><input class="form-control" name="Id" readonly value="<?php echo !empty($_POST['Id']) ? $_POST['Id'] : ''; ?>"></div>
					
					</div><!-- row busqueda-->
					<br><br>
                   <div class="row">
			  <div class="col-lg-6">  
				<div class="form-group"><label>Nombre Espacio Curricular *</label><input class="form-control" name="NombreEspCurr" value="<?php echo !empty($_POST['NombreEspCurr']) ? $_POST['NombreEspCurr'] : ''; ?>"></div>
			  </div>
			  <div class="col-lg-6">  					
                <div class="form-group"><label>Área *</label>
				    <select class="form-control" name="Area" id="Area">
                    <option value=""></option>
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
							<option value="<?php echo $ListadoAreas[$i]['ID']; ?>"<?php echo $selected;?>><?php echo $ListadoAreas[$i]['DENOMINACION']; ?></option>
<?php
						}
?>
                    </select>
				</div>
			  </div>
			</div><!-- /.row input --><br><br>
					<div class="row">
				<div class="col-lg-3"></div>
				<div class="col-lg-6">
				<div class="panel panel-info">
         <div class="panel-heading"><center><b>Curso en el que se dicta</b></center></div>
          <div class="panel-body">
				       <div class="form-group">
                                          
                                            <?php
								/*	if($CantEspCurricular==0)
									  { */
										for ($i=0; $i < $CantidadCursos; $i++) 
										  { 
										  	$EsDeCurso = 0;
                                                 if ($TieneCurso==1) {
                                                 	
                                                 	if ($Listado[$i]['ID'] == $EspCurrEncontrado['CURSO']) {
                                                 		$EsDeCurso=1;
                                                 	}
                                                 } else {

                                                 }
                                                 ?>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="curso" id="<?php echo $Listado[$i]['ID']; ?>" value="<?php echo $Listado[$i]['ID']; ?>" <?php echo ($EsDeCurso == 1) ? 'checked' : ''; ?>>Año:  <?php echo $Listado[$i]['ANIO']." - Division: ".$Listado[$i]['DIVISION']; ?>

                                                </label>
											</div>
                                     <?php
										  }
									?>	  
										  <div class="radio">
                                                <label>
                                                    <input type="radio" name="curso" id="curso" value="0" <?php echo ($TieneCurso == 0) ? 'checked' : '';?>><font color="red">El espacio curricular no se encuentra registrado en ningún curso</font>
                                                </label>
											</div>
										  
										  
										  
										 <?php  
										  
					/*				  }
									else{  

                                            for ($i=0; $i < $CantidadCursos; $i++) { 
                                                 ?>
                                                 <div class="radio">
                                                <label>
                                                    <input type="radio" name="curso" id="<?php echo $Listado[$i]['ID']; ?>" value="<?php echo $Listado[$i]['ID']; ?>" <?php echo ($Listado[$i]['ID'] == $EspacCurric['CURSO']) ? 'checked' : ''; ?>>Año:  <?php echo $Listado[$i]['ANIO']." - Division: ".$Listado[$i]['DIVISION']; ?>

                                                </label>
											</div>
											  <?php
										  }
									?>	  
										  <div class="radio">
                                                <label>
                                                    <input type="radio" name="curso" id="curso" value="0" checked><font color="red">No se dicta en ningún curso</font>
                                                </label>
											</div>
											
                                     <?php } */	
                                            ?>
				
				
				</div></div>
				</div>
				 </div>
				</div>
			
			
			
			
            <div class="row" align="center">
			  <div class="col-lg-12"> <div class="alert alert-dismissible alert-info"><center><b>Los campos marcados con  * son obligatorios</b></div></div>
			</div><!-- /.row aviso --><br>
            <div class="row" align="center">
			  <div class="col-lg-2"></div>
			  <div class="col-lg-4">
			     <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar"onClick="return confirm ('¿Seguro que desea guardar los cambios del Espacio Curricular?');"><box-icon  name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button>
              </div> 
</form>			  
			  <div class="col-lg-4">
			    <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button>
			  </div>
			</diV>  
	
        </div>  <!-- /.panel-body -->       
       </div> <!-- /.panel -->
	 </div>	 <!-- /.col principal -->			
    </div>  <!-- /.row principal --> 
   </div><!--  page wrapper -->
</div>    <!-- /#wrapper -->


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
