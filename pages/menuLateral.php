<?php
//Conecto a la base de datos
require_once 'funciones/conexion.php';
$MiConexion = ConexionBD();
//listo los espacios curriculares que dicta el docente
require_once 'funciones/listarEspaciosCurricularesXDocente.php';
$EspsCurrs = ListarEspCurrXDocente($MiConexion, $_SESSION['Id']);
$CantidadEspCurr = count($EspsCurrs);
?>
<script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>

<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li>
                <a href="index.php">
                    <box-icon class="border border-secondary border-3 rounded-circle" name="home" size="lg" color="#3498DB" border='circle' animation="spin-hover"></box-icon> Panel Principal
                </a>
            </li>

            <?php
            //De acuerdo a la categoría del usuario activo, le muestro las opciones de lo que puede acceder el mismo
            if ($_SESSION['Categoria'] == 'Coordinador/a' || $_SESSION['Categoria'] == 'Secretario/a') { ?>
                <li>
                    <a href="administrarDocentes.php">
                        <box-icon class="border border-secondary border-3 rounded-circle" name="group" size="lg" color="#3498DB" border='circle' animation="spin-hover"></box-icon> Docentes
                    </a>
                </li>
            <?php  }
            ?>
            <?php
            if ($_SESSION['Categoria'] != 'Docente') { ?>
                <li>
                    <a href="administrarTutores.php">
                        <box-icon class="border border-secondary border-3 rounded-circle" name="radar" size="lg" color="#3498DB" border='circle' animation="spin-hover"></box-icon> Tutores
                    </a>
                </li>
                <li>
                    <a href="administrarEstudiantes.php">
                        <box-icon class="border border-secondary border-3 rounded-circle" name="glasses" size="lg" color="#3498DB" border='circle' animation="spin-hover"></box-icon> Estudiantes
                    </a>
                </li>
            <?php }
            ?>
            <?php
            if ($_SESSION['Categoria'] == 'Preceptor/a') { ?>
                <li>
                    <a href="administrarAsistencias.php">
                        <box-icon class="border border-secondary border-3 rounded-circle" name="calendar" size="lg" color="#3498DB" border='circle' animation="spin-hover"></box-icon> Asistencias <box-icon class="border border-secondary border-3 rounded-circle" name="chevrons-down" size="md" color="#3498DB" animation="tada-hover"></box-icon>
                    </a>
                    <ul class="nav nav-second-level">
                        <li><?php echo '<a href="RegAsistDiaria.php">Asistencia Diaria <box-icon  name="calendar-edit" size="sm"  color="#3498DB"  animation="tada-hover"></box-icon></a>'; ?>
                        </li>
                        <li><i class='bx  bx-right-arrow' color="blue"></i><?php echo '<a href="EmitirInformeAsistencia.php"> Informe de Asistencia  <box-icon  name="download" size="sm" color="#3498DB" animation="fade-down-hover"></box-icon></a>'; ?>
                        </li>

                    </ul>
                </li>
            <?php }
            if ($_SESSION['Categoria'] == 'Coordinador/a') { ?>
                <li>
                    <a href="EmitirInformeAsistencia.php">
                        <box-icon class="border border-secondary border-3 rounded-circle" name="list-check" size="lg" color="#3498DB" border='circle' animation="spin-hover"></box-icon> Asistencias
                    </a>
                </li>

            <?php }
            if ($_SESSION['Categoria'] != 'Docente') { ?>
                <li>
                    <a href="administrarCursos.php">
                        <box-icon class="border border-secondary border-3 rounded-circle" name="folder-open" size="lg" color="#3498DB" border='circle' animation="spin-hover"></box-icon> Cursos
                    </a>
                </li>
            <?php }
            ?>

            <?php
            if ($_SESSION['Categoria'] == 'Coordinador/a' || $_SESSION['Categoria'] == 'Secretario/a') { ?>
                <li>
                    <a href="administrarEspaciosCurriculares.php">
                        <box-icon class="border border-secondary border-3 rounded-circle" name="shopping-bag" size="lg" color="#3498DB" border='circle' animation="spin-hover"></box-icon> Espacios Curriculares
                    </a>
                </li>
            <?php }
            ?>

            <?php
            for ($i = 0; $i < $CantidadEspCurr; $i++) { ?>
                <li>
                    <a href="#"><i class="glyphicon glyphicon-pushpin fa-fw"></i> <?php echo $EspsCurrs[$i]['NOMBREESPACCURRIC'];  ?><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li><?php echo '<a href="administrarContenidosYAprendizajes.php?Cx=' . $EspsCurrs[$i]['NOMBREESPACCURRIC'] . '">Contenidos y Aprendizajes</a>'; ?>
                        </li>
                        <li>
                            <?php echo '<a href="administrarEvaluaciones.php?Cx=' . $EspsCurrs[$i]['NOMBREESPACCURRIC'] . '">Evaluaciones</a>'; ?>

                        </li>
                        <li>
                            <?php echo '<a href="administrarNotasFinales.php?Cx=' . $EspsCurrs[$i]['NOMBREESPACCURRIC'] . '">Notas Finales</a>'; ?>

                        </li>
                    </ul>
                </li>
            <?php }
            ?>

            <?php
            if ($_SESSION['Categoria'] == 'Coordinador/a') { ?>
                <li>
                    <a href="EmitirListadoAprendizajesXEspCurr.php">
                        <box-icon class="border border-secondary border-3 rounded-circle" name="grid" size="lg" color="#3498DB" border='circle' animation="spin-hover"></box-icon> Contenidos y Aprendizajes
                    </a>
                </li>
                <li>
                    <a href="administrarEvaluaciones.php?Cx=">
                        <box-icon class="border border-secondary border-3 rounded-circle" name="layer" size="lg" color="#3498DB" border='circle' animation="spin-hover"></box-icon> Evaluaciones
                    </a>
                </li>
                <li>
                    <a href="administrarNotasFinales.php?Cx=">
                        <box-icon class="border border-secondary border-3 rounded-circle" name="list-ul" size="lg" color="#3498DB" border='circle' animation="spin-hover"></box-icon> Notas Finales
                    </a>
                </li>
            <?php }
            ?>

            <?php if ($_SESSION['Categoria'] != 'Docente') { ?>
                <li>
                    <a href="#">
                        <box-icon class="border border-secondary border-3 rounded-circle" name="paste" size="lg" color="#3498DB" border='circle' animation="spin-hover"></box-icon> Libretas e Informes <box-icon class="border border-secondary border-3 rounded-circle" name="chevrons-down" size="md" color="#3498DB" animation="tada-hover"></box-icon>
                    </a>
                    <ul class="nav nav-second-level">

                        <?php

                        if ($_SESSION['Categoria'] == 'Coordinador/a') { ?>
                            <li>
                                <a href="libretasCalificaciones.php">Libretas de Calificaciones</a>
                            </li>
                            <li>
                                <a href="#">Gestion Docente</a>
                            </li>
                            <li>
                                <a href="#">Gestion Estudiante <box-icon class="border border-secondary border-3 rounded-circle" name="chevrons-down" size="md" color="#3498DB" animation="tada-hover"></box-icon></a>

                                <ul class="nav nav-second-level">
                                    <li>
                                        <a href="estudiantesNotas.php?tipo=aprobado">Estudiantes aprobados</a>
                                    </li>
                                    <li>
                                        <a href="estudiantesNotas.php?tipo=reprobado">Estudiantes reprobados</a>
                                    </li>
                                </ul>
                            </li>

                        <?php }
                        if ($_SESSION['Categoria'] == 'Secretario/a') { ?>
                            <li>
                                <a href="racs.php">RAC</a>
                            </li>
                        <?php }
                        if ($_SESSION['Categoria'] == 'Coordinador/a' || $_SESSION['Categoria'] == 'Preceptor/a') { ?>
                            <li>
                                <a href="informesContenidosYAprendizajes.php">Informes de Contenidos y Aprendizajes</a>
                            </li>
                        <?php }
                        ?>
                        <li>
                            <a href="#">Graficos Estadisticos<box-icon class="border border-secondary border-3 rounded-circle" name="chevrons-down" size="md" color="#3498DB" animation="tada-hover"></box-icon> </a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="administrarEstadisticas.php">Trayectorias Escolares</a>
                                </li>
                                <li>
                                    <a href="estudiantesEnRiesgo.php">Estudiantes en Riesgo</a>
                                </li>
                                <li>
                                    <a href="estadoEstudiantesXArea.php">Estado de Estudiantes por Area</a>
                                </li>
                        </li>

                    </ul>
                </li>
            <?php } ?>

        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->
</nav>