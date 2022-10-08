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

require_once 'funciones/buscarCurso.php';
$CursoElegido = array();
require_once 'funciones/buscarEstudiante.php';
$EstudElegido = array();
require_once 'funciones/informeAsistencia.php';
$TotalesAsistencias = array();

//Declaro variables
$mensaje='';
$ListoEmitir = 0;
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
     <div class="col-lg-10"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Asistencias </b></font></h2></div>
  </div><br><!-- fin row titulo -->
<?php
    //Listo los cursos
require_once 'funciones/listaCursos.php';
$ListadoCursos=array();
$ListadoCursos = ListarCursos($MiConexion);
$CantidadCursos = count($ListadoCursos);
$CursoElegido = 0;

require_once 'funciones/listarEstudiantes.php';
$ListadoEstudiantes=array();
/* $ListadoEstudiantes = Listar_Estudiantes($MiConexion);
 $CantidadEstudiantes = count($ListadoEstudiantes); */
?>
    <div class="row">
      <div class="col-lg-10">
        <div class="panel panel-primary">
         <div class="panel-heading"><center> Informe de Asistencia por Estudiante</center></div><br>
         <div class="panel-body">
          <div class="row"align="center">
		   <div class="col-lg-1"></div>
            <div class="col-lg-10">
             <form role="form" method="post">
<?php 
             //Si cancela vuelvo a administrarAsistencias
               if(!empty($_POST['Cancelar']))
				 {
                   header('Location: index.php');
                 } 
?>
               <div class="form-group">
				 <div class="row">
                  <div class="col-lg-2"><label>Curso</label></div>
                  <div class="col-lg-6"><select class="form-control" name="Curso" id="Curso">
                   <option value="">Seleccione un curso</option>
<?php 
                    $selectedC='';
                    for($i=0 ; $i < $CantidadCursos ; $i++)
					  {
                       if(!empty($_POST['Curso']) && $_POST['Curso'] ==  $ListadoCursos[$i]['ID'])
						{
                         $selectedC = 'selected';
                        }
					   else
						{
                         $selectedC='';
                        }
 ?>
					    <option value="<?php echo $ListadoCursos[$i]['ID']; ?>" <?php echo $selectedC; ?>  > Año:  <?php echo $ListadoCursos[$i]['ANIO']." - Division: ".$ListadoCursos[$i]['DIVISION']; ?></option>
 <?php 
						} 
?>
                                        </select></diV>
				 <div class="col-lg-2"><button type="submit" class="btn btn-primary" value="ElegirCurso" name="ElegirCurso"><box-icon  name="show-alt"  size="sm" color="white" animation="tada-hover"></box-icon> Ver</button></diV>
				</div> <!--Fin row 1--></div><br>  
<?php   
		if(!empty($_POST['ElegirCurso']))
		   {
            $CursoElegido = buscarCurso($MiConexion,$_POST['Curso']);
            $_SESSION['CursoEleg'] = $_POST['Curso'];
           
             $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$_POST['Curso']);
             $CantidadEstudiantes = count($ListadoEstudiantes);
             if($CantidadEstudiantes == 0) 
			   {
?>
				<div class="row">
				  <div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong><center>El curso seleccionado no tiene estudiantes asignados</center></strong></div></div>
				</div><!--Fin row alerta 1--><br>
 <?php
               }
			 else 
			   {
                $CursoElegido = 1;
               }
            }
		if($CursoElegido==1) 
		 { 
?>
            <div class="form-group">
			  <div class="row">
               <div class="col-lg-2"><label>Estudiante</label></div>
               <div class="col-lg-6"><select class="form-control" name="Estudiante" id="Estudiante">
                                        <option value="0">Seleccione un estudiante</option>
<?php 
                                          $selectedE='';
                                          for($i=0 ; $i < $CantidadEstudiantes ; $i++)
											{
                                              if (!empty($_POST['Estudiante']) && $_POST['Estudiante'] ==  $ListadoEstudiantes[$i]['ID']) 
											    {
                                                 $selectedE = 'selected';
                                                }
											  else
												{
                                                 $selectedE='';
                                                }
?>
                                                <option value="<?php echo $ListadoEstudiantes[$i]['ID']; ?>" <?php echo $selectedE; ?> ><?php echo $ListadoEstudiantes[$i]['ID']."- ".$ListadoEstudiantes[$i]['APELLIDO']." ".$ListadoEstudiantes[$i]['NOMBRE']; ?></option>
<?php
											} 
?>
                                       </select></div>
			<div class="col-lg-2"><button type="submit" class="btn btn-primary" value="ElegirEstudiante" name="ElegirEstudiante"><box-icon  name="show-alt"  size="sm" color="white" animation="tada-hover"></box-icon> Ver</button></diV></div><!--fin form-->
			</div><!--Fin row 2--><br>
<?php  
        }
		 if(!empty($_POST['ElegirEstudiante']))
		   {
            if(!empty($_POST['Curso'])) 
			 {
              if($_POST['Estudiante'] != 0)
				{
                 $CursoElegido = buscarCurso($MiConexion,$_SESSION['CursoEleg']);
				 $EstudElegido = buscarEstudiante($MiConexion,$_POST['Estudiante']);
                 $_SESSION['EstudEleg'] = $_POST['Estudiante'];
                 $anioActual = date("Y");
				 $TotalesAsistencias = contarTotalesAsistencia($MiConexion,$EstudElegido['ID'],$anioActual);
                 $ListoEmitir = 1;
                } 
			  else 
			    {
?>
				 <div class="row"><div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong><center>Por favor seleccione un estudiante.</center></strong> </div></div>
				 </div><!--Fin row alerta2-->
<?php
                }
			 } 
			else 
			 {
?>
			  <div class="row"><div class="col-lg-12"><div class="alert alert-dismissible alert-danger"><strong><center>Por favor seleccione un curso</center></strong></div></div>
			  </div><!--Fin row alerta3-->
							
<?php
			 }
		 } 
