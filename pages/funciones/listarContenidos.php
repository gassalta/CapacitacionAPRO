<?php 
function Listar_Contenidos($vConexion,$espCurric) {
    $Listado=array();

    	$SQL = "SELECT DISTINCT id, denominacion, estado
        FROM contenidos 
        WHERE espacioCurricular='$espCurric'
        ORDER BY id";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['DENOMINACION'] = utf8_decode($data['denominacion']);
            $Listado[$i]['ESTADO'] = $data['estado'];
            $i++;
    }
    return $Listado;
}
?>