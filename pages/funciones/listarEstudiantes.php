<?php
function Listar_Estudiantes($vConexion) {
    $Listado=array();

    	$SQL = "SELECT DISTINCT E.id, E.nroLegajo, E.nroLibroMatriz, E.nroFolio, E.apellido, E.nombre, E.dni, E.telefono, E.mail, N.nacion AS nacionalidad, E.escDeProcedencia, C.Anio AS anio, C.Division AS division, E.lugarNacim, E.fechaNacim, E.domicilio, B.nombre AS barrio, E.fechaPreinscripcion
        FROM estudiantes E, nacionalidades N, cursos C, barrios B
        WHERE E.nacionalidad = N.id AND E.curso = C.Id AND E.barrio = B.id AND C.Anio!='Sin Curso'
        ORDER BY E.curso, E.apellido, E.nombre";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['NROLEGAJO'] = $data['nroLegajo'];
            $Listado[$i]['NROLIBROMATRIZ'] = $data['nroLibroMatriz'];
            $Listado[$i]['NROFOLIO'] = $data['nroFolio'];
            $Listado[$i]['APELLIDO'] = $data['apellido'];
            $Listado[$i]['NOMBRE'] = $data['nombre'];
            $Listado[$i]['DNI'] = $data['dni'];
            $Listado[$i]['TELEFONO'] = $data['telefono'];
            $Listado[$i]['MAIL'] = $data['mail'];
            $Listado[$i]['NACIONALIDAD'] = $data['nacionalidad'];
            $Listado[$i]['ESCDEPROCEDENCIA'] = $data['escDeProcedencia'];
            $Listado[$i]['ANIO'] = $data['anio'];
            $Listado[$i]['DIVISION'] = $data['division'];
            $Listado[$i]['LUGARNACIM'] = $data['lugarNacim'];
            $Listado[$i]['FECHANACIM'] = $data['fechaNacim'];
            $Listado[$i]['DOMICILIO'] = $data['domicilio'];
            $Listado[$i]['BARRIO'] = $data['barrio'];
            $Listado[$i]['FECHAPREINSCRIPCION'] = $data['fechaPreinscripcion'];

            $i++;
    }
$Cant = count($Listado);
    $OtroListado = array();
    $OtroListado = ListarEstudiantesSinCurso($vConexion);
    $CantOtro = count($OtroListado);
    $Ultimo = $i;
    if ($CantOtro!= 0) {
        for ($i=0; $i < $CantOtro; $i++) { 
            $esta = 0;
            for ($j=0; $j < $Cant; $j++) { 
                if ($OtroListado[$i]['ID'] == $Listado[$j]['ID']) {
                    $esta = 1;
                }
            }
            if ($esta == 0) {
                $Listado[$Ultimo] = $OtroListado[$i];
                $Ultimo++;
                $Cant = count($Listado);
            }
        }
    }
    return $Listado;

}

function ListarEstudiantesSinCurso($vConexion){
    $Listado = array();
    $SQL = "SELECT E.id, E.nroLegajo, E.nroLibroMatriz, E.nroFolio, E.apellido, E.nombre, E.dni, E.telefono, E.mail, N.nacion AS nacionalidad, E.escDeProcedencia, E.lugarNacim, E.fechaNacim, E.domicilio, B.nombre AS barrio, E.fechaPreinscripcion
        FROM estudiantes E, nacionalidades N, barrios B
        WHERE E.nacionalidad = N.id AND E.barrio = B.id
        ORDER BY E.apellido, E.nombre";
    $rs = mysqli_query($vConexion, $SQL);
    $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['NROLEGAJO'] = $data['nroLegajo'];
            $Listado[$i]['NROLIBROMATRIZ'] = $data['nroLibroMatriz'];
            $Listado[$i]['NROFOLIO'] = $data['nroFolio'];
            $Listado[$i]['APELLIDO'] = $data['apellido'];
            $Listado[$i]['NOMBRE'] = $data['nombre'];
            $Listado[$i]['DNI'] = $data['dni'];
            $Listado[$i]['TELEFONO'] = $data['telefono'];
            $Listado[$i]['MAIL'] = $data['mail'];
            $Listado[$i]['NACIONALIDAD'] = $data['nacionalidad'];
            $Listado[$i]['ESCDEPROCEDENCIA'] = $data['escDeProcedencia'];
            $Listado[$i]['ANIO'] = "";
            $Listado[$i]['DIVISION'] = "";
            $Listado[$i]['LUGARNACIM'] = $data['lugarNacim'];
            $Listado[$i]['FECHANACIM'] = $data['fechaNacim'];
            $Listado[$i]['DOMICILIO'] = $data['domicilio'];
            $Listado[$i]['BARRIO'] = $data['barrio'];
            $Listado[$i]['FECHAPREINSCRIPCION'] = $data['fechaPreinscripcion'];

            $i++;
    }
    return $Listado;
}

