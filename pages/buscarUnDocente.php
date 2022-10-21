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
$DocenteBuscado=array();
$DocenteEncontrado = 0; //0-No - 1-Sí

 $IdDocente=$_REQUEST['Cx'];
 $_POST['Id']= $IdDocente;
  $Cy=$_REQUEST['Cy'];
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
?>
<div id="page-wrapper">
 <div class="row">
      <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Informaci&oacuten del docente</b></font></h2>  </div></div><br>
    </div>  <!-- /.row titulo --><br>

<div class="row">
  <div class="col-lg-10">
    <div class="panel panel-primary">
       <div class="panel-heading"><center>Docente N° <?php echo $IdDocente?></center></div>
       <div class="panel-body">
        <form role="form" method="post">
 <?php 
        //Si cancela vuelvo a administrarDocentes
        if(!empty($_POST['Cancelar']))
		  {
		   if($Cy==0)
		     {
              header('Location: administrarDocentes.php');
			 }
		   else
		   {
			header('Location: administrarConceptosDocentes.php');   
		   }   
          }
		//if(!empty($_POST['Buscar']))
		//  {
			require_once 'funciones/buscarDocente.php';
            $DocenteBuscado = buscarDocente($MiConexion,$_POST['Id']);
            $Cant = count($DocenteBuscado);
			$_POST['Apellido'] = $DocenteBuscado['APELLIDO'];
            $_POST['Nombre'] = $DocenteBuscado['NOMBRE'];
            $_POST['DNI'] = $DocenteBuscado['DNI'];
            $_POST['FechaNacim'] = $DocenteBuscado['FECHANACIM'];
            $_POST['NroLegajoJunta'] = $DocenteBuscado['NROLEGAJOJUNTA'];
            $_POST['Titulo'] = $DocenteBuscado['TITULO'];
            $_POST['FechaEscalafon'] = $DocenteBuscado['FECHAESCALAFON'];
            $_POST['categorias'] = $DocenteBuscado['CATEGORIA'];
                                            $_POST['Mail'] = $DocenteBuscado['MAIL'];
                                            $_POST['UltIngreso'] = $DocenteBuscado['ULTINGRESO'];
                                          //}
                                          //  }
			if(!empty($_POST['EspCurr']))
     		  {
               if(empty($_POST['DNI']))
				{ 
?>
                  <div class="alert alert-dismissible alert-danger"><strong>Primero debe seleccionar un docente</strong></div>
<?php 
				} 
			   else
				{
                  $_SESSION['DNIDocenteElegido'] = $_POST['DNI'];
                  $_SESSION['Envia'] = 'buscarUnDocente.php';
                  header('Location: EspCurrXDocente.php?Cx='.$IdDocente);
                }
             }
 ?>            
				<br><div class="row">
                  <div class="col-lg-6"><div class="form-group"><label for="disabledSelect">Apellido y Nombre</label><input class="form-control" name="Apellido" value="<?php echo !empty($_POST['Apellido']) ? $_POST['Apellido'] : '';echo', '; echo !empty($_POST['Nombre']) ? $_POST['Nombre'] : ''; ?>" readonly></div></div>
				  <div class="col-lg-3"><div class="form-group"><label for="disabledSelect">DNI</label><input class="form-control" name="DNI" value="<?php echo !empty($_POST['DNI']) ? $_POST['DNI'] : ''; ?>" readonly> </div></div>
				  <div class="col-lg-3"><div class="form-group"><label for="disabledSelect">Legajo en Junta</label><input class="form-control" name="NroLegajoJunta" value="<?php echo !empty($_POST['NroLegajoJunta']) ? $_POST['NroLegajoJunta'] : ''; ?>" readonly></div></div>
				</div><!-- fin primera row--><br>
				<div class="row">
                  <div class="col-lg-6"><div class="form-group"><label for="disabledSelect">E-mail</label><input class="form-control" name="Mail" value="<?php echo !empty($_POST['Mail']) ? $_POST['Mail'] : ''; ?>" readonly></div></div>
				   <div class="col-lg-6"><div class="form-group"><label for="disabledSelect">Título</label><textarea class="form-control" rows="1" name="Titulo" readonly><?php echo !empty($_POST['Titulo']) ? $_POST['Titulo'] : ''; ?></textarea></div></div>
				 </div><!-- fin segunda row--><br>
				<div class="row" align="center" >
				<div class="form-group">
				 <div class="col-lg-4"><label for="disabledSelect">Fecha de Nacimiento </label><input id="date" type="date" name="FechaNacim" value="<?php echo !empty($_POST['FechaNacim']) ? $_POST['FechaNacim'] : ''; ?>" readonly></div>
				 <div class="col-lg-4"><label for="disabledSelect">Fecha Último Ingreso</label><input class="form-control" id="disabledInput" type="text" name="UltIngreso" value="<?php echo !empty($_POST['UltIngreso']) ? $_POST['UltIngreso'] : ''; ?>" readonly></div>
				 <div class="col-lg-4"><label for="disabledSelect">Fecha de Escalafón </label><input id="date" type="date" name="FechaEscalafon" value="<?php echo !empty($_POST['FechaEscalafon']) ? $_POST['FechaEscalafon'] : ''; ?>" readonly></div></div>
				</div><!-- fin tercera row--><br><br>
		       <div class="row" align="center">
			    <div class="col-lg-2"></div>	
			    
				  <div class="col-lg-4">
				  <div class="panel panel-info">
				   <div class="panel-heading"><center><b>Espacio Curriculares</b></center></div>
				   <div class="panel-body">
				   <ul>
