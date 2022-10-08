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

?>
<!DOCTYPE html>
<html lang="en">

<head>
<?php
    require_once 'encabezado.php';
	
?>
   <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>
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
           <h1 class="page-header"><font color="#85C1E9">Contenido Nuevo</font></h1>
        </div>
     </div>      
            
    <?php
    //Listo los espacios curriculares
        require_once 'funciones/listarEspaciosCurricularesXDocente.php';
        $ListadoEC=array();
        $ListadoEC = ListarEspCurrXDocente($MiConexion,$_SESSION['Id']);
        $CantidadEspCurr = count($ListadoEC);
        $EC=$_REQUEST['Cx'];
    ?>
    <div class="row">
        <div class="col-md-12">
         <div class="tile">
        
          </div>
        </div>
        <div class="clearfix"></div>
        
      </div>
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-primary">
                       <div class="panel-heading">
                            Ingrese los datos del contenido
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <form role="form" method="post">
                                        <?php 
                                            //Si confirma verifico si ya existe
                                            if (!empty($_POST['Confirmar'])) {
                                                require_once 'funciones/buscarContenido.php';
                                                $mensaje = '';
                                                if (empty($_POST['Denominacion'])) {
                                                    $mensaje = "Tiene que completar el contenido - ";
                                                }
                                                $mensaje = $mensaje.contenidoExiste($MiConexion,$_POST['Denominacion'],$_POST['EspaCurri']);
                                                
                                                //Si está todo bien creo contenido nuevo en base de datos, sino, muestro mensaje
                                                if ($mensaje == '') {
                                                    require_once 'funciones/guardarContenido.php';
                                                        if (contenidoNuevo($MiConexion,$_POST['EspaCurri'],$_POST['Denominacion'])) {
                                                            ?>
                                                            <div class="bs-component">
                <div class="alert alert-dismissible alert-success">
                  <strong>Contenido nuevo guardado!</strong>
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

                                            ?>
                                        <div class="form-group">
                                            <label>Espacio Curricular</label>
                                            <select class="form-control" name="EspaCurri" id="EspaCurri" readonly>
                                               <!-- <option value=""></option>
                                                <?php /*
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
                                                <?php } */?> -->

                                                <?php 
                          $selected='';
                          for ($i=0 ; $i < $CantidadEspCurr ; $i++) 
                            {
                              //if (!empty($_POST['EspaCurri']) && $_POST['EspaCurri'] ==  $ListadoEC[$i]['ID']) 
                                //{
                                // $selected = 'selected';}
                              //else 
                                //{
                                 //$selected='';
                               // }
                            if ($_SESSION['Categoria']!=='Coordinador/a')
                                {       
                                 if ($EC==$ListadoEC[$i]['NOMBREESPACCURRIC'])
                                    { //echo $ListadoEC[$i]['NOMBREESPACCURRIC'];
                                    echo '<option value="'.$ListadoEC[$i]["ID"].'">';
                                    echo $selected; 
                                    echo $ListadoEC[$i]['NOMBREESPACCURRIC']; 
                                    echo"</option>";
                                    }
                                }
                            else 
                                    { //echo $ListadoEC[$i]['NOMBREESPACCURRIC'];
                                    echo '<option value="'.$ListadoEC[$i]["ID"].'">';
                                    echo $selected; 
                                    echo $ListadoEC[$i]['NOMBREESPACCURRIC']; 
                                    echo"</option>";
                  $EsDocente=1;
                                    }
                            } 
                        
?>
                                            </select>
                                        </div><hr>
                                        <div class="form-group">
                                            <label>Contenido</label>
                                            <input class="form-control" placeholder="Ingrese el nombre" name="Denominacion" value="<?php echo !empty($_POST['Denominacion']) ? $_POST['Denominacion'] : ''; ?>">
                                        </div><hr>
                                        
									   <div class="row">
									   <div class="col-lg-1"></div>
									   <div class="col-lg-3">
											<button type="submit" class="btn btn-default" value="Confirmar" name="Confirmar" style="background-color: #337ab7; color: white;"onClick="return confirm ('Seguro que desea guardar el nuevo contenido?');"><box-icon  name="check-double" type="solid" size="sm" color="white" animation="tada-hover"></box-icon>Confirmar</button></div>
										<div class="col-lg-2"></div>
									
										<div class="col-lg-3"><button class="btn btn-danger" type="submit" name="Cancelar" formaction="administrarContenidosYAprendizajes.php?Cx=<?php echo $EC; ?>"><box-icon  name="x" type="solid" size="sm" color="white" animation="tada-hover"></box-icon> Cancelar</button></div></div>
                                                
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
