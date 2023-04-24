<?php //Terminar de adaptar
function Listar_EspCurr($vConexion) {
    $Listado=array();

    	$SQL = "SELECT DISTINCT EC.Id, EC.NombreEspacCurric,CUR.Anio AS Anio,CUR.Division AS Division,A.Denominacion AS Area
        FROM espacioscurriculares EC,cursos CUR,areas A
        WHERE EC.Curso = CUR.Id AND EC.Area = A.Id
        ORDER BY EC.Curso, EC.NombreEspacCurric";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['Id'];
            $Listado[$i]['NOMBREESPACCURRIC'] = utf8_decode($data['NombreEspacCurric']);
            $Listado[$i]['ANIO'] = $data['Anio'];
            $Listado[$i]['DIVISION'] = $data['Division'];
            $Listado[$i]['AREA'] = $data['Area'];

            $i++;
    }
    
    $Cant = count($Listado);
    $OtroListado = array();
    $OtroListado = ListarEspCurrSinCurso($vConexion);
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

function ListarEspCurrSinCurso($vConexion){
    $Listado = array();
    $SQL = "SELECT EC.Id, EC.NombreEspacCurric,A.Denominacion AS Area
        FROM espacioscurriculares EC,areas A
        WHERE EC.Area = A.Id
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
?>