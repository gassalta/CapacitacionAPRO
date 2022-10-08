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
$EstudianteBuscado=array();
$EstudianteEncontrado = 0; //0-No - 1-Sí

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
                            Datos Estudiante
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

                                            if(!empty($_POST['RegCurso'])) {
                                                if (empty($_POST['DNI'])) { ?>
                                                    <div class="alert alert-dismissible alert-danger">
                  <strong>Primero debe seleccionar un estudiante</strong>
              </div>
                                               <?php } else {
                                                $_SESSION['DNIEstudianteElegido'] = $_POST['DNI'];
                                                $_SESSION['IdEstudianteSeleccionado'] = $_POST['Id'];
                                                header('Location: RegCursoEstudiante.php');
                                            }
                                            } 
  
                                       if (!empty($_POST['Buscar'])) {
                                                require_once 'funciones/buscarEstudiante.php';
                                                $EstudianteBuscado = buscarEstudiante($MiConexion,$_POST['Id']);
                                                $Cant = count($EstudianteBuscado);
                                                if ($Cant==0) { ?>
                                                    <div class="alert alert-dismissible alert-danger">
                  <strong>Número de estudiante no válido</strong>
                </div>
                                          <?php      } else {
                                            $_POST['nroLegajo'] = $EstudianteBuscado['NROLEGAJO'];
                                            $_POST['nroLibroMatriz'] = $EstudianteBuscado['NROLIBROMATRIZ'];
                                            $_POST['nroFolio'] = $EstudianteBuscado['NROFOLIO'];
                                            $_POST['Apellido'] = $EstudianteBuscado['APELLIDO'];
                                            $_POST['Nombre'] = $EstudianteBuscado['NOMBRE'];
                                            $_POST['DNI'] = $EstudianteBuscado['DNI'];
                                            $_POST['telefono'] = $EstudianteBuscado['TELEFONO'];
                                            $_POST['Mail'] = $EstudianteBuscado['MAIL'];
                                            $_POST['Nacionalidad'] = $EstudianteBuscado['NACIONALIDAD'];
                                            $_POST['escDeProcedencia'] = $EstudianteBuscado['ESCDEPROCEDENCIA'];
                                            $_POST['lugarNacim'] = $EstudianteBuscado['LUGARNACIM'];
                                            $_POST['fechaNacim'] = $EstudianteBuscado['FECHANACIM'];
                                            $_POST['domicilio'] = $EstudianteBuscado['DOMICILIO'];
                                            $_POST['Barrio'] = $EstudianteBuscado['BARRIO'];
                                            $_POST['fechaPreinscripcion'] = $EstudianteBuscado['FECHAPREINSCRIPCION'];
                                            $_POST['Padre'] = $EstudianteBuscado['PADRE'];
                                            $_POST['Madre'] = $EstudianteBuscado['MADRE'];
                                            $_POST['Tutor'] = $EstudianteBuscado['TUTOR'];
                                            $_POST['Curso'] = $EstudianteBuscado['CURSO'];
                                          }
                                            }?>
                                       <label >Nro Estudiante</label>
                                            <input class="form-control" name="Id" value="<?php echo !empty($_POST['Id']) ? $_POST['Id'] : ''; ?>">
                                            <button class="btn btn-default" type="submit" value="Buscar" name="Buscar">Buscar
                                                </button>
                                        <div class="form-group">
                                            <label>Numero de Legajo</label>
                                            <input class="form-control" name="nroLegajo" value="<?php echo !empty($_POST['nroLegajo']) ? $_POST['nroLegajo'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Numero de Libro Matriz</label>
                                            <input class="form-control" name="nroLibroMatriz" value="<?php echo !empty($_POST['nroLibroMatriz']) ? $_POST['nroLibroMatriz'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Numero de Folio</label>
                                            <input class="form-control" name="nroFolio" value="<?php echo !empty($_POST['nroFolio']) ? $_POST['nroFolio'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Apellido*</label>
                                            <input class="form-control" name="Apellido" value="<?php echo !empty($_POST['Apellido']) ? $_POST['Apellido'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Nombre*</label>
                                            <input class="form-control" name="Nombre" value="<?php echo !empty($_POST['Nombre']) ? $_POST['Nombre'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>DNI*</label>
                                            <input class="form-control" name="DNI" value="<?php echo !empty($_POST['DNI']) ? $_POST['DNI'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Numero de Telefono*</label>
                                            <input class="form-control" name="telefono" value="<?php echo !empty($_POST['telefono']) ? $_POST['telefono'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>e-mail*</label>
                                            <input class="form-control" name="Mail" value="<?php echo !empty($_POST['Mail']) ? $_POST['Mail'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Nacionalidad*</label>
                                            <select class="form-control" name="Nacionalidad" id="Nacionalidad" readonly>
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
                                        </div>

                                        <div class="form-group">
                                            <label>Escuela de Procedencia</label>
                                            <input class="form-control" name="escDeProcedencia" value="<?php echo !empty($_POST['escDeProcedencia']) ? $_POST['escDeProcedencia'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Lugar de Nacimiento*</label>
                                            <input class="form-control" name="lugarNacim" value="<?php echo !empty($_POST['lugarNacim']) ? $_POST['lugarNacim'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Fecha de Nacimiento*</label>
                                            <input id="date" type="date" name="fechaNacim" value="<?php echo !empty($_POST['fechaNacim']) ? $_POST['fechaNacim'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Domicilio*</label>
                                            <input class="form-control" name="domicilio" value="<?php echo !empty($_POST['domicilio']) ? $_POST['domicilio'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Barrio*</label>
                                            <select class="form-control" name="Barrio" id="Barrio" readonly>
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
                                        </div>

                                        <div class="form-group">
                                            <label>Fecha de Preinscripcion</label>
                                            <input id="date" type="date" name="fechaPreinscripcion" value="<?php echo !empty($_POST['fechaPreinscripcion']) ? $_POST['fechaPreinscripcion'] : ''; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Padre</label>
                                            <select class="form-control" name="Padre" id="Padre" readonly>
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
                                            <select class="form-control" name="Madre" id="Madre" readonly>
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
                                            <select class="form-control" name="Tutor" id="Tutor" readonly>
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
                                        </div>
                                        <center>
                                            <div class="form-group">
                                            <label>Curso</label>
                                            <select class="form-control" name="Curso" id="Curso" readonly>
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantidadCursos ; $i++) {
                                                    if (!empty($_POST['Curso']) && $_POST['Curso'] ==  $ListadoCursos[$i]['ID']) {
                                                        $selected = 'selected';
                                                    }else {
                                                        $selected='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoCursos[$i]['ID']; ?>" <?php echo $selected; ?>  >
                                                        <?php echo $ListadoCursos[$i]['ANIO']." - Division: ".$ListadoCursos[$i]['DIVISION']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div> 
                                        </center>
                                        <button type="submit" class="btn btn-default" value="Cancelar" name="Cancelar" style="background-color: #fb0000; color: white;">Cancelar</button>
                                        <br>
                                        <center>
                                            <div>
                                                <button type="submit" class="btn btn-default" value="RegCurso" name="RegCurso" style="background-color: #888ffc">Registrar Curso Actual</button>
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
