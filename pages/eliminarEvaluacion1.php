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

$EC=$_REQUEST['Ec'];
$IdEval = $_REQUEST['Cx'];
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
				<h1 class="page-header"><font color="#85C1E9"><center>Eliminar Evaluaciones</center></font></h1>
                   
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
    <?php 
        //Listo los espacios curriculares
        require_once 'funciones/listarEspaciosCurricularesXDocente.php';
        $ListadoEC=array();
        $ListadoEC = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
        $CantidadEspCurr = count($ListadoEC);

        //Listo las instancias
        require_once 'funciones/listarInstancias.php';
        $ListadoInstancias=array();
        $ListadoInstancias = Listar_Instancias($MiConexion);
        $CantidadInstancias = count($ListadoInstancias);

        require_once 'funciones/buscarEvaluacion.php';
    ?>
    
            
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                          Seleccione una evaluación
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a administrarEvaluaciones
                                            if (!empty($_POST['Cancelar'])) {
                                                header('Location: administrarEvaluaciones.php?Cx='.$EC);
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                                $mensaje = '';
                                                $_POST['EspaCurri'] = $_SESSION['IdECdeEvalBuscada'];
                                                $evalCalif=array();
                                                $evalCalif=evaluacionCalificada($MiConexion,$_SESSION['IdEvalBuscada']);
                                                $CantEvalCalif = count($evalCalif);
                                                if ($CantEvalCalif != 0) {
                                                    $mensaje = $mensaje."La evaluación ya fue calificada, por lo que no puede eliminarla";
                                                }
                                                
                                                //Si está todo bien elimino evaluación de base de datos, sino, muestro mensaje
                                                if ($mensaje == '') {
                                                    $id= $_SESSION['IdEvalBuscada'];
                                                    $mensaje = eliminarLaEvaluacion($MiConexion,$id);
                                                    $_POST['Id']="";
                                                    $_POST['EspaCurri'] ="";
                                                    $_POST['Fecha'] = "";
                                                    $_POST['Instancia'] = "";
                                                    $IdEval="";
                                                } 
                                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong><?php echo $mensaje; ?></strong>
                </div>
              </div>
             <?php
                                                } 

                                            

                                    //        if (!empty($_POST['Buscar'])) {
                                                if ($IdEval!=0){
                                                $EvaluacionBuscada = buscarEvaluacion($MiConexion,$IdEval);
                                                $Cant = count($EvaluacionBuscada);
                                           /*     if ($Cant==0) { ?>
                                                    <div class="alert alert-dismissible alert-danger">
                  <strong>Número de evaluación no válido</strong>
                </div>
                                          <?php   
                                                    $_POST['EspaCurri'] ="";
                                            $_POST['Fecha'] = "";
                                            $_POST['Instancia'] = "";
                                            
                                                } else { */
                                                    
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
                                            
                                                        $_SESSION['IdEvalBuscada'] = $IdEval;
                                                        $_SESSION['IdECdeEvalBuscada'] = $EvaluacionBuscada['IDESPACURRI'];
                                                    } else { ?>
                                                        <div class="alert alert-dismissible alert-danger">
                  <strong>No tiene acceso a la evaluación indicada</strong>
                </div>
                                              <?php     
                                            $_POST['EspaCurri'] ="";
                                            $_POST['Fecha'] = "";
                                            $_POST['Instancia'] = "";
                                               }
                                            
                                         } 
                                      //      }?>
                                        <div class="row" align="center"><font color="#85C1E9">
											<div class="col-lg-2"><label>Número de evaluación</label></div>
											 <div class="col-lg-2"align="left">
                                                <input class="form-control" name="Id" value="<?php echo $IdEval; ?>" readonly></div>
												<div class="col-lg-6"align="left" >
									<!--		<button class="btn-md btn btn-primary" type="submit" value="Buscar" name="Buscar"><box-icon name="search-alt" type="solid" size="md" color="white" animation="tada" ></box-icon> Buscar
                                                </button> --> </div></font>
										</div><hr>  
										<div class="row">
                                         <div class="col-lg-6">
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
                                         <div class="col-lg-6">
                                        <div class="form-group">
                                        </div></div></div>
                                         <div class="row">
                                         <div class="col-lg-6">
                                        <div class="form-group">
                                            <label valign="bottom">Fecha</label>
                                            <input valign="bottom" id="date" type="date" name="Fecha" value="<?php echo !empty($_POST['Fecha']) ? $_POST['Fecha'] : ''; ?>" disabled>
                                        </div></div></div>
                                        <div class="row">
                                        <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Instancia</label>
                                            <select class="form-control" name="Instancia" id="Instancia" disabled>
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantidadInstancias ; $i++) {
                                                    if (!empty($_POST['Instancia']) && $_POST['Instancia'] ==  $ListadoInstancias[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                            $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoInstancias[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoInstancias[$i]['DENOMINACION']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                           
                                        </div></div>
                                          </div>
										
									  
									   <div class="col-lg-4"align="center" >
                                           <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('Seguro que desea eliminar la evaluación? - No podrá recuperar los datos');"><box-icon name="check-double"  size="sm" color="white"animation="tada" ></box-icon> Confirmar</button></div> 
										   
										 <div class="col-lg-4"align="center" >
											<button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" ><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"  ></box-icon> Retornar</button></div>
                                          </div>
										  
                                        <br>
                                        
                                        
                                
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
