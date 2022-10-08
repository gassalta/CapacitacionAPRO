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
require_once 'funciones/baseDeDatos.php';
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

$mensajesAprendizajes=array();
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
  <div class="row" align="center">
      <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Calificaciones de la evaluación de <?php echo $EvaluacionBuscada['ESPACCURRIC']; ?></b></font></h2>  </div></div>
    </div> <!-- /.row titulo --><br>
	<div class="row" align="center">
      <div class="col-lg-10">

      
<?php 

        //Listo los estudiantes a calificar en la evaluación
        require_once 'funciones/buscarEspacioCurricular.php';
        require_once 'funciones/listarEstudiantes.php';
        $EspCurr = array();
        $EspCurr = buscarEspacCurric($MiConexion,$EvaluacionBuscada['IDESPACURRI']);
        $ListadoEstudiantes = array();
        $ListadoEstudiantes = ListarEstudiatesReprobadosTotales($MiConexion,$EvaluacionBuscada['IDESPACURRI']);
        $CantidadEstudiantes = count($ListadoEstudiantes);
        //Listo los aprendizajes de la evaluación
        require_once 'funciones/listarAprendizajesXEspCurr.php';
        $ListadoAprendizajes=array();
        $ListadoAprendizajes = Listar_AprendizajesXEval($MiConexion,$EvaluacionBuscada['IDESPACURRI'],$EvaluacionBuscada['ID']);
        $CantidadAprendizajes = count($ListadoAprendizajes);
?>
    <div class="panel panel-primary">
     <div class="panel-heading"><center><?php  echo'EXAMEN - Fecha de evaluación: '.$nuevaFecha; ?> </center></div><br><br>
    <div class="panel-body">
     <!--<div class="row" align="center">
      <div class="col-lg-12">-->
       <form role="form" method="post">
