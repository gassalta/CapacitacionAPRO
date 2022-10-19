<div class="row">
    <div class="col-lg-1"></div>
    <div class="col-lg-1">
        <box-icon class="border border-secondary border-3 rounded-circle" name="bell" type="solid" size="lg" color="#3498DB" animation="tada"></box-icon>
    </div>
    <div class="col-lg-8">
        <h1 class="page-header" align="center">
            <font color="#3498DB"><strong>Novedades PRoA</strong></font>
        </h1>
    </div>
    <div class="col-lg-1">
        <box-icon class="border border-secondary border-3 rounded-circle" name="bell" type="solid" size="lg" color="#3498DB" animation="tada"></box-icon>
    </div>
    <div class="col-lg-1"></div>
</div> <!-- end novedades proa -->
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <p>Se informa que el receso de invierno será desde el día 11 al 22 de Julio, retomando las actividades escolares el día lunes 25 de Julio. Mientras dure el receso, la Escuela permanecerá cerrada y es posible que se realicen tareas de mantenimiento en los servidores.</p>
                <p>
                    <center>Solicitamos por favor no cargar nueva información en el sistema durante esos días.
                </p><br>
                <p><b>
                        <center>¡Muchas gracias y Felices Vacaciones!</center>
                </p><br>
                <?php /*
 if($_SESSION ['Categoria']=='Coordinador/a'){
?>						
	<div class="row" align="center">
		<div class="col-lg-6">
			<div id="cargaTorta"></div></div>
		<div class="col-lg-6">
			<div id="cargaLineal"></div></div>
	</div>
					</div>	
 <?php }	*/ ?>
            </div>
        </div>
    </div>
</div> <!--  end aviso -->
<div class="row" align="center">
    <div class="col-lg-10">
        <div class="tile">
            <h2 class="tile-title">
                <font color="#85C1E9">
                    <center><b>Informes de Alumnos, Docentes y Materias</b>
                </font>
                </center>
            </h2>
        </div>
    </div>
</div> <!-- /.row titulo --><br>
<div class="row">

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading"></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form role="form" method="post">
                            <?php
                            //Si cancela vuelvo a administrarEspaciosCursos
                            if (!empty($_POST['Cancelar'])) {
                                header('Location: index.php');
                            }

                            ?>
                    </div>
                    <!--Cierra col errores-->
                </div>
                <!--Cierra Row errores--><br>
                <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-2"><label>Informes</label></DIV>
                    <div class="col-lg-6">
                        <select class="form-control" name="informe" id="informe">
                            <option value="#" selected>Seleccione un informe</option>
                            <option value="aaaMateriaMasCursadaPDF.php">Consulta de las materias más cursadas</option>
                            <option value="home/promediomateriabaja.php">Consultar promedio de materia más baja.</option>
                            <option value="" disabled>Curso con materias de notas más alto.</option>
                            <option value="" disabled>Curso con materias de notas más bajo.</option>
                            <option value="" disabled>Mejor promedio de alumno de la escuela.</option>
                            <option value="home/mejorpromediodealumnos.php">Mejores 3 promedios de alumnos del último curso.</option>
                            <option value="?p=buscarAlumnoxDni">Buscar alumno por dni.</option>
                            <option value="?p=buscardocentexdni">Buscar docente por dni.</option>

                        </select>
                    </DIV>
                </div><!-- row busqueda--><br><br>
                <script type="text/javascript">
                    /* ABRIR EN NUEVA VENTANA SEGUN LA SELECION DE LA LISTA */
                    var selectInforme = document.querySelector('#informe');
                    selectInforme.addEventListener('change', (event) => {
                        var resultado = `${event.target.value}`;

                        if(resultado !='#'){
                            window.open(resultado) // location = valor;
                        }
                        console.log('SELECCION REALIZADA: ',event.target.value)
                    });
                </script>

                <div class="row" align="center">

                    <div class="col-lg-12">
                        <!--     <button type="submit" class="btn btn-primary" value="EmitiInformes" name="EmitirInformes">
                                        <box-icon type='solid' name='user-detail' size="sm" color="white" animation="tada"></box-icon> Emitir Informes de Contenidos y Aprendizajes de los Estudiantes del Curso
                                    </button> -->
                    </div>
                </div>


            </div> <!-- /.panel-body -->
        </div> <!-- /.panel primary -->
    </div> <!-- /.col informes de alumnos -->
</div> <!-- /.row principal -->