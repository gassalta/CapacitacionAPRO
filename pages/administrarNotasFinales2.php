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
<link href="estilos.css" rel="stylesheet"  type="text/css" />
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
require_once 'funciones/listarEspaciosCurriculares.php';

$ListadoEspaciosCurriculares=array();
$EspCurrCoord = array();
if($_SESSION['Categoria']=='Coordinador/a'){
$ListadoEspaciosCurriculares = Listar_EspCurr($MiConexion);	
$EspCurrCoord=ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
}
else{
$ListadoEspaciosCurriculares = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);	
}

$CantidadEspCurr = count($ListadoEspaciosCurriculares);
$CantEspCurrCoord = count($EspCurrCoord);

?>
   <div id="page-wrapper">
     <div class="row">
        <div class="col-lg-10">
           <h2 class="tile-title" ><font color="#85C1E9"><center><b>Calificaciones Finales </b></font></h2>
        </div>
     </div><br>
     <div class="row">
       <div class="col-lg-10">
        <div class="panel panel-primary">
          <div class="panel-heading">
           Seleccione una opción
          </div>
		  <div class="clearfix"></div>
        <div class="panel-body">
         
                <form role="form" method="post">
				<div class="form-group">
				 <div class="row">
				
            <div class="col-lg-10">
				  <label>Espacio curricular</label>
					<select class="form-control" name="EspCurr" id="EspCurr"> 
					
					</div></div>
					<option value=""></option>
 <?php 
$selected='';
for ($i=0 ; $i < $CantidadEspCurr ; $i++) 
	{ 
      
		if (!empty($_POST['EspCurr']) && $_POST['EspCurr'] ==  $ListadoEspaciosCurriculares[$i]['ID'])
		  {
           $selected = 'selected';
           $NombreEC=consultaEspaciosCurricularesxID($_POST['EspCurr']);
			$NEC=mysqli_fetch_array($NombreEC);
		   }
			else 
			{
              $selected='';
			}
		
	    if ($_SESSION['Categoria']!=='Coordinador/a')
				{   $EC=$_REQUEST['Cx'];
					if ($EC==$ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC'])
						{ //echo $ListadoEC[$i]['NOMBREESPACCURRIC'];
						  echo '<option value="'.$ListadoEspaciosCurriculares[$i]["ID"].'"';
						  echo $selected.'>'; 
                          echo $ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']; 
                          echo"</option>";
						}
				}
				else {
						  //echo $ListadoEC[$i]['NOMBREESPACCURRIC'];
						  echo '<option value="'.$ListadoEspaciosCurriculares[$i]["ID"].'"';
						  echo $selected.'>'; 
                          echo $ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']; 
							echo"</option>";
					    }
								
				
				
 
	} 
?>
         
				</select>
					<br>		
				<div class="row">
                  <div class="col-lg-6"align="right">
					<button type="submit" class="btn btn-primary" value="ElegirEspCurricular" name="ElegirEspCurricular"><box-icon  name="check-double" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Seleccionar</button></div>
				   <div class="col-lg-6">
					<button class="btn btn-danger" type="submit" name="Cancelar" formaction="index.php"><box-icon  name="x" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Cancelar</button></div></div>
               
                <div class="form-group">
<?php 
				 if(!empty($_POST['EspCurr'])){
?>
				
<?php              $EC=$_POST['EspCurr'];
					$EsDocente = 0;
					for ($i=0; $i < $CantEspCurrCoord; $i++) { 
						if ($_POST['EspCurr']==$EspCurrCoord[$i]['ID']) {
							$EsDocente=1;
						}
					}
				   $EC_Elegido=consultaEstudianteCursoxEspacioC($_POST['EspCurr']);
					if (mysqli_num_rows($EC_Elegido)==0){
					 echo"<h3>No existen coincidencias con la b&uacutesqueda </h3>";
					 }
					else{ 
						require_once 'funciones/buscarEspacioCurricular.php';
						$NEC=array();
						$NEC =buscarEspacCurric($MiConexion,$_POST['EspCurr']);
?>              	 <br><hr><label>Estudiantes de <?php echo $NEC['NOMBREESPACCURRIC'];?></label><hr>
					 <div class="row">
					
					
<?php 
				while($Fila=mysqli_fetch_array($EC_Elegido))
					{
						$IdEstudiante=$Fila['idEstudiante'];
						$NombreEstudiante=$Fila['nombre'];
						$ApellidoEstudiante=$Fila['apellido'];
						$Consulta=consultaCalificacionFinal($IdEstudiante,$_POST['EspCurr']);
						$Calificacion=0;
						while ($Fila=mysqli_fetch_array($Consulta))
								{
									$Calificacion=$Fila['calificacion'];
								}
					if ($_SESSION['Categoria']=='Docente'|| $EsDocente==1) {
						echo'<div class="row" align="left"><div class="col-lg-4"><a href="NotasFinalesXEstudiante.php?Cx='.$IdEstudiante.'&Cn='.$EC.'"><box-icon  name="user-circle" type="solid" size="md" color="#005eff" animation="spin-hover"></box-icon><b> '.$ApellidoEstudiante.'</b>,'.$NombreEstudiante.'</a></div>';
					} else {
						echo'<div class="row" align="left"><div class="col-lg-4"><box-icon  name="user-circle" type="solid" size="md" color="#005eff" animation="spin-hover"></box-icon><b> '.$ApellidoEstudiante.'</b>,'.$NombreEstudiante.'</a></div>';
					}
						
						echo'<div class="col-lg-3">';
						if($Calificacion!==0){
							echo'Calificación:'.$Calificacion;
							}
					     echo'</div></div>';
					}
						
                     echo"</tr></tbody>";		
					}
?>
					 </div>
<?php }?>
				 <!--</form>-->
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