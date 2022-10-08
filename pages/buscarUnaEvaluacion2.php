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
?>
<!DOCTYPE html>
<html lang="en">

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
             <div class="row">
                <div class="col-lg-10">
				<h1 class="page-header"><font color="#85C1E9"><center>Consulta de Evaluaciones</center></font></h1>
                   
                </div>
                <!-- /.col-lg-8 -->
            </div>
            <!-- /.row -->
            
    <?php 
        //Listo los espacios curriculares de acuerdo al docente
    $ListadoEspaciosCurriculares=array();
    if ($_SESSION['Categoria']=='Coordinador/a') {
        require_once 'funciones/listarEspaciosCurriculares.php';
        $ListadoEC = Listar_EspCurr($MiConexion);
    } else {
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
        //$_SESSION['IdEvalBuscada']==$Evaluacion;	
    ?>
     <div class="row">
       <div class="col-lg-10">
            
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                         Información Evaluación N° <?php echo $Evaluacion;?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <form role="form" method="post">
                                        <?php 
                                            /*Si cancela vuelvo a administrarEvaluaciones
                                            if (!empty($_POST['Cancelar'])) {
                                                header('Location: administrarEvaluaciones.php');
                                            }

                                            if (!empty($_POST['Calificaciones'])) {
                                                if (!empty($_POST['Id']) && $_SESSION['IdEvalBuscada']==$_POST['Id']) {
                                                    header('Location: registrarCalificaciones.php');
                                                    
                                                } else {
                                                    */?>
                                                    <!--<div class="alert alert-dismissible alert-danger">
                  <strong>Primero debe buscar una evaluación para calificar</strong>
                </div>-->
                                          <?php
                                               // }
                                           // }

                                           // if (!empty($_POST['Buscar'])) {
                                                //$EvaluacionBuscada = buscarEvaluacion($MiConexion,$_POST['Id']);
												$EvaluacionBuscada = buscarEvaluacion($MiConexion,$Evaluacion);
                                                $Cant = count($EvaluacionBuscada);
                                                if ($Cant==0) { ?>
                                                    <div class="alert alert-dismissible alert-danger">
                  <strong>Número de evaluación no válido</strong>
                </div>
                                          <?php   
                                                    $_POST['EspaCurri'] ="";
                                            $_POST['Fecha'] = "";
                                            $_POST['Instancia'] = "";
                                            
                                                } else {
                                                    
                                                    $band = 0;
                                                    for ($i=0; $i < $CantidadEspCurr; $i++) { 
                                                        if ($EvaluacionBuscada['IDESPACURRI'] == $ListadoEC[$i]['ID']) {
                                                            $band = 1;
                                                        }
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
                <div class="alert alert-dismissible alert-danger">
                  <strong>La evaluación seleccionada no tiene aprendizajes asociados</strong>
                </div>
              </div>
             <?php
                                    }  else {
                                        $_SESSION['TieneAprendizajes'] = 1;
                                        $_SESSION['EstudianteBuscado'] = 0;
                                        $_SESSION['AprendizajesGuardados'] = 0;
                                    }
                                                    } else { ?>
                                                        <div class="alert alert-dismissible alert-danger">
                  <strong>No tiene acceso a la evaluación indicada</strong>
                </div></div>
                                              <?php     
                                            $_POST['EspaCurri'] ="";
                                            $_POST['Fecha'] = "";
                                            $_POST['Instancia'] = "";
                                               }
                                            
                                          } 
                                      //      }?>
											</form>
                                        <div class="row" align="center"><font color="#85C1E9">
											<!--<div class="col-lg-2"><label>Ingrese el número de evaluación</label></div>
											 <div class="col-lg-2"align="left">
                                                <input class="form-control" name="Id" value="<?php //echo !empty($_POST['Id']) ? $_POST['Id'] : ''; ?>"></div>
												<div class="col-lg-6"align="left" >
											<button class="btn-md btn btn-primary" type="submit" value="Buscar" name="Buscar"><box-icon name="search-alt" type="solid" size="md" color="white" animation="tada" ></box-icon> Buscar
                                                </button></div></font>-->
												<div class="col-lg-2"><label>Evaluación</label></div>
											 <div class="col-lg-2"align="left">
                                                <input class="form-control" name="Id" readonly value="<?php echo $Evaluacion; ?>"></div>
												<div class="col-lg-2"align="left" >
									<!--		<button class="btn-md btn btn-primary" type="submit" value="Buscar" name="Buscar"><box-icon name="search-alt" type="solid" size="md" color="white" animation="tada" ></box-icon> Buscar
                                                </button></div></font> -->
										</div><hr>  
										<div class="row">
                                         <div class="col-lg-10">
                                        <div class="form-group">
                                            <label>Espacio Curricular</label>
                                            <select class="form-control" name="EspaCurri" id="EspaCurri" disabled>
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantidadEspCurr ; $i++) {
                                                    if (!empty($_POST['EspaCurri']) && $_POST['EspaCurri'] ==  $ListadoEC[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                            $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoEC[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoEC[$i]['NOMBREESPACCURRIC']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div></div>
                                         <!--<div class="col-lg-4">
                                        <div class="form-group">
                                        </div></div>-->
										</div>
                 <div class="row">
                    <div class="col-lg-10">
                     <div class="form-group">
                     <label valign="bottom">Fecha</label>
                      <input valign="bottom" id="date" type="date" name="Fecha" value="<?php echo !empty($_POST['Fecha']) ? $_POST['Fecha'] : ''; ?>" disabled>
                      </div></div>
				</div><!--row fechaa -->
                 <div class="row">
                    <div class="col-lg-10">
                     <div class="form-group">
                     <label>Instancia</label>
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
					</div><!--row instancia -->
					<div class="row">
					<div class="col-lg-5">
                    <hr style="color: #888ffc"/>
					<?php if (!empty($ListadoAprendizajes)) { ?>
                    <center><font size="4" face="Verdana, Arial, Helvetica, sans-serif"><b>Aprendizajes a evaluar</b></font></center>
					</div>
					</div> <!--row Titulo tabla --> 
					<br> 
					<div class="row">
					<div class="col-lg-5">
                    <div class="table-responsive">
					<table class="table-md table-striped table-bordered bg-info">
					 <thead><tr>
						<th>N°</th>
						<th>Contenido</th>
						<th>Aprendizaje</th></tr>
					 </thead>
					 <tbody>
                        <?php
                        //Cargo a la tabla el listado de los aprendizajes
                            for ($i=0; $i < $CantidadAprendizajes; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $ListadoAprendizajes[$i]['ID'], "- "; ?></td>
                                    <td><?php echo $ListadoAprendizajes[$i]['CONTENIDO'], "- "; ?></td>
                                    <td><?php echo $ListadoAprendizajes[$i]['DENOMINACION']; ?></td>
                                </tr> 
                         <?php   } ?>
                     </tbody>
					</table></div></div>
					</div><!--row tabla -->
                                        <?php  }?> 
                                       
                                        <br>
                                        <div class="row">
										
										<div class="col-lg-2"></div>
										 <div class="col-lg-2">
										 <Form Action="administrarEvaluaciones.php" Method="Post">
											<button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" ><box-icon  name="x" type="solid" size="sm" color="white" animation="tada-hover"  ></box-icon> Cancelar</button></form></div>
										<div class="col-lg-5"></div>
                                        <?php if ($_SESSION['TieneAprendizajes'] == 1) {
                                            ?>	
                                         <div class="col-lg-2" ><Form Action="registrarCalificaciones.php" Method="Post"><button type="submit" class="btn btn-primary" value="Calificaciones" name="Calificaciones"><box-icon name="task" type="solid" size="sm" color="white" animation="tada"></box-icon> Ir a las calificaciones</button><form></div>
										</div><!--row botones -->
                                        <?php } ?>
        </div><!-- /.panel-body -->
      </div><!-- /.panel -->
	  </div></div><!-- row 10 -->
    </div><!-- /#page-wrapper -->
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
