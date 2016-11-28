<?php
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['mod_id'])) {
           $errors[] = "ID vacío";
        }else if (empty($_POST['mod_codigo'])) {
           $errors[] = "Código vacío";
        } else if (empty($_POST['mod_nombre'])){
			$errors[] = "Nombre del producto vacío";
		} else if ($_POST['mod_estado']==""){
			$errors[] = "Selecciona el estado del producto";
		} else if (empty($_POST['mod_precio'])){
			$errors[] = "Precio de venta vacío";
		} else if (
			!empty($_POST['mod_id']) &&
			!empty($_POST['mod_codigo']) &&
			!empty($_POST['mod_nombre']) &&
			$_POST['mod_estado']!="" &&
			!empty($_POST['mod_precio'])
		){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$codigo=mysqli_real_escape_string($con,(strip_tags($_POST["mod_codigo"],ENT_QUOTES)));
		// $modelo=mysqli_real_escape_string($con,(strip_tags($_POST["mod_modelo"],ENT_QUOTES)));
		$nombre=mysqli_real_escape_string($con,($_POST["mod_nombre"]));
		$fabricante=intval($_POST['mod_fabricante']);
		$estado=intval($_POST['mod_estado']);
		$precio_venta=floatval($_POST['mod_precio']);
		$id_producto=$_POST['mod_id'];

		// Foto de producto
		$target_dir="../img/productos/";
		$imageFileType = pathinfo($_FILES["foto_0"]["name"],PATHINFO_EXTENSION);
		$target_file = $target_dir . time() . "." . $imageFileType ;
		$imageFileZise=$_FILES["foto_0"]["size"];

		if ($imageFileZise>0){
			move_uploaded_file($_FILES["foto_0"]["tmp_name"], $target_file);
			$target_file = str_replace('../', '', $target_file);
			$foto_update=", foto_producto='$target_file'";
		} else { $foto_update=""; }

		$sql="UPDATE products SET codigo_producto='".$codigo."', nombre_producto='".$nombre."', id_marca_producto='".$fabricante."', status_producto='".$estado."', precio_producto='".$precio_venta."'" . $foto_update . " WHERE id_producto='".$id_producto."'";
		$query_update = mysqli_query($con,$sql);
			if ($query_update){
				$messages[] = "Producto ha sido actualizado satisfactoriamente.";
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
