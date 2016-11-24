<?php
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['mod_id'])) {
           $errors[] = "ID vacío";
        }else if (empty($_POST['mod_cliente'])) {
           $errors[] = "Nombre del cliente está vacío.";
        }  else if (
			!empty($_POST['mod_id']) &&
			!empty($_POST['mod_cliente']) 
		){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$cliente=mysqli_real_escape_string($con,(strip_tags($_POST["mod_cliente"],ENT_QUOTES)));
		$nombre_comercial=mysqli_real_escape_string($con,(strip_tags($_POST["mod_nombre_comercial"],ENT_QUOTES)));
		$registro=mysqli_real_escape_string($con,(strip_tags($_POST["mod_registro"],ENT_QUOTES)));
		$giro=mysqli_real_escape_string($con,(strip_tags($_POST["mod_giro"],ENT_QUOTES)));
		$direccion=mysqli_real_escape_string($con,(strip_tags($_POST["mod_direccion"],ENT_QUOTES)));
		$email=mysqli_real_escape_string($con,(strip_tags($_POST["mod_email"],ENT_QUOTES)));
		$fijo=mysqli_real_escape_string($con,(strip_tags($_POST["mod_fijo"],ENT_QUOTES)));
		
		$id_cliente=$_POST['mod_id'];
		$sql="UPDATE  clients SET nombre_cliente='$cliente', nombre_comercial='$nombre_comercial', numero_identificacion='$registro',giro='$giro',direccion='$direccion',  email='$email', fijo='$fijo' WHERE id='$id_cliente'";
		$query_update = mysqli_query($con,$sql);
			if ($query_update){
				$messages[] = "Cliente ha sido actualizado satisfactoriamente.";
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