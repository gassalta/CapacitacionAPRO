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
    <div class="col-lg-8"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Calificaciones Finales </b></font></h2></div>
   </div><!--fin row titulo--><br>
  <div class="row">
    <div class="col-lg-8">
     <div class="panel panel-primary">
       <div class="panel-heading"></div><br>
	   <div class="panel-body">
        <form role="form" method="post">
		  <div class="form-group">
			<div class="row"align="center">
			 <div class="col-lg-1"></div>
			 <div class="col-lg-2"><label>Espacio curricular</label></div>
			<div class="col-lg-8"><select class="form-control" name="EspCurr" id="EspCurr"> 
				<?php
				$EC=$_REQUEST['Cx'];
				if ($EC == "") { ?>
					<option value=""></option>
		<?php		}
				?>
			
 <?php 
			  $selected='';
			  for($i=0 ; $i < $CantidadEspCurr ; $i++) 
				{ 
				  if(!empty($_POST['EspCurr']) && $_POST['EspCurr'] ==  $ListadoEspaciosCurriculares[$i]['ID'])
					{
					 $selected = 'selected';
					 $NombreEC=consultaEspaciosCurricularesxID($_POST['EspCurr']);
					 $NEC=mysqli_fetch_array($NombreEC);
					}
				  else 
					{
					 $selected='';
					}
					
				 if($EC != "")  //$_SESSION['Categoria']!=='Coordinador/a')
				   {
					
					if($EC==$ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC'])
					  { //echo $ListadoEC[$i]['NOMBREESPACCURRIC'];
					   echo '<option value="'.$ListadoEspaciosCurriculares[$i]["ID"].'"';
					   echo $selected.'>'; 
                       echo $ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']; 
                       echo"</option>";
					  }
					}
				 else 
				    {
					 //echo $ListadoEC[$i]['NOMBREESPACCURRIC'];
					 echo '<option value="'.$ListadoEspaciosCurriculares[$i]["ID"].'"';
					 echo $selected.'>'; 
                     echo $ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']; 
					 echo"</option>";
					}
				} 
?>
            </select></div>
			</div><!--fin row1--><br><br>		
			<div class="row" align="center">
			   <div class="col-lg-2"></div>
               <div class="col-lg-4"><button type="submit" class="btn btn-primary" value="ElegirEspCurricular" name="ElegirEspCurricular"><box-icon  name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Seleccionar</button></div>
				<div class="col-lg-4"><button class="btn btn-danger" type="submit" name="Cancelar" formaction="index.php"><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div>
			</div><!--fin row botones--><br><br>
			</div><!--fin form group-->
           <div class="form-group">
<?php 
if(!empty($_POST['EspCurr']))
	{
	 $EC=$_POST['EspCurr'];
	 $EsDocente = 0;
	 for($i=0; $i < $CantEspCurrCoord; $i++)
	   { 
		if($_POST['EspCurr']==$EspCurrCoord[$i]['ID'])
		 {
		  $EsDocente=1;
		 }
		}
	 $EC_Elegido=consultaEstudianteCursoxEspacioC($_POST['EspCurr']);
	 if(mysqli_num_rows($EC_Elegido)==0)
	  {
	   echo"<h3>No existen coincidencias con la b&uacutesqueda </h3>";
	  }
	 else
	  { 
	    require_once 'funciones/buscarEspacioCurricular.php';
		$NEC=array();
		$NEC =buscarEspacCurric($MiConexion,$_POST['EspCurr']);
?>              	 
		<div class="row">
         <div class="col-lg-12">
		
		 <div class="table-responsive"><table class="table table-striped table-bordered bg-info">
         <thead>
          <tr class="bg-primary">
<?php
		  if ($_SESSION['Categoria']=='Docente'|| $EsDocente==1)
			{
		     echo '<th>Editar</th>';
		    }
?>
            <th>Apellido y Nombre</th>
			  <th>Calificación</th>
          </tr>
         </thead>
         <tbody>
<?php 
		require_once 'funciones/listarEstudiantes.php';
		$TodosEstudiantes = ListarEstudiantesXCurso($MiConexion, $NEC['CURSO']);
		$CantTodosEst = count($TodosEstudiantes);
		$EstudiantesConNota = ListarEstudiantesConNota($MiConexion,$_POST['EspCurr']);
		$CantEstCNota = count($EstudiantesConNota);
		for ($i=0; $i < $CantTodosEst; $i++) 
		  { 
			$Calificacion = 0;
			$IdEstudiante=$TodosEstudiantes[$i]['ID'];
			$NombreEstudiante=$TodosEstudiantes[$i]['NOMBRE'];
			$ApellidoEstudiante=$TodosEstudiantes[$i]['APELLIDO'];
			for($j=0; $j < $CantEstCNota; $j++)
			   { 
				if($EstudiantesConNota[$j]['ID']==$TodosEstudiantes[$i]['ID'])
				  {
					$Calificacion = $EstudiantesConNota[$j]['CALIFICACION'];
				  }
				}
			if($_SESSION['Categoria']=='Docente'|| $EsDocente==1)
			  {
				echo'  <tr><td><a href="NotasFinalesXEstudiante.php?Cx='.$IdEstudiante.'&Cn='.$EC.'"><box-icon  name="edit-alt" type="solid" size="md" color="#005eff" animation="spin-hover"></box-icon></a></td><td><b> '.$ApellidoEstudiante.'</b>,'.$NombreEstudiante.'</td>';
			  }
			else 
			  {
			   echo' <tr><td><b> '.$ApellidoEstudiante.'</b>,'.$NombreEstudiante.'</a></td> ';
			  }
			if($Calificacion!==0)
			  {
				 echo'<td><b>'.$Calificacion.'</b></td></tr>';
			  }
			else
			  {
				echo'<td></td></tr>';
			  }
		  }
 ?>		  
		    </tbody>
              </table>
            </div><!-- fin tabla-->
         
        </div><!-- fin col tabla-->
       
        
      </div> 
<?php
	}
   }

 ?>					
                 
		
      
    </div><!-- fin form group</form>-->
	</div><!-- fin panel body-->
	</div><!-- fin panel primary-->
	 </div><!-- fin col principal-->
	</div><!-- fin row principal-->
	</div><!-- fin page wrapper -->
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