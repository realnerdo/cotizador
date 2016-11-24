<?php
function counter($table){
	global $con;
	$sql="select count(*) as totales from $table";
	$query=mysqli_query($con, $sql);
	$row=mysqli_fetch_array($query);
	return $totales=$row['totales'];
}
function counterNew($table){
	global $con;
	$anio=date("Y");
	$mes=date("m");
	$diaF=date("d");
	$inicio="$anio-$mes-01 00:00:00";
	$fin="$anio-$mes-$diaF 23:59:59";
	if ($table=="estimates"){
		$campo="fecha_cotizacion";
	} else if ($table=="clients"){
		$campo="fecha_agregado";

	}else {
		$campo="date_added";
	}
	$sql="select count(*) as totales from $table where $campo between '$inicio' and '$fin'";
	$query=mysqli_query($con, $sql);
	$row=mysqli_fetch_array($query);
	return $totales=$row['totales'];
}

function is_valid(){
	return true;
}
// $domain=$_SERVER['SERVER_NAME'];
$domain='artificestore.mx';
$product="1";
$licenseServer = "http://obedalvarado.pw/code/api/";
$postvalue="domain=$domain&product=".urlencode($product);
$ch = curl_init();
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, $licenseServer);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postvalue);
$result= json_decode(curl_exec($ch), true);
curl_close($ch);
if($result['status'] != 200) {
            $html = "<div align='center'>
    <table width='100%' border='0' style='padding:15px; border-color:#F00; border-style:solid; background-color:#FF6C70; font-family:Tahoma, Geneva, sans-serif; font-size:22px; color:white;'>

    <tr>

        <td><b>Usted no tiene permiso para utilizar este producto. El mensaje del servidor es: <%returnmessage%> <br > P&oacute;ngase en contacto con el desarrollador de este producto al e-mail: info@obedalvarado.pw</b></td >

    </tr>

    </table>

</div>";
            $search = '<%returnmessage%>';
            $replace = $result['message'];
            $html = str_replace($search, $replace, $html);


            die( $html );

            }

		function agregar_cotizacion($id_producto,$session_id){
			global $con;
			$precio=floatval(get_precio($id_producto));
			$query=mysqli_query($con,"select * from tmp_estimate where 	id_producto='$id_producto'");
			$count=mysqli_num_rows($query);

			if ($count==0){
				$insert=mysqli_query($con,"INSERT INTO tmp_estimate (id_tmp, id_producto, cantidad_tmp, descuento_tmp, precio_tmp, session_id) VALUES
				(NULL, '$id_producto', '1', '0', '$precio', '$session_id')");
				if ($insert){
					return true;
				} else {
					return false;
				}
			} else {
				$update=mysqli_query($con,"update tmp_estimate set cantidad_tmp=cantidad_tmp+1 where id_producto='$id_producto'");
				if ($update){
					return true;
				} else {
					return false;
				}
			}
		}
		function get_precio($id_producto){
			global $con;
			$query=mysqli_query($con,"select precio_producto from products where id_producto='$id_producto'");
			$row=mysqli_fetch_array($query);
			$precio=$row['precio_producto'];

			return $precio;
		}

?>
