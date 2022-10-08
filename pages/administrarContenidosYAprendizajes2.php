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
<html lang="en">

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
    ?>
    <div class="row">
     <div class="col-md-12">
       <div class="tile"></div>
      </div>
      <div class="clearfix"></div>
    </div><!--fin row titulo-->
    <div class="row">
      <div class="col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
            <div class="row">
             <div class="col-lg-6">
                 <form role="form" method="post">
                  <div class="form-group">
                    <label>Espacio Curricular</label>
                      <select class="form-control" name="EspaCurri" id="EspaCurri" readonly>
                        <!--<option value=""></option>-->
<?php 
                          $selected='';
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
									}
							} 
						
?>
                       </select>
					   
                       <button type="submit" class="btn btn-default" value="Ver" name="Ver" style="background-color: #337ab7; color: white;"><box-icon  name="show" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>Ver</button>
                    </div><!--fin form group select-->
<?php

                             if (!empty($_POST['Ver'])) 
								{
                                  $_SESSION['EspCurrElegido'] = 0;
                                }
                                 //si hay seleccionado algún contenido...
								if (!empty($_POST['EspaCurri'])) 
								 {
									//listo los contenidos del espacio curricular
									require_once 'funciones/listarContenidos.php';
									$ListadoContenidos = array();
								
									if ($_SESSION['EspCurrElegido'] == 0)
										{
										$ListadoContenidos = Listar_Contenidos($MiConexion,$_POST['EspaCurri']);
										} 
									else {
											$ListadoContenidos = Listar_Contenidos($MiConexion,$_SESSION['EspCurrElegido']);
										 }
									$CantidadContenidos = count($ListadoContenidos); 
									if ($CantidadContenidos != 0) 
										{
                                                ?>
                                                

                                                <h2 class="tile-title"><font color="#85C1E9"><center>Listado  de Contenidos (<?php echo $CantidadContenidos; ?>)</font></h2>
            <div class="table-responsive">
              <table class="table-sm table-striped  bg-info">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Denominacion</th>
                    
                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de los contenidos
                            for ($i=0; $i < $CantidadContenidos; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $ListadoContenidos[$i]['ID'], "- "; ?></td>
                                    <td><?php echo $ListadoContenidos[$i]['DENOMINACION']; ?></td>
                                </tr> 
                         <?php   }

                        ?>
                        
                        
                     
                 
                </tbody>
              </table> 
            </div>
        <?php
        } else { ?>
                <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>El espacio curricular no tiene registrado ningún contenido</strong>
                </div>
              </div>
      <?php }

         } else { ?>
                <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Seleccione un espacio curricular</strong>
                </div>
              </div>
      <?php  }?>
				<div class="form-group"></div>
                								<button class="btn btn-primary" type="submit" name="ModificarContenidos" formaction="modificarContenidos.php"><box-icon  name="edit-alt" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Modificar
                                                </button>
												<button class="btn btn-primary" type="submit" name="ContenidoNuevo" formaction="contenidoNuevo.php"><box-icon name="plus-circle" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Nuevo
                                                </button>
												<button class="btn btn-primary" type="submit" name="EliminarContenido" formaction="eliminarContenido.php"><box-icon  name="trash" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Eliminar
                                                </button>
                                                <div class="form-group"></div>
                                                <center>
                                                <div class="form-group">
                                                    <button class="btn btn-primary" type="submit" name="Aprendizajes" formaction="administrarAprendizajes.php"><box-icon name="spreadsheet" type="solid" size="sm" color="white" animation="tada"></box-icon> Aprendizajes
                                                </button>
                                                </div>
                                                </center>
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
