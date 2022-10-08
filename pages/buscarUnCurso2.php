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
?>
<!DOCTYPE html>
<html lang="en">
<head>
 <?php
   require_once 'encabezado.php';
   $idCurso=$_REQUEST['Cx'];   
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
    <div class="col-lg-10"><div class="tile"><h2 class="tile-title" ><font color="#85C1E9"><center><b>Datos del Curso</b></font></h2>  </div></div><br>
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
                    header('Location: administrarCursos.php');
                    }
				if(!empty($_POST['EstudiantesXCurso'])) 
				  {
            require_once 'funciones/listarEstudiantes.php';
                                    $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$idCurso);
                                    $CantidadEstudiantes = count($ListadoEstudiantes);
                                    if ($CantidadEstudiantes == 0) {
                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>El curso seleccionado no tiene estudiantes asignados</strong>
                </div>
              </div>
             <?php
                                    } else {
                                      header('Location: funciones/emitirPDFListadoEstudiantesXCurso.php?Cx='.$idCurso);
                                    }
       /*            if(empty($_POST['AnioCurso']))
					{ 
?>
                       <div class="alert alert-dismissible alert-danger"><strong><center>Primero debe buscar un Curso</center></strong></div>
<?php 
					}
				   else 
				    { 
                     $_SESSION['IdCursoSeleccionado'] = $_POST['Id'];
                     $_SESSION['AnioCursoSeleccionado'] = $_POST['AnioCurso'];
                     $_SESSION['DivisionCursoSeleccionado'] = $_POST['DivisionCurso'];
                     header('Location: listadoEstudiantesXCurso.php?Cx='.$idCurso);
                    } */
                   } 
                if(!empty($_POST['EspCurrXCurso'])) 
				  {
            header('Location: funciones/emitirPDFListadoEspCurrXCurso.php?Cx='.$idCurso);
             /*      if(empty($_POST['AnioCurso']))
					{ 
?>
                        <div class="alert alert-dismissible alert-danger"><center><strong>Primero debe buscar un Curso</center></strong></div>
<?php   
					} 
				   else 
				    { 
                     if($_POST['AnioCurso'] != '1ro' && $_POST['AnioCurso'] != '2do' && $_POST['AnioCurso'] != '3ro' && $_POST['AnioCurso'] != '4to' && $_POST['AnioCurso'] != '5to' && $_POST['AnioCurso'] != '6to' && $_POST['AnioCurso'] != '7mo')
						{
?>
                          <div class="alert alert-dismissible alert-danger"><strong><center>El curso elegido no posee Espacios Curriculares</center></strong></div>
<?php
                        } 
					 else 
						{
                         $_SESSION['IdCursoSeleccionado'] = $_POST['Id'];
                         $_SESSION['AnioCursoSeleccionado'] = $_POST['AnioCurso'];
                         header('Location: listadoEspCurrXCurso.php?Cx='.$idCurso);
                        }
                    }  */
                  } 

                      //Si confirma verifico los campos
  /*                if(!empty($_POST['Buscar']))
					{
                     if(empty($_POST['Id']))
					   {
?>
						<div class="alert alert-dismissible alert-danger"><strong><center>Debe ingresar el número del Curso</center></strong></div>
<?php  
                        } 
					  else
					    { */
                         $CursoEncontrado = array();
                         $CursoEncontrado = buscarCurso($MiConexion,$idCurso);
                         $Cont = 0;
                         $Cont = count($CursoEncontrado);
                         if($Cont != 0)
							{
                             $_POST['AnioCurso'] = $CursoEncontrado['ANIO'];
                             $_POST['DivisionCurso'] = $CursoEncontrado['DIVISION'];
                            } 
						 else 
							{
?>
							 <div class="alert alert-dismissible alert-danger"><strong><center>Número identificador de Curso no válido</center></strong></div>
<?php
                              $_POST['AnioCurso'] = '';
                              $_POST['DivisionCurso'] = '';
                            }
                  //      }
                //    } 
