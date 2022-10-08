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
<html lang="es"
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
           
            
    <?php
    //Listo los espacios curriculares
        require_once 'funciones/listarEspaciosCurricularesXDocente.php';
        $ListadoEC=array();
        $ListadoEC = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
        $CantidadEspCurr = count($ListadoEC);

        $IdContenido = $_REQUEST['Cx'];
        require_once 'funciones/buscarEspacioCurricular.php';
        $NEC=$_REQUEST['Ec'];
        $_SESSION['IdContenidoElegido'] = $IdContenido;

        require_once 'funciones/buscarContenido.php';
        $ContenidoBuscado = buscarContenido($MiConexion,$IdContenido);
        $Cant = count($ContenidoBuscado);
        $IdEC = $ContenidoBuscado['IDESPCUR'];
    ?>
   <div class="row">
	  <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Modificar contenido</b></font></h2>  </div></div>
	</div><br><!-- row titulo-->
        
    
   <div class="row">
     <div class="col-lg-10">
       <div class="panel panel-primary">
		<div class="panel-heading"><center>Datos del Contenido N° <?php echo $IdContenido;?></center></div><br>	
         <div class="panel-body">
           <div class="row">
             <div class="col-lg-12">
               <form role="form" method="post">
<?php 
                  //Si confirma verifico campos
                 if (!empty($_POST['Confirmar'])) 
				    {
                     $mensaje = '';
                     if(empty($_POST['Denominacion']))
					 {
                       $mensaje = "Tiene que completar los datos del contenido - ";
                      }
                       /*if ($_SESSION['IdContenidoElegido'] == "") {$mensaje = "No tiene acceso al contenido seleccionado";}*/
                                                                                                
                     //Si está todo bien modifico el contenido en base de datos, sino, muestro mensaje
                     if ($mensaje == '')
						{
                         require_once 'funciones/guardarContenido.php';
                         if (modificarContenido($MiConexion,$IdContenido,$IdEC,$_POST['Denominacion']))
							{
                              $_POST['Id'] = $_SESSION['IdContenidoElegido'];
							  $ContenidoBuscado['DENOMINACION']=$_POST['Denominacion'];
 ?>
                             <div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡El contenido número <?php echo $_SESSION['IdContenidoElegido']; ?> se modific&oacute correctamente!</center></strong> </div> </div>
<?php    
							}
                        }
					 else 
					    {
 ?>
							<div class="alert alert-dismissible alert-danger"><strong><center><?php echo $mensaje; ?></center</strong></div>
					    
             <?php
						}
						
					}
					
					 $_POST['Denominacion'] = $ContenidoBuscado['DENOMINACION'];
                                       //Si busca por Id...
                                    //   if (!empty($_POST['Buscar'])) {
                                     //           require_once 'funciones/buscarContenido.php';
                                                //Buscar el contenido y contarlo
                                     //           $ContenidoBuscado = buscarContenido($MiConexion,$IdContenido);
                                     //           $Cant = count($ContenidoBuscado);
                                     //           $_SESSION['IdContenidoElegido'] = $IdContenido;
                                                //Verificar si encontró el contenido
                                            /*    if ($Cant==0) { 
                                                    //Si no lo encontró, aviso y dejo en blanco los controles ?>
                                                    <div class="alert alert-dismissible alert-danger">
                  <strong>Número de contenido no válido</strong>
                </div>
                                          <?php   
                                                    $_POST['EspaCurri'] ="";
                                            $_POST['Denominacion'] = "";
                                            $_SESSION['IdContenidoElegido'] = "";
                                            
                                                } else { */
                                                    //Si lo encontró, revisar si el espacio curricular del mismo corresponde con los del docente que está usando el sistema
                                                //    $ok = 0;
                                                   // for ($i=0; $i < $CantidadEspCurr; $i++) { 
                                                   /*     if ($ContenidoBuscado['ESPACCURRIC'] == $NEC) {
                                                            $ok = 1;
                                                        } */
                                                  //  }
                                                    //Si corresponde...
                                                //    if ($ok == 1) {
                                                        //Cargar los controles
                                                //        $_POST['EspaCurri'] = $ContenidoBuscado['ESPACCURRIC'];
                                                       
                                               //         $_SESSION['IdContenidoElegido'] = $IdContenido;
                                                /*    } else {
                                                        //Si no corresponde, informar y dejar en blanco los controles ?>
                                                        <div class="alert alert-dismissible alert-danger">
                  <strong>No tiene acceso al número de contenido elegido</strong>
                </div>
                                          <?php   
                                                    $_POST['EspaCurri'] ="";
                                            $_POST['Denominacion'] = "";
                                            $_SESSION['IdContenidoElegido'] = "";
                                                    } */
                                            
                                        //  }
                                         //   }?>
                                      <!--   <div class="form-group">          
                                                 <label >Nro</label>
                                            <input class="form-control" name="Id" value="<?php //echo $IdContenido; ?>" readonly>
                                        <button type="submit" class="btn btn-default" value="Buscar" name="Buscar" style="background-color: #337ab7; color: white;"
                                       ><box-icon  name="search-alt" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>Buscar</button> 
                                        </div>-->
						</div>		
					    </div><!--row errores--><br>
										
										<div class="row" >
										
										<div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Espacio Curricular</label>
                                        <!--    <select class="form-control" name="EspaCurri" id="EspaCurri">
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantidadEspCurr ; $i++) {
                                                    if (!empty($_POST['EspaCurri']) && $_POST['EspaCurri'] ==  $ListadoEC[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                            $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoEC[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoEC[$i]['NOMBREESPACCURRIC']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select> -->
                                            <input class="form-control" name="EspaCurri" value="<?php echo $NEC; ?>" readonly>
                                        </div></div>
										<div class="col-lg-6 bg-info">
                                        <div class="form-group">
                                            <label>Contenido</label>
                                            <input class="form-control" name="Denominacion" value="<?php echo !empty($_POST['Denominacion']) ? $_POST['Denominacion'] : ''; ?>">
                                        </div>
                                        </div>
										</div><!--row input--><br>
										<div class="row"align="center">
										<div class="col-lg-2"></div>
									     <div class="col-lg-4">
									  	 <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('¿Seguro que desea guardar el nuevo contenido?');"><box-icon  name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon>Confirmar</button></div>
									   	<div class="col-lg-4">
										 <button class="btn btn-danger" type="submit" name="Cancelar" formaction="administrarContenidosYAprendizajes.php?Cx=<?php echo $NEC; ?>"><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button>
                                        </div>
										</div><!-- /.row botones --><br>
       </div> <!-- /.panel-body -->
           </div>   <!-- /.panel primary -->
      </div>  <!-- /.col principal-->
    </div>  <!-- /.row principal -->
 </div>   <!-- /#page-wrapper -->
    </div>  <!-- /#wrapper -->
  

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
