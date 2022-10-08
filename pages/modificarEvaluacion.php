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

$NroEval = $_REQUEST['Cx'];
$EC=$_REQUEST['Ec'];

require_once 'funciones/buscarEvaluacion.php';
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
  <div class="row" align="center">
      <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Modificar Evaluación</b></font></h2>  </div></div>
    </div> <!-- /.row titulo --><br>
<?php 
	//Listo las instancias
    require_once 'funciones/listarInstancias.php';
    $ListadoInstancias=array();
    $ListadoInstancias = Listar_Instancias($MiConexion);
    $CantidadInstancias = count($ListadoInstancias);
?>
  <div class="row">
   <div class="col-lg-10">
     <div class="panel panel-primary">
       <div class="panel-heading"><center>Evaluación N° <?php echo $NroEval;?></center></div>
         <div class="panel-body">
          <div class="row">
           <div class="col-lg-12">
            <form role="form" method="post">
 <?php 
             //Si cancela vuelvo a administrarEvaluaciones
            if(!empty($_POST['Cancelar']))
			  {
                header('Location: administrarEvaluaciones.php?Cx='.$EC);
               }
			//Si quiere ir a los aprendizajes a evaluar, reviso que la evaluación no haya sido calificada
            if(!empty($_POST['Aprendizajes']))
			  {
               $_POST['EspaCurri'] = $_SESSION['ECdeEvalBuscada'];
               $evalCalif=array();
               $evalCalif=evaluacionCalificada($MiConexion,$_SESSION['IdEvalBuscada']);
               $CantEvalCalif = count($evalCalif);
               if($CantEvalCalif != 0)
				{
?>
                 <div class="alert alert-dismissible alert-danger"><strong><center>La evaluación ya fue calificada, por lo que no puede modificarla</center></strong></div>
<?php
                }
			   else
				{
                 $_SESSION['FechaEvalElegida'] = $_POST['Fecha'];
                 $_SESSION['EspaCurriEleg'] = $EvaluacionBuscada['IDESPACURRI'];
                 $_SESSION['NroEval'] = $NroEval;
                 $_SESSION['EnviaEval'] = "modificarEvaluacion.php";
                 header('Location: aprendizajesXEvaluacion.php?Cx='.$EC);
                }
               }
                //Si confirma verifico los campos
            if(!empty($_POST['Confirmar']))
			  {
               $mensaje = '';
               $_POST['EspaCurri'] = $_SESSION['ECdeEvalBuscada'];
               if(empty($_POST['Fecha']) || empty($_POST['Instancia']))
				{
                 $mensaje = "Debe seleccionar fecha e instancia correspondientes a la evaluación - ";
                }
                $evalCalif=array();
                $evalCalif=evaluacionCalificada($MiConexion,$_SESSION['IdEvalBuscada']);
                $CantEvalCalif = count($evalCalif);
               if($CantEvalCalif != 0)
				 {
                  $mensaje = $mensaje."La evaluación ya fue calificada, por lo que no puede modificarla";
                 }
                //Si está todo bien modifico evaluación en base de datos, sino, muestro mensaje
               if($mensaje == '')
				 {
                  require_once 'funciones/guardarEvaluacion.php';
                  $id= $_SESSION['IdEvalBuscada'];
                  if(modificarEvaluación($MiConexion,$id,$_POST['Instancia'],$_POST['Fecha']))
					{
                     $_POST['Id'] = $_SESSION['IdEvalBuscada'];
?>
					 <div class="alert alert-dismissible alert-success"><strong><center>¡Evaluación número <?php echo $_SESSION['IdEvalBuscada']; ?> modificada correctamente!</center></strong></div></div>
<?php  
					}
                 } 
			   else 
			    {
 ?>
				  <div class="alert alert-dismissible alert-danger"><strong><center><?php echo $mensaje; ?></center></strong></div>
<?php
                } 

               }
?>
			</div>
            </div><!--fin row errores--><br>
<?php
        require_once 'funciones/buscarEvaluacion.php';
        $EvaluacionBuscada = buscarEvaluacion($MiConexion,$NroEval);
        $Cant = count($EvaluacionBuscada);
        if($Cant==0)
		  { 
?>
           <div class="alert alert-dismissible alert-danger"><strong><center>Número de evaluación no válido</center></strong></div>
<?php   
           $_POST['EspaCurri'] =$EC;
           $_POST['Fecha'] = "";
           $_POST['Instancia'] = "";
          } 
		else 
		  {
            $_POST['EspaCurri'] = $EC;
            $_POST['Fecha'] = $EvaluacionBuscada['FECHA'];
            $_POST['Instancia'] = $EvaluacionBuscada['IDINSTANCIA'];
            $_SESSION['IdEvalBuscada'] = $NroEval;
            $_SESSION['ECdeEvalBuscada'] = $EC;
		   } 
?>
        <div class="row" align="center">
          <div class="form-group">
		
		  <div class="col-lg-2"></div>
          <div class="col-lg-8"><label>Espacio Curricular</label><input class="form-control" name="EspaCurri" value="<?php echo $EC; ?>" readonly></div> </div>
       </div><!--fin row espacio curricular--><br><br>
        <div class="row"align="center">
         <div class="form-group">
		 <div class="col-lg-2"></div>
		   <div class="col-lg-1"><label valign="bottom">Fecha</label></diV>
		   <div class="col-lg-2"><input valign="bottom" id="date" type="date" name="Fecha" value="<?php echo !empty($_POST['Fecha']) ? $_POST['Fecha'] : ''; ?>"></div>
		   	 <div class="col-lg-1"></div>
           <div class="col-lg-1"><label>Instancia</label></div>
           <div class="col-lg-3">
		    <select class="form-control" name="Instancia" id="Instancia">
<?php 
            $selected='';
            for($i=0 ; $i < $CantidadInstancias ; $i++) 
			 {
              if(!empty($_POST['Instancia']) && $_POST['Instancia'] ==  $ListadoInstancias[$i]['ID'])
				{
                 $selected = 'selected';
                }
			   else
				{
                 $selected='';
                }
?>
				<option value="<?php echo $ListadoInstancias[$i]['ID']; ?>" <?php echo $selected; ?>><?php echo $ListadoInstancias[$i]['DENOMINACION']; ?></option>
<?php
			 }
?>
            </select></div>
		</div></div><!--fin row fecha instancia--><br><br><br>
		 <div class="row">
		      <div class="col-lg-1"></div>
              <div class="col-lg-4"><button type="submit" class="btn btn-primary" value="Aprendizajes" name="Aprendizajes"  onclick="return confirm ('¿Desea dirigirse a los Aprendizajes a evaluar? - Puede perder los cambios realizados a la evaluación')"><box-icon name="spreadsheet" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Modificar Aprendizajes a Evaluar</button></div>
			    <div class="col-lg-1"></div>
            <div class="col-lg-2"align="right"><button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('¿Desea modificar la evaluación?');"><box-icon name="check-double"  size="sm" color="white"animation="tada" ></box-icon> Confirmar</button></div> 
			 <div class="col-lg-1"></div>
			<div class="col-lg-2"align="right"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" onclick="return confirm ('¿Desea retornar? - No se guardarán los cambios')"><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"  ></box-icon> Retornar</button></div>
        </diV><!--fin row botones--><br>
           
     </div> <!-- fin panel-body -->      
    </div>  <!-- fin panel primary-->
   </div> <!-- fin col principal --> 
  </div>  <!-- fin row principal -->   
</div><!-- fin page-wrapper -->
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
