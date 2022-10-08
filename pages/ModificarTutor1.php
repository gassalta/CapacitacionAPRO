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
$TutorBuscado=array();
$TutorEncontrado = 0; //0-No - 1-Sí

require_once 'funciones/listarNacionalidades.php';
$ListadoNacionalidades = array();
$ListadoNacionalidades=Listar_Nacionalidades($MiConexion);
$CantNacionalidades = count($ListadoNacionalidades);
$idEstud = $_REQUEST['Cx'];
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
                    <h1 class="page-header">Padres, Madres, Tutores/as</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
    <?php
    //Listo los estudiantes
        require_once 'funciones/listarTutores.php';
        $Listado=array();
        $Listado = ListarTutores($MiConexion);
        $CantidadTutores = count($Listado);
    ?>
    <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <h3 class="tile-title">Listado Padres, Madres, Tutores/as (<?php echo $CantidadTutores; ?>)</h3>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>e-mail</th>
                    <th>Ocupación</th>
                    <th>Teléfono del Trabajo</th>

                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de los estudiantes
                            for ($i=0; $i < $CantidadTutores; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $Listado[$i]['ID']; ?></td>
                                    <td><?php echo $Listado[$i]['APELLIDO']; ?></td>
                                    <td><?php echo $Listado[$i]['NOMBRE']; ?></td>
                                    <td><?php echo $Listado[$i]['DNI']; ?></td>
                                    <td><?php echo $Listado[$i]['TELEFONO']; ?></td>
                                    <td><?php echo $Listado[$i]['MAIL']; ?></td>
                                    <td><?php echo $Listado[$i]['OCUPACION'];?></td>
                                    <td><?php echo $Listado[$i]['TELTRABAJO']; ?></td>
                                </tr> 
                         <?php   }
                        ?>
                        
                        
                     
                 
                </tbody>
              </table>
            </div>
          </div>
        </div>
    <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Datos Padre, Madre o Tutor/a
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si cancela vuelvo a EstudianteNuevo
                                            if (!empty($_POST['Cancelar'])) {
                                                
                                                header('Location: modificarEstudiante.php?Cx='.$idEstud);
                                            }

                                            //Si confirma verifico los campos
                                            if (!empty($_POST['Confirmar'])) {
                                                require_once 'funciones/verificarCamposTutor.php';
                                                require_once 'funciones/buscarTutor.php';
                                                $mensaje = '';
                                                $mensaje = verificarCamposTutor();
                                                //Si está todo bien actualizo tutor en base de datos, sino, muestro mensaje
                                                if ($mensaje == '') {
                                                    require_once 'funciones/guardarTutor.php';
                                                    $id = $_SESSION['IdTElegido'];
                                                        if (modificarTutor($MiConexion,$id,$_POST['ApellidoTutor'],$_POST['NombreTutor'],$_POST['DNITutor'],$_POST['telefonoTutor'],$_POST['MailTutor'],$_POST['ocupacionTutor'],$_POST['telTrabajoTutor'],$_POST['NacionalidadTutor'])) {
                                                            $_POST['IdTutor'] = $_SESSION['IdTElegido'];
                                                            ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Padre, madre, tutor o tutora número <?php echo $_SESSION['IdTElegido']; ?> modificado/a correctamente!</strong>
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

 
                                       }  
                                       if (!empty($_POST['Buscar'])) {
                                                require_once 'funciones/buscarTutor.php';
                                                $TutorBuscado = buscarTutor($MiConexion,$_POST['IdTutor']);
                                                $Cant = count($TutorBuscado);
                                                if ($Cant==0) { ?>
                                                    <div class="alert alert-dismissible alert-danger">
                  <strong>Número de tutor no válido</strong>
                </div>
                                          <?php  
                                                    $_POST['ApellidoTutor'] = "";
                                            $_POST['NombreTutor'] = "";
                                            $_POST['DNITutor'] = "";
                                            $_POST['telefonoTutor'] = "";
                                            $_POST['MailTutor'] = "";
                                            $_POST['ocupacionTutor'] = "";
                                            $_POST['telTrabajoTutor'] = "";
                                            $_POST['NacionalidadTutor'] = "";
                                                } else {
                                            $_POST['ApellidoTutor'] = $TutorBuscado['APELLIDO'];
                                            $_POST['NombreTutor'] = $TutorBuscado['NOMBRE'];
                                            $_POST['DNITutor'] = $TutorBuscado['DNI'];
                                            $_POST['telefonoTutor'] = $TutorBuscado['TELEFONO'];
                                            $_POST['MailTutor'] = $TutorBuscado['MAIL'];
                                            $_POST['ocupacionTutor'] = $TutorBuscado['OCUPACION'];
                                            $_POST['telTrabajoTutor'] = $TutorBuscado['TELTRABAJO'];
                                            $_POST['NacionalidadTutor'] = $TutorBuscado['NACIONALIDAD'];
                                            $_SESSION['IdTElegido'] = $_POST['IdTutor'];
                                            }
                                            }?>
                                       <label >Nro Padre</label>
                                            <input class="form-control" name="IdTutor" value="<?php echo !empty($_POST['IdTutor']) ? $_POST['IdTutor'] : ''; ?>">
                                            <button class="btn btn-default" type="submit" value="Buscar" name="Buscar">Buscar
                                                </button>
                                        <div class="form-group">
                                            <label>Apellido*</label>
                                            <input class="form-control" name="ApellidoTutor" value="<?php echo !empty($_POST['ApellidoTutor']) ? $_POST['ApellidoTutor'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Nombre*</label>
                                            <input class="form-control" name="NombreTutor" value="<?php echo !empty($_POST['NombreTutor']) ? $_POST['NombreTutor'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>DNI*</label>
                                            <input class="form-control" name="DNITutor" value="<?php echo !empty($_POST['DNITutor']) ? $_POST['DNITutor'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Numero de Telefono*</label>
                                            <input class="form-control" name="telefonoTutor" value="<?php echo !empty($_POST['telefonoTutor']) ? $_POST['telefonoTutor'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>e-mail*</label>
                                            <input class="form-control" name="MailTutor" value="<?php echo !empty($_POST['MailTutor']) ? $_POST['MailTutor'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Ocupación</label>
                                            <input class="form-control" name="ocupacionTutor" value="<?php echo !empty($_POST['ocupacionTutor']) ? $_POST['ocupacionTutor'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Teléfono del Trabajo</label>
                                            <input class="form-control" name="telTrabajoTutor" value="<?php echo !empty($_POST['telTrabajoTutor']) ? $_POST['telTrabajoTutor'] : ''; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label>Nacionalidad*</label>
                                            <select class="form-control" name="NacionalidadTutor" id="NacionalidadTutor">
                                                <option value=""></option>
                                                <?php 
                                                $selected='';
                                                for ($i=0 ; $i < $CantNacionalidades ; $i++) {
                                                    if (!empty($_POST['NacionalidadTutor']) && $_POST['NacionalidadTutor'] ==  $ListadoNacionalidades[$i]['ID']) {
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
                                        <label>* Campos Obligatorios</label>
                                        <button type="submit" class="btn btn-default" value="Confirmar" name="Confirmar" style="background-color: #7b16b6; color: white;"
                                            onClick="return confirm ('Seguro que desea guardar modificaciones del padre/madre/tutor/a?');"
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
