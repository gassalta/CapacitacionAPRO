<?php
$idCurso = $_REQUEST['Cx'];
$etapa = $_REQUEST['Cn'];
require_once 'buscarCurso.php';
$CursoElegido = array();
$CursoElegido = buscarCurso($MiConexion,$idCurso);
$AnioCurso = $CursoElegido['ANIO'];
$DivisionCurso = $CursoElegido['DIVISION'];
//CREAR UN ARCHIVO ZIP
$zip = new ZipArchive();
// Ruta absoluta

$nombreArchivoZip = __DIR__ . "/Libretas.zip";

if (!$zip->open($nombreArchivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
    exit("Error abriendo ZIP en $nombreArchivoZip");
}
//AGREGAR LOS PDF AL COMPRIMIDO
$zip->addGlob("*.pdf");
//CERRAR EL ARCHIVO
$resultado = $zip->close();
if (!$resultado) {
    exit("Error creando archivo");
}
//DAR UN NOMBRE AL ARCHIVO QUE SE DESCARGA Y ENVIARLO POR EL NAVEGADOR
$nombreAmigable = "Libretas.zip";
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=$nombreAmigable");
// Leer el contenido binario del zip y enviarlo
readfile($nombreArchivoZip);

// Si quieres puedes eliminarlo después:
unlink($nombreArchivoZip);
?>