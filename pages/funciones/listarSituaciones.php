<?php //Terminar de adaptar
function Listar_Situaciones($vConexion) {
    $Listado=array();

    	$SQL = "SELECT id, denominacion
        FROM situaciones
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