<?php 
        //Si cancela vuelvo a administrarEvaluaciones
        if(!empty($_POST['Cancelar']))
		  {
           if($_SESSION['Categoria']=='Docente' || ($EsDocente == 1 && $EC != ""))
			{
              header('Location: administrarEvaluaciones.php?Cx='.$EvaluacionBuscada['ESPACCURRIC']);
            }
		   else
			{
             header('Location: administrarEvaluaciones.php?Cx=');
            }
          }
          if ($CantidadEstudiantes ==0) {
            ?>
            <div class="row" align="center">
            <div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong><center>El Espacio Curricular no tiene a ningún estudiante en proceso</center></strong></div></div>
            </div><!--fin row aviso-->
<?php
          } else {
?>
        <div class="row" align="center">
		
           <div class="col-lg-4"align="right"><label>Estudiante <?php echo ($_SESSION['Categoria']=='Docente' || ($EsDocente == 1 && $EC != "")) ? 'a calificar' : ''; ?> </label></div>
           <div class="col-lg-4"><select class="form-control" name="Estudiante" id="Estudiante">
            <option value="">Seleccione un estudiante</option>
<?php 
             $selected='';
            for($i=0 ; $i < $CantidadEstudiantes ; $i++)
			  {
               if(!empty($_POST['Estudiante']) && $_POST['Estudiante'] ==  $ListadoEstudiantes[$i]['ID'])
				{
                 $selected = 'selected';
                }
			   else 
			    {
                 $selected='';
                }
 ?>
                 <option value="<?php echo $ListadoEstudiantes[$i]['ID']; ?>" <?php echo $selected; ?>><?php echo $ListadoEstudiantes[$i]['APELLIDO']." ".$ListadoEstudiantes[$i]['NOMBRE']; ?></option>
 <?php
			 }
?>
              </select></div>
              <div class="col-lg-2"><button class="btn-md btn btn-primary" type="submit" value="Buscar" name="Buscar"><box-icon name="show-alt" type="solid" size="sm" color="white" animation="tada" ></box-icon> Ver</button></div>
               </div><br><br><!-- fin row busqueda estudiante-->
<?php
}
            if(!empty($_POST['Buscar']))
			  {
                $_SESSION['EstudianteBuscado'] = 0;
                $_SESSION['AprendizajesGuardados'] = 0;
                $_SESSION['CalificacionGuardada'] = 0;
                $_SESSION['CantLogrados'] = 0;
                if(!empty($_POST['Estudiante']))
				  {
                   if($CantidadAprendizajes == 0)
					{
?>
					  <div class="row" align="center">
						<div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong><center>La evaluación no tiene aprendizajes a evaluar</center></strong></div></div>
					  </div><!--fin row aviso-->
<?php
                    } 
					else 
					{ 
                     $_SESSION['EstudianteBuscado'] = 1;
                     $_SESSION['EstBusc'] = $_POST['Estudiante'];
                    }
				 }
				else 
				 {
 ?>
                  <div class="row" align="center">
					<div class="col-lg-12"><div class="alert alert-dismissible alert-danger">
                  <strong><center>Debe seleccionar un estudiante</center></strong> </div> </div>
                 </div><!--fin row aviso-->
 <?php
				}
               }
               if (!empty($_POST['GuardarEstadoAprendizajes'])) {
                $_SESSION['CantLogrados'] = 0;
                    $_SESSION['AprendizajesGuardados']= 1;
                   $_POST['Estudiante'] = $_SESSION['EstBusc'];
                    //GUARDE LOS APRENDIZAJES LOGRADOS Y PENDIENTES
                    require_once 'funciones/guardarCalificacion.php';
                    //Buscar la evaluacion
                    $ExECalif = evaluacionXEstCalificada($MiConexion,$EvaluacionBuscada['ID'],$_POST['Estudiante']);
                    $CantExE = count($ExECalif);
                    $aprendizajesEst = ListarAprendizajesXEstudXECXEstadoSInst($MiConexion, $_POST['Estudiante'], $EvaluacionBuscada['IDESPACURRI'], 1);
                      $cantAE = count($aprendizajesEst);
                    //Si la evaluación fue calificada...
                    if($CantExE != 0)
                      {
                       //LISTAR LOS LOGRADOS DEL ESTUDIANTE EN LA EVALUACION
                      $aprendizajesLogrados = array();
                      $aprendizajesLogrados = ListarAprendizajesLogradosXEvalXEstudiante($MiConexion,$EvaluacionBuscada['ID'],$_POST['Estudiante']);
                      $cantAL = count($aprendizajesLogrados);
                      //Recorro el listado de aprendizajes
                      for($i=0; $i < $CantidadAprendizajes; $i++)
                        {
                          $Logrado = 0;
                          $L = 0;
                          //Reviso si son de los que tiene logrados el estudiante
                          for($j=0; $j < $cantAL; $j++)
                            { 
                              if($ListadoAprendizajes[$i]['ID']==$aprendizajesLogrados[$j]['ID'])
                                {
                                 $Logrado=1;
                                }
                            }
                          
                            for ($j=0; $j < $cantAE; $j++) { 
                              if ($aprendizajesEst[$j]['ID'] == $ListadoAprendizajes[$i]['ID']) {
                                $L = 1;
                              }
                          }
                          //Reviso si el checkbox correspondiente está checked
                          if(!empty($_POST[$ListadoAprendizajes[$i]['ID']]) && $_POST[$ListadoAprendizajes[$i]['ID']] == 'SI' || $L == 1)  {
                            $_SESSION['CantLogrados'] = $_SESSION['CantLogrados'] +1;
                             //Si está checked verifico si no es de los que ya tenía Logrados
                             if($Logrado==0)
                               {
                                //Lo guardo como logrado
                                if(modificarDetalleAprendizaje($MiConexion,$ListadoAprendizajes[$i]['ID'],1,$ExECalif[0]['ID']))
                                  {
                                    $mensajesAprendizajes[]='¡El aprendizaje fue guardado como logrado!';
 /*?>
                                    <div class="row"><div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡El aprendizaje fue guardado como logrado!</center></strong></div></div></div></div>
<?php*/
                                  }
                               }
                            } 
                      /*    else
                            {
                             //Si no está checked verifico si es de los que tenía logrados
                             if($Logrado == 1)
                               {
                                //Lo guardo como pendiente
                                if(modificarDetalleAprendizaje($MiConexion,$ListadoAprendizajes[$i]['ID'],2,$ExECalif[0]['ID']))
                                 {
                                    $mensajesAprendizajes[]='¡El aprendizaje fue guardado como pendiente!';
 
?>
                                    <div class="row"><div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡El aprendizaje fue guardado como pendiente!</center></strong></div></div></div></div>
<?php
                                  }
                                }
                            } */
                        }//fin for aprendizajes
                       $aprendizajesEst = ListarAprendizajesXEvalXEstudiante($MiConexion,$EvaluacionBuscada['ID'],$_SESSION['EstBusc']);
                       $cantAE = count($aprendizajesEst);
                     } 
                    else
                     {
                       //VER CÓMO GUARDAR APRENDIZAJES SI LA EVALUACIÓN NO FUE CALIFICADA
                       //Si no fue calificada la evaluacion, creo la calificación pero con nota 0
                      if(calificacionNueva($MiConexion,$_SESSION['EstBusc'],$EvaluacionBuscada['ID'],0))
                        {
                         $ExECalif = evaluacionXEstCalificada($MiConexion,$EvaluacionBuscada['ID'],$_SESSION['EstBusc']);
                         $CantExE = count($ExECalif);
                         //Recorro el listado de aprendizajes
                         for($i=0; $i < $CantidadAprendizajes; $i++)
                            { 
                              $Logrado = 0;
                              for ($j=0; $j < $cantAE; $j++) { 
                              if ($aprendizajesEst[$j]['ID'] == $ListadoAprendizajes[$i]['ID']) {
                                $Logrado = 1;
                              }
                            }
                              //Reviso si el checkbox correspondiente está checked
                             if(!empty($_POST[$ListadoAprendizajes[$i]['ID']]) && $_POST[$ListadoAprendizajes[$i]['ID']] == 'SI' || $Logrado == 1)
                               {
                                $_SESSION['CantLogrados'] = $_SESSION['CantLogrados'] +1;
                                //Si está checked, lo guardo como logrado
                                if(guardarDetallesAprendizajes($MiConexion,$ListadoAprendizajes[$i]['ID'],1, $ExECalif[0]['ID']))
                                  {
                                    $mensajesAprendizajes[]='¡El aprendizaje fue guardado como logrado!';
 /*
 ?>
                                    <div class="row"><div class="col-lg-12"><div class="bs-component"> <div class="alert alert-dismissible alert-success"><strong><center>¡El aprendizaje fue guardado como logrado!</center></strong></div></div></div></div>
<?php*/
                                  }
                               } 
                             else
                                {
                                  //Si no está checked, lo guardo como pendiente
                                 if (guardarDetallesAprendizajes($MiConexion,$ListadoAprendizajes[$i]['ID'],2, $ExECalif[0]['ID']))
                                    {
                                    $mensajesAprendizajes[]='¡El aprendizaje fue guardado como pendiente!';
 /*
?>
                                      <div class="row"><div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>El aprendizaje fue guardado como pendiente!</center></strong></div></div></div></div>
<?php*/
                                    }
                                }
                            }//fin for aprendizajes
                        }
                      }
               }
             if($_SESSION['EstudianteBuscado'] == 1)
			   {
?>              
                 <div class="row">
				    
					<div class="col-lg-12">
					<div class="panel panel-info">
					<div class="panel-heading">
					  <div class="row" align="center"><b>
						<div class="col-lg-2">Contenido</div>
						<div class="col-lg-8">Aprendizaje</div>
						<div class="col-lg-2">¿Logrado?</div></b>
					 </div></div><br>
				<div class="panel-body">
				
 <?php              
		$aprendizajesEst = ListarAprendizajesXEstudXECXEstadoSInst($MiConexion, $_POST['Estudiante'], $EvaluacionBuscada['IDESPACURRI'], 1);
    /* $ExECalif = evaluacionXEstCalificada($MiConexion,$EvaluacionBuscada['ID'],$_POST['Estudiante']);
        $CantExE = count($ExECalif);
        if($CantExE != 0)
		   {
             //LISTAR LOS LOGRADOS DEL ESTUDIANTE EN LA EVALUACION
            $aprendizajesEst = ListarAprendizajesXEvalXEstudiante($MiConexion,$EvaluacionBuscada['ID'],$_SESSION['EstBusc']);
          */  $cantAE = count($aprendizajesEst);
            //MOSTRAR LOS APRENDIZAJES TILDANDO LOS APRENDIZAJES LOS LOGRADOS
            for($i=0; $i < $CantidadAprendizajes; $i++)
			   {  
          $Log = 0;
          for ($j=0; $j < $cantAE; $j++) { 
            if ($aprendizajesEst[$j]['ID'] == $ListadoAprendizajes[$i]['ID']) {
              $Log = 1;
            }
          }
?>
                <div class="row">
                  <div class="col-lg-2"><div class="form-group"><label><?php echo $ListadoAprendizajes[$i]['CONTENIDO']; ?></label> </div></div>
                  <div class="col-lg-8"><div class="form-group"><?php echo $ListadoAprendizajes[$i]['DENOMINACION']; ?> </div></div>
                  <div class="col-lg-2"><div class="form-group"><div class="checkbox"><label><input type="checkbox" name="<?php echo $ListadoAprendizajes[$i]['ID']; ?>" value="SI" <?php echo ($Log == 1) ? 'checked' : ''; ?> <?php echo ($_SESSION['Categoria']!='Docente' && ($EsDocente!=1 || $EC == "") || $Log==1) ? 'disabled' : ''; ?>/></label></div></div></div><br>
				  </div><!--fin row contenidos y aprendizajes-->
<?php
               }
     /*       } 
		 else {
                for ($i=0; $i < $CantidadAprendizajes; $i++) 
				  {  
 ?>
                    <div class="row">
                      <div class="col-lg-2"><div class="form-group"><label><?php echo $ListadoAprendizajes[$i]['CONTENIDO']; ?></label> </div></div>
                      <div class="col-lg-8"><div class="form-group"><?php echo $ListadoAprendizajes[$i]['DENOMINACION']; ?></div></div>
                      <div class="col-lg-2"><div class="form-group"><div class="checkbox"><label><input type="checkbox" name="<?php echo $ListadoAprendizajes[$i]['ID']; ?>" value="SI" <?php echo ($_SESSION['Categoria']!='Docente' && ($EsDocente!=1 || $EC == "")) ? 'readonly' : ''; ?>></label></div></div></div><br>
					</div><!--fin row contenidos y aprendizajes-->
<?php
                  }
               } */
?>
                  <br> <br>
				  <div class="row"align="center">
					<div class="col-lg-1"></div>
					<div class="col-lg-10"><div class="alert alert-dismissible alert-info"><center> <b>Recuerde sólo tildar los aprendizajes logrados</b></center></div></div>
				  </div>  <!-- fin row aviso --> <br>
                
 <?php
                    if ($_SESSION['Categoria']=='Docente' || ($EsDocente == 1 && $EC != ""))
					  {
                        if ($_SESSION['AprendizajesGuardados']==0) {
?>
                       <div class="row" align="center">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-4"><button type="submit" class="btn btn-primary" value="GuardarEstadoAprendizajes" name="GuardarEstadoAprendizajes" onClick="return confirm ('¿Desea guardar el estado de los aprendizajes?');"><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar Aprendizajes</button></div>
					   </div><!-- fin row confirmar aprendizajes -->
<?php
                        }
                      }
					else 
					  { 
              if ($CantExE != 0) {
                
?>
						<div class="row" align="center">
                          <div class="form-group">
                            <div class="col-lg-2"></diV>
                            <div class="col-lg-2"><label>Calificación</label></div>
                            <div class="col-lg-6"><input class="form-control" name="Calificacion" value="<?php echo $ExECalif[0]['CALIFICACION']; ?>" <?php echo ($_SESSION['Categoria']!='Docente' && ($EsDocente!=1 || $EC == "")) ? 'readonly' : ''; ?>></div> </div>
						</div>
<?php
              } else {
                ?>
                  <br> <br>
          <div class="row"align="center">
          <div class="col-lg-1"></div>
          <div class="col-lg-10"><div class="alert alert-dismissible alert-danger"><center> <b>El estudiante aún no fue calificado</b></center></div></div>
          </div>  <!-- fin row aviso --> <br>
                
 <?php
              }
                     }
?>
							</div> <!--fin panelinfo  body secundaria-->
							</div><!--fin panel info secundaria-->
							</div><!--fin col secundaria-->
							</div><!--fin row secundaria-->
                                   
<?php
                  }//cierra if estudiante buscado
				if($_SESSION['AprendizajesGuardados']== 1)
				   {
                    $CantMensajes = count($mensajesAprendizajes);
                    if ($CantMensajes != 0) {
                        for ($i=0; $i < $CantMensajes; $i++) { 
                            ?>
                                      <div class="row"><div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center><?php echo $mensajesAprendizajes[$i]; ?></center></strong></div></div></div></div>
<?php
                        }
                    }
                    
                    //MUESTRE PORCENTAJE DE LOGRADOS Y PORCENTAJE DE PENDIENTES, JUNTO CON NOTA SUGERIDA
                  /*  $aprendizajesLogrados = array();
                    $aprendizajesLogrados = ListarAprendizajesLogradosXEvalXEstudiante($MiConexion,$EvaluacionBuscada['ID'],$_POST['Estudiante']); */
                    $cantAL = $_SESSION['CantLogrados']; //count($aprendizajesLogrados);
					$PorcLogr = round(($cantAL*100)/$CantidadAprendizajes);
                    $PorcPend = 100-$PorcLogr;
                    $NotaSugerida = $PorcLogr/10;
 ?>
                <br><br><div class="row"align="center">
                  <div class="col-lg-1"></div>
                  <div class="col-lg-3 bg-success"><center><?php echo'<b>Aprendizajes aprobados '.$PorcLogr.' %';?></div>
				  <div class="col-lg-1"></div>
                <div class="col-lg-3 bg-danger"><center><?php echo'<b>Aprendizajes pendientes '.$PorcPend.' % ';?></diV>
				<div class="col-lg-1"></div>
				<div class="col-lg-3"><label>Nota sugerida: <?php echo round($NotaSugerida,0,PHP_ROUND_HALF_UP);?> </label></div>
                </div><br><br>
                
              		
              
<?php
$NotaAnterior = 0;
$ExECalif = evaluacionXEstCalificada($MiConexion,$EvaluacionBuscada['ID'],$_POST['Estudiante']);
                    $CantExE = count($ExECalif);
                    if ($CantExE >0) {

                  if ($ExECalif[0]['CALIFICACION'] != 0)
					{
                      $NotaAnterior = $ExECalif[0]['CALIFICACION'];
                    }
                }     
                                                                        
              } //fin if si apretamos guardar aprendizaje
              if (!empty($_POST['Confirmar'])) {
                  $_SESSION['CalificacionGuardada'] = 1;
              }
			  
               if($_SESSION['AprendizajesGuardados']==1 && $_SESSION['EstudianteBuscado'] == 1)
				{
          $HayNota = 0;
          if (!empty($_POST['Calificacion'])) {
            $HayNota = $_POST['Calificacion'];
          } else {
            if ($NotaAnterior != 0) {
              $HayNota = $NotaAnterior;
            }
          }
?>  
                   <div class="form-group">
				
                  <div class="row"align="center">
				  <div class="col-lg-3"></div>
				  <div class="col-lg-2"><label><b>Calificación :</b> </label></div>
				   <div class="col-lg-4"><input class="form-control" name="Calificacion" value="<?php echo ($HayNota!=0) ? $HayNota : ''; ?>" <?php echo ($_SESSION['Categoria']!='Docente' && $EsDocente!=1) ? 'readonly' : ''; ?>></div></b>
                 </div></div><br>
                   <div class="row"align="center">
 <?php
                   if($_SESSION['Categoria']=='Docente' || ($EsDocente == 1 && $EC != ""))
					 {
                        if ($_SESSION['CalificacionGuardada'] == 0) {
                            
?>
                       
				         <div class="col-lg-2"></div>
						<div class="col-lg-4"><button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('¿Desea guardar la calificación del estudiante?');"><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
 <?php
                        }
                     }
                }
            if ($_SESSION['Categoria']=='Docente' || ($EsDocente == 1 && $EC != ""))
                        {
                            
?>

                   <div class="col-lg-4"></div>
                            <div class="col-lg-4"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" onClick="return confirm ('¿Desea cancelar? - Se perderán los datos que no haya guardado');"><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div>
<?php  
                        }
                    else 
                      { 
 ?>
 
                   <div class="col-lg-4"></div>
                        <!--    <div class="col-lg-2"> </div> -->
                        <div class="col-lg-4"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" ><box-icon name="arrow-back" type="solid" size="sm" color="white" animation="tada"></box-icon> Retornar</button></div>
<?php 
                        }
						
                        ?>
                 </div> <br>
                        <?php
                    if($_SESSION['CalificacionGuardada'] == 1) 
					  {
                       $_SESSION['EstudianteBuscado'] = 0;
                       $_SESSION['AprendizajesGuardados'] = 0;
                       $_SESSION['CalificacionGuardada'] = 0;
                       //GUARDAR CALIFICACIÓN
                       if(!empty($_POST['Calificacion']))
						{
                         require_once 'funciones/guardarCalificacion.php';
                         //Buscar la evaluacion
                         $ExECalif = evaluacionXEstCalificada($MiConexion,$EvaluacionBuscada['ID'],$_POST['Estudiante']);
                         $CantExE = count($ExECalif);
                         if($ExECalif[0]['CALIFICACION'] != 0)
						   {
                            if(modificarCalificacion($MiConexion,$ExECalif[0]['ID'],$_POST['Calificacion']))
							  {
                  require_once 'funciones/buscarNota.php';
                   $NF = consultaNotaFinalSola($MiConexion,$_POST['Estudiante'],$EvaluacionBuscada['IDESPACURRI']);
                        if ($NF < $_POST['Calificacion']) {
                          UpdateNotaFinal($_POST['Estudiante'],$EvaluacionBuscada['IDESPACURRI'],$_POST['Calificacion']);
                        }
?>
								<div class="row" align="center"><div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡Calificación modificada correctamente! Elija otro estudiante para continuar calificando</center></strong></div> </div></div> </div>
<?php
                              }
							else
							  { 
?>    
								<div class="row" align="center"><div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong><center>Error al modificar la calificación</center></strong></div></div></div>
<?php
                              }
                            }
						 else 
						  {
                           if(modificarCalificacion($MiConexion,$ExECalif[0]['ID'],$_POST['Calificacion']))
							 {
                require_once 'funciones/buscarNota.php';
                   $NF = consultaNotaFinalSola($MiConexion,$_POST['Estudiante'],$EvaluacionBuscada['IDESPACURRI']);
                        if ($NF < $_POST['Calificacion']) {
                          UpdateNotaFinal($_POST['Estudiante'],$EvaluacionBuscada['IDESPACURRI'],$_POST['Calificacion']);
                        }
?>
                               <div class="row" align="center"><div class="col-lg-12"><div class="bs-component"><div class="alert alert-dismissible alert-success"><strong><center>¡Calificación guardada correctamente! Elija otro estudiante para continuar calificando</center></strong></div></div></div></div>
<?php
                             } 
							else
							{ 
?>
                             <div class="row" align="center"><div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong><center>Error al guardar la calificación</center></strong></div></div> </div>
 <?php
                            }
                          }
                        }
                     }//fin if confirmar

?>
                                          
      </div>    <!-- fin panel-body -->
     </div> <!-- fin panel primary -->
	 </div>    <!-- fin col principal -->
  </div> <!-- fin row principal --> 
  </div> <!-- fin page-wrapper -->
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