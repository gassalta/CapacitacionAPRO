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

//$ListadoContenidos=array();
//$ListadoContenidos = mysqli_fetch_array(consultaContenido());
//$CantidadContenidos = count($ListadoContenidos);
	
?>
   <div id="page-wrapper">
     <div class="row">
        <div class="col-lg-12">
           <h1 class="page-header">Consulta de contenidos y aprendizajes</h1>
        </div>
     </div>
     <div class="row">
       <div class="col-lg-18">
        <div class="panel panel-default">
          <!--<div class="panel-heading">
            Búsqueda..
          </div>-->
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-15">
                <form role="form" method="post">
				<div class="form-group">
					<center>
                                                <div class="form-group">
                                                    <button class="btn btn-primary" type="submit" name="Aprendizajes" formaction="EmitirListadoAprendizajesXEspCurr.php"><box-icon name="spreadsheet" type="solid" size="sm" color="white" animation="tada"></box-icon> Emitir listado de Aprendizajes por Espacio Curricular
                                                </button>
                                                </div>
                                                </center>
				  <label>Espacio curricular</label>
					<select class="form-control" name="EspCurr" id="EspCurr"> 
					<option value="">Seleccione una opción</option>
					
 <?php 
						
				/*	$CEspacio=consultaEspaciosCurriculares();
					while($Fila=mysqli_fetch_array($CEspacio)){
							$CoEspacio=$Fila['Id'];
							$NEspacio=$Fila['NombreEspacCurric'];
							echo "<option value='$CoEspacio'>$NEspacio</option>";}
					$NombreEC=consultaEspaciosCurricularesxID($_POST['EspCurr']);
					$NEC=mysqli_fetch_array($NombreEC); */

                                                $selected='';
                                                for ($i=0 ; $i < $CantidadEspCurr ; $i++) {
                                                    if (!empty($_POST['EspaCurri']) && $_POST['EspaCurri'] ==  $ListadoEspaciosCurriculares[$i]['ID']) {
                                                        $selected = 'selected';
                                                        $NombreEC=consultaEspaciosCurricularesxID($_POST['EspCurr']);
														$NEC=mysqli_fetch_array($NombreEC);
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoEspaciosCurriculares[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']; ?>
                                                    </option>
                                                <?php } ?>
?>          
					</select>
					<br>						
                  <button type="submit" class="btn btn-primary" value="ElegirEspCurricular" name="ElegirEspCurricular"> Seleccionar</button>
    			<button class="btn btn-primary" type="submit" name="Cancelar" formaction="administrarContenidosYAprendizajes.php"><box-icon  name="task-x" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Cancelar
                                                </button>
                <br>
                <div class="form-group">
<?php 
				 if(!empty($_POST['EspCurr'])){
?>
				 <!--<form Action="Aprendizajes.php" Method="Post">  -->
<?php           
				   $EC_Elegido=consultaContenidoxEspaciosCurriculares($_POST['EspCurr']);
					if (mysqli_num_rows($EC_Elegido)==0){
					 echo"<h3>No existen coincidencias con la b&uacutesqueda </h3>";
					 }
					else{ 
						require_once 'funciones/buscarEspacioCurricular.php';
						$NEC=array();
						$NEC =buscarEspacCurric($MiConexion,$_POST['EspCurr']);
?>              	 <br><br><label>Contenido/s de  <?php echo $NEC['NOMBREESPACCURRIC'];?></label>
					 <div class="table-responsive-sm">
					 <table class="table  table-striped table-hover">
					 <tbody><tr>
<?php 
					 while($Fila=mysqli_fetch_array($EC_Elegido)){
						$CoContenido=$Fila['id'];
						$NContenido=$Fila['denominacion'];
						echo'<td><a href="Aprendizajes.php?Cx='.$CoContenido.'&Cn='.$NContenido.'">'.$NContenido.'</a></td>';
					//echo"<td><button type='submit' class='btn btn-info' value='$CoContenido' name='ElegirContenido'>$NContenido</button></td>";
					
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