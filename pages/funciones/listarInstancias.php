<?php 
function Listar_Instancias($vConexion) {
    $Listado=array();

    	$SQL = "SELECT id, denominacion
        FROM instancias
        ORDER BY denominacion";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['DENOMINACION'] = $data['denominacion'];

            $i++;
    }
    
    return $Listado;

}
?>