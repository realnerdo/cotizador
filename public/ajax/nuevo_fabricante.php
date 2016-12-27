<?php
	/*Inicia validacion del lado del servidor*/
		if (empty($_POST['nombre'])){
			$errors[] = "Nombre del fabricante vacío";
		} else if ($_POST['estado']==""){
			$errors[] = "Selecciona el estado del producto";
		}  else if (
			!empty($_POST['nombre']) &&
			$_POST['estado']!=""
		){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$nombre=mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));
		$estado=intval($_POST['estado']);
		$date_added=date("Y-m-d H:i:s");

		function slugify($text)
		{
		  // replace non letter or digits by -
		  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

		  // transliterate
		  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		  // remove unwanted characters
		  $text = preg_replace('~[^-\w]+~', '', $text);

		  // trim
		  $text = trim($text, '-');

		  // remove duplicate -
		  $text = preg_replace('~-+~', '-', $text);

		  // lowercase
		  $text = strtolower($text);

		  if (empty($text)) {
		    return 'n-a';
		  }

		  return $text;
		}

		$slug = slugify($nombre);

		$sql="INSERT INTO ctlg_categorias (idCatPadre, titulo, contenido, slug, tipo, opciones, orden, fechaPublicacion, mostrarEn, keywords, estatus) VALUES (0,'$nombre','','$slug','brand','N;',0,'$date_added','','','$estado')";
		$query_new_insert = mysqli_query($con,$sql);
			if ($query_new_insert){
				$messages[] = "Fabricante ha sido ingresado satisfactoriamente.";
			} else{
				$errors []= "Lo siento algo ha salido mal intenta nuevamente.".mysqli_error($con);
			}
		} else {
			$errors []= "Error desconocido.";
		}

		if (isset($errors)){

			?>
			<div class="alert alert-danger" role="alert">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>Error!</strong>
					<?php
						foreach ($errors as $error) {
								echo $error;
							}
						?>
			</div>
			<?php
			}
			if (isset($messages)){

				?>
				<div class="alert alert-success" role="alert">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>¡Bien hecho!</strong>
						<?php
							foreach ($messages as $message) {
									echo $message;
								}
							?>
				</div>
				<?php
			}

?>
