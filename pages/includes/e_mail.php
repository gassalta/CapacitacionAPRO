<?php
function clave_mail($Mail,$codigo)
{
// Varios destinatarios
$para=$Mail; // atención a la coma
//$para = 'tizibradaschia@gmail.com';
$codigo=$codigo;
// título
$título = 'Recuperación de contraseña';
//$codigo=rand(1000,9999);
// mensaje
$mensaje = '
<html>

<body>
  <div style="text-alingn:center;background-color:#63b4cf">
  <h1><font color="white"><center>CalificacionesPRoA</h1>
  <center>
 <img class="center-block" src="C:\wamp\www\CalificacionesPROA\pages\images\Logo CalificacionesPRoA SF.png"  width="300" height="300"/>

 
  
  <h2>Su codigo para restablecer es: '.$codigo.' </h2>
  
  <a href="http://localhost/CalificacionesPROA/pages/login.php">Ingrese aqui para loguearse</a>
  <p><small><text-alingn:right>Si usted no solicito el mensaje, omitalo</small></p>
  </div>
</body>
</html>
';

// Para enviar un correo HTML, debe establecerse la cabecera Content-type
$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

/* Cabeceras adicionales
$cabeceras .= 'To: Tizi <tizibradaschia@gmail.com>, Martin<martin_garcia_21@hotmail.com>' . "\r\n";
$cabeceras .= 'From: Recordatorio <cumples@example.com>' . "\r\n";
$cabeceras .= 'Cc: birthdayarchive@example.com' . "\r\n";
$cabeceras .= 'Bcc: birthdaycheck@example.com' . "\r\n";
*/
 //Enviarlo
$enviado=false;
if(mail($para, $título, $mensaje, $cabeceras))
   {
	$enviado=true;
   }
}
function cliente_mail($Mail,$DescripcionCierre,$Nombre_Personal,$Nombre_Cliente,$CodReclamo)
{
$para=$Mail;
$DescripcionCierre=$DescripcionCierre;
$Encargado=$Nombre_Personal;
$Cliente=$Nombre_Cliente;
$Cod_Reclamo=$CodReclamo;
$título = 'Información de su reclamo';

$mensaje = '
<html>

<body>
  <div style="text-alingn:center;background-color:#5b1889">
  <h1><font color="white"><center>LIBRO DE QUEJAS<hr></h1>
  <center>
  <img src="http://localhost/LDQ_NuevaInterfaz/imagenes/LDQ.png"  width="100" height="100"></img>
  
 
  <h2>Informaci&oacuten sobre el Reclamo N&deg '.$Cod_Reclamo.' </h2>
  </center>
  Estimado/a '.$Nombre_Cliente.':
  <p><b>Conclusi&oacuten final:</b> '.$DescripcionCierre.'</b></p>
  
  
  <br><br><p>Su Reclamo fue resuelto por:'.$Encargado.'</p>
  
  <a href="http://localhost/LDQ_NuevaInterfaz/IngresoUsuario.html">Ingrese aqui para verificar la informaci&oacuten de su reclamo</a>
  <p><small><text-alingn:right>Si usted no es el interesado,por favor omitalo</small></p>
  </div>
</body>
</html>
';

$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$enviado=false;
if(mail($para, $título, $mensaje, $cabeceras))
{
    $enviado=true;;
}
}
?>