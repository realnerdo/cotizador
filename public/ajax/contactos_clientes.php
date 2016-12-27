<?php	
	/*-------------------------
	Autor: Obed Alvarado
	Web: obedalvarado.pw
	Mail: info@obedalvarado.pw
	---------------------------*/
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }
	if (!file_exists ('../config/db.php')){
		header("location: install/paso1.php");
		exit;
	}	
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	$id_cliente = (isset($_REQUEST['id_cliente'])&& $_REQUEST['id_cliente'] !=NULL)?$_REQUEST['id_cliente']:'';
	if ($id_cliente!=""){
		$id_cliente=intval($id_cliente);
		$sql=mysqli_query($con,"select * from contacts where id_client='".$id_cliente."'");
		?>
			<option value="">Selecciona</option>
		<?php
		while ($row=mysqli_fetch_array($sql)){
			?>
			<option value="<?php echo $row['id_contact'];?>" data-telefono="<?php echo $row['telefono_contact'];?>" data-email="<?php echo $row['email_contact'];?>"><?php echo $row['nombre_contact'];?></option>
			<?php
		}
	}
?>	
