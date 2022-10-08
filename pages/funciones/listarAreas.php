<?php 
function Listar_Areas($vConexion) {
    $Listado=array();

    	$SQL = "SELECT Id, Denominacion
        FROM areas 
        ORDER BY Id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['Id'];
            $Listado[$i]['DENOMINACION'] = $data['Denominacion'];

            $i++;
    }
    return $Listado;
}
?>