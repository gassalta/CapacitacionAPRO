<?php

function ConexionBD($Host = 'localhost' ,  $User = 'root',  $Password = '', $BaseDeDatos='calificaproa' ) {

    $linkConexion = mysqli_connect($Host, $User, $Password, $BaseDeDatos);
    if ($linkConexion!=false) {
        return $linkConexion;
    }else {
        die ('No se pudo establecer la conexión.');
}
}

?>