<?php 
date_default_timezone_set("America/Argentina/Buenos_Aires");
function Listar_Evaluaciones($vConexion,$espCurric) {
    $fechaAct = date('Y');
    $Listado=array();

    	$SQL = "SELECT id, fecha, espacioCurricular
        FROM evaluaciones 
        WHERE espacioCurricular='$espCurric' AND YEAR(fecha)='$fechaAct'
        ORDER BY id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['FECHA'] = $data['fecha'];
            $Listado[$i]['ESPACIOCURRICULAR'] = $data['espacioCurricular'];
            $i++;
    }
    return $Listado;
}
?>