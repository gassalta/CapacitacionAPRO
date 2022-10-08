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
//Busco la evaluación seleccionada en la pantalla anterior
require_once 'funciones/buscarEvaluacion.php';
$EvaluacionBuscada = buscarEvaluacion($MiConexion,$_SESSION['IdEvalBuscada']);
$Cant = count($EvaluacionBuscada);

$EsDocente= 0;
if ($_SESSION['Categoria']=='Coordinador/a') {
    $espaciosCurricularesDoc = array();
    require_once 'funciones/listarEspaciosCurricularesXDocente.php';
    $espaciosCurricularesDoc = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
    $cantEspCurr=count($espaciosCurricularesDoc);
    for ($i=0; $i < $cantEspCurr; $i++) { 
        if ($espaciosCurricularesDoc[$i]['ID']==$EvaluacionBuscada['IDESPACURRI']) {
            $EsDocente=1;
        }
    }
}

$ExECalif=array();
$aprendizajesEst = array();

//Cambio Formato de la fecha
$originalDate =$EvaluacionBuscada['FECHA'];
//original date is in format YYYY-mm-dd
$timestamp = strtotime($originalDate); 
$nuevaFecha = date("d-m-Y", $timestamp );

$EC=$_REQUEST['Cx'];
?>
<!DOCTYPE html>
<html lang="en">

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
			    <div class="col-lg-12">
				<h1 class="page-header"><font color="#85C1E9"><center>Calificaciones de la evaluación de <?php echo $EvaluacionBuscada['ESPACCURRIC']; ?></center></font></h1>
                   
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <?php if ($_SESSION['Categoria'] == 'Docente' || ($EsDocente == 1 && $EC != "")) { ?>
                
            <div class="row">
                <div class="alert alert-dismissible alert-danger">
                  <strong>Una vez calificada la evaluación, no podrá modificarla ni eliminarla, asegúrese de que los datos de la misma son correctos</strong>
                </div>
            </div>
            <!-- /.row -->            
            
    <?php 
}
        //Listo los estudiantes a calificar en la evaluación
        require_once 'funciones/buscarEspacioCurricular.php';
        require_once 'funciones/listarEstudiantes.php';
        $EspCurr = array();
        $EspCurr = buscarEspacCurric($MiConexion,$EvaluacionBuscada['IDESPACURRI']);
        $ListadoEstudiantes = array();
        $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$EspCurr['CURSO']);
        $CantidadEstudiantes = count($ListadoEstudiantes);
        //Listo los aprendizajes de la evaluación
        require_once 'funciones/listarAprendizajesXEspCurr.php';
        $ListadoAprendizajes=array();
        $ListadoAprendizajes = Listar_AprendizajesXEval($MiConexion,$EvaluacionBuscada['IDESPACURRI'],$EvaluacionBuscada['ID']);
        $CantidadAprendizajes = count($ListadoAprendizajes);
		
    ?>
    <div class="panel panel-primary">
                        <div class="panel-heading">
                          <?php  echo'Fecha de evaluación '.$nuevaFecha; ?>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a administrarEvaluaciones
                                            if (!empty($_POST['Cancelar'])) {
                                                if ($_SESSION['Categoria']=='Docente' || ($EsDocente == 1 && $EC != "")) {
                                                    header('Location: administrarEvaluaciones.php?Cx='.$EvaluacionBuscada['ESPACCURRIC']);
                                                } else {
                                                header('Location: administrarEvaluaciones.php?Cx=');
                                            }
                                            }
                                            ?>
                                        <div class="row" align="center"><font color="#85C1E9">
                                            <div class="col-lg-4"><label>Seleccione el estudiante <?php echo ($_SESSION['Categoria']=='Docente' || ($EsDocente == 1 && $EC != "")) ? 'a calificar' : ''; ?> </label></div>
                                             <div class="col-lg-4"align="left">
                                                <select class="form-control" name="Estudiante" id="Estudiante">
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantidadEstudiantes ; $i++) {
                                                    if (!empty($_POST['Estudiante']) && $_POST['Estudiante'] ==  $ListadoEstudiantes[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                            $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoEstudiantes[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoEstudiantes[$i]['APELLIDO']." ".$ListadoEstudiantes[$i]['NOMBRE']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select></div>
                                                <div class="col-lg-4"align="left" >
                                            <button class="btn-md btn btn-primary" type="submit" value="Buscar" name="Buscar"><box-icon name="search-alt" type="solid" size="md" color="white" animation="tada" ></box-icon> Buscar
                                                </button></div></font>
                                        </div><hr>
                                        <?php
                                            if (!empty($_POST['Buscar'])) {
                                                $_SESSION['EstudianteBuscado'] = 0;
                                                $_SESSION['AprendizajesGuardados'] = 0;
                                                if (!empty($_POST['Estudiante'])) {
                                                    if ($CantidadAprendizajes == 0) {
                                                        ?>
                                                        <div class="alert alert-dismissible alert-danger">
                  <strong>La evaluación no tiene aprendizajes a evaluar</strong>
                </div>
                                              <?php
                                                    } else { 
                                                        
                                                        $_SESSION['EstudianteBuscado'] = 1;
                                                        $_SESSION['EstBusc'] = $_POST['Estudiante'];
                                                        
                                                    }

                                                
                                        
                                                } else {
                                                    ?>
                                                        <div class="alert alert-dismissible alert-danger">
                  <strong>Debe seleccionar un estudiante</strong>
                </div>
                                              <?php
                                                }
                                            }
                            if($_SESSION['EstudianteBuscado'] == 1){
                                ?>
                                <div class="row" align="center"><font color="red">
                                            <div class="col-lg-12"><label>Tildar sólo los aprendizajes logrados</label></div></font></div>

                                            <div class="row" align="right"><font color="#85C1E9">
                                            <div class="col-lg-11"><label>     Logrado</label></div></font></div>
                        <?php               $ExECalif = evaluacionXEstCalificada($MiConexion,$EvaluacionBuscada['ID'],$_POST['Estudiante']);
                                            $CantExE = count($ExECalif);
                                            if ($CantExE != 0) {
                                                //LISTAR LOS LOGRADOS DEL ESTUDIANTE EN LA EVALUACION
                                                $aprendizajesEst = ListarAprendizajesXEvalXEstudiante($MiConexion,$EvaluacionBuscada['ID'],$_SESSION['EstBusc']);
                                                $cantAE = count($aprendizajesEst);
                                                //MOSTRAR LOS APRENDIZAJES TILDANDO LOS APRENDIZAJES LOS LOGRADOS
                                                for ($i=0; $i < $cantAE; $i++) {  ?>
                                                <div class="row">
                                                  <div class="col-lg-3">
                                                    <div class="form-group">
                                                      <label><?php echo $aprendizajesEst[$i]['CONTENIDO']; ?></label> </div></div>
                                                  <div class="col-lg-7">
                                                    <div class="form-group">
                                                      <label><?php echo $aprendizajesEst[$i]['DENOMINACION']; ?></label> </div></div>
                                                      
                                                   <div class="col-lg-2">
                                                    <div class="form-group">
                                                      <div class="checkbox">
                                                                        
                                                                        <label>
                                                    <input type="checkbox" name="<?php echo $aprendizajesEst[$i]['ID']; ?>" value="SI" <?php echo ($aprendizajesEst[$i]['LOGOPEND'] == 1) ? 'checked' : ''; ?> <?php echo ($_SESSION['Categoria']!='Docente' && ($EsDocente!=1 || $EC == "")) ? 'disabled' : ''; ?>/>

                                                </label><br><br>
                                                                        
                                                                    </div>
                                                                </div></div></div>
                                            <?php
                                                }
                                            } else {
                                                for ($i=0; $i < $CantidadAprendizajes; $i++) {  ?>
                                                    <div class="row">
                                                      <div class="col-lg-3">
                                                        <div class="form-group">
                                                          <label><?php echo $ListadoAprendizajes[$i]['CONTENIDO']; ?></label> </div></div>
                                                      <div class="col-lg-7">
                                                        <div class="form-group">
                                                          <label><?php echo $ListadoAprendizajes[$i]['DENOMINACION']; ?></label> </div></div>
                                                      <div class="col-lg-2">
                                                        <div class="form-group">
                                                          <div class="checkbox">
                                                            <label>
                                                    <input type="checkbox" name="<?php echo $ListadoAprendizajes[$i]['ID']; ?>" value="SI" <?php echo ($_SESSION['Categoria']!='Docente' && ($EsDocente!=1 || $EC == "")) ? 'readonly' : ''; ?>>

                                                </label><br><br>
                                                                        
                                                                    </div>
                                                                </div></div></div> <?php
                                                }
                                            }
                                ?>
                                        <div class="row">
                                <div class="col-lg-12">
                                <div class="col-lg-2"></div>
                                <?php
                                if ($_SESSION['Categoria']=='Docente' || ($EsDocente == 1 && $EC != "")) {
                                    
                                ?>
                                <div class="col-lg-4">
                                        <button type="submit" class="btn btn-primary" value="GuardarEstadoAprendizajes" name="GuardarEstadoAprendizajes" onClick="return confirm ('Seguro que desea guardar el estado de los aprendizajes?');"
                                       ><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar Aprendizajes</button></div></div></div>
                                <?php
                                } else { 
                                    ?>
                                    </div></div>
                            <div class="row">
                                <div class="col-lg-12"></div>
                                <hr>
                                <div class="col-lg-12"></div>
                            </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-lg-2">
                                            <label>Calificación</label></div>
                                            <div class="col-lg-6">
                                                <input class="form-control" name="Calificacion" value="<?php echo $ExECalif[0]['CALIFICACION']; ?>" <?php echo ($_SESSION['Categoria']!='Docente' && ($EsDocente!=1 || $EC == "")) ? 'readonly' : ''; ?>></div>
                                        </div></div>
                            <div class="row">
                                <div class="col-lg-12"></div>
                                <hr>
                                <div class="col-lg-12"></div>
                            </div>
                            <?php
                                }
                            }

                                if (!empty($_POST['GuardarEstadoAprendizajes'])) {
                                    $_POST['Estudiante'] = $_SESSION['EstBusc'];
                                    
                                    //GUARDE LOS APRENDIZAJES LOGRADOS Y PENDIENTES
                                    require_once 'funciones/guardarCalificacion.php';
                                    //Buscar la evaluacion
                                    $ExECalif = evaluacionXEstCalificada($MiConexion,$EvaluacionBuscada['ID'],$_POST['Estudiante']);
                                    $CantExE = count($ExECalif);
                                    //Si la evaluación fue calificada...
                                    if ($CantExE != 0) {
                                        //LISTAR LOS LOGRADOS DEL ESTUDIANTE EN LA EVALUACION
                                        $aprendizajesLogrados = array();
                                        $aprendizajesLogrados = ListarAprendizajesLogradosXEvalXEstudiante($MiConexion,$EvaluacionBuscada['ID'],$_POST['Estudiante']);
                                        $cantAL = count($aprendizajesLogrados);
                                        //Recorro el listado de aprendizajes
                                        for ($i=0; $i < $CantidadAprendizajes; $i++) {
                                            $Logrado = 0;
                                            //Reviso si son de los que tiene logrados el estudiante
                                            for ($j=0; $j < $cantAL; $j++) { 
                                                if ($ListadoAprendizajes[$i]['ID']==$aprendizajesLogrados[$j]['ID']) {
                                                    $Logrado=1;
                                                }
                                            }
                                        //Reviso si el checkbox correspondiente está checked
                                        if(!empty($_POST[$ListadoAprendizajes[$i]['ID']]) && $_POST[$ListadoAprendizajes[$i]['ID']] == 'SI') {
                                            //Si está checked verifico si no es de los que ya tenía Logrados
                                            if ($Logrado==0) {
                                                //Lo guardo como logrado
                                                if (modificarDetalleAprendizaje($MiConexion,$ListadoAprendizajes[$i]['ID'],1,$ExECalif[0]['ID'])) {
                                                                ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>El aprendizaje fue guardado como logrado!</strong>
                </div>
              </div>
                                                    <?php
                                                }
                                            }
                                        } else {
                                            //Si no está checked verifico si es de los que tenía logrados
                                            if ($Logrado == 1) {
                                                //Lo guardo como pendiente
                                                if (modificarDetalleAprendizaje($MiConexion,$ListadoAprendizajes[$i]['ID'],2,$ExECalif[0]['ID'])) {
                                                                ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>El aprendizaje fue guardado como pendiente!</strong>
                </div>
              </div>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    $aprendizajesEst = ListarAprendizajesXEvalXEstudiante($MiConexion,$EvaluacionBuscada['ID'],$_SESSION['EstBusc']);
                                    $cantAE = count($aprendizajesEst);
                                } else {
                                    //VER CÓMO GUARDAR APRENDIZAJES SI LA EVALUACIÓN NO FUE CALIFICADA
                                    //Si no fue calificada la evaluacion, creo la calificación pero con nota 0
                                    if (calificacionNueva($MiConexion,$_SESSION['EstBusc'],$EvaluacionBuscada['ID'],0)) {
                                        $ExECalif = evaluacionXEstCalificada($MiConexion,$EvaluacionBuscada['ID'],$_SESSION['EstBusc']);
                                        $CantExE = count($ExECalif);
                                    
                                    //Recorro el listado de aprendizajes
                                    for ($i=0; $i < $CantidadAprendizajes; $i++) { 
                                        //Reviso si el checkbox correspondiente está checked
                                        if(!empty($_POST[$ListadoAprendizajes[$i]['ID']]) && $_POST[$ListadoAprendizajes[$i]['ID']] == 'SI') {
                                            //Si está checked, lo guardo como logrado
                                            if (guardarDetallesAprendizajes($MiConexion,$ListadoAprendizajes[$i]['ID'],1, $ExECalif[0]['ID'])) {
                                                                ?>
                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>El aprendizaje fue guardado como logrado!</strong>
                </div>
              </div>
                                                    <?php
                                            }
                                        } else {
                                            //Si no está checked, lo guardo como pendiente
                                            if (guardarDetallesAprendizajes($MiConexion,$ListadoAprendizajes[$i]['ID'],2, $ExECalif[0]['ID'])) {
                                                                ?>
                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>El aprendizaje fue guardado como pendiente!</strong>
                </div>
              </div>
                                                    <?php
                                            }
                                        }
                                    }
                                }
                                }
                                            
                                    //MUESTRE PORCENTAJE DE LOGRADOS Y PORCENTAJE DE PENDIENTES, JUNTO CON NOTA SUGERIDA
                                    $aprendizajesLogrados = array();
                                    $aprendizajesLogrados = ListarAprendizajesLogradosXEvalXEstudiante($MiConexion,$EvaluacionBuscada['ID'],$_POST['Estudiante']);
                                    $cantAL = count($aprendizajesLogrados);

                                    $PorcLogr = round(($cantAL*100)/$CantidadAprendizajes);
                                    $PorcPend = 100-$PorcLogr;
                                    $NotaSugerida = $PorcLogr/10;
                                    ?>
                <div class="row">
                 <div class="col-lg-12"></div>
                 <div class="col-lg-12"></div>
                  <div class="col-lg-4"><center>
                <?php 
                
                 echo'<div class="well well-sm"><b>Aprendizajes aprobados '.$PorcLogr.' % </div>';?>
                </div>
                <div class="col-lg-4"><center>
                <?php 
                
                 echo'<div class="well well-sm"><b>Aprendizajes pendientes '.$PorcPend.' % </div>';?>
                </div>
                </div>
                <div class="form-group">
               <center><label>Nota Sugerida</label>
               <label><?php echo round($NotaSugerida,0,PHP_ROUND_HALF_UP);?></label></div>
                                    <?php
                                    
                                    if ($ExECalif[0]['CALIFICACION'] != 0) {
                                        $_POST['Calificacion'] = $ExECalif[0]['CALIFICACION'];
                                    }
                                    $_SESSION['AprendizajesGuardados']= 1;
                                                                        
                                }
                                if ($_SESSION['AprendizajesGuardados']==1 && $_SESSION['EstudianteBuscado'] == 1) {
                                
                                ?> 
                                
                            </div></div>
                            <div class="row">
                                <div class="col-lg-12"></div>
                                <hr>
                                <div class="col-lg-12"></div>
                            </div>
                                    <div class="row">
                                        <div class="form-group">
                                            <div class="col-lg-2">
                                            <label>Calificación</label></div>
                                            <div class="col-lg-6">
                                                <input class="form-control" name="Calificacion" value="<?php echo !empty($_POST['Calificacion']) ? $_POST['Calificacion'] : ''; ?>" <?php echo ($_SESSION['Categoria']!='Docente' && $EsDocente!=1) ? 'readonly' : ''; ?>></div>
                                        </div></div>
                            <div class="row">
                                <div class="col-lg-12"></div>
                                <hr>
                                <div class="col-lg-12"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                <div class="col-lg-2"></div>
                                <?php
                                if ($_SESSION['Categoria']=='Docente' || ($EsDocente == 1 && $EC != "")) {
                                    
                                ?>
                                <div class="col-lg-4">
                                        <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('Seguro que desea guardar la calificación del estudiante?');"
                                       ><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
                                <?php
                                }
                                }
                                if (!empty($_POST['Confirmar'])) {
                                    $_SESSION['EstudianteBuscado'] = 0;
                                    $_SESSION['AprendizajesGuardados'] = 0;
                                    //GUARDAR CALIFICACIÓN
                                    if (!empty($_POST['Calificacion'])) {
                                        require_once 'funciones/guardarCalificacion.php';
                                        //Buscar la evaluacion
                                        $ExECalif = evaluacionXEstCalificada($MiConexion,$EvaluacionBuscada['ID'],$_POST['Estudiante']);
                                        $CantExE = count($ExECalif);
                                        if ($ExECalif[0]['CALIFICACION'] != 0) {
                                            if (modificarCalificacion($MiConexion,$ExECalif[0]['ID'],$_POST['Calificacion'])) {
                                                ?>
                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Calificación modificada correctamente!</strong>
                </div>
              </div>
                                                    <?php
                                            } else { ?>
                                                <div class="row">
                <div class="alert alert-dismissible alert-danger">
                  <strong>Error al modificar la calificación</strong>
                </div>
            </div> <?php
                                            }
                                        } else {
                                            if (modificarCalificacion($MiConexion,$ExECalif[0]['ID'],$_POST['Calificacion'])) {
                                                ?>
                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Calificación guardada correctamente!</strong>
                </div>
              </div>
                                                    <?php
                                            } else { ?>
                                                <div class="row">
                <div class="alert alert-dismissible alert-danger">
                  <strong>Error al guardar la calificación</strong>
                </div>
            </div> <?php
                                            }
                                        }
                                    }
                                }
                                            
                                ?>      
                                <div class="col-lg-4">
                                    <?php if ($_SESSION['Categoria']=='Docente' || ($EsDocente == 1 && $EC != "")) { ?>
                                        <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" onClick="return confirm ('Seguro que desea cancelar? - Se perderán los datos que no haya guardado');"><box-icon name="x" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Cancelar</button><div>
                                 <?php   } else { ?>
                                        <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" ><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button><div>
                                        <?php } ?>
                                        <br>  
</div></div>                                        
                                </div>
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
         
        </div>
        <!-- /#page-wrapper -->

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