?>
			</div><!--Cierra col errores-->
		</div><!--Cierra Row errores-->
		<div class="row">
			 <div class="col-lg-3"><label >N° Curso</label></DIV>
             <div class="col-lg-3"> <input class="form-control" name="Id" value="<?php echo $idCurso; ?>" readonly></div>
         <!--    <div class="col-lg-4"><button class="btn btn-primary" type="submit" value="Buscar" name="Buscar"><box-icon  name="search-alt" size="sm" color="white" animation="tada"></box-icon>Buscar</button></div> -->
		</div><!-- row busqueda--><hr>
		<div class="row">
			  <div class="col-lg-6">  
				<div class="form-group"><label>Año 	</label><input class="form-control" name="AnioCurso" value="<?php echo !empty($_POST['AnioCurso']) ? $_POST['AnioCurso'] : ''; ?>" readonly></div>
			  </div>
			  <div class="col-lg-6">  					
                 <div class="form-group"><label>Divisi&oacuten </label><input class="form-control" name="DivisionCurso" value="<?php echo !empty($_POST['DivisionCurso']) ? $_POST['DivisionCurso'] : ''; ?>" readonly></div>
			  </div>
		</div><!-- /.row input --><hr>
		
		<div class="row" align="center">
		 
			<div class="col-lg-12">  
				<button type="submit" class="btn btn-primary" value="EstudiantesXCurso" name="EstudiantesXCurso" ><box-icon  type='solid' name='user-detail'  size="sm" color="white" animation="tada-hover"></box-icon> Emitir Listado Estudiantes por Curso</button>
			  </div></div>
         <hr>
                                        <br>

<div class="panel panel-info">
  <div class="panel-heading">Espacios Curriculares del Curso</div>
  <div class="panel-body">
    <?php 
                                            $ListadoEspaciosCurriculares = ListarEspCurrXCurso($MiConexion,$idCurso);
                      $CantidadEspaciosCurriculares = count($ListadoEspaciosCurriculares);
                      if ($CantidadEspaciosCurriculares == 0) { ?>
    <div class="alert alert-dismissible alert-danger">
        <strong>El curso seleccionado no tiene Espacios Curriculares asignados</strong>
    </div>
<?php
} ?>

<?php       if (!empty($ListadoEspaciosCurriculares)) { ?>
                                        
                                    <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nombre Espacio Curricular</th>
                    <th>Área</th>
                    </tr>
                </thead>
                <tbody>
<?php
                        //Cargo a la tabla el listado de los estudiantes
                            for ($i=0; $i < $CantidadEspaciosCurriculares; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $ListadoEspaciosCurriculares[$i]['ID']; ?></td>
                                    <td><?php echo $ListadoEspaciosCurriculares[$i]['NOMBREESPACCURRIC']; ?></td>
                                    <td><?php echo $ListadoEspaciosCurriculares[$i]['AREA']; ?></td>
                </tr> 
                         <?php   }
                        ?>
                </tbody>
              </table>
            </div>
        
<div class="row" align="center">
        <div class="col-lg-12">            
                  <button type="submit" class="btn btn-primary" value="EspCurrXCurso" name="EspCurrXCurso"><box-icon  name="shopping-bag"  size="sm" color="white" animation="tada-hover"></box-icon> Emitir Listado Espacios Curriculares por Curso</button>
        </div>
    </div><!-- /.row input -->
<?php } ?>
  </div>
</div>


		<div class="row" align="center">
		 <div class="col-lg-12">  
           <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon  name="arrow-back"  size="sm" color="white" animation="tada-hover"></box-icon> Retornar</button></div>
		</div>
                                       
                                           <!--  <div>
                                                <button type="submit" class="btn btn-default" value="EstudiantesXCurso" name="EstudiantesXCurso" style="background-color: #888ffc">Listado de Estudiantes por Curso</button>
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-default" value="EspCurrXCurso" name="EspCurrXCurso" style="background-color: #888ffc">Listado de Espacios Curriculares por Curso</button>
                                            </div>
                                        </center>-->
                               
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
