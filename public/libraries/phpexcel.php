<?php
function importar_productos($file){
global $con;	
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
/** Include PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/../classes/phpexcel/Classes/PHPExcel/IOFactory.php';
$objPHPExcel = PHPExcel_IOFactory::load($file);
$objWorksheet = $objPHPExcel->getActiveSheet();
$objPHPExcel->getActiveSheet()->getCell('B2')->getValue();
 $mes=1;
 $error=0;
 $sheet = $objPHPExcel->getSheet(0);
 $highestRow = $sheet->getHighestRow(); //Numero de filas que contiene la hoja de calculo
for ($i = 2; $i <=  $highestRow; $i++) {
	$columna_a="A".$i;
	$columna_b="B".$i;
	$columna_c="C".$i;
	$columna_d="D".$i;
	$columna_e="E".$i;
	$codigo=  $objPHPExcel->getActiveSheet()->getCell($columna_a)->getValue();
	$codigo_producto=mysqli_real_escape_string($con,(strip_tags($codigo, ENT_QUOTES)));
	
	$modelo=  $objPHPExcel->getActiveSheet()->getCell($columna_b)->getValue();
	$modelo_producto=mysqli_real_escape_string($con,(strip_tags($modelo, ENT_QUOTES)));
	
	$nombre=  $objPHPExcel->getActiveSheet()->getCell($columna_c)->getValue();
	$nombre_producto=mysqli_real_escape_string($con,(strip_tags($nombre, ENT_QUOTES)));
	
	$fabricante=  $objPHPExcel->getActiveSheet()->getCell($columna_d)->getValue();
	$fabricante_producto=mysqli_real_escape_string($con,(strip_tags($fabricante, ENT_QUOTES)));
	$nombre_fabricante=ucfirst(strtolower($fabricante_producto));
	
	$precio=  $objPHPExcel->getActiveSheet()->getCell($columna_e)->getValue();
	$precio_producto=floatval($precio);
	
	$status_producto=1;//Activo por defecto
	$date_added=date("Y-m-d H:i:s");//fecha y hora EN
	
	if (!empty($nombre_fabricante)){
		$id_fabricante=id_fabricante($nombre_fabricante);
	} else{
		$id_fabricante=0;
	}
	
	
	$count=mysqli_query($con,"select * from  products where codigo_producto='$codigo_producto' ");
	$counter=mysqli_num_rows($count);
	if ($counter==0){
		$sql="INSERT INTO products  (id_producto, codigo_producto, nombre_producto, modelo_producto, id_marca_producto, status_producto, date_added, precio_producto) VALUES 
		(NULL, '$codigo_producto','$nombre_producto','$modelo_producto','$id_fabricante','$status_producto','$date_added','$precio_producto');";
		if ($query=mysqli_query($con,$sql)){
			$error+=0;
		} else {
			$error+=1;
		}
	} else {
		$sql="UPDATE products SET nombre_producto='$nombre_producto', modelo_producto='$modelo_producto', precio_producto='$precio_producto', id_marca_producto='$id_fabricante' where codigo_producto='$codigo_producto'";
		if ($query=mysqli_query($con,$sql)){
			$error+=0;
		} else {
			$error+=1;
		}
	}
	$mes++;
	
}
return $error;
}

function id_fabricante($nombre){
	global $con;
	
	$query=mysqli_query($con,"select * from  manufacturers where nombre_marca='$nombre'");
	$count=mysqli_num_rows($query);
	if ($count==0){
		$date_added=date("Y-m-d H:i:s");//fecha y hora EN
		$insert=mysqli_query($con,"INSERT INTO manufacturers (id_marca, nombre_marca, status_fabricante, date_added) VALUES (NULL, '$nombre', '1', '$date_added');");
		$sql2=mysqli_query($con,"select * from  manufacturers where nombre_marca='$nombre'");
		$row=mysqli_fetch_array($sql2);
		$id=$row['id_marca'];
	} else {
		$rw=mysqli_fetch_array($query);
		$id=$rw['id_marca'];
	}
		return $id;
}

?>