<?php
				require_once 'funciones/listarEspaciosCurriculares.php';
				require_once 'funciones/buscarDocente.php';
				$Listado=array();
				$Listado = buscarDocenteXDNI($MiConexion,$DocenteBuscado['DNI']);
				$CantidadDocentes = count($Listado);
				$ListadoEspCurricular = array();
				$ListadoEspCurricular = Listar_EspCurr($MiConexion);
				$CantEspCurricular = count($ListadoEspCurricular);
				$ListadoEspCurrXDoc = ListarEspCurrXDocente($MiConexion,$Listado['ID']);
				$CantEspCurrXDoc = count($ListadoEspCurrXDoc);
				for($i=0; $i < $CantEspCurricular; $i++)
				  { 
					for($j=0; $j < $CantEspCurrXDoc; $j++)
					  { 
					   if($ListadoEspCurricular[$i]['ID'] == $ListadoEspCurrXDoc[$j]['ID'])
						{
						 echo'<li>'.$ListadoEspCurricular[$i]['NOMBREESPACCURRIC'].'</li>';	
    
						}
					  } 
				  }
?>
					</ul>
					</div>
				    </div>
				  </div>
				   <div class="col-lg-3">	
                    <div class="form-group">	
				    <div class="panel panel-info">
				    <div class="panel-heading"><center><b>Categoría</b></center></div>
					<div class="panel-body"
					<div class="row" align="center"><div class="col-lg-12"><b> <?php  if ($DocenteBuscado['CATEGORIA']==1){echo 'Cordinador/a';} elseif($DocenteBuscado['CATEGORIA']==2){echo 'Secretario/a';} elseif($DocenteBuscado['CATEGORIA']==3){echo 'Preceptor/a';}else{echo 'Docente';}  ?></b></div></div>                   
				   </div></div></div>
				   </div><!--fin row paneles--><br>
		
	
		
				<div class="row" align="center">
				<!-- <div class="col-lg-12"><button type="button" onclick="window.history.back();" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada"  ></box-icon> Retornar</button></div> -->
                </div>  <!--fin row botones --><br>
      
     </div>   <!-- /.panel-body -->
    </div>   <!-- fin panel primary -->
   </div> <!-- fin col primary -->
  </div><!-- /fin row primaryl -->
 </div> <!-- /#page-wrapper -->
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