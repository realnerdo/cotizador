<?php
			require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
			require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
			require_once ("../libraries/phpexcel.php");//Funciones phpexcel
			// escaping, additionally removing everything that could be (html/javascript-) code
     		 $target_dir="";
			 $target_file = $target_dir . basename($_FILES["archivo"]["name"]);
			 $fileType = pathinfo($target_file,PATHINFO_EXTENSION);
			 $fileType=strtolower($fileType );
			 $fileZise=$_FILES["archivo"]["size"];	
			 
			 if($fileType == "xlsx" or  $fileType == "ods") 
			 {
				if (move_uploaded_file($_FILES["archivo"]["tmp_name"], $target_file)){
					$importar=importar_productos($target_file);
				
					if ($importar==0){
						$messages[]="Los datos fueron importados con éxito.";
					} else{
						$errors[] = "No se pudo realizar la importación de datos.";
					}
					unlink($target_file);//elimino el archivo
					
				}	else {
					$errors[] = "No se pudo cargar el archivo.";
				}
			 } 
			else
			{
				
				$errors[]= "<p>Lo sentimos, sólo se permiten archivos .xlsx</p>";
				
				
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