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

$CantECEsDoc = 0;
$EsDocente= 0;
require_once 'funciones/buscarEvaluacion.php';

?>
<!DOCTYPE html>
<html lang="es">

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
    $EC=$_REQUEST['Cx'];
    //Listo los espacios curriculares
        require_once 'funciones/listarEspaciosCurricularesXDocente.php';
        $ListadoEC=array();
    if ($_SESSION['Categoria'] == 'Docente') {
        $ListadoEC = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
    } else {
        if ($EC!='') {
            $ListadoEC = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
        } else {
        require_once 'funciones/listarEspaciosCurriculares.php';
        $ListadoEC = Listar_EspCurr($MiConexion);
        $ListadoEspCurrEsDoc = array();
        $ListadoEspCurrEsDoc = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
        $CantECEsDoc = count($ListadoEspCurrEsDoc);
    }
    }
    $CantidadEspCurr = count($ListadoEC);
    
    ?>
    <div class="row" align="center">
      <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>
	  Evaluaciones</b></font></h2>  </div></div><br>
    </div> <!-- /.row titulo --><br>
      <div class="row">
       <div class="col-lg-10">
        <div class="panel panel-primary">
		<div class="panel-heading"></div><br>
          <div class="panel-body">
           <div class="row"align="center">
             <form role="form" method="post">
			 <div class="col-lg-1"></div>
               <div class="col-lg-2"> <label>Espacio Curricular</label></div>
               <div class="form-group">
               <div class="col-lg-6	">
                <select class="form-control" name="EspaCurri" id="EspaCurri">
<?php 
                 $selected='';
                 for($i=0 ; $i < $CantidadEspCurr ; $i++) 
					{
                     if(!empty($_POST['EspaCurri']) && $_POST['EspaCurri'] ==  $ListadoEC[$i]['ID']) 
					  {
						$selected = 'selected';
					  }
					 else 
					   {
                         $selected='';
                       }
					 if($_SESSION['Categoria']!=='Coordinador/a')
					   {   	
						if($EC==$ListadoEC[$i]['NOMBREESPACCURRIC'])
						 { //echo $ListadoEC[$i]['NOMBREESPACCURRIC'];
						  echo '<option value="'.$ListadoEC[$i]["ID"].'"';
						  echo $selected.'>'; 
                          echo $ListadoEC[$i]['NOMBREESPACCURRIC']; 
                          echo"</option>";
						 }
						}
					  else 
						{ //echo $ListadoEC[$i]['NOMBREESPACCURRIC'];
						  echo '<option value="'.$ListadoEC[$i]["ID"].'"';
						  echo $selected.'>'; 
                          echo $ListadoEC[$i]['NOMBREESPACCURRIC']; 
                          echo"</option>";
						}
					}
?>
                </select></div>
				<div class="col-lg-2"><button type="submit" class="btn btn-primary" value="Ver" name="Ver" ><box-icon  name="show-alt" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Ver</button></diV>
                </div>
				</div><!--fin row 1--><br><br>
