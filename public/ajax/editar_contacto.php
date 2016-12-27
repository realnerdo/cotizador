<?php
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['id_contact'])) {
           $errors[] = "ID del contacto está vacío.";
        } else if (empty($_POST['nombre_contact'])) {
           $errors[] = "Nombre del contacto está vacío.";
        } else if (empty($_POST['telefono_contact'])) {
           $errors[] = "Teléfono del contacto está vacío.";
        }  else if (
			!empty($_POST['id_contact']) &&
			!empty($_POST['nombre_contact']) &&
			!empty($_POST['telefono_contact'])
			
		){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$nombre_contact=mysqli_real_escape_string($con,(strip_tags($_POST["nombre_contact"],ENT_QUOTES)));
		$telefono_contact=mysqli_real_escape_string($con,(strip_tags($_POST["telefono_contact"],ENT_QUOTES)));
		$email_contact=mysqli_real_escape_string($con,(strip_tags($_POST["email_contact"],ENT_QUOTES)));
		$id_contact=intval($_POST['id_contact']);
		
		
		$sql="UPDATE contacts SET nombre_contact='$nombre_contact', telefono_contact='$telefono_contact', email_contact='$email_contact'	 WHERE id_contact='".$id_contact."'";
		$query_new_insert = mysqli_query($con,$sql);
			if ($query_new_insert){
				$messages[] = "Contacto ha sido actualizado satisfactoriamente.";
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