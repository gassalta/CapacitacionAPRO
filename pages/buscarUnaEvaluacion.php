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
$EvaluacionBuscada=array();

require_once 'funciones/buscarEvaluacion.php';
require_once 'funciones/listarAprendizajesXEspCurr.php';
$ListadoAprendizajes=array();
$_SESSION['AprendizajesGuardados']= 0;
$_SESSION['EstudianteBuscado'] = 0;
$_SESSION['CalificacionGuardada'] = 0;
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
<div class="row" align="center">
      <div class="col-lg-8"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>
	  Informaci&oacuten de Evaluaci&oacuten </b></font></h2>  </div></div><br>
    </div> <!-- /.row titulo --><br>

<?php 
     //Listo los espacios curriculares de acuerdo al docente
    $ListadoEspaciosCurriculares=array();
    if($_SESSION['Categoria']=='Coordinador/a')
	  {
        require_once 'funciones/listarEspaciosCurriculares.php';
        $ListadoEC = Listar_EspCurr($MiConexion);
      }
	else
	  {
        require_once 'funciones/listarEspaciosCurricularesXDocente.php';
        $ListadoEC = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
      }
    $CantidadEspCurr = count($ListadoEC);
    //Listo las instancias
    require_once 'funciones/listarInstancias.php';
    $ListadoInstancias=array();
    $ListadoInstancias = Listar_Instancias($MiConexion);
    $CantidadInstancias = count($ListadoInstancias);    
    $Evaluacion=$_REQUEST['Cx'];
    $EC=$_REQUEST['Ec'];
    //$_SESSION['IdEvalBuscada']==$Evaluacion;	
    ?>
    <div class="row">
      <div class="col-lg-8">
       <div class="panel panel-primary">
        <div class="panel-heading"></div>
       <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <form role="form" method="post">
<?php 
    $EvaluacionBuscada = buscarEvaluacion($MiConexion,$Evaluacion);
    $Cant = count($EvaluacionBuscada);
    if ($Cant==0) { ?>
                <div class="alert alert-dismissible alert-danger"><b><center>Número de evaluación no válido</center></b></div>
<?php   
     $_POST['EspaCurri'] ="";
     $_POST['Fecha'] = "";
     $_POST['Instancia'] = "";}
	else
		{
         $band = 0;
         for ($i=0; $i < $CantidadEspCurr; $i++) { 
            if ($EvaluacionBuscada['IDESPACURRI'] == $ListadoEC[$i]['ID']) { $band = 1;}
            }
         if ($band == 1) {
             $_POST['EspaCurri'] = $EvaluacionBuscada['IDESPACURRI'];
             $_POST['Fecha'] = $EvaluacionBuscada['FECHA'];
             $_POST['Instancia'] = $EvaluacionBuscada['IDINSTANCIA'];
             $_SESSION['IdEvalBuscada'] = $Evaluacion;
             $_SESSION['IdECdeEvalBuscada'] = $EvaluacionBuscada['IDESPACURRI'];
             $ListadoAprendizajes = Listar_AprendizajesXEval($MiConexion,$_POST['EspaCurri'],$Evaluacion);
             $CantidadAprendizajes = count($ListadoAprendizajes);
             if ($CantidadAprendizajes == 0) {
                 $_SESSION['TieneAprendizajes'] = 0;
?>
                <div class="alert alert-dismissible alert-danger"><b><center>La evaluación seleccionada no tiene aprendizajes asociados</center></b></div>
        
<?php
				}else 
					{
					 $_SESSION['TieneAprendizajes'] = 1;
					 $_SESSION['EstudianteBuscado'] = 0;
					 $_SESSION['AprendizajesGuardados'] = 0;
					}
            } else { ?>
					<div class="alert alert-dismissible alert-danger"><b><center>No tiene acceso a la evaluación indicada</center></b></div>
<?php     
					$_POST['EspaCurri'] ="";
					$_POST['Fecha'] = "";
					$_POST['Instancia'] = "";
                    }
                                            
         } 
                                      //      }?>
									        </div>
		 </div><!--row antes-Evaluacion --> 
</form>
        <div class="row" >
		<div class="col-lg-1"></div>
			<div class="col-lg-3"><label>Evaluación N°</label></div>
			<div class="col-lg-2"align="left"><input class="form-control" name="Id" readonly value="<?php echo $Evaluacion; ?>"></div>
		</div><!--row -Evaluacion --> <br>  
		<div class="row">
			<div class="col-lg-1"></div>
           <div class="col-lg-3"><label>Espacio Curricular</label></div>
		    <div class="col-lg-6">
		    <div class="form-group">
				<select class="form-control" name="EspaCurri" id="EspaCurri" disabled>
                <option value=""></option>
