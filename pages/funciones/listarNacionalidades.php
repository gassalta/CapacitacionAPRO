<?php //Terminar de adaptar
function Listar_Nacionalidades($vConexion) {
    $Listado=array();

    	$SQL = "SELECT id, nacion
        FROM nacionalidades
        ORDER BY nacion";

     $rs = mysqli_query($vConexion, $SQL);
        
     $i=0;
    while ($data = mysqli_fetch_array($rs)) {
            $Listado[$i]['ID'] = $data['id'];
            $Listado[$i]['NACION'] = $data['nacion'];

            $i++;
    }
    
    return $Listado;

}
?>