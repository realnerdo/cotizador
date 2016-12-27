<?php
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['codigo'])) {
           $errors[] = "Código vacío";
        } else if (empty($_POST['nombre'])){
			$errors[] = "Nombre del producto vacío";
		} else if (empty($_POST['precio'])){
			$errors[] = "Precio de venta vacío";
		} else if (
			!empty($_POST['codigo']) &&
			!empty($_POST['nombre']) &&
			!empty($_POST['precio'])
		){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$codigo=mysqli_real_escape_string($con,(strip_tags($_POST["codigo"],ENT_QUOTES)));
		// $modelo=mysqli_real_escape_string($con,(strip_tags($_POST["modelo"],ENT_QUOTES)));
		$nombre=mysqli_real_escape_string($con,($_POST["nombre"]));
		$marca=intval($_POST['marca']);
		$precio_venta=floatval($_POST['precio']);
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

		$sql="INSERT INTO ctlg_entradas (titulo, sku, precio, idUser, tipo, slug, opciones, fechaPublicacion, fechaModificacion, estatus) VALUES ('$nombre','$codigo','$precio_venta',3,'producto','$slug','N;', '$date_added', '$date_added', 1)";
		$query_new_insert = mysqli_query($con,$sql);

		$id_producto = mysqli_insert_id($con);

		$sql_pivot = "INSERT INTO ctlg_cats_entradas (idCat, idEntrada) VALUES($marca, $id_producto)";
		$query_pivot_insert = mysqli_query($con,$sql_pivot);

		// Foto de producto
		if(isset($_FILES['foto_0'])){
			$target_dir="../img/productos/";
			$imageFileType = pathinfo($_FILES["foto_0"]["name"],PATHINFO_EXTENSION);
			$target_file = $target_dir . time() . "." . $imageFileType ;
			$imageFileZise=$_FILES["foto_0"]["size"];

			if ($imageFileZise>0){
				move_uploaded_file($_FILES["foto_0"]["tmp_name"], $target_file);
				$nombre_foto=$_FILES["foto_0"]["name"];
				$sql_foto = "INSERT INTO ctlg_imagenes (nomArchivo, tipo, idEntrada, estatus, url, pie, opciones, contenido, orden, titulo) VALUES ('$nombre_foto', 'featurePage', $id_producto, 1, '', '', 'N;', '', 0, '')";
				$query_foto = mysqli_query($con, $sql_foto);
			}
		}

			if ($query_new_insert){
				$messages[] = "Producto ha sido ingresado satisfactoriamente.";
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
