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
 $idEC=$_REQUEST['Cx'];
	$_POST['Id']=$idEC;
require_once 'funciones/buscarEspacioCurricular.php';
//Conecto con la base de datos
require_once 'funciones/conexion.php';
$MiConexion=ConexionBD();

//Listo las áreas
require_once 'funciones/listarAreas.php';
$ListadoAreas = Listar_Areas($MiConexion);
$CantAreas = count($ListadoAreas);
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
?>
   <div id="page-wrapper">
    <div class="row">
      <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Consulta del espacio curricular</b></font></h2>  </div></div><br>
    </div>  <!-- /.row titulo --><br>
    <div class="row">
      <div class="col-lg-10">
       <div class="panel panel-primary">
         <div class="panel-heading">Datos del espacio curricular N° <?php echo $idEC ?></div>
          <div class="panel-body">
            <div class="row">
              <div class="col-lg-12">
               <form role="form" method="post">
<?php 
                //Si cancela vuelvo a administrarEspaciosCurriculares
                if(!empty($_POST['Cancelar']))
				  {
                   header('Location: administrarEspaciosCurriculares.php');
                  }
				if(!empty($_POST['Curso']))
				  {
                   if(empty($_POST['NombreEspCurr']))
					{ 
?>
                     <div class="alert alert-dismissible alert-danger"><strong><center>Primero debe buscar un Espacio Curricular</center></strong></div>
<?php   
					} 
				   else
					{ 
                     $_SESSION['IdEspCurrSeleccionado'] = $_POST['Id'];
                     $_SESSION['NombreEspCurrSeleccionado'] = $_POST['NombreEspCurr'];
                     header('Location: EspCurrXCurso.php');
                    } 
                  }
                    //Si confirma verifico los campos
                //if(!empty($_POST['Buscar']))
				 // {
                   if(empty($_POST['Id']))
					{
?>
					 <div class="alert alert-dismissible alert-danger"><strong><center>Debe ingresar un número  de Espacio Curricular</center></strong></div>
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
                            }
						 else 
							{
?>
							 <div class="alert alert-dismissible alert-danger"><strong><center>Número de Espacio Curricular no válido</center></strong></div>
 <?php
                             $_POST['NombreEspCurr'] = '';
                             $_POST['Area'] = '';
                            }
                        } 
					}
                   //} 
?>
			     </div>
			    </div><!--row errores-->
				<div class="row">
				  <div class="col-lg-3"><label >N° Espacio Curricular</label></DIV>
				  <div class="col-lg-3"> <input class="form-control" name="Id" value="<?php echo !empty($_POST['Id']) ? $_POST['Id'] : ''; ?>" readonly></div>
                  <!--<div class="col-lg-4"><button class="btn btn-primary" type="submit" value="Buscar" name="Buscar"><box-icon  name="search-alt" size="sm" color="white" animation="tada"></box-icon>Buscar</button></div>-->
				</div><!-- row busqueda--><hr>
				<div class="row">
				  <div class="col-lg-6">  
				   <div class="form-group"> <label>Nombre Espacio Curricular</label><input class="form-control" name="NombreEspCurr" value="<?php echo !empty($_POST['NombreEspCurr']) ? $_POST['NombreEspCurr'] : ''; ?>" readonly></div>
			      </div>
			      <div class="col-lg-6">  					
                   <div class="form-group"><label>Área</label><select class="form-control" name="Area" id="Area" readonly>
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantAreas ; $i++) {
                                                    if (!empty($_POST['Area']) && $_POST['Area'] ==  $ListadoAreas[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoAreas[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoAreas[$i]['DENOMINACION']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select></div>
			      </div>
		        </div><!-- /.row input --><hr>
				<div class="row">
				 <div class="col-lg-3"></div>
				 <div class="col-lg-6">
				  <div class="panel panel-info">
					<div class="panel-heading"><center><b>Curso en el que se dicta</b></center></div>
					<div class="panel-body" align="center">
				       <div class="form-group">
 <?php
						  if($CantEspCurricular==0)
							{
?>	  
								<label>Actualmente no se dicta en ningún curso</label>
<?php  
							}
						  else
						    {  
							 for($i=0; $i < $CantidadCursos; $i++)
								{ 
								 if($Listado[$i]['ID'] == $EspacCurric['CURSO'])
								   {
?>			
									<label>Año:  <?php echo $Listado[$i]['ANIO']." - Division: ".$Listado[$i]['DIVISION']; ?></label>
<?php 
									} 
								}
							}
?>
						</div>
					</div>
				 </div>
				</div>
				</div><!-- /.row cursos --><br>
				<div class="row" align="center">
				 <div class="col-lg-12">  
				 <button type="submit" class="btn btn-primary" value="Cancelar" name="Cancelar"><box-icon name="check-double"  type='solid'size="sm" color="white" animation="tada"></box-icon> Aceptar</button></div>
			    </div>

                                     <!--   <center>
                                            <div>
                                                <button type="submit" class="btn btn-default" value="Curso" name="Curso" style="background-color: #888ffc">Curso en que se dicta</button>
                                            </div>
                                        </center>
                                </div> -->
   
            </div><!-- /.panel-body -->
          </div>  <!-- /.panel primary -->
        </div><!-- /.col-proncipal -->
       </div> <!-- /.row principal -->
     </div>  <!-- /#page-wrapper -->
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
