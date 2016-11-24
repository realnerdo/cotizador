<?php
	/*Inicia validacion del lado del servidor*/
	if (empty($_POST['mod_id'])) {
           $errors[] = "ID vacío";
        } else if (empty($_POST['nombre'])){
			$errors[] = "Nombre de la moneda vacío";
		} else if (empty($_POST['simbolo'])){
			$errors[] = "Símbolo de la moneda vacío";
		} else if (empty($_POST['precision'])){
			$errors[] = "Precisión de la moneda vacío";
		} else if (empty($_POST['millar'])){
			$errors[] = "Selecciona separador de miles";
		} else if (empty($_POST['decimal'])){
			$errors[] = "Selecciona separador de decimales";
		} else if (empty($_POST['codigo'])){
			$errors[] = "Código de la moneda vacío";
		}   else if (
			!empty($_POST['nombre']) &&
			!empty($_POST['simbolo']) &&
			!empty($_POST['precision']) &&
			!empty($_POST['millar']) &&
			!empty($_POST['decimal']) &&
			!empty($_POST['codigo']) &&
			!empty($_POST['mod_id']) 
			
		){
		/* Connect To Database*/
		require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
		require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
		// escaping, additionally removing everything that could be (html/javascript-) code
		$nombre=mysqli_real_escape_string($con,(strip_tags($_POST["nombre"],ENT_QUOTES)));
		$simbolo=mysqli_real_escape_string($con,(strip_tags($_POST["simbolo"],ENT_QUOTES)));
		$precision=intval($_POST['precision']);
		$millar=mysqli_real_escape_string($con,(strip_tags($_POST["millar"],ENT_QUOTES)));
		$decimal=mysqli_real_escape_string($con,(strip_tags($_POST["decimal"],ENT_QUOTES)));
		$codigo=mysqli_real_escape_string($con,(strip_tags($_POST["codigo"],ENT_QUOTES)));
		$id_moneda=intval($_POST['mod_id']);
		$sql="UPDATE currencies SET  name='".$nombre."', symbol='".$simbolo."', decimals='".$precision."', thousand_separator	='".$millar."', decimal_separator='".$decimal."', code='".$codigo."'  WHERE id='".$id_moneda."'";
		$query_update = mysqli_query($con,$sql);
			if ($query_update){
				$messages[] = "Moneda ha sido actualizada satisfactoriamente.";
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