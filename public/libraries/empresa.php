	<?php
				$target_dir="img/logo/";
				$target_file = $target_dir . basename($_FILES["imagefile"]["name"]);
				$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
				$imageFileZise=$_FILES["imagefile"]["size"];
				/* Inicio Validacion*/
				// Allow certain file formats
				if(($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) and $imageFileZise>0) {
				$errors[]= "<p>Lo sentimos, sólo se permiten archivos JPG , JPEG, PNG y GIF.</p>";
				} else if ($imageFileZise > 1048576) {//1048576 byte=1MB
				$errors[]= "<p>Lo sentimos, pero el archivo es demasiado grande. Selecciona logo de menos de 1MB</p>";
				}else if (empty($_POST["nombre"])){
				$errors[] = "Nombre o Razón Social vacío";
				} else if (empty($_POST["propietario"])){
				$errors[] = "Propietario vacío";
				} else if (empty($_POST["direccion"])){
				$errors[] = "Dirección vacío";
				} else if (empty($_POST["telefono"])){
				$errors[] = "Teléfono vacío";
				} else if (empty($_POST["giro"])){
				$errors[] = "Actividad económica vacío";
				}  else if (empty($_POST["nrc"])){
				$errors[] = "NRC vacío";
				}  elseif (!empty($_POST['nombre'])
				&& !empty($_POST['propietario'])
				&& !empty($_POST['nrc'])
				)
			{
				// escaping, additionally removing everything that could be (html/javascript-) code
				$nombre = mysqli_real_escape_string($con,(strip_tags($_POST['nombre'], ENT_QUOTES)));
				$propietario = mysqli_real_escape_string($con,(strip_tags($_POST['propietario'], ENT_QUOTES)));
				$direccion = mysqli_real_escape_string($con,(strip_tags($_POST['direccion'], ENT_QUOTES)));
				$telefono = mysqli_real_escape_string($con,(strip_tags($_POST['telefono'], ENT_QUOTES)));
				$email = mysqli_real_escape_string($con,(strip_tags($_POST['email'], ENT_QUOTES)));
				$giro = mysqli_real_escape_string($con,(strip_tags($_POST['giro'], ENT_QUOTES)));
				$id_moneda = intval($_POST['moneda']);
				$nrc = mysqli_real_escape_string($con,(strip_tags($_POST['nrc'], ENT_QUOTES)));
				
				$iva=intval($_POST["iva"]);
				/* Fin Validacion*/
				if ($imageFileZise>0){
				move_uploaded_file($_FILES["imagefile"]["tmp_name"], $target_file);
				$logo_update=" ,logo_url='$target_file' ";
				}	else { $logo_update="";}
                    $sql = "UPDATE empresa SET nombre='".$nombre."', propietario='".$propietario."',  direccion='".$direccion."', telefono='".$telefono."', email='".$email."', giro='".$giro."',  iva='".$iva."', nrc='".$nrc."', iva='".$iva."', id_moneda='".$id_moneda."' $logo_update
                            WHERE id_empresa='1';";
                    $query_new_insert = mysqli_query($con,$sql);

                   
                    if ($query_new_insert) {
                        $messages[] = "Datos de empresa actualizados satisfactoriamente.";
                    } else {
                        $errors[] = "Lo sentimos, actualización falló. Intente nuevamente. ".mysqli_error($con);
                    }
					
				
				
				
				}
				
					
				
				
				
		
	?>