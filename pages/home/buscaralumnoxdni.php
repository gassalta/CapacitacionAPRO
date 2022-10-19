<div class="row">

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading"></div>
            <div class="panel-body">
                <!--Cierra Row errores--><br>
                <div class="row">
                    <form name="buscarxdnialumno" method="POST" action="">
                        <div class="col-lg-2"></div>
                        <div class="col-lg-2"><label>Buscar Alumno por Dni:</label></div>
                        <div class="col-lg-6">

                            <input type="number" class="form-control" name="dni" id="dni" placeholder="Ingrese dni sin puntos...">
                            <button class="btn btn-primary" type="send">Buscar</button>
                        </div>
                    </form>
                </div><!-- row busqueda--><br><br>

                <div class="row" align="center">

                    <div class="col-lg-12">
                        <?php
                        require_once 'funciones/conexion.php';
                        $MiConexion = ConexionBD();
                        // Recibo la info de busqueda
                        $dni = isset($_POST['dni']) ? $_POST['dni'] : '';

                        if ($dni != '') {
                            $SQL = "SELECT e.id,e.nroLegajo,CONCAT(e.apellido,', ',e.nombre) nombres,e.dni FROM estudiantes e WHERE e.dni = '{$dni}';";
                            $rs = mysqli_query($MiConexion, $SQL);
                            if ($rs->num_rows > 0) {
                                while ($row = $rs->fetch_assoc()) {
                                    echo '<div class="table-responsive">
                                    <table class="table table-striped table-bordered bg-info">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th>Id</th>
                                                <th>Nro Legajo</th>
                                                <th>Apellido y Nombre</th>
                                                <th>Dni</th>
                                                <th># Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>'.$row["id"].'</td>
                                                <td>'.$row["nroLegajo"].'</td>
                                                <td>'.$row["nombres"].'</td>
                                                <td>'.$row["dni"].'</td>
                                                <td><a class="btn btn-primary btn-sm" href="buscarUnEstudiante.php?Cx='.$row["id"].'">Ver alumno</a></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>';
                                }
                            }else{
                                echo '<h3>Sin resultados de dni '.$dni.'</h3>';
                            }
                        }else{
                            echo '<h3>Usted ingreso valores vacios</h3>';
                        }
                        ?>
                    </div>
                </div>


            </div> <!-- /.panel-body -->
        </div> <!-- /.panel primary -->
    </div> <!-- /.col informes de alumnos -->
</div> <!-- /.row principal -->