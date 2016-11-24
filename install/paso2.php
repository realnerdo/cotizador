<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Instalaci&oacute;n del Sistema</title>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
</head>
<body>
<div id="container">
   <div id="content">
    <div id="content_top"></div>
    <div id="content_middle">
	
<h1 style="background: url('view/image/installation.png') no-repeat;">Paso 2 - Pre-Instalaci&oacute;n</h1>
<div style="width: 100%; display: inline-block;">
  <div style="float: left; width: 569px;">
    <?php if (isset($error_warning)) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="paso3.php" method="post"  id="form">
      <p>1. Favor configura los requerimientos PHP listados abajo.</p>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 15px;">
        <table width="100%">
          <tr>
            <th width="35%" align="left"><b>Configuraci&oacute;n PHP</b></th>
            <th width="25%" align="left"><b>Configuraci&oacute;n Actual</b></th>
            <th width="25%" align="left"><b>Configuraci&oacute;n Requerida</b></th>
            <th width="15%" align="center"><b>Estado</b></th>
          </tr>
          <tr>
            <td>PHP Version:</td>
            <td><?php echo phpversion(); ?></td>
            <td>5.3+</td>
            <td align="center"><?php echo (phpversion() >= '5.3') ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Bad" />'; ?></td>
          </tr>
          <tr>
            <td>Register Globals:</td>
            <td><?php echo (ini_get('register_globals')) ? 'On' : 'Off'; ?></td>
            <td>Off</td>
            <td align="center"><?php echo (!ini_get('register_globals')) ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Bad" />'; ?></td>
          </tr>
          <tr>
            <td>Magic Quotes GPC:</td>
            <td><?php echo (ini_get('magic_quotes_gpc')) ? 'On' : 'Off'; ?></td>
            <td>Off</td>
            <td align="center"><?php echo (!ini_get('magic_quotes_gpc')) ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Bad" />'; ?></td>
          </tr>
          <tr>
            <td>File Uploads:</td>
            <td><?php echo (ini_get('file_uploads')) ? 'On' : 'Off'; ?></td>
            <td>On</td>
            <td align="center"><?php echo (ini_get('file_uploads')) ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Bad" />'; ?></td>
          </tr>
          <tr>
            <td>Session Auto Start:</td>
            <td><?php echo (ini_get('session_auto_start')) ? 'On' : 'Off'; ?></td>
            <td>Off</td>
            <td align="center"><?php echo (!ini_get('session_auto_start')) ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Bad" />'; ?></td>
          </tr>
		  <tr>
            <td>short_open_tag:</td>
            <td><?php echo (ini_get('short_open_tag')) ? 'On' : 'Off'; ?></td>
            <td>On</td>
            <td align="center"><?php echo (ini_get('short_open_tag')) ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Bad" />'; ?></td>
          </tr>
        </table>
      </div>
      <p>2. Favor comprueba que las extensiones listadas abajo esten instaladas.</p>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 15px;">
        <table width="100%">
          <tr>
            <th width="35%" align="left"><b>Extension</b></th>
            <th width="25%" align="left"><b>Configuraci&oacute;n Actual</b></th>
            <th width="25%" align="left"><b>Configuraci&oacute;n Requerida</b></th>
            <th width="15%" align="center"><b>Estado</b></th>
          </tr>
          <tr>
            <td>MySQLI:</td>
            <td><?php echo extension_loaded('mysqli') ? 'On' : 'Off'; ?></td>
            <td>On</td>
            <td align="center"><?php echo extension_loaded('mysql') ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Bad" />'; ?></td>
          </tr>

          <tr>
            <td>ZIP:</td>
            <td><?php echo extension_loaded('zlib') ? 'On' : 'Off'; ?></td>
            <td>On</td>
            <td align="center"><?php echo extension_loaded('zlib') ? '<img src="view/image/good.png" alt="Good" />' : '<img src="view/image/bad.png" alt="Bad" />'; ?></td>
          </tr>
		 
        </table>
      </div>
       <div style="text-align: right;"><input type="submit" value="Continuar" style="padding:4px; cursor:pointer;"/><span class="button_right"></span></a></div>
    </form>
  </div>
  <div style="float: right; width: 205px; height: 400px; padding: 10px; color: #663300; border: 1px solid #FFE0CC; background: #FFF5CC;">
    <ul>
      <li>Licencia</li>
      <li><b>Pre-Instalaci&oacute;n</b></li>
      <li>Configuraci&oacute;n</li>
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