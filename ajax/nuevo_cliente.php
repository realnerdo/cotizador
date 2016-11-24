<?php
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['cliente'])) {
           $errors[] = "Nombre del cliente está vacío.";
        }  else if (
			!empty($_POST['cliente'])
			
		){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$cliente=mysqli_real_escape_string($con,(strip_tags($_POST["cliente"],ENT_QUOTES)));
		$nombre_comercial=mysqli_real_escape_string($con,(strip_tags($_POST["nombre_comercial"],ENT_QUOTES)));
		$registro=mysqli_real_escape_string($con,(strip_tags($_POST["registro"],ENT_QUOTES)));
		$giro=mysqli_real_escape_string($con,(strip_tags($_POST["giro"],ENT_QUOTES)));
		$direccion=mysqli_real_escape_string($con,(strip_tags($_POST["direccion"],ENT_QUOTES)));
		$email=mysqli_real_escape_string($con,(strip_tags($_POST["email"],ENT_QUOTES)));
		$fijo=mysqli_real_escape_string($con,(strip_tags($_POST["fijo"],ENT_QUOTES)));
		
		$date_added=date("Y-m-d H:i:s");
		$sql="INSERT INTO clients (nombre_cliente, nombre_comercial, numero_identificacion,giro,direccion, email, fijo, fecha_agregado) VALUES ('$cliente','$nombre_comercial','$registro','$giro','$direccion','$email','$fijo','$date_added')";
		$query_new_insert = mysqli_query($con,$sql);
			if ($query_new_insert){
				$messages[] = "Cliente ha sido ingresado satisfactoriamente.";
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