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

$EsDocente =0;
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
    //Listo los espacios curriculares
        require_once 'funciones/listarEspaciosCurricularesXDocente.php';
        $ListadoEC=array();
        $ListadoEC = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
        $CantidadEspCurr = count($ListadoEC); 
    $NEC = $_REQUEST['Cx'];
    $IdEC = 0;
    for ($i=0; $i < $CantidadEspCurr; $i++) { 
      if ($NEC == $ListadoEC[$i]['NOMBREESPACCURRIC']) {
        $IdEC = $ListadoEC[$i]['ID'];
      }
    }
    ?>
    <div class="row">
     <div class="col-md-10">
       <h1 class="page-header"><font color="#85C1E9">Contenidos y Aprendizajes</font></h1>
      </div>
      <div class="clearfix"></div>
    </div><!--fin row titulo-->
    <div class="row">
      <div class="col-lg-10">
        <div class="panel panel-primary">
		<div class="panel-heading">Seleccione el espacio curricular a consultar</div>
          <div class="panel-body">
            
                 <form role="form" method="post">
				 <div class="row">
				 <div class="form-group">
				 <div class="col-lg-4">
                  
                     <!--<label>Espacio Curricular</label>-->
                <!--      <select class="form-control" name="EspaCurri" id="EspaCurri" readonly>-->
                        <!--<option value=""></option>-->
<?php 
              /*            $selected='';
						  for ($i=0 ; $i < $CantidadEspCurr ; $i++) 
							{
                              //if (!empty($_POST['EspaCurri']) && $_POST['EspaCurri'] ==  $ListadoEC[$i]['ID']) 
								//{
								// $selected = 'selected';}
							  //else 
								//{
                                 //$selected='';
                               // }
							if ($_SESSION['Categoria']!=='Coordinador/a')
								{   $EC=$_REQUEST['Cx'];	
								 if ($EC==$ListadoEC[$i]['NOMBREESPACCURRIC'])
									{ //echo $ListadoEC[$i]['NOMBREESPACCURRIC'];
									echo '<option value="'.$ListadoEC[$i]["ID"].'">';
									echo $selected; 
                                    echo $ListadoEC[$i]['NOMBREESPACCURRIC']; 
                                    echo"</option>";
									}
								}
							else 
									{ //echo $ListadoEC[$i]['NOMBREESPACCURRIC'];
									echo '<option value="'.$ListadoEC[$i]["ID"].'">';
									echo $selected; 
                                    echo $ListadoEC[$i]['NOMBREESPACCURRIC']; 
                                    echo"</option>";
                  $EsDocente=1;
									}
							} */					
?>
                        <input class="form-control" name="EspaCurri" value="<?php echo $NEC; ?>" readonly>
                       </select>
					   </div>
					   <div class="col-lg-2">
                  <!--     <button type="submit" class="btn btn-default" value="Ver" name="Ver" style="background-color: #337ab7; color: white;"><box-icon  name="show" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>Ver</button> -->
                    </div><!--fin form group select--> </div></diV><br><hr>
					
