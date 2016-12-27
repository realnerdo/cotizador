<?php 
// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("../libraries/password_compatibility_library.php");
}	

if (isset($_POST['db_host']))
{
/*Recoleccion de variables para la conexion de la base de datos*/
$db_host=$_POST['db_host'];
$db_user=$_POST['db_user'];
$db_password=$_POST['db_password'];
$db_host=$_POST['db_host'];
$db_name=$_POST['db_name'];
/*Fin de recoleccion de variables para la conexion de la base de datos*/

/*Recoleccion de datos de inicio de sesion al sistema*/
$username=$_POST['username'];
$password=$_POST['password'];
$user_password_hash = password_hash($password, PASSWORD_DEFAULT);
/*Fin de recoleccion de datos de inicio de sesion al sistema*/
if(!@$con=mysqli_connect($db_host,$db_user,$db_password, $db_name))
{
$error_warning='Error: No se pudo conectar a la base de datos por favor aseg&uacute;rese de que el servidor de base de datos, nombre de usuario y la contrase&ntilde;a es correcta!';
}
else
{
			//Creacion de archivo config.php que contiene los datos de conexion a la base de datos	
			$output  = '<?php' . "\n";
			$output .= '/*Datos de conexion a la base de datos*/' . "\n";
			$output .= 'define(\'DB_HOST\', \'' . $db_host. '\');' . "\n";
			$output .= 'define(\'DB_USER\', \'' . $db_user. '\');' . "\n";
			$output .= 'define(\'DB_PASS\', \'' . $db_password. '\');' . "\n";
			$output .= 'define(\'DB_NAME\', \'' . $db_name. '\');' . "\n \n";
			$output .= '/*Datos del usuario administrador del sistema*/' . "\n";
			$output .= 'define(\'USERNAME\', \'' . $username. '\');' . "\n";
			$output .= 'define(\'PASSWORD\', \'' . $password. '\');' . "\n";			
			$output .= '?>';
			$file = fopen('../config/db.php', 'w');//Abre el archivo y si no existe lo crea
			fwrite($file, $output);//Escritura en el archivo
			fclose($file);//Cierre del fichero
			
			/*------Volcado de los datos --------*/
			include('../config/db.php');//Datos de conexion a base de datos
			
			mysqli_query($con, "SET NAMES 'utf8'");
			mysqli_query($con, "SET CHARACTER SET utf8");
			mysqli_query($con, "DROP TABLE IF EXISTS detail_estimate, estimates, manufacturers, products, tmp_estimate, users");
			$file ='innovawebsv.sql';//Lectura de archivo
			if ($sql = file($file)) 
			{
			$query = '';
			foreach($sql as $line) 
			{
				$tsl = trim($line);
				if (($sql != '') && (substr($tsl, 0, 2) != "--") && (substr($tsl, 0, 1) != '#')) {
					$query .= $line;
  					if (preg_match('/;\s*$/', $line)) {
						$result = mysqli_query($con, $query);
  						if (!$result) {
							die(mysqli_error($con));
						}
							$query = '';
					}
				}
			}
			
			$date_added=date("Y-m-d H:i:s");
			mysqli_query($con, "SET CHARACTER SET utf8");
			mysqli_query($con, "SET @@session.sql_mode = 'MYSQL40'");
			mysqli_query($con, "TRUNCATE TABLE users");
			mysqli_query($con, "insert into users (user_name, user_password_hash, user_email, date_added) values('$username','$user_password_hash','admin@admin.com','$date_added')");
			
		echo "<script>location.replace('paso4.php');</script>";
		}		
}		

}

 ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Instalaci&oacute;n del Sistema</title>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<script language="JavaScript" type="text/javascript" src="../js/install.js"></script>
</head>
<body>
<div id="container">
  <div id="content">
    <div id="content_top"></div>
    <div id="content_middle">
	
	<h1 style="background: url('view/image/configuration.png') no-repeat;">Paso 3 - Configuraci&oacute;n</h1>
<div style="width: 100%; display: inline-block;">
  <div style="float: left; width: 569px;">
    <?php if (isset($error_warning)) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form  method="post"  id="form" name='form' onsubmit="return validar();">
      <p>1 . Por favor, introduzca sus datos de conexi&oacute;n a la base de datos.</p>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 15px;">
        <table>
          <tr>
            <td width="185"><span class="required">*</span>Servidor:</td>
            <td><input type="text" name="db_host" id="db_host" value="localhost" required/>
              <br /><td>
          </tr>
          <tr>
            <td><span class="required">*</span>Usuario:</td>
            <td><input type="text" name="db_user" id="db_user"  required/>
              <br /><td>
          </tr>
          <tr>
            <td>Contrase&ntilde;a:</td>
            <td><input type="text" name="db_password" id="db_password" /></td>
          </tr>
          <tr>
            <td><span class="required">*</span>Nombre de la Base de Datos:</td>
            <td><input type="text" name="db_name" id="db_name" required/>
              <br />
            </td>
          </tr>
        </table>
      </div>
      <p>2. Por favor, introduzca un nombre de usuario y una contrase&ntilde;a para la administraci&oacute;n.</p>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 15px;">
        <table>
          <tr>
            <td width="185"><span class="required">*</span>Nombre de Usuario:</td>
            <td><input type="text" name="username" id="username" value="admin" required/>
			</td>
          </tr>
          <tr>
            <td><span class="required">*</span>Contrase&ntilde;a:</td>
            <td><input type="text" name="password" id="password" required/>
			</td>
          </tr>

        </table>
      </div>
    <div style="text-align: right;"><input type="submit" value="Continuar" style="padding:4px; cursor:pointer;"/><span class="button_right"></span></a></div>
    </form>
  </div>
  <div style="float: right; width: 205px; height: 400px; padding: 10px; color: #663300; border: 1px solid #FFE0CC; background: #FFF5CC;">
    <ul>
      <li>Licencia</li>
      <li>Pre-Instalaci&oacute;n</li>
      <li><b>Configuraci&oacute;n</b></li>
      <li>Finalizado</li>
    </ul>
  </div>
</div>
   </div>
    <div id="content_bottom"></div>
  </div>
  </div>
</body>
</html>

	