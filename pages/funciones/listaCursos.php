<?php //Terminar de adaptar
function ListarCursos($vConexion) {
    $Listado=array();

    	$SQL = "SELECT Id, Anio, Division
        FROM cursos 
        WHERE Anio!='Sin Curso'
        ORDER BY Id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['Id'];
            $Listado[$i]['ANIO'] = $data['Anio'];
            $Listado[$i]['DIVISION'] = $data['Division'];

            $i++;
    }


    return $Listado;

}
function ListarCursosComp($vConexion) {
    $Listado=array();

        $SQL = "SELECT Id, Anio, Division
        FROM cursos 
        ORDER BY Id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['Id'];
            $Listado[$i]['ANIO'] = $data['Anio'];
            $Listado[$i]['DIVISION'] = $data['Division'];

            $i++;
    }


    return $Listado;

}

?>