<?php

            /*                 if (!empty($_POST['Ver'])) 
								{
                                  $_SESSION['EspCurrElegido'] = 0;
                                }
                                 //si hay seleccionado algún contenido...
								if (!empty($_POST['EspaCurri'])) 
								 { */
									//listo los contenidos del espacio curricular
									require_once 'funciones/listarContenidos.php';
									$ListadoContenidos = array();
								
								//	if ($_SESSION['EspCurrElegido'] == 0)
								//		{
										$ListadoContenidos = Listar_Contenidos($MiConexion,$IdEC);
							/*			} 
									else {
											$ListadoContenidos = Listar_Contenidos($MiConexion,$_SESSION['EspCurrElegido']);
										 } */
									$CantidadContenidos = count($ListadoContenidos); 
									if ($CantidadContenidos != 0) 
										{
                                                ?>
                                                
												<div class="row">
												<div class="col-lg-8">
                                                <h3	 class="tile-title"><font color="#85C1E9"><center>Hay <?php echo $CantidadContenidos; ?> contenidos en este espacio curricular<hr></font></h3></div></div>
                                                
												<div class="row">
			<div class="col-lg-1"></div>
				 <div class="col-lg-9">
            <div class="table-responsive">
              <table class="table-md table-striped table-bordered bg-info">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Contenido</th>
					<th>Aprendizajes</th>
                      <?php // if ($_SESSION['Categoria'] == 'Docente' || $EsDocente==1) { ?>
                    <th>Modificar</th>
					<th>Eliminar</th>
                <?php // } ?>
                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de los contenidos
                            for ($i=0; $i < $CantidadContenidos; $i++) 
							{ ?>
                                <tr class="table-info">
                                    <td><?php echo $ListadoContenidos[$i]['ID'], "- "; ?></td>
                                    <td><?php echo $ListadoContenidos[$i]['DENOMINACION']; ?></td>
                                    
								
									<?php                                    
									echo'<td><a href="administrarAprendizajes.php?Ec='.$IdEC.'&Cx='.$ListadoContenidos[$i]['ID'].'"><box-icon name="grid" " size="md" color="blue" animation="tada-hover"></box-icon></a></td>';
          //                          if ($_SESSION['Categoria'] == 'Docente' || $EsDocente==1) 
					//				{
										echo'<td><a href="modificarContenidos.php?Tx=M&Cx='.$ListadoContenidos[$i]['ID'].'&Ec='.$NEC.'"><box-icon name="edit-alt" type="solid" size="md" color="blue" animation="tada-hover"></box-icon></a></td>';
										echo'<td><a href="eliminarContenido.php?Tx=M&Cx='.$ListadoContenidos[$i]['ID'].'&Ec='.$NEC.'"><box-icon name="trash" " size="md" color="red" animation="tada-hover"></box-icon></a></td>';
						//			}
									echo"</tr>"; 
							}
							?>
    
                </tbody>
              </table> 
            </div></div></div><hr>
        <?php
        } else { ?>
                <div class="bs-component">
                <div class="alert alert-dismissible alert-danger">
                  <strong>El espacio curricular no tiene registrado ningún contenido</strong>
                </div>
              </div>
      <?php }

         /*} else { ?>
                <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Seleccione un espacio curricular</strong>
                </div>
              </div>
      <?php  }
      $EspCurrElegido=array();
      $NEC=$_REQUEST['Cx'];
       if (!empty($_POST['EspaCurri'])) {
        require_once 'funciones/buscarEspacioCurricular.php';
        $EspCurrElegido=buscarEspacCurric($MiConexion,$_POST['EspaCurri']);
        $NEC = $EspCurrElegido['NOMBREESPACCURRIC'];
      } */
        
      ?>
				  <div class="row">
                  
				<div class="form-group">
				<div class="col-lg-1"></div>
				 <div class="col-lg-3">
				<?php // if ($_SESSION['Categoria'] == 'Docente' || $EsDocente==1) { ?>
				<button class="btn btn-primary" type="submit" name="ContenidoNuevo" formaction="contenidoNuevo.php?Cx=<?php echo $NEC; ?>"><box-icon name="plus-circle" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Nuevo
                </button></div>
				<?php // } 

        
        ?>
				
				 <div class="col-lg-3">
				 <button class="btn btn-primary" type="submit" name="Aprendizajes" formaction="administrarAprendizajes.php?Cx=<?php echo $NEC; ?>"><box-icon name="spreadsheet" type="solid" size="sm" color="white" animation="tada"></box-icon> Aprendizajes </button></div>
			 
				 <div class="col-lg-3"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"formaction="index.php"><box-icon name="x" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Cancelar</button></div></div></div>
				 
												
                         
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
