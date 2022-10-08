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

?>
<!DOCTYPE html>
<html lang="es">

<head>
<?php
    require_once 'encabezado.php';

?>
   <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
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
	  <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Contenido Nuevo</b></font></h2>  </div>
	 </div></div><br>
	       
            
    <?php
    //Listo los espacios curriculares
        require_once 'funciones/listarEspaciosCurricularesXDocente.php';
        $ListadoEC=array();
        $ListadoEC = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
        $CantidadEspCurr = count($ListadoEC);
        $EC=$_REQUEST['Cx'];
		 require_once 'funciones/guardarContenido.php';
?>
     <div class="row">
       <div class="col-lg-10">
         <div class="panel panel-primary">
           <div class="panel-heading"><center> Ingrese el nombre del nuevo contenido </div><br>
           <div class="panel-body">
              <div class="row">
                <div class="col-lg-12">
                  <form role="form" method="post">
 <?php 
                     //Si confirma verifico si ya existe
                   if(!empty($_POST['Confirmar']))
					 {
                        require_once 'funciones/buscarContenido.php';
                        $mensaje = '';
                        if(empty($_POST['Denominacion'])||$_POST['Denominacion']==' ')
						  {
							  ?>
							
							 <div class="alert alert-dismissible alert-danger"><strong><center>Tiene que completar el nombre contenido<?php //echo $mensaje; ?></center></strong> </div>
                         
						    <?php 
                          }
						  else 
						  {  
						  if (contenidoExiste($MiConexion,$_POST['Denominacion'],$_POST['EspaCurri'])==0)
                        //$mensaje = $mensaje.contenidoExiste($MiConexion,$_POST['Denominacion'],$_POST['EspaCurri']);
                    //Si está todo bien creo contenido nuevo en base de datos, sino, muestro mensaje
                        // if($mensaje == '')
							{
                             
                              if(contenidoNuevo($MiConexion,$_POST['EspaCurri'],$_POST['Denominacion'])) 
							     {
?>
                                  <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡Contenido nuevo guardado!</center></strong></div></div>
<?php    
								 }
                            } 
						      elseif (contenidoExiste($MiConexion,$_POST['Denominacion'],$_POST['EspaCurri'])==1)
							{ 
?>	
						
							 <div class="alert alert-dismissible alert-danger"><strong><center>El contenido ya existe.<?php //echo $mensaje; ?></center></strong> </div>

<?php 							}
							else
							{  $Id=contenidoExiste($MiConexion,$_POST['Denominacion'],$_POST['EspaCurri']);
						      if(modificarContenidoEstado($MiConexion,$Id,$_POST['EspaCurri'],$_POST['Denominacion']))
							   {
?> 
						      <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡Contenido fue dado de alta nuevamente!</center></strong></div></div> 
<?php 								  
							   }
								  
								

                            }
						  }

					}
?>
                 </div>  <!-- col errores-->
				 </div>  <!-- row errores--><br>
				 <div class="row">
				  <div class="form-group">
                   <div class="col-lg-6"><label>Espacio Curricular</label>
				   
				     <select class="form-control" name="EspaCurri" id="EspaCurri" readonly>
<?php 
                          $selected='';
                           for($i=0 ; $i < $CantidadEspCurr ; $i++) 
                             {
                               if($_SESSION['Categoria']!=='Coordinador/a')
                                {       
                                  if ($EC==$ListadoEC[$i]['NOMBREESPACCURRIC'])
                                    { 
                                     echo '<option value="'.$ListadoEC[$i]["ID"].'">';
                                     echo $selected; 
                                     echo $ListadoEC[$i]['NOMBREESPACCURRIC']; 
                                     echo"</option>";
                                    }
                                }
                               else 
                                   {
                                    echo '<option value="'.$ListadoEC[$i]["ID"].'">';
                                    echo $selected; 
                                    echo $ListadoEC[$i]['NOMBREESPACCURRIC']; 
                                    echo"</option>";
                                    $EsDocente=1;
                                    }
                             } 
                        
?>
                    </select>
					</div>
					<div 	class="col-lg-6 bg-info">
					<div class="form-group"><label>Contenido </label><input class="form-control" placeholder="Ingrese el nombre" name="Denominacion" value="<?php echo !empty($_POST['Denominacion']) ? $_POST['Denominacion'] : ''; ?>"></div></div>
					 </div><!-- row form-->
                   </div><!-- row imput--><br><br>
					<div class="row" align="center">
									   <div class="col-lg-2"></div>
									   <div class="col-lg-4">
											<button type="submit" class="btn btn-default" value="Confirmar" name="Confirmar" style="background-color: #337ab7; color: white;"onClick="return confirm ('Seguro que desea guardar el nuevo contenido?');"><box-icon  name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon>Confirmar</button></div>
										
										<div class="col-lg-4"><button class="btn btn-danger" type="submit" name="Cancelar" formaction="administrarContenidosYAprendizajes.php?Cx=<?php echo $EC; ?>"><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div>
					
					</div> <!-- /.row botones --><br>
     
           </div> <!-- /.panel-body -->
          </div>  <!-- /.panel primary-->
       </div> <!-- /.col principal -->
     </div>       <!-- /.row principal-->
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
