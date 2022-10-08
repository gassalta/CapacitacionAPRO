<?php
// Creamos un instancia de la clase ZipArchive
 $zip = new ZipArchive();
// Creamos y abrimos un archivo zip temporal
 $zip->open("Libretas.zip",ZipArchive::CREATE);
 // Añadimos un archivo en la raid del zip.
 $zip->addGlob("*.pdf");
 // Una vez añadido los archivos deseados cerramos el zip.
 $zip->close();
 // Creamos las cabezeras que forzaran la descarga del archivo como archivo zip.
 header("Content-type: application/octet-stream");
 header("Content-disposition: attachment; filename=Libretas.zip");
 // leemos el archivo creado
 readfile('Libretas.zip');
 // Por último eliminamos el archivo temporal creado
 unlink('Libretas.zip');//Destruye el archivo temporal
?>
/*//CREAR UN ARCHIVO ZIP
$zip = new ZipArchive();
$nombreArchivoZip = __DIR__ . "/Libretas.zip";
if (!$zip->open($nombreArchivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
    exit("Error abriendo ZIP en $nombreArchivoZip");
}
$zip->addGlob("*.pdf");
$resultado=$zip->close();
if ($resultado) {
    echo "Archivo creado";
} else {
    echo "Error creando archivo";
}
$nombreAmigable = "Libretas.zip";
header('Content-Type: application/octet-stream');
header("Content-Transfer-Encoding: Binary");
header("Content-disposition: attachment; filename=$nombreAmigable");
// Leer el contenido binario del zip y enviarlo
readfile($nombreArchivoZip);

// Si quieres puedes eliminarlo después:
unlink($nombreArchivoZip); */
?>