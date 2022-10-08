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

require_once 'funciones/buscarCurso.php';
$CursoElegido = array();

require_once 'funciones/listarEstudiantes.php';
$ListadoEstudiantes=array();

$idCurso=$_REQUEST['Cx'];
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
                    <h1 class="page-header">Listado de Estudiantes por Curso</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
    <?php
    //Listo los cursos
        require_once 'funciones/listaCursos.php';
$ListadoCursos=array();
$ListadoCursos = ListarCursos($MiConexion);
$CantidadCursos = count($ListadoCursos);
?>
            <div class="row">
                <div class="col-lg-18">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Listado de Estudiantes
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-15">
                                    <form role="form" method="post">
                                        <?php 
                                            $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$_SESSION['IdCursoSeleccionado']);
											$CantidadEstudiantes = count($ListadoEstudiantes);
											if ($CantidadEstudiantes == 0) { ?>
    <div class="alert alert-dismissible alert-danger">
        <strong>El curso seleccionado no tiene estudiantes asignados</strong>
    </div>
   </div>
<?php
}

                                            //Si cancela vuelvo a buscarUnCurso
                                            if (!empty($_POST['Cancelar'])) {
                                                
                                                header('Location: buscarUnCurso.php?Cx='.$idCurso);
                                            } ?>

                                    <div class="form-group">
                                            <label>Curso</label>
                                            <select class="form-control" name="Curso" id="Curso">
                                                <option value=""></option>
                                                <?php 
                                                $selectedC='';
                                                for ($i=0 ; $i < $CantidadCursos ; $i++) {
                                                    if (!empty($_POST['Curso']) && $_POST['Curso'] ==  $ListadoCursos[$i]['ID']) {
                                                        $selectedC = 'selected';
                                                    }else {
                                                        $selectedC='';
                                                    }
                                                    ?>
                                                    <option value="<?php echo $ListadoCursos[$i]['ID']; ?>" <?php echo $selectedC; ?>  >
                                                        Año:  <?php echo $ListadoCursos[$i]['ANIO']." - Division: ".$ListadoCursos[$i]['DIVISION']; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <button type="submit" class="btn btn-default" value="ElegirCurso" name="ElegirCurso"> Elegir</button>
                           <?php   if (!empty($_POST['ElegirCurso'])) {
                                    if (!empty($_POST['Curso'])) {
                                    	$CursoElegido = buscarCurso($MiConexion,$_POST['Curso']);
                                    $_SESSION['IdCursoSeleccionado'] = $_POST['Curso'];
                                    if (!empty($CursoElegido)) {
                                        $_SESSION['AnioCursoSeleccionado'] = $CursoElegido['ANIO'];
                                    	$_SESSION['DivisionCursoSeleccionado'] = $CursoElegido['DIVISION'];
                                    	echo $CursoElegido['ANIO']." - ".$CursoElegido['DIVISION']." Division";
                                    }
                                    $ListadoEstudiantes = ListarEstudiantesXCurso($MiConexion,$_POST['Curso']);
                                    $CantidadEstudiantes = count($ListadoEstudiantes);
                                    if ($CantidadEstudiantes == 0) {
                                        ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>El curso seleccionado no tiene estudiantes asignados</strong>
                </div>
              </div>
             <?php
                                    }
                             } else {
                                    ?>
                <div class="alert alert-dismissible alert-danger">
                  <strong>Debe seleccionar un curso primero</strong>
                </div>
              </div>
             <?php
                                }
                                    
                           } ?>
                                        </div>
                                        </div>
                                        <hr style="color: #888ffc"/>
            <?php       if (!empty($ListadoEstudiantes)) { ?>
                                        <font size="5" face="Verdana, Arial, Helvetica, sans-serif">Curso: <?php echo $_SESSION['AnioCursoSeleccionado']." - ".$_SESSION['DivisionCursoSeleccionado']." Division"; ?> - <?php echo ($_SESSION['AnioCursoSeleccionado']=='1ro'||$_SESSION['AnioCursoSeleccionado']=='2do'||$_SESSION['AnioCursoSeleccionado']=='3ro') ? 'Ciclo Basico' : 'Ciclo Orientado'; ?></font><br>
                                    <font size="4" face="Verdana, Arial, Helvetica, sans-serif">Cantidad de Estudiantes: <?php echo $CantidadEstudiantes; ?></font><br>
                                    <center><font size="7" face="Verdana, Arial, Helvetica, sans-serif">Estudiantes</font></center><br>
                                    <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nro Legajo</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>e-mail</th>
                  </tr>
                </thead>
                <tbody>
                    

                        <?php
                        //Cargo a la tabla el listado de los estudiantes
                            for ($i=0; $i < $CantidadEstudiantes; $i++) { ?>
                                <tr class="table-info">
                                    <td><?php echo $ListadoEstudiantes[$i]['ID']; ?></td>
                                    <td><?php echo $ListadoEstudiantes[$i]['NROLEGAJO']; ?></td>
                                    <td><?php echo $ListadoEstudiantes[$i]['APELLIDO']; ?></td>
                                    <td><?php echo $ListadoEstudiantes[$i]['NOMBRE']; ?></td>
                                    <td><?php echo $ListadoEstudiantes[$i]['DNI']; ?></td>
                                    <td><?php echo $ListadoEstudiantes[$i]['TELEFONO']; ?></td>
                                    <td><?php echo $ListadoEstudiantes[$i]['MAIL']; ?></td>
								</tr> 
                         <?php   }
                        ?>
                </tbody>
              </table>
            </div>
        <?php } ?>
                                        <hr>
                                        <br>
                                        <button type="submit" class="btn btn-default" value="Emitir" name="Emitir" style="background-color: #7b16b6; color: white;" formaction="funciones/emitirPDFListadoEstudiantesXCurso.php" 
                                            onClick="return confirm ('Seguro que desea emitir el listado?');"
                                       >Emitir</button>
                                        <button type="submit" class="btn btn-default" value="Cancelar" name="Cancelar" style="background-color: #fb0000; color: white;" onclick="return confirm ('Seguro que desea cancelar?')">Cancelar</button>
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