<?php
                if(!empty($_POST['Ver']))
				  {
                    $_SESSION['EspCurrElegido'] = 0;
                  } 
                 //si hay seleccionado algún contenido...
                if(!empty($_POST['EspaCurri']))
				  {
                   //listo los contenidos del espacio curricular
                    require_once 'funciones/listarEvaluaciones.php';
                    $ListadoEvaluaciones = array();
                   if($_SESSION['EspCurrElegido'] == 0)
				     {
                       $ListadoEvaluaciones = Listar_Evaluaciones($MiConexion,$_POST['EspaCurri']);  
                       if($_SESSION['Categoria'] != 'Docente' && $CantECEsDoc > 0)
						{
                         for($i=0; $i < $CantECEsDoc; $i++)
						  { 
                           if($ListadoEspCurrEsDoc[$i]['ID']==$_POST['EspaCurri']) 
						     {
                              $EsDocente=1;
                             }
                          }
                        }
                       } 
					else
					   {
                         $ListadoEvaluaciones = Listar_Evaluaciones($MiConexion,$_SESSION['EspCurrElegido']);
                        for($i=0; $i < $CantECEsDoc; $i++)
						   { 
                            if($ListadoEspCurrEsDoc[$i]['ID']==$_SESSION['EspCurrElegido'])
							  {
                               $EsDocente=1;
                              }
                            }
                       }
                    $CantidadEvaluaciones = count($ListadoEvaluaciones); 
					if($CantidadEvaluaciones != 0)
					  {
?>
                        <!--<div class="row">
						  <div class="col-lg-12"><h3	 class="tile-title"><font color="#85C1E9"><center>Hay <?php// echo $CantidadEvaluaciones; ?> evaluacion/es para este espacio curricular<hr></font></h3></div></div>-->
						<br>
						<div class="row">
						 <div class="col-lg-2"></div>
						 <div class="col-lg-8"><div class="table-responsive">
							<table class="table table-striped table-bordered table-hover bg-info">
							 <thead>
								<tr class="bg-primary">
								<th>N° Evaluación</th>
								<th>Fecha</th>
								<th>Ver</th>
<?php 
								if($_SESSION['Categoria'] == 'Docente' || $EC != '') 
								  {
 ?>
									<th>Modificar</th>
									<th>Eliminar</th>
<?php 
								  } 
?>
								</tr>
							 </thead>
							<tbody>
<?php
                        //Cargo a la tabla el listado de las evaluaciones
							for($i=0; $i < $CantidadEvaluaciones; $i++)
								{ 
?>
                                  <tr>
                                    <td><font color="#005eff"><?php echo $ListadoEvaluaciones[$i]['ID']; ?></font></td>
									<?php
									$originalDate =$ListadoEvaluaciones[$i]['FECHA'];
									$timestamp = strtotime($originalDate); 
									$nuevaFecha = date("d-m-Y", $timestamp );?>
                                    <td><font color="#005eff"><?php echo $nuevaFecha; ?></font></td>
<?php                                    
									echo'<td><a href="buscarUnaEvaluacion.php?Tx=M&Cx='.$ListadoEvaluaciones[$i]['ID'].'&Ec='.$EC.'"><box-icon name="show-alt"  size="md" color="#005eff" animation="tada-hover"></box-icon></a></td>';
                                if ($_SESSION['Categoria'] == 'Docente' || $EC != '')
									{
									  $evalCalif=array();
									  $evalCalif=evaluacionCalificada($MiConexion, $ListadoEvaluaciones[$i]['ID']);
									  $CantEvalCalif = count($evalCalif);
									if($CantEvalCalif == 0)
									 {
									  echo'<td><a href="modificarEvaluacion.php?Tx=M&Cx='.$ListadoEvaluaciones[$i]['ID'].'&Ec='.$EC.'"><box-icon name="edit-alt" type="solid" size="md" color="#005eff"animation="tada-hover"></box-icon></a></td>';
?>
										<td><button class="btn btn-danger btn-circle" type="submit"  name="eliminar" formaction="eliminarEvaluacion.php?Cx=<?php echo$ListadoEvaluaciones[$i]['ID'].'&Ec='.$EC ?>" onclick="return confirm ('¿Seguro que desea eliminarlo?')"><box-icon  name="trash"  size="sm" color="white" animation="tada-hover"></box-icon></button></td>
<?php
									 }
									 else
									  {
										  echo '<td style="disabled"><box-icon name="edit-alt" size="md" color="grey" disabled></box-icon></td>';
										  echo'<td><box-icon  name="trash"  size="md" color="grey" animation=""></box-icon></td>';
										}
                                    }
								echo'</tr> ';
								}
 ?>
							</tbody>
						 </table> 
						</div></div>
						</div><!--fin row tabla --><br>
        <?php
        } else { ?>
		        <div class="row">
				 <div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-danger"><strong><center>El espacio curricular no tiene registrada ninguna evaluación</center></strong></div></div></div>
				</div>
      <?php }

         } 
		 else { ?>
                  <!--  <div class="row">
						<div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-info"><strong><center>Seleccione un espacio curricular</center></strong></div></div></div>
					</div> -->
      <?php  }?>
	    <br>
	  
 <?php  if($_SESSION['Categoria'] == 'Docente' || $EC != '') 
			{ 
 ?>	  
	          <div class="row" align="center">
                <div class="form-group">
				<div class="col-lg-2"></div>
				 <div class="col-lg-4"><button class="btn btn-primary" type="submit" name="ContenidoNuevo" formaction="<?php echo 'evaluacionNueva.php?Cx='.$EC; ?>"><box-icon name="layer-plus"  size="sm" color="white" animation="tada"></box-icon> Nueva Evaluaci&oacuten	
                </button></div>
				<div class="col-lg-4"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"formaction="index.php"><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div></div></div><br>
 <?php 
			}
		  else
		   {  	   
?>
				 <div class="row" align="center">
				<div class="col-lg-4"></div>
				 <div class="col-lg-4"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"formaction="index.php"><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada"></box-icon> Retornar</button></div></div><br>
 <?php 
			}
?>		
        <br><div class="row">
	  <div class="col-lg-12">
        <div class="alert alert-dismissible alert-danger"><strong> <center>¡Importante! </strong>Una vez calificada una evaluación ya no podrá modificarla ni eliminarla.</center> </div></diV>
      </div>  <!-- fin row aviso --> <br> 
     </div> <!-- fin.panel-body -->        
          </div>   <!-- fin .panel primary -->  
        </div>           <!-- fin col principal -->
    </div> <!-- fin row principal-->
   </div> <!-- fin page-wrapper -->
   </div><!-- fin wrapper -->
    

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js">
}</script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

</body>

</html>
