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
require_once 'funciones/baseDeDatos.php';
$EspacioCurricElegido = array();
$ContenidoElegido = array();
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
	//Listo los Espacios Curriculares
require_once 'funciones/listarEspaciosCurricularesXDocente.php';
$ListadoEspaciosCurriculares=array();
$ListadoEspaciosCurriculares = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
$CantidadEspaciosCurriculares = count($ListadoEspaciosCurriculares);
//$EC=$_REQUEST['Ec']; traigo el numero de espacio curricular
	$EsDocente=0;
?>
   <div id="page-wrapper">
     <div class="row">
        <div class="col-lg-12">
           <h1 class="page-header"><font color="#85C1E9">Consulta de contenidos y aprendizajes</font></h1>
        </div>
     </div>
     <div class="row">
       <div class="col-lg-10">
        <div class="panel panel-primary">
          <div class="panel-heading">Contenidos por Espacio Curricular</div>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-10">
                <form role="form" method="post">
				<div class="form-group">
				<div class="row">                 
				  <div class="col-lg-3"> <label>Espacio curricular</label></div>
					<div class="col-lg-5">
					 <select class="form-control" name="EspCurr" id="EspCurr" readonly> 
					<!--	<option value="">Seleccione una opción</option> -->
					
 <?php 
		
                     $selected='';
                     for ($i=0 ; $i < $CantidadEspCurr ; $i++) 
						{
							
                        /*  if (!empty($_POST['EspCurr']) && $_POST['EspCurr'] ==  $ListadoEspaciosCurriculares[$i]['ID']) 
							{
                             $selected = 'selected';
                             $NombreEC=consultaEspaciosCurricularesxID($_POST['EspCurr']);
							 $NEC=mysqli_fetch_array($NombreEC); }
					     else {
                               $selected='';}

?>
						<option value="<?php echo $ListadoEspaciosCurriculares[$i]['ID']; ?>" <?php echo $selected; ?>  > <?php echo $ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']; ?> </option> */
						if ($_SESSION['Categoria']!=='Coordinador/a')
								{   $EC=$_REQUEST['Cx'];	
								 if ($EC==$ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC'])
									{ //echo $ListadoEC[$i]['NOMBREESPACCURRIC'];
									echo '<option value="'.$ListadoEspaciosCurriculares[$i]["ID"].'">';
									echo $selected; 
                                    echo $ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']; 
                                    echo"</option>";
									}
								}
							else 
									{ //echo $ListadoEC[$i]['NOMBREESPACCURRIC'];
									echo '<option value="'.$ListadoEspaciosCurriculares[$i]["ID"].'">';
									echo $selected; 
                                    echo $ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']; 
                                    echo"</option>";
                  $EsDocente=1;
									}
 					}
 ?>
         
					</select>
					</div>
					<div class="col-lg-2">
						<button type="submit" class="btn btn-primary" value="ElegirEspCurricular" name="ElegirEspCurricular"><box-icon  name="show" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Ver</button>
					</div>
				</diV><hr>						
               
                <div class="form-group">
<?php 
				 if(!empty($_POST['EspCurr']))
					{
					 $EC_Elegido=consultaContenidoxEspaciosCurriculares($_POST['EspCurr']);
					 if (mysqli_num_rows($EC_Elegido)==0)
						{
							                echo'<div class="bs-component">
                <div class="alert alert-dismissible alert-danger">
                  <strong>No existen coincidencias con la b&uacutesqueda</strong>
                </div>
              </div>';
						// echo"<h3> </h3>";
						}
					else{ 
						require_once 'funciones/buscarEspacioCurricular.php';
						$NEC=array();
						$NEC =buscarEspacCurric($MiConexion,$_POST['EspCurr']);
						$NombreEC = $NEC['NOMBREESPACCURRIC'];
?>              	 	
						<div class="row">
						 <div class="col-lg-10"><label>Contenido/s de  <?php echo $NEC['NOMBREESPACCURRIC'];?></label></div></div>
						<div class="row">
						 <div class="col-lg-10">
						   <ul>
<?php 
						while($Fila=mysqli_fetch_array($EC_Elegido))
							{
							 $CoContenido=$Fila['id'];
							 $NContenido=$Fila['denominacion'];
							 echo'<li><a href="Aprendizajes.php?Cx='.$CoContenido.'&Cn='.$NContenido.'&Ec='.$NombreEC.'">'.$NContenido.' <box-icon name="edit-alt" type="solid" size="sm" color="blue" animation="tada"></box-icon></a>';
							}
                     echo"</ul>";		
						}
?>
					 </div></div><hr>
					 
<?php }

$EspCurrElegido=array();
      $NEC=$_REQUEST['Cx'];
      if (!empty($_POST['EspCurr'])) {
        require_once 'funciones/buscarEspacioCurricular.php';
        $EspCurrElegido=buscarEspacCurric($MiConexion,$_POST['EspCurr']);
        $NEC = $EspCurrElegido['NOMBREESPACCURRIC'];
      }
?>
<div class="row">
    
    <div class="col-lg-3">
   <button class="btn btn-danger" type="submit" name="Cancelar" formaction="administrarContenidosYAprendizajes.php?Cx=<?php echo $NEC; ?>"><box-icon  name="x" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Cancelar </button> </div>
  <div class="col-lg-6">											
        <button class="btn btn-primary" type="submit" name="Aprendizajes" formaction="EmitirListadoAprendizajesXEspCurr.php?Cx=<?php echo $NEC; ?>"><box-icon name="spreadsheet" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Emitir listado de Aprendizajes por Espacio Curricular</button></div></div>
                                                






				 <!--</form>-->
                </div>  
		</div>
       </div>
      </div>
    </div>
	</div>
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