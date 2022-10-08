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

require_once 'funciones/buscarDocente.php';
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
				<h1 class="page-header"><font color="#85C1E9"><center>Baja de docentes</center></font></h1>
                   
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

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                                
                                                $id= $_POST['Id'];
                                                $mensaje = eliminarElDocente($MiConexion,$id);
                                                    ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong><?php echo $mensaje; ?></strong>
                </div>
              </div>
             <?php
                                                $_POST['Id'] = "";
                                                $_POST['Apellido'] = "";
                                            $_POST['Nombre'] = "";
                                            $_POST['DNI'] = "";
                                            $_POST['FechaNacim'] = "";
                                            $_POST['NroLegajoJunta'] = "";
                                            $_POST['Titulo'] = "";
                                            $_POST['FechaEscalafon'] = "";
                                            $_POST['categorias'] = "";
                                            $_POST['Mail'] = "";
                                            $_POST['UltIngreso'] = "";
                                                $Listado = Listar_Docentes($MiConexion);
                                                $CantidadDocentes = count($Listado);
                                                }
                                            if (!empty($_POST['Buscar'])) {
                                                
                                                $DocenteBuscado = buscarDocente($MiConexion,$_POST['Id']);
                                                $Cant = count($DocenteBuscado);
                                                if ($Cant==0) { ?>
                                                    <div class="alert alert-dismissible alert-danger">
                  <strong>Número de docente no válido</strong>
                </div>
                                          <?php      } else {
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
                                          }
                                            }?>
											<div class="row" align="center"><font color="#85C1E9">
											<div class="col-lg-2"><label>Ingrese el número de legajo</label></div>
											 <div class="col-lg-2"align="left">
                                                <input class="form-control" name="Id" value="<?php echo !empty($_POST['Id']) ? $_POST['Id'] : ''; ?>"></div>
												<div class="col-lg-6"align="left" >
											<button class="btn-md btn btn-primary" type="submit" value="Buscar" name="Buscar"><box-icon name="search-alt" type="solid" size="md" color="white" animation="tada" ></box-icon> Buscar
                                                </button></div></div></font><hr>
											<div class="row">
                                         <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="disabledSelect">Apellido</label>
                                            <input class="form-control" name="Apellido" 
                                                value="<?php echo !empty($_POST['Apellido']) ? $_POST['Apellido'] : ''; ?>" disabled>
                                        </div></div>
										<div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="disabledSelect">Nombre</label>
                                            <input class="form-control" name="Nombre" value="<?php echo !empty($_POST['Nombre']) ? $_POST['Nombre'] : ''; ?>" disabled>
                                        </div></div></div>
										 <div class="row">
                                         <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="disabledSelect">DNI</label>
                                            <input class="form-control" name="DNI" value="<?php echo !empty($_POST['DNI']) ? $_POST['DNI'] : ''; ?>" disabled>
                                        </div></div>
										 <div class="col-lg-6">
                                       
                                        <div class="form-group">
                                            <label for="disabledSelect">Número de Legajo en Junta</label>
                                            <input class="form-control" name="NroLegajoJunta" value="<?php echo !empty($_POST['NroLegajoJunta']) ? $_POST['NroLegajoJunta'] : ''; ?>" disabled>
                                        </div></div></div>	
											
										 <div class="row">
                                         <div class="col-lg-12">
										 <div class="form-group">
                                            <label for="disabledSelect">E-mail</label>
                                            <input class="form-control" name="Mail" value="<?php echo !empty($_POST['Mail']) ? $_POST['Mail'] : ''; ?>" disabled>
                                        </div></div></div>	
											
										
										<div class="row">
                                         <div class="col-lg-6">
										<div class="form-group">
										 <label for="disabledSelect">Fecha de Nacimiento</label>
                                            <input id="date" type="date" name="FechaNacim" value="<?php echo !empty($_POST['FechaNacim']) ? $_POST['FechaNacim'] : ''; ?>" disabled>
                                        </div></div>
										 <div class="col-lg-6">
										 <div class="form-group">
                                            <label for="disabledSelect">Fecha de Escalafón</label>
                                            <input id="date" type="date" name="FechaEscalafon" value="<?php echo !empty($_POST['FechaEscalafon']) ? $_POST['FechaEscalafon'] : ''; ?>" disabled>
                                        </div></div></div>
										<div class="row">
                                         <div class="col-lg-9">
										<div class="form-group">
                                            <label for="disabledSelect">Título</label>
                                            <textarea class="form-control" rows="2" name="Titulo" disabled><?php echo !empty($_POST['Titulo']) ? $_POST['Titulo'] : ''; ?></textarea>
                                        </div></div>
										 <div class="col-lg-3">
										<div class="form-group">
                                                <label for="disabledSelect">Último Ingreso</label>
                                                <input class="form-control" id="disabledInput" type="text" name="UltIngreso" value="<?php echo !empty($_POST['UltIngreso']) ? $_POST['UltIngreso'] : ''; ?>" readonly>
												</div></div></div>
											<div class="form-group">
                                            <label for="disabledSelect">Categoría</label>
                                            <div class="radio">
                                                <label class="radio-inline" for="disabledSelect">
                                                    <input type="radio" name="categorias" id="1" value="1" <?php echo (!empty($_POST['categorias'])) && ($_POST['categorias']=='1') ? 'checked':''; ?> disabled>Coordinador/a
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="radio-inline" for="disabledSelect">
                                                    <input type="radio" name="categorias" id="2" value="2" <?php echo (!empty($_POST['categorias'])) && ($_POST['categorias']=='2') ? 'checked':''; ?> disabled>Secretario/a
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="radio-inline" for="disabledSelect">
                                                    <input type="radio" name="categorias" id="3" value="3" <?php echo (!empty($_POST['categorias'])) && ($_POST['categorias']=='3') ? 'checked':''; ?> disabled>Preceptor/a
                                                </label>
                                            </div>
                                            <div class="radio">                   
                                                <label class="radio-inline" for="disabledSelect">
                                                    <input type="radio" name="categorias" id="4" value="4"  <?php echo (!empty($_POST['categorias'])) && ($_POST['categorias']=='4') ? 'checked':''; ?> disabled>Docente
                                                </label>
                                            </div>
                                       
                                        </div>
                                        	<div class="row">
									  <div class="col-lg-2"></div>
									  
									   <div class="col-lg-4" align="right">
                                           <button type="submit" class="btn btn-primary" value="Confirmar" name="Confirmar"  onClick="return confirm ('Seguro que desea eliminar docente? (Si acepta no podrá recuperar los datos)');"><box-icon name="check-double"  size="sm" color="white"animation="tada" ></box-icon> Confirmar</button></div> 
										 <div class="col-lg-4">
											<button type="submit" class="btn btn-danger" value="Cancelar" name="Cancelar"><box-icon  name="x" type="solid" size="sm" color="white" animation="tada-hover"  ></box-icon> Cancelar</button></div>
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
