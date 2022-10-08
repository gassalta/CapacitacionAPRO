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
require_once 'funciones/buscarCurso.php';
//Conecto con la base de datos
require_once 'funciones/conexion.php';
$MiConexion=ConexionBD();

//Declaro variables
$mensaje='';

require_once 'funciones/listaCursos.php';
$ListadoCursos=array();
$ListadoCursos = ListarCursos($MiConexion);
$CantidadCursos = count($ListadoCursos);
$Emitidas = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php
   require_once 'encabezado.php';
?>
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
  <div class="row">
    <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Libretas de Calificaciones</b></font></h2>  </div></div><br>
  </div>  <!-- /.row titulo -->
  <div class="row">
   <div class="col-lg-10">
     <div class="panel panel-primary">
        <div class="panel-heading"></div>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12">
              <form role="form" method="post">
<?php 
                //Si cancela vuelvo a administrarEspaciosCursos
                if(!empty($_POST['Cancelar'])) 
				   {
                    header('Location: index.php');
                    }
				if(!empty($_POST['EmitirInformes'])) 
				  {
            if (empty($_POST['Curso'])) {
              ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>Debe seleccionar un curso</strong>
                </div>
             <?php
            } else {
              if (empty($_POST['Etapa'])) {
                ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>Debe seleccionar una etapa</strong>
                </div>
             <?php
              } else {
                $files = glob('funciones/Informes/*'); //obtenemos todos los nombres de los ficheros
foreach($files as $file){
    if(is_file($file))
    unlink($file); //elimino el fichero
}
             require_once 'funciones/listarEstudiantes.php';
            $_SESSION['idCurso'] = $_POST['Curso'];
            $_SESSION['Etapa'] = $_POST['Etapa'];
            $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$_SESSION['idCurso']);
            $CantidadEstudiantes = count($ListadoEstudiantes);
              if ($CantidadEstudiantes == 0) {
                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>El curso seleccionado no tiene estudiantes asignados</strong>
                </div>
             <?php
              } else {
                require_once 'funciones/emitirPDFInformesCYA.php';
                for ($i=0; $i < $CantidadEstudiantes; $i++) { 
                  generate($_SESSION['idCurso'],$_SESSION['Etapa'],$ListadoEstudiantes[$i]['ID']);
                }
                ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong><center>Los informes se generaron y están listos para su descarga</center></strong>
                </div>
              </div>
                                                    <?php
                $Emitidas = 1;
              }
            }
            }
          } 
?>
			</div><!--Cierra col errores-->
		</div><!--Cierra Row errores-->
		<div class="row">
			<div class="col-lg-3"><label>Curso</label></DIV>
      <select class="form-control" name="Curso" id="Curso">
        <option value=""></option>
        <?php 
          $selectedC='';
          for ($i=0 ; $i < $CantidadCursos ; $i++) {
            if (!empty($_POST['Curso']) && $_POST['Curso'] ==  $ListadoCursos[$i]['ID']) {
              $selectedC = 'selected';
            }else {
              $selectedC='';
            }
            if ($ListadoCursos[$i]['DIVISION'] != "" && $ListadoCursos[$i]['DIVISION'] != "Ninguna") {
        ?>
            <option value="<?php echo $ListadoCursos[$i]['ID']; ?>" <?php echo $selectedC; ?>  >
              Año:  <?php echo $ListadoCursos[$i]['ANIO']." - Division: ".$ListadoCursos[$i]['DIVISION']; ?>
            </option>
    <?php   }
          } ?>
      </select>
    </div><!-- row busqueda--><hr>
    <div class="row">
      <div class="col-lg-3"><label>Etapa</label></DIV>
      <select class="form-control" name="Etapa" id="Etapa">
        <option value=""></option>
        <option value="1">
          Primera Etapa
        </option>
        <option value="2">
          Segunda Etapa
        </option>
      </select>
    </div><!-- row busqueda--><hr>
		
		<div class="row" align="center">
		 
			<div class="col-lg-12">  
				<button type="submit" class="btn btn-primary" value="EmitiInformes" name="EmitirInformes" ><box-icon  type='solid' name='user-detail'  size="sm" color="white" animation="tada-hover"></box-icon> Emitir Informes de Contenidos y Aprendizajes de los Estudiantes del Curso</button>
			  </div></div>
         <hr>
         <?php
          if ($Emitidas == 1) {
            ?>
            <div class="row" align="center">
              <div class="col-lg-12">  
        <button type="submit" class="btn btn-primary" value="DescargarInformes" name="DescargarInformes" ><box-icon  type='solid' name='download'  size="sm" color="white" animation="tada-hover"></box-icon> Descargar Informes</button>
        </div></div>
         <hr>
            <?php
          }

          if (!empty($_POST['DescargarInformes'])) {
            header('Location: funciones/generarZipConInformes.php');

           /* foreach (glob("*.pdf") as $filename) {
   echo "$filename size " . filesize($filename) . "\n";
   unlink($filename);
}  */
          } 
         ?>
                                        <br>


		<div class="row" align="center">
		 <div class="col-lg-12">  
           <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon  name="arrow-back"  size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div>
		</div>
                                       
                                           
    </center>
                               
                                <!-- /.col-lg-6 (nested) -->
                       
                            <!-- /.row (nested) -->
                        
                        
                   
                  
            
               
         </div> <!-- /.panel-body -->   
    </div>  <!-- /.panel primary -->     
    </div>   <!-- /.col principal-->  
   </div>    <!-- /.row principal -->  
 </div>   <!-- /#page-wrapper -->
</div>    <!-- /#wrapper -->
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
