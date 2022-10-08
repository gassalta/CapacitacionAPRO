
<?php
//Configuro la zona horaria local
date_default_timezone_set("America/Argentina/Buenos_Aires");
//Verifico que haya una sesión abierta
session_start();
if (empty($_SESSION['Nombre'])) {
  header('Location: pages/login.php');
  exit;
} else {
  header('Location: pages/index.php');
  exit;
}

?>
