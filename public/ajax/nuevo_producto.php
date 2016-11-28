<?php
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['codigo'])) {
           $errors[] = "Código vacío";
        } else if (empty($_POST['nombre'])){
			$errors[] = "Nombre del producto vacío";
		} else if ($_POST['estado']==""){
			$errors[] = "Selecciona el estado del producto";
		} else if (empty($_POST['precio'])){
			$errors[] = "Precio de venta vacío";
		} else if (
			!empty($_POST['codigo']) &&
			!empty($_POST['nombre']) &&
			$_POST['estado']!="" &&
			!empty($_POST['precio'])
		){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$codigo=mysqli_real_escape_string($con,(strip_tags($_POST["codigo"],ENT_QUOTES)));
		// $modelo=mysqli_real_escape_string($con,(strip_tags($_POST["modelo"],ENT_QUOTES)));
		$nombre=mysqli_real_escape_string($con,($_POST["nombre"]));
		$fabricante=intval($_POST['fabricante']);
		$estado=intval($_POST['estado']);
		$precio_venta=floatval($_POST['precio']);
		$date_added=date("Y-m-d H:i:s");

		// Foto de producto
		$target_dir="../img/productos/";
		$imageFileType = pathinfo($_FILES["foto_0"]["name"],PATHINFO_EXTENSION);
		$target_file = $target_dir . time() . "." . $imageFileType ;
		$imageFileZise=$_FILES["foto_0"]["size"];

		if ($imageFileZise>0){
			move_uploaded_file($_FILES["foto_0"]["tmp_name"], $target_file);
			$foto_insert=" ,foto_producto";
			$target_file = str_replace('../', '', $target_file);
			$foto_value=" , '$target_file'";
		} else { $foto_insert=""; $foto_value=""; }

		$sql="INSERT INTO products (codigo_producto, nombre_producto, id_marca_producto, status_producto, date_added, precio_producto". $foto_insert .") VALUES ('$codigo','$nombre','$fabricante','$estado','$date_added','$precio_venta' ".$foto_value.")";
		$query_new_insert = mysqli_query($con,$sql);
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
