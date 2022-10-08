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

require_once 'funciones/listarNacionalidades.php';
$ListadoNacionalidades = array();
$ListadoNacionalidades=Listar_Nacionalidades($MiConexion);
$CantNacionalidades = count($ListadoNacionalidades);

require_once 'funciones/listaCursos.php';
$ListadoCursos=array();
$ListadoCursos = ListarCursos($MiConexion);
$CantidadCursos = count($ListadoCursos);

require_once 'funciones/listarBarrios.php';
$ListadoBarrios = array();
$ListadoBarrios=Listar_Barrios($MiConexion);
$CantBarrios = count($ListadoBarrios);

require_once 'funciones/listarTutores.php';
$ListadoTutores = array();
$ListadoTutores=ListarTutores($MiConexion);
$CantTutores = count($ListadoTutores);
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
                    <h1 class="page-header">Estudiantes</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
    <div class="row">
        <div class="col-md-12">
        <div class="clearfix"></div>
        
      </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos Estudiante Nuevo
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a administrarEstudiantes
                                            if (!empty($_POST['Cancelar'])) {
                                                
                                                header('Location: administrarEstudiantes.php');
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                                require_once 'funciones/verificarCamposEstudiante.php';
                                                require_once 'funciones/buscarEstudiante.php';
                                                $mensaje = '';
                                                $mensaje = verificarCamposEstud();
                                                if (!empty($mensaje)) {
                                                    $mensaje = $mensaje." - ";
                                                }
                                                $mensaje = $mensaje.estudianteExiste($MiConexion,$_POST['DNI']);
                                                
                                                //Si está todo bien creo estudiante nuevo en base de datos, sino, muestro mensaje
                                                if ($mensaje == '') {
                                                    require_once 'funciones/guardarEstudiante.php';
                                                        if (estudianteNuevo($MiConexion,$_POST['nroLegajo'],$_POST['nroLibroMatriz'],$_POST['nroFolio'],$_POST['Apellido'],$_POST['Nombre'],$_POST['DNI'],$_POST['telefono'],$_POST['Mail'],$_POST['Nacionalidad'],$_POST['escDeProcedencia'],$_POST['lugarNacim'],$_POST['fechaNacim'],$_POST['domicilio'],$_POST['Barrio'],$_POST['fechaPreinscripcion'],$_POST['Padre'],$_POST['Madre'],$_POST['Tutor'])) {
                                                            ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Estudiante nuevo guardado!</strong>
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

 
                                       }  ?>
                                        <div class="form-group">
                                            <label>Numero de Legajo</label>
                                            <input class="form-control" name="nroLegajo" value="<?php echo !empty($_POST['nroLegajo']) ? $_POST['nroLegajo'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Numero de Libro Matriz</label>
                                            <input class="form-control" name="nroLibroMatriz" value="<?php echo !empty($_POST['nroLibroMatriz']) ? $_POST['nroLibroMatriz'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Numero de Folio</label>
                                            <input class="form-control" name="nroFolio" value="<?php echo !empty($_POST['nroFolio']) ? $_POST['nroFolio'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Apellido*</label>
                                            <input class="form-control" name="Apellido" value="<?php echo !empty($_POST['Apellido']) ? $_POST['Apellido'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Nombre*</label>
                                            <input class="form-control" name="Nombre" value="<?php echo !empty($_POST['Nombre']) ? $_POST['Nombre'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>DNI*</label>
                                            <input class="form-control" name="DNI" value="<?php echo !empty($_POST['DNI']) ? $_POST['DNI'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Numero de Telefono*</label>
                                            <input class="form-control" name="telefono" value="<?php echo !empty($_POST['telefono']) ? $_POST['telefono'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>e-mail*</label>
                                            <input class="form-control" name="Mail" value="<?php echo !empty($_POST['Mail']) ? $_POST['Mail'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Nacionalidad*</label>
                                            <select class="form-control" name="Nacionalidad" id="Nacionalidad">
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantNacionalidades ; $i++) {
                                                    if (!empty($_POST['Nacionalidad']) && $_POST['Nacionalidad'] ==  $ListadoNacionalidades[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoNacionalidades[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoNacionalidades[$i]['NACION']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" class="btn btn-default" value="NacionalidadNueva" name="NacionalidadNueva" formaction="NuevaNacionalidad.php"> Nueva Nacionalidad</button>
                                        </div>

                                        <div class="form-group">
                                            <label>Escuela de Procedencia</label>
                                            <input class="form-control" name="escDeProcedencia" value="<?php echo !empty($_POST['escDeProcedencia']) ? $_POST['escDeProcedencia'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Lugar de Nacimiento*</label>
                                            <input class="form-control" name="lugarNacim" value="<?php echo !empty($_POST['lugarNacim']) ? $_POST['lugarNacim'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Fecha de Nacimiento*</label>
                                            <input id="date" type="date" name="fechaNacim" value="<?php echo !empty($_POST['fechaNacim']) ? $_POST['fechaNacim'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Domicilio*</label>
                                            <input class="form-control" name="domicilio" value="<?php echo !empty($_POST['domicilio']) ? $_POST['domicilio'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Barrio*</label>
                                            <select class="form-control" name="Barrio" id="Barrio">
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantBarrios ; $i++) {
                                                    if (!empty($_POST['Barrio']) && $_POST['Barrio'] ==  $ListadoBarrios[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoBarrios[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoBarrios[$i]['NOMBRE']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" class="btn btn-default" value="BarrioNuevo" name="BarrioNuevo" formaction="NuevoBarrio.php"> Nuevo Barrio</button>
                                        </div>

                                        <div class="form-group">
                                            <label>Fecha de Preinscripcion</label>
                                            <input id="date" type="date" name="fechaPreinscripcion" value="<?php echo !empty($_POST['fechaPreinscripcion']) ? $_POST['fechaPreinscripcion'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Padre</label>
                                            <select class="form-control" name="Padre" id="Padre">
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantTutores ; $i++) {
                                                    if (!empty($_POST['Padre']) && $_POST['Padre'] ==  $ListadoTutores[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoTutores[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoTutores[$i]['APELLIDO']." ".$ListadoTutores[$i]['NOMBRE']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <label>Madre</label>
                                            <select class="form-control" name="Madre" id="Madre">
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantTutores ; $i++) {
                                                    if (!empty($_POST['Madre']) && $_POST['Madre'] ==  $ListadoTutores[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoTutores[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoTutores[$i]['APELLIDO']." ".$ListadoTutores[$i]['NOMBRE']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <label>Tutor/a</label>
                                            <select class="form-control" name="Tutor" id="Tutor">
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantTutores ; $i++) {
                                                    if (!empty($_POST['Tutor']) && $_POST['Tutor'] ==  $ListadoTutores[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoTutores[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoTutores[$i]['APELLIDO']." ".$ListadoTutores[$i]['NOMBRE']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" class="btn btn-default" value="TutorNuevo" name="TutorNuevo" formaction="NuevoPadre.php"> Nuevo Padre, Madre o Tutor</button>
                                        </div>
                                        
                                        <label>* Campos Obligatorios</label>
                                        <button type="submit" class="btn btn-default" value="Confirmar" name="Confirmar" style="background-color: #7b16b6; color: white;"
                                            onClick="return confirm ('Seguro que desea guardar el nuevo estudiante?');"
                                       >Confirmar</button>
                                        <button type="submit" class="btn btn-default" value="Cancelar" name="Cancelar" style="background-color: #fb0000; color: white;" onclick="return confirm ('Seguro que desea cancelar? - No se guardarán los datos que no haya guardado')">Cancelar</button>
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