<?php 
                $selected='';
                 for ($i=0 ; $i < $CantidadEspCurr ; $i++) {
                    if (!empty($_POST['EspaCurri']) && $_POST['EspaCurri'] ==  $ListadoEC[$i]['ID']) {
                        $selected = 'selected';}
						else {
                               $selected='';}
?>
                <option value="<?php echo $ListadoEC[$i]['ID']; ?>" <?php echo $selected; ?>  >
						<?php echo $ListadoEC[$i]['NOMBREESPACCURRIC']; ?></option>
                        <?php } ?>
                </select>
            </div></div>	
        </div><!--row espacio curricular --><br> 
		  <div class="row">
				 	<div class="col-lg-1"></div>
                    <div class="col-lg-3"><label>Instancia</label> </div>
                     <div class="form-group">
                      <div class="col-lg-6">
                     <select class="form-control" name="Instancia" id="Instancia" disabled>
                      <option value=""></option>
                     <?php 
                      $selected='';
                        for ($i=0 ; $i < $CantidadInstancias ; $i++) {
                             if (!empty($_POST['Instancia']) && $_POST['Instancia'] ==  $ListadoInstancias[$i]['ID']) {
                             $selected = 'selected';  }
							 else {
                                    $selected='';}?>
                        <option value="<?php echo $ListadoInstancias[$i]['ID']; ?>" <?php echo $selected; ?>  >
                        <?php echo $ListadoInstancias[$i]['DENOMINACION']; ?> </option>
                          <?php } ?></select>
                     </div></div>
					</div><!--row instancia --><br>
                 <div class="row">
				 	<div class="col-lg-1"></div>
                    <div class="col-lg-3">
					 <label valign="bottom">Fecha</label></div>
					  <div class="col-lg-2">
                     <div class="form-group">
                     
                      <input valign="bottom" align='center' id="date" type="date" name="Fecha" value="<?php echo !empty($_POST['Fecha']) ? $_POST['Fecha'] : ''; ?>" disabled>
                      </div></div>
				</div><!--row fechaa --><br> 
               
					
<?php
		if(!empty($ListadoAprendizajes))
		{ 
?>
		
		<div class="row" align="center">
		
		<div class="col-lg-12">
          <div class="table-responsive">
			<table class="table table-striped table-bordered bg-info">
			 <thead>
			   <tr class="bg-primary">
				<th>N°</th>
				<th>Contenido</th>
				<th>Aprendizaje</th>
			 </tr>
			</thead>
			<tbody>
 <?php
             //Cargo a la tabla el listado de los aprendizajes
            for($i=0; $i < $CantidadAprendizajes; $i++)
			  {
?>
                <tr class="table-info">
                <td><?php echo $ListadoAprendizajes[$i]['ID']; ?></td>
                <td><?php echo $ListadoAprendizajes[$i]['CONTENIDO']	; ?></td>
                <td><?php echo $ListadoAprendizajes[$i]['DENOMINACION']; ?></td>
                </tr> 
 <?php
			  } 
?>
             </tbody>
			</table>
		 </div>
		 </div>
		 </div><!--row tabla -->  <br>
<?php 
		}
?> 
        <div class="row" align="center">
<?php
        $IrA='';
		if($_SESSION['TieneAprendizajes'] == 1)
		  {
            if ($EvaluacionBuscada['IDINSTANCIA']==1 || $EvaluacionBuscada['IDINSTANCIA']== 2) {
                $IrA='registrarCalificaciones.php?Cx='.$EC;
            } else {
                if ($EvaluacionBuscada['IDINSTANCIA']==3 || $EvaluacionBuscada['IDINSTANCIA'] == 4) {
                    $IrA='registrarCalificacionesColoquios.php?Cx='.$EC;
                } else{
                    if ($EvaluacionBuscada['IDINSTANCIA']==5 || $EvaluacionBuscada['IDINSTANCIA']==6) {
                        $IrA='registrarCalificacionesNoEtapas.php?Cx='.$EC;
                    }
                }
            }
?>	
			<div class="col-lg-2"></div>
			<div class="col-lg-4">
			 <Form Action="<?php echo 'administrarEvaluaciones.php?Cx='.$EC; ?>" Method="Post"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" ><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"  ></box-icon> Retornar</button></form></div>
			<div class="col-lg-4" ><Form Action="<?php echo $IrA; ?>" Method="Post"><button type="submit" class="btn btn-primary" value="Calificaciones" name="Calificaciones"><box-icon name="task" type="solid" size="sm" color="white" animation="tada"></box-icon> Ir a las calificaciones</button><form></div>
<?php
		 }
		 else
		  {
?>
	        <div class="col-lg-4"></div>
			<div class="col-lg-4">
			 <Form Action="<?php echo 'administrarEvaluaciones.php?Cx='.$EC; ?>" Method="Post"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" ><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada"  ></box-icon> Retornar</button></form></div>


<?php
		 }

?>	
		
		</div><!--row botones -->
                                       
        </div>
      </div><!-- /.panel-body -->
	  </div><!-- /.panel primary -->
	  </div><!-- col-row 10 -->
  </div> <!-- fin page-wrapper -->
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
