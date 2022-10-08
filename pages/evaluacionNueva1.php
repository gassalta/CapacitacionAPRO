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
                    <h1 class="page-header" align="center"><font color="#85C1E9">Alta Evaluación</font></h1>
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
    ?>
        
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            Ingrese los datos 
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
                                            if(!empty($_POST['Aprendizajes'])) {
                                                $_SESSION['FechaEvalElegida'] = $_POST['Fecha'];
                                                $_SESSION['EspaCurriEleg'] = $_POST['EspaCurri'];
                                                $_SESSION['EnviaEval'] = "evaluacionNueva.php";
                                                header('Location: aprendizajesXEvaluacion.php?Cx='.$EC);
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                                require_once 'funciones/buscarEvaluacion.php';
                                                $mensaje = '';
                                                if (empty($_POST['EspaCurri']) || empty($_POST['Fecha']) || empty($_POST['Instancia'])) {
                                                    $mensaje = "Debe seleccionar espacio curricular, fecha e instancia correspondientes a la evaluación - ";
                                                }
                                                
                                                $mensaje = $mensaje.evaluacionExiste($MiConexion,$_POST['Fecha'],$_POST['EspaCurri']);
                                                
                                                //Si está todo bien creo evaluación nueva en base de datos, sino, muestro mensaje
                                                if ($mensaje == '') {
                                                    require_once 'funciones/guardarEvaluacion.php';
                                                        if (evaluacionNueva($MiConexion,$_POST['EspaCurri'],$_POST['Instancia'],$_POST['Fecha'])) {
                                                            ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Evaluación nueva guardada!</strong>
                </div>
              </div>
                                                    <?php    }
                                                    
                                                        
                                                    } else {
                                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong><?php echo $mensaje; ?></strong>
                </div>
              </div>
             <?php
                                                }

 
                                       } ?>
									    <div class="row">
                                         <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Espacio Curricular</label>
                                            <select class="form-control" name="EspaCurri" id="EspaCurri">
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
                                            <input valign="bottom" id="date" type="date" name="Fecha" value="<?php echo !empty($_POST['Fecha']) ? $_POST['Fecha'] : ''; ?>">
                                        </div></div></div>
                                        <div class="row">
										<div class="col-lg-6">
										<div class="form-group">
                                            <label>Instancia</label>
                                            <select class="form-control" name="Instancia" id="Instancia">
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
                                            <button type="submit" class="btn btn-default" value="NuevaInstancia" name="NuevaInstancia" style="background-color: #337ab7; color: white;" onclick="return confirm ('Seguro que desea dirigirse a agregar nuevas instancias? - Puede perder los cambios que no haya guardado')" formaction=" <?php echo 'instanciaNueva.php?Cx='.$EC; ?>"
                                       ><box-icon  name="plus-circle" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>Nueva Instancia</button>
                                        </div></div>
										  </div>
										   
										<div class="row">  
										<div class="col-lg-2"></div>
											<div class="col-lg-4" align="center">
                                        <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('Seguro que desea guardar la nueva evaluación?');"
                                       ><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
									   <div class="col-lg-4" align="center">
                                        <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" onclick="return confirm ('Seguro que desea cancelar? - No se guardarán los datos que no haya guardado')"><box-icon name="x" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>   Cancelar</button></div></div>
                                        <br>
                                        <center>
                                            <div>
                                                <button type="submit" class="btn btn-primary" value="Aprendizajes" name="Aprendizajes"  onclick="return confirm ('Seguro que desea dirigirse a los Aprendizajes a evaluar? - Puede perder los cambios y, si no guarda la nueva evaluación, no le podrá asignar aprendizajes')"><box-icon name="spreadsheet" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Seleccionar Aprendizajes a Evaluar</button>
                                            </div>
                                        </center>
                                        
                                
                                </div>
                                <!-- /.col-lg-6 (nested) -->
                            </div>
                            <!-- /.row (nested) -->
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