?>
     
     </div> <!--fin col input-->
     </div><!--fin row input--><br><br>
                                       
<?php       
		if (!empty($TotalesAsistencias)) 
			{ 
?>
	  <div class="panel panel-info">
		<div class="panel-heading bg-info">
          <div class="row">
				<div class="col-lg-3"><label><?php echo $EstudElegido['APELLIDO']." , ".$EstudElegido['NOMBRE']?></label></div>
				<div class="col-lg-2"><label>Dni:</label> <?php echo $EstudElegido['DNI']?></div>
                <div class="col-lg-3"><label>Legajo:</label> <?php echo $EstudElegido['NROLEGAJO']?></div>
				<div class="col-lg-4">"<?php echo $CursoElegido['ANIO']." - ".$CursoElegido['DIVISION']." Division        -              "; ?><?php echo ($CursoElegido['ANIO']=='1ro'||$CursoElegido['ANIO']=='2do'||$CursoElegido['ANIO']=='3ro') ? 'Ciclo Basico' : 'Ciclo Orientado'; ?>"</div>
            </div><!--fin row heading-->
		</div><!--fin panel heading-->	<br>
       <div class="panel-body">  
		  <div class="row"align="center">
			
			<div class="col-lg-6">
				<div class="panel panel-primary">
					<div class="bg-primary"><font size="3px">Asistencias</font></div><br>
					<div class="row"><div class="col-lg-12"><label>Presente: </label><?php echo !empty($TotalesAsistencias) ? $TotalesAsistencias['PRESENTES'] : ''; ?></div></div>
					<hr>
					<div class="row"align="left"><div class="col-lg-12"><label><font color="#2d7ac0" size="3px">Total de Asistencias : </label><?php echo !empty($TotalesAsistencias) ? $TotalesAsistencias['TOTAL'] : ''; ?></font></div></div>
				</div>
			</div><!--col asistencias-->
			<div class="col-lg-6">
				<div class="panel panel-primary">
				<div class="bg-primary"><font size="3px">Inasistencias</font></div><br>
				<div class="row">
				   <div class="col-lg-6"> <label>Justificadas: </label><?php echo !empty($TotalesAsistencias) ? $TotalesAsistencias['JUSTIFICADAS'].'     -     ' : '     -     '; ?></div>
				   <div class="col-lg-6"><label>Injustificadas: </label><?php echo !empty($TotalesAsistencias) ? $TotalesAsistencias['INJUSTIFICADAS'] : ''; ?></div>
				</div>
				<hr>
				<div class="row" align="left"><div class="col-lg-12"><label><font color="#2d7ac0" size="3px">Total de Inasistencias: </label><?php echo !empty($TotalesAsistencias) ? $TotalesAsistencias['INASISTENCIAS'] : ''; ?></font></div>
				</div>
					
				</div>	
				</div><!--col asistencias-->
			</div><!--row asistencias-->	
			</div>	<!--fin panel body info-->	
            </div>	<!--fin panel info-->	
<?php  }
?>  					<div class="row" align="center">
						
					   
<?php             if ($ListoEmitir == 1) 
					{ 
?>
						   <div class="col-lg-2"> </div>
                          <div class="col-lg-4"> 
						   <button type="submit" class="btn btn-primary" value="Emitir" name="Emitir" formaction="funciones/emitirPDFInformeAsistencia.php"  onClick="return confirm ('¿Desea emitir el informe?');"><box-icon  name="down-arrow"  size="sm" color="white" animation="fade-down"></box-icon> Emitir Informe</button>
						  </div>
						  <div class="col-lg-4">
                            <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"  onclick="return confirm ('¿Desea cancelar?')"><box-icon  name="arrow-back"  size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button>
                          </div>
						  
 <?php
					}
				  else 
				    {
?>
						<div class="col-lg-12">
                          <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"  onclick="return confirm ('¿Desea cancelar?')"><box-icon  name="arrow-back"  size="sm" color="white" animation="tada"></box-icon> Retornar</button>
                         </div>
<?php               
					}
?>      
                          </div><!-- /.row botones --><br>
     </div><!-- /.panel body--> 
    </div> <!-- /.panel primary-->
   </div><!-- fin col primary-->
	</div> <!-- fin row primary-->      
  </div>  <!-- fin page-wrapper -->
    </div><!-- fin wrapper -->

    
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
