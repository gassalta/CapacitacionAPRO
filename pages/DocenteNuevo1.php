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
                    <h1 class="page-header" align="center"><font color="#85C1E9">Alta Docente</font></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
    <?php
    //Listo los docentes
        require_once 'funciones/listarDocentes.php';
        $Listado=array();
        $Listado = Listar_Docentes($MiConexion);
        $CantidadDocentes = count($Listado);
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
                                            //Si cancela vuelvo a administrarDocentes
                                            if (!empty($_POST['Cancelar'])) {
                                                
                                                header('Location: administrarDocentes.php');
                                            }

                                            if(!empty($_POST['EspCurr'])) {
                                                $_SESSION['DNIDocenteElegido'] = $_POST['DNI'];
                                                $_SESSION['Envia'] = 'DocenteNuevo.php';
                                                header('Location: EspCurrXDocente.php');
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                                require_once 'funciones/verificarCamposDocente.php';
                                                require_once 'funciones/buscarDocente.php';
                                                $mensaje = '';
                                                $mensaje = verificarCamposDoc();
                                                if (!empty($mensaje)) {
                                                    $mensaje = $mensaje." - ";
                                                }
                                                $mensaje = $mensaje.docenteExiste($MiConexion,$_POST['DNI']);
                                                
                                                //Si está todo bien creo docente nuevo en base de datos, sino, muestro mensaje
                                                if ($mensaje == '') {
                                                    require_once 'funciones/guardarDocente.php';
                                                        if (docenteNuevo($MiConexion,$_POST['Apellido'],$_POST['Nombre'],$_POST['DNI'],$_POST['FechaNacim'],$_POST['NroLegajoJunta'],$_POST['Titulo'],$_POST['FechaEscalafon'],$_POST['categorias'],$_POST['Apellido'].$_POST['DNI'],$_POST['Mail'])) {
                                                            ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Docente nuevo guardado!</strong>
                </div>
              </div>
                                                    <?php    }
                                                    $Listado = Listar_Docentes($MiConexion);
                                                    $CantidadDocentes = count($Listado);
                                                        
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
                                            <label>Apellido*</label>
                                            <input class="form-control" name="Apellido" value="<?php echo !empty($_POST['Apellido']) ? $_POST['Apellido'] : ''; ?>">
                                        </div></div>
										 <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>Nombre*</label>
                                            <input class="form-control" name="Nombre" value="<?php echo !empty($_POST['Nombre']) ? $_POST['Nombre'] : ''; ?>">
                                        </div></div></div>
										 <div class="row">
                                         <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>DNI*</label>
                                            <input class="form-control" name="DNI" value="<?php echo !empty($_POST['DNI']) ? $_POST['DNI'] : ''; ?>">
                                        </div></div>
										<div class="col-lg-6">
										<div class="form-group">
                                            <label>Número de Legajo en Junta</label>
                                            <input class="form-control" name="NroLegajoJunta" value="<?php echo !empty($_POST['NroLegajoJunta']) ? $_POST['NroLegajoJunta'] : ''; ?>">
                                        </div></div>
										  </div>
										   <div class="row">  
											<div class="col-lg-12">
                                        <div class="form-group">
                                            <label>e-mail*</label>
                                            <input class="form-control" name="Mail" value="<?php echo !empty($_POST['Mail']) ? $_POST['Mail'] : ''; ?>">
                                        </div></div></div>
										  
										   <div class="row">
										<div class="col-lg-6">
                                        <div class="form-group">
                                            <label valign="bottom">Fecha de Nacimiento*</label>
                                            <input valign="bottom" id="date" type="date" name="FechaNacim" value="<?php echo !empty($_POST['FechaNacim']) ? $_POST['FechaNacim'] : ''; ?>">
                                        </div></div>
										<div class="col-lg-6">
										<div class="form-group">
                                            <label>Fecha de Escalafón</label>
                                            <input id="date" type="date" name="FechaEscalafon" value="<?php echo !empty($_POST['FechaEscalafon']) ? $_POST['FechaEscalafon'] : ''; ?>">
                                        </div>
		                                  </div></div>
										  <div class="row">  
											<div class="col-lg-12">
                                        <div class="form-group">
                                            <label>Título*</label>
                                            <textarea class="form-control" rows="2" name="Titulo"><?php echo !empty($_POST['Titulo']) ? $_POST['Titulo'] : ''; ?></textarea>
                                        </div>
                                        </div></div>
                                        <div class="form-group">
                                            <label>Categoría*</label>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="categorias" id="1" value="1" <?php echo (!empty($_POST['categorias']) && $_POST['categorias'] == 1) ? 'checked' : ''; ?>>Coordinador/a
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="categorias" id="2" value="2" <?php echo (!empty($_POST['categorias']) && $_POST['categorias'] == 2) ? 'checked' : ''; ?>>Secretario/a
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="categorias" id="3" value="3" <?php echo (!empty($_POST['categorias']) && $_POST['categorias'] == 3) ? 'checked' : ''; ?>>Preceptor/a
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="categorias" id="4" value="4" <?php echo (!empty($_POST['categorias']) && $_POST['categorias'] == 4) ? 'checked' : (empty($_POST['categorias'])) ? 'checked' : ''; ?>>Docente
                                                </label>
                                            </div>
                                        </div>
										
                                        
                                        <label>* Campos Obligatorios</label><br>
										<div class="row">  
										<div class="col-lg-2"></div>
											<div class="col-lg-4" align="center">
                                        <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('Seguro que desea guardar el nuevo docente?');"
                                       ><box-icon name="check-double" type="solid" size="sm" color="white" animation="tada"></box-icon> Confirmar</button></div>
									   <div class="col-lg-4" align="center">
                                        <button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar" onclick="return confirm ('Seguro que desea cancelar? - No se guardarán los datos que no haya guardado')"><box-icon name="x" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>   Cancelar</button></div></div>
                                        <br>
                                        <center>
                                            <div>
                                                <button type="submit" class="btn btn-primary" value="EspCurr" name="EspCurr"  onclick="return confirm ('Seguro que desea dirigirse a los Espacios Curriculares del docente? - Puede perder los cambios y, si no guarda al docente nuevo, no le podrá guardar Espacios Curriculares a cargo')"><box-icon name="shopping-bag" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Espacios Curriculares a Cargo</button>
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
