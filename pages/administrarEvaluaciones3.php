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

$CantECEsDoc = 0;
$EsDocente= 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php
    require_once 'encabezado.php';
	
?>
   <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
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
            
    <?php
    //Listo los espacios curriculares
        require_once 'funciones/listarEspaciosCurricularesXDocente.php';
        $ListadoEC=array();
    if ($_SESSION['Categoria'] == 'Docente') {
        $ListadoEC = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
    } else {
        require_once 'funciones/listarEspaciosCurriculares.php';
        $ListadoEC = Listar_EspCurr($MiConexion);
        $ListadoEspCurrEsDoc = array();
        $ListadoEspCurrEsDoc = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
        $CantECEsDoc = count($ListadoEspCurrEsDoc);
    }
    $CantidadEspCurr = count($ListadoEC);

    ?>
    <div class="row">
        <div class="col-lg-10">
         <h1 class="page-header"><font color="#85C1E9">Evaluaciones</font></h1>
        </div>
        <div class="clearfix"></div>
        
      </div>
            <div class="row">
                <div class="col-lg-10">
                    <div class="panel panel-primary">
					<div class="panel-heading">Seleccione el espacio curricular a consultar</div>
                        <div class="panel-body">
                            <div class="row">
                                
                                    <form role="form" method="post">
                                        <div class="col-lg-3"> <label>Espacio Curricular</label></div>
                                        <div class="form-group">
                                           <div class="col-lg-5">
                                            <select class="form-control" name="EspaCurri" id="EspaCurri">
                                                <option value="">Seleccione una opción</option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantidadEspCurr ; $i++) {
                                                    if (!empty($_POST['EspaCurri']) && $_POST['EspaCurri'] ==  $ListadoEC[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                    /*    if ($selected != 'selected' && $_SESSION['EspCurrElegido']!= 0 && $_SESSION['EspCurrElegido'] == $ListadoEC[$i]['ID']) {
                                                            $selected = 'selected';
                                                        } else { */
                                                            $selected='';
                                                     //   }
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoEC[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoEC[$i]['NOMBREESPACCURRIC']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select></div>
											 <div class="col-lg-2">
                                            <button type="submit" class="btn btn-default" value="Ver" name="Ver" style="background-color: #337ab7; color: white;"
                                       ><box-icon  name="show" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>Ver</button></diV>
                                        </div></div><hr>
										
                                        <?php
                                        if (!empty($_POST['Ver'])) {
                                            $_SESSION['EspCurrElegido'] = 0;
                                        }
                                            //si hay seleccionado algún contenido...
                                            if (!empty($_POST['EspaCurri'])) {
                                                //listo los contenidos del espacio curricular
                                                require_once 'funciones/listarEvaluaciones.php';
                                                $ListadoEvaluaciones = array();
                                                
                                                if ($_SESSION['EspCurrElegido'] == 0) {
                                                    $ListadoEvaluaciones = Listar_Evaluaciones($MiConexion,$_POST['EspaCurri']);  
                                                    if ($_SESSION['Categoria'] != 'Docente' && $CantECEsDoc > 0) {
                                                        for ($i=0; $i < $CantECEsDoc; $i++) { 
                                                            if ($ListadoEspCurrEsDoc[$i]['ID']==$_POST['EspaCurri']) {
                                                                $EsDocente=1;
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    $ListadoEvaluaciones = Listar_Evaluaciones($MiConexion,$_SESSION['EspCurrElegido']);
                                                    for ($i=0; $i < $CantECEsDoc; $i++) { 
                                                            if ($ListadoEspCurrEsDoc[$i]['ID']==$_SESSION['EspCurrElegido']) {
                                                                $EsDocente=1;
                                                            }
                                                        }
                                                }
                                                $CantidadEvaluaciones = count($ListadoEvaluaciones); 

                                                 if ($CantidadEvaluaciones != 0) {
                                                ?>
                                                 <div class="row">
												<div class="col-lg-10">
                                                <h3	 class="tile-title"><font color="#85C1E9"><center>Hay <?php echo $CantidadEvaluaciones; ?> evaluacion/es para este espacio curricular<hr></font></h3></div></div>
												 <div class="row">
												<div class="col-lg-10">
            <div class="table-responsive">
              <table class="table-md table-striped table-bordered table-hover bg-info">
                <thead>
                  <tr>
                    <th>N°</th>
                    <th>Fecha</th>
					<th>Inspeccionar</th>
                    <?php if ($_SESSION['Categoria'] == 'Docente' || $EsDocente==1) { ?>
                    <th>Modificar</th>
					<th>Eliminar</th>
                <?php } ?>
                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de las evaluaciones
                            for ($i=0; $i < $CantidadEvaluaciones; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $ListadoEvaluaciones[$i]['ID'], "- "; ?></td>
									<?php
									$originalDate =$ListadoEvaluaciones[$i]['FECHA'];
									$timestamp = strtotime($originalDate); 
									$nuevaFecha = date("d-m-Y", $timestamp );?>
                                    <td><?php echo $nuevaFecha; ?></td>
									
									<?php                                    
									echo'<td><a href="buscarUnaEvaluacion.php?Tx=M&Cx='.$ListadoEvaluaciones[$i]['ID'].'"><box-icon name="search-alt"  size="md" color="black" animation="tada-hover"></box-icon></a></td>';
                                    if ($_SESSION['Categoria'] == 'Docente' || $EsDocente==1) {
									echo'<td><a href="modificarEvaluacion.php?Tx=M&Cx='.$ListadoEvaluaciones[$i]['ID'].'"><box-icon name="edit-alt" type="solid" size="md" color="black" animation="tada-hover"></box-icon></a></td>';
									echo'<td><a href="eliminarEvaluacion.php?Tx=M&Cx='.$ListadoEvaluaciones[$i]['ID'].'"><box-icon name="trash" " size="md" color="black" animation="tada-hover"></box-icon></a></td>';
                                }?>
                                </tr> 
                         <?php   }

                        ?>
                        
                        
                     
                 
                </tbody>
              </table> 
            </div></div></div>
        <?php
        } else { ?>
                <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>El espacio curricular no tiene registrada ninguna evaluación</strong>
                </div>
              </div>
      <?php }

         } else { ?>
                <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Seleccione un espacio curricular</strong>
                </div>
              </div>
      <?php  }?>
	    <br>
	  <div class="row">
                               
				<div class="form-group">
				<div class="col-lg-2"></div>
				 <div class="col-lg-4">
        <?php if ($_SESSION['Categoria'] == 'Docente' || $EsDocente==1) { ?>
				<button class="btn btn-primary" type="submit" name="ContenidoNuevo" formaction="evaluacionNueva.php"><box-icon name="plus-circle" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Nueva
                </button></div>
        <?php } ?>
				 <div class="col-lg-4"><button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"formaction="index.php"><box-icon name="x" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Cancelar</button></div></div></div>
                			
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
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
