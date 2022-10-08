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
$DocenteBuscado=array();
$DocenteEncontrado = 0; //0-No - 1-Sí

$Doc=$_REQUEST['Cx'];
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
				<h1 class="page-header"><font color="#85C1E9"><center>Modificar docentes</center></font></h1>
                   
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
    
            
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                          Seleccione un docente
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a administrarDocentes
                                            if (!empty($_POST['Cancelar'])) {
                                                //$accion = 0;
                                                header('Location: administrarDocentes.php');
                                            }
                                            if(!empty($_POST['EspCurr'])) {
                                                $_SESSION['DNIDocenteElegido'] = $_POST['DNI'];
                                                $_SESSION['Envia'] = 'modificarDocente.php';
                                                header('Location: EspCurrXDocente.php');
                                            }
                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                                require_once 'funciones/verificarCamposDocente.php';
                                                $mensaje = '';
                                                $mensaje = verificarCamposDoc();
                                                if ($mensaje == '') {
                                                    require_once 'funciones/guardarDocente.php';
                                                            $id= $_SESSION['IdDElegido'];
                                                        if (modificarDocente($MiConexion,$id,$_POST['Apellido'],$_POST['Nombre'],$_POST['DNI'],$_POST['FechaNacim'],$_POST['NroLegajoJunta'],$_POST['Titulo'],$_POST['FechaEscalafon'],$_POST['categorias'],$_POST['Mail'])) {
                                                            $_POST['Id'] = $_SESSION['IdDElegido'];
                                                            ?>

                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Docente número <?php echo $_SESSION['IdDElegido']; ?> modificado correctamente!</strong>
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

                                            }

                                       //     if (!empty($_POST['Buscar'])) {
                                                require_once 'funciones/buscarDocente.php';
                                                $DocenteBuscado = buscarDocente($MiConexion,$Doc);
                                                $Cant = count($DocenteBuscado);
                                                if ($Cant==0) { ?>
                                                    <div class="alert alert-dismissible alert-danger">
                  <strong>Número de docente no válido</strong>
                </div>
                                          <?php   
                                                    $_POST['Apellido'] ="";
                                            $_POST['Nombre'] = "";
                                            $_POST['DNI'] = "";
                                            $_POST['FechaNacim'] = "";
                                            $_POST['NroLegajoJunta'] = "";
                                            $_POST['Titulo'] = "";
                                            $_POST['FechaEscalafon'] = "";
                                            $_POST['categorias'] = "";
                                            $_POST['Mail'] = "";
                                            $_POST['UltIngreso'] = "";
                                                } else {
                                            $_POST['Apellido'] = $DocenteBuscado['APELLIDO'];
                                            $_POST['Nombre'] = $DocenteBuscado['NOMBRE'];
                                            $_POST['DNI'] = $DocenteBuscado['DNI'];
                                            $_POST['FechaNacim'] = $DocenteBuscado['FECHANACIM'];
                                            $_POST['NroLegajoJunta'] = $DocenteBuscado['NROLEGAJOJUNTA'];
                                            $_POST['Titulo'] = $DocenteBuscado['TITULO'];
                                            $_POST['FechaEscalafon'] = $DocenteBuscado['FECHAESCALAFON'];
                                            $_POST['categorias'] = $DocenteBuscado['CATEGORIA'];
                                            $_POST['Mail'] = $DocenteBuscado['MAIL'];
                                            $_POST['UltIngreso'] = $DocenteBuscado['ULTINGRESO'];
                                            $_SESSION['IdDElegido'] = $Doc;
                                          }
                                        //    }?>
                                        <div class="row" align="center"><font color="#85C1E9">
											<div class="col-lg-2"><label>Número</label></div>
											 <div class="col-lg-2"align="left">
                                                <input class="form-control" name="Id" value="<?php echo $Doc; ?>" readonly></div>
												<div class="col-lg-6"align="left" >
										<!--	<button class="btn-md btn btn-primary" type="submit" value="Buscar" name="Buscar"><box-icon name="search-alt" type="solid" size="md" color="white" animation="tada" ></box-icon> Buscar
                                                </button> --> </div></font>
										</div><hr>  
										<div class="row">
                                         <div class="col-lg-6">
											<div class="form-group"><label>Apellido *</label>
                                            <input class="form-control" name="Apellido" 
                                                value="<?php echo !empty($_POST['Apellido']) ? $_POST['Apellido'] : ''; ?>">
											</div></div>
										 <div class="col-lg-6">
											<div class="form-group"><label>Nombre *</label>
                                            <input class="form-control" name="Nombre" value="<?php echo !empty($_POST['Nombre']) ? $_POST['Nombre'] : ''; ?>">
											</div></div>
										</div>	
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
                                        </div></div></div>		
											
										 <div class="row">
                                         <div class="col-lg-12">
										 <div class="form-group">
                                            <label>E-mail *</label>
                                            <input class="form-control" name="Mail" value="<?php echo !empty($_POST['Mail']) ? $_POST['Mail'] : ''; ?>">
                                        </div></div></div>	
										
										<div class="row">
                                         <div class="col-lg-6">
										<div class="form-group">
										 <label>Fecha de Nacimiento *</label>
                                            <input id="date" type="date" name="FechaNacim" value="<?php echo !empty($_POST['FechaNacim']) ? $_POST['FechaNacim'] : ''; ?>" >
                                        </div></div>
										 <div class="col-lg-6">
										 <div class="form-group">
                                            <label>Fecha de Escalafón</label>
                                            <input id="date" type="date" name="FechaEscalafon" value="<?php echo !empty($_POST['FechaEscalafon']) ? $_POST['FechaEscalafon'] : ''; ?>" >
                                        </div></div></div>
											<div class="row">
                                         <div class="col-lg-9">
										<div class="form-group">
                                            <label>Título *</label>
                                            <textarea class="form-control" rows="2" name="Titulo"><?php echo !empty($_POST['Titulo']) ? $_POST['Titulo'] : ''; ?></textarea>
                                        </div></div>
										 <div class="col-lg-3">
										<div class="form-group">
                                                <label for="disabledSelect">Último Ingreso</label>
                                                <input class="form-control" id="disabledInput" type="text" disabled name="UltIngreso" value="<?php echo !empty($_POST['UltIngreso']) ? $_POST['UltIngreso'] : ''; ?>">
												</div></div></div>	
                                        
                                        <div class="form-group">
                                            <label>Categoría*</label>
                                            <div class="radio">
                                                <label class="radio-inline">
                                                    <input type="radio" name="categorias" id="1" value="1" <?php echo (!empty($_POST['categorias'])) && ($_POST['categorias']=='1') ? 'checked':''; ?>>Coordinador/a
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="radio-inline">
                                                    <input type="radio" name="categorias" id="2" value="2" <?php echo (!empty($_POST['categorias'])) && ($_POST['categorias']=='2') ? 'checked':''; ?>>Secretario/a
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="radio-inline">
                                                    <input type="radio" name="categorias" id="3" value="3" <?php echo (!empty($_POST['categorias'])) && ($_POST['categorias']=='3') ? 'checked':''; ?>>Preceptor/a
                                                </label>
                                            </div>
                                            <div class="radio">                   
                                                <label class="radio-inline">
                                                    <input type="radio" name="categorias" id="4" value="4"  <?php echo (!empty($_POST['categorias'])) && ($_POST['categorias']=='4') ? 'checked':''; ?>>Docente
                                                </label>
                                            </div>
                                       
                                        </div>
										<label class="label-info	">* Campos Obligatorios</label><br><hr>
                                         	<div class="row">
									    
									  
									   <div class="col-lg-4"align="center" >
                                           <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar" onClick="return confirm ('Seguro que desea modificar docente?');"><box-icon name="check-double"  size="sm" color="white"animation="tada" ></box-icon> Confirmar</button></div> 
										   <div class="col-lg-4"align="center"  >
										   <button type="submit" class="btn btn-primary" value="EspCurr" name="EspCurr"  onclick="return confirm ('Seguro que desea dirigirse a los Espacios Curriculares del docente? - Puede perder los cambios y, si no guarda al docente nuevo, no le podrá guardar Espacios Curriculares a cargo')"><box-icon name="shopping-bag"  size="sm" color="white"animation="tada-hover" ></box-icon> Espacios Curriculares a Cargo</button>
										   </div>
										 <div class="col-lg-4"align="center" >
											<button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon  name="arrow-back" type="solid" size="sm" color="white" animation="tada-hover"  ></box-icon> Retornar</button></div>
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
