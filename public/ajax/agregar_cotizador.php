<?php
	/*-------------------------
	Autor: Obed Alvarado
	Web: obedalvarado.pw
	Mail: info@obedalvarado.pw
	---------------------------*/
session_start();
$session_id= session_id();
if (isset($_POST['id'])){$id=$_POST['id'];}
if (isset($_POST['cantidad'])){$cantidad=intval($_POST['cantidad']);}
if (isset($_POST['precio_venta'])){floatval($precio_venta=$_POST['precio_venta']);}
if (isset($_POST['descuento'])){$descuento=intval($_POST['descuento']);}

	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

if (!empty($id) and !empty($cantidad) and !empty($precio_venta))
{
$insert_tmp=mysqli_query($con, "INSERT INTO tmp_estimate (id_producto,cantidad_tmp,descuento_tmp,precio_tmp,session_id) VALUES ('$id','$cantidad','$descuento','$precio_venta','$session_id')");
}
if (isset($_GET['id']))//codigo elimina un elemento de la DB
{
$id_tmp=intval($_GET['id']);
$delete=mysqli_query($con, "DELETE FROM tmp_estimate WHERE id_tmp='".$id_tmp."'");
}
if (isset($_POST['cantidad_item'])){
	$id_tmp_item=intval($_POST['id_tmp']);
	$cantidad_item=intval($_POST['cantidad_item']);
	$precio_item=floatval($_POST['precio_item']);
	$descuento_item=intval($_POST['descuento_item']);

	$update=mysqli_query($con,"update tmp_estimate set cantidad_tmp='$cantidad_item', descuento_tmp='$descuento_item', precio_tmp='$precio_item' where id_tmp='$id_tmp_item' ");
}
/*Datos de la moneda*/
	$id_moneda=intval($_REQUEST['moneda']);
	$sql_currencies=mysqli_query($con,"SELECT * FROM currencies where id='$id_moneda'");
	$rw_currency=mysqli_fetch_array($sql_currencies);
	$moneda=$rw_currency['symbol'];
	$decimals=$rw_currency['decimals'];
	$dec_point=$rw_currency['decimal_separator'];
	$thousands_sep=$rw_currency['thousand_separator'];
/*Fin datos moneda*/
/*Datos de la empresa*/
	$sql_empresa=mysqli_query($con,"SELECT * FROM empresa where id_empresa=1");
	$rw_empresa=mysqli_fetch_array($sql_empresa);
	$iva=$rw_empresa["iva"];
/*Fin datos empresa*/
?>
<table class="table">
<tr>
	<th>CODIGO</th>
	<th>FOTO</th>
	<th class='text-center'>CANT.</th>
	<th>DESCRIPCION</th>
	<th class='text-right'>PRECIO UNIT.</th>
	<th class='text-right'>DESC.</th>
	<th class='text-right'>PRECIO TOTAL</th>
	<th></th>
</tr>
<?php
	$sumador_total=0;
	$sumador_descuento=0;
	$sql=mysqli_query($con, "select * from products, tmp_estimate where products.id_producto=tmp_estimate.id_producto and tmp_estimate.session_id='".$session_id."'");
	while ($row=mysqli_fetch_array($sql))
	{
	$id_tmp=$row["id_tmp"];
	$codigo_producto=$row['codigo_producto'];
	$foto_producto=$row['foto_producto'];
	$cantidad=$row['cantidad_tmp'];
	$porcentaje=$row['descuento_tmp'] / 100;
	$nombre_producto=$row['nombre_producto'];
	$id_marca_producto=$row['id_marca_producto'];
	if (!empty($id_marca_producto))
	{
	$sql_marca=mysqli_query($con, "select nombre_marca from manufacturers where id_marca='$id_marca_producto'");
	$rw_marca=mysqli_fetch_array($sql_marca);
	$nombre_marca=$rw_marca['nombre_marca'];
	$marca_producto=" ".strtoupper($nombre_marca);
	}
	else {$marca_producto='';}
	$precio_venta=$row['precio_tmp'];
	$precio_unitario=number_format($precio_venta,$decimals,'.','');
	$precio_total=$precio_unitario*$cantidad;
	$precio_total=number_format($precio_total,$decimals,'.','');
	$total_descuento=$precio_total*$porcentaje;//Total descuento
	$total_descuento=number_format($total_descuento,$decimals,'.','');
	$sumador_descuento+=$total_descuento;
	$sumador_total+=$precio_total;//Sumador

	if (isset($id_tmp_item) and $id_tmp_item==$id_tmp)
	{
		$clase="info";
	} else {
		$clase="";
	}
		?>
		<tr class="<?php echo $clase;?>">
			<td><?php echo $codigo_producto;?></td>
			<td><img src="<?php echo $foto_producto;?>" width="100px" /></td>
			<td class='text-center'><?php echo $cantidad;?></td>
			<td><?php echo $nombre_producto.$marca_producto;?></td>
			<td><span class="pull-right"><?php echo number_format($precio_unitario,$decimals,$dec_point,$thousands_sep);?></span></td>
			<td><span class="pull-right"><?php echo number_format($total_descuento,$decimals,$dec_point,$thousands_sep);?></span></td>
			<td><span class="pull-right"><?php echo number_format($precio_total,$decimals,$dec_point,$thousands_sep);?></span></td>
			<td class='text-right'>
				<a href="#editModalItem" data-toggle="modal" data-codigo="<?php echo $codigo_producto;?>" data-cantidad="<?php echo $cantidad;?>" data-descripcion="<?php echo strip_tags($nombre_producto);?>" data-descuento="<?php echo number_format($row['descuento_tmp'],2,'.',''); ?>" data-precio="<?php echo number_format($precio_unitario,2,'.','');?>" data-id="<?php echo $id_tmp?>"><i class="fa fa-edit"></i></a>
				<a href="#" onclick="eliminar('<?php echo $id_tmp ?>')"><i class="glyphicon glyphicon-trash"></i></a>
			</td>
		</tr>
		<?php
	}
	$total_parcial=number_format($sumador_total,$decimals,'.','');
	$sumador_descuento=number_format($sumador_descuento,$decimals,'.','');
	$total_neto=$total_parcial-$sumador_descuento;
	$total_neto=number_format($total_neto,$decimals,'.','');
	$total_iva=($total_neto*$iva) / 100;
	$total_iva=number_format($total_iva,$decimals,'.','');
	$total_cotizacion=$total_neto+$total_iva;
?>
<tr>
	<td colspan=5><span class="pull-right">PARCIAL <?php echo $moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_parcial,$decimals,$dec_point,$thousands_sep);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right">DESCUENTO <?php echo $moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($sumador_descuento,$decimals,$dec_point,$thousands_sep);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right">NETO <?php echo $moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_neto,$decimals,$dec_point,$thousands_sep);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right">IVA <?php echo "$iva% $moneda";?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_iva,$decimals,$dec_point,$thousands_sep);?></span></td>
	<td></td>
</tr>
<tr>
	<td colspan=5><span class="pull-right">TOTAL <?php echo $moneda;?></span></td>
	<td><span class="pull-right"><?php echo number_format($total_cotizacion,$decimals,$dec_point,$thousands_sep);?></span></td>
	<td></td>
</tr>
</table>