function ListarEstudiantesXCurso($vConexion,$curso) {
    $Listado=array();

        $SQL = "SELECT id, nroLegajo, apellido, nombre, dni, telefono, mail
        FROM estudiantes
        WHERE curso = '$curso'
        ORDER BY apellido, nombre";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['NROLEGAJO'] = $data['nroLegajo'];
            $Listado[$i]['APELLIDO'] = $data['apellido'];
            $Listado[$i]['NOMBRE'] = $data['nombre'];
            $Listado[$i]['DNI'] = $data['dni'];
            $Listado[$i]['TELEFONO'] = $data['telefono'];
            $Listado[$i]['MAIL'] = $data['mail'];
            
            $i++;
    }
    return $Listado;

}
function ListarEstudiantesConNota($vConexion,$espCurric){
    require_once 'buscarEspacioCurricular.php';
    $EspCurr = array();
    $EspCurr = buscarEspacCurricSimple($vConexion,$espCurric);
    $Listado = array();
    $curso = $EspCurr['CURSO'];
    $SQL = "SELECT E.id, E.apellido, E.nombre, CFEC.calificacion AS calificacion
            FROM estudiantes E, calificacionfinalxespcurr CFEC
            WHERE E.id=CFEC.estudiante AND E.curso ='$curso' AND CFEC.espacioCurricular='$espCurric'
            ORDER BY E.apellido, E.nombre";
    $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['APELLIDO'] = $data['apellido'];
            $Listado[$i]['NOMBRE'] = $data['nombre'];
            $Listado[$i]['CALIFICACION'] = $data['calificacion'];
            
            $i++;
    }
    return $Listado;
}
function ListarEstudiatesReprobados($vConexion,$espCurric) {
    require_once 'buscarEspacioCurricular.php';
    $EspCurr = array();
    $EspCurr = buscarEspacCurricSimple($vConexion,$espCurric);
    $Listado = array();
    $curso = $EspCurr['CURSO'];
    $SQL = "SELECT E.id, E.apellido, E.nombre, CFEC.calificacion AS calificacion
            FROM estudiantes E, calificacionfinalxespcurr CFEC
            WHERE E.id=CFEC.estudiante AND E.curso ='$curso' AND CFEC.espacioCurricular='$espCurric' AND CFEC.calificacion < 7
            ORDER BY E.apellido, E.nombre";
    $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['APELLIDO'] = $data['apellido'];
            $Listado[$i]['NOMBRE'] = $data['nombre'];
            $Listado[$i]['CALIFICACION'] = $data['calificacion'];
            
            $i++;
    }
    return $Listado;
}

function ListarEstudiatesReprobadosTotales($vConexion,$espCurric) {
    $Listado = array();
    $SQL = "SELECT E.id, E.apellido, E.nombre, CFEC.calificacion AS calificacion
            FROM estudiantes E, calificacionfinalxespcurr CFEC
            WHERE E.id=CFEC.estudiante AND CFEC.espacioCurricular='$espCurric' AND CFEC.calificacion < 7
            ORDER BY E.apellido, E.nombre";
    $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['APELLIDO'] = $data['apellido'];
            $Listado[$i]['NOMBRE'] = $data['nombre'];
            $Listado[$i]['CALIFICACION'] = $data['calificacion'];
            
            $i++;
    }
    return $Listado;
}
?>