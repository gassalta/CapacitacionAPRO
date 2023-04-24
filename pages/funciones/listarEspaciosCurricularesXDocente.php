<?php
function ListarEspCurrXDocente($vConexion,$id) {
    $Listado=array();

    	$SQL = "SELECT DISTINCT * FROM espacioscurriculares ec
        INNER JOIN cursos cur ON ec.Curso = cur.Id
        INNER JOIN areas a ON ec.Area = a.Id 
        WHERE ec.Docente = '{$id}'";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['Id'];
            $Listado[$i]['NOMBREESPACCURRIC'] = $data['NombreEspacCurric'];
            $Listado[$i]['ANIO'] = $data['Anio'];
            $Listado[$i]['DIVISION'] = $data['Division'];
            $Listado[$i]['AREA'] = $data['Area'];

            $i++;
    }
    $Cant = count($Listado);
    $OtroListado = array();
    $OtroListado = ListarEspCurrXDocSinCurso($vConexion,$id);
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

function ListarEspCurrXDocSinCurso($vConexion,$id){
    $Listado = array();
    $SQL = "SELECT EC.Id, EC.NombreEspacCurric,A.Denominacion AS Area
        FROM espacioscurriculares EC,areas A
        WHERE EC.Docente = '$id' AND EC.Area = A.Id
        ORDER BY EC.NombreEspacCurric";
    $rs = mysqli_query($vConexion, $SQL);
    $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['Id'];
            $Listado[$i]['NOMBREESPACCURRIC'] = $data['NombreEspacCurric'];
            $Listado[$i]['ANIO'] = "";
            $Listado[$i]['DIVISION'] = "";
            $Listado[$i]['AREA'] = $data['Area'];

            $i++;
    }
    return $Listado;
}
function ListarEspCurrXCurso($vConexion,$idCurso) {
    $Listado=array();

        $SQL = "SELECT EC.Id, EC.NombreEspacCurric,A.Denominacion AS Area
        FROM espacioscurriculares EC,areas A
        WHERE EC.Curso = '$idCurso' AND EC.Area = A.Id
        ORDER BY EC.NombreEspacCurric";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['Id'];
            $Listado[$i]['NOMBREESPACCURRIC'] = $data['NombreEspacCurric'];
            $Listado[$i]['AREA'] = $data['Area'];

            $i++;
    }
    return $Listado;
}
