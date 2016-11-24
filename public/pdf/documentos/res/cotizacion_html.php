<style type="text/css">
<!--
div.zone
{
    border: solid 0.5mm red;
    border-radius: 2mm;
    padding: 1mm;
    background-color: #FFF;
    color: #440000;
}
div.zone_over
{
    width: 30mm;
    height: 20mm;
    
}
.bordeado
{
	border: solid 0.5mm #999;
	border-radius: 1mm;
	padding: 0mm;
	font-size:12px;
}
.table {
  border-spacing: 0;
  border-collapse: collapse;
}
.table-bordered td, .table-bordered th {
  padding: 2px;
  text-align: left;
  vertical-align: top;
}
.table-bordered {
  border: 1px solid #999;
  border-collapse: separate;
  
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}
.left{
	border-left: 1px solid #999;
	
}
.top{
	border-top: 1px solid #999;
}
.bottom{
	border-bottom: 1px solid #999;
}
p
{
	margin: 0px;
}
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
-->
</style>
<page backtop="15mm" backbottom="15mm" backleft="15mm" backright="15mm" style="font-size: 13px; font-family: helvetica" >
    <?php 
		include("page_footer.php");
	?>    
       <table style="width:100%">
        <tr style="vertical-align: top">
            <td style="width:25%">
               <?php 
				if (!empty($logo_url)){
					?>
					<img src="../../<?php echo $logo_url;?>" style="width: 100%;">
					<?php 
				}	
				?>
                
                
            </td>
			<td style="width:55%;text-align:center">
				<span style="font-size:13pt"><strong><?php echo $nombre_empresa;?></strong></span><br>
				<span style="color:#555"><?php echo $propietario;?></span><br>
				<span style="color:#555"><?php echo $giro;?></span><br>
				<span style="color:#555"><?php echo $direccion;?></span><br>
				<span style="color:#555">Tel.: <?php echo $telefono;?></span>
			</td>
            <td style="width:20%">
               
                <div class="zone zone_over" style="text-align: center; vertical-align: top; ">
				COTIZACION<br><br>
				<p style="color:red;font-size:14pt;font-weight:bold">Nº: <?php echo $numero_cotizacion;?></p> 
				
				</div>
               
            </td>
            
        </tr>
        
    </table>
    
	 <p style="width:100%;text-align:right;margin-right:10mm;margin-top:10px;margin-bottom:5px"><strong>Fecha:</strong> <?php echo date("d/m/Y");?></p>
  <table style="width:100%" class="table-bordered">
		<tr style="vertical-align: top">
            <td style="width:75%"><strong>Atención: </strong> <?php echo $nombre_contact; ?></td>
			<td style="width:25%;"><strong>Teléfono:</strong> <?php echo $telefono_contact; ?></td>
		</tr>
		<tr style="vertical-align: top">
            <td style="width:75%"><strong>Empresa: </strong> <?php echo $empresa ?></td>
			<td style="width:25%;"><strong>Teléfono:</strong> <?php echo $tel2; ?></td>
		</tr>
		<tr style="vertical-align: top">
            <td style="width:75%"><strong>E-mail: </strong> <?php echo $email ?></td>
			
		</tr>
		 
        
    </table>
	<p style="margin:5px">A continuación Presentamos nuestra oferta que esperamos sea de su conformidad.</p>
  
    <table class="table-bordered" style="width:100%;" cellspacing=0>
        <tr>
            <th class="bottom" style="width: 10%">CANT.</th>
            <th class="bottom left" style="width: 50%">DESCRIPCION</th>
            <th class="bottom left" style="width: 14%;text-align:right">PRECIO UNIT.</th>
			<th class="bottom left" style="width: 12%;text-align:right">DESC.</th>
            <th class="bottom left" style="width: 14%;text-align:right">TOTAL</th>
            
        </tr>
<?php
$sumador_descuento=0;
$sumador_total=0;
$sql=mysqli_query($con, "select * from products, tmp_estimate where products.id_producto=tmp_estimate.id_producto and tmp_estimate.session_id='".$session_id."'");
while ($row=mysqli_fetch_array($sql))
	{
	$id_tmp=$row["id_tmp"];
	$id_producto=$row["id_producto"];
	$codigo_producto=$row['codigo_producto'];
	$cantidad=$row['cantidad_tmp'];
	$porcentaje=$row['descuento_tmp'] / 100;
	$descuento_percent=$row['descuento_tmp'];
	$nombre_producto=$row['nombre_producto'];
	$id_marca_producto=$row['id_marca_producto'];
	if (!empty($id_marca_producto))
	{
	$sql_marca=mysqli_query($con,"select nombre_marca from manufacturers where id_marca='$id_marca_producto'");
	$rw_marca=mysqli_fetch_array($sql_marca);
	$nombre_marca=$rw_marca['nombre_marca'];
	$marca_producto=" ".strtoupper($nombre_marca);
	}
	else {$marca_producto='';}
	$precio_venta=$row['precio_tmp'];
	$precio_unitario=number_format($precio_venta,$decimals,'.','');
	$precio_total=$precio_venta*$cantidad;
	$precio_total=number_format($precio_total,$decimals,'.','');
	$total_descuento=$precio_total*$porcentaje;//Total descuento
	$total_descuento=number_format($total_descuento,$decimals,'.','');
	$sumador_descuento+=$total_descuento;
	$sumador_total+=$precio_total;//Sumador
	
	$nombre_producto=str_replace("color=\"","style=\"color:",$nombre_producto);
	$nombre_producto=str_replace("font-family","familias",$nombre_producto);
	?>
	
        <tr>
            <td class="" style="width: 10%; text-align: center"><?php echo $cantidad; ?></td>
            <td class="left" style="width: 50%; text-align: left"><?php echo $nombre_producto.$marca_producto;?></td>
            <td class="left" style="width: 14%; text-align: right"><?php echo number_format($precio_unitario,$decimals,$dec_point,$thousands_sep);?></td>
            <td class="left" style="width: 12%; text-align: right"><?php echo number_format($total_descuento,$decimals,$dec_point,$thousands_sep);?></td>
			<td class="left" style="width: 14%; text-align: right"><?php echo number_format($precio_total,$decimals,$dec_point,$thousands_sep);?></td>
            
        </tr>
    
	<?php 
	//Insert en la tabla detalle_cotizacion
	$insert_detail=mysqli_query($con, "INSERT INTO detail_estimate VALUES ('','$numero_cotizacion','$id_producto','$cantidad','$descuento_percent','$precio_unitario')");
	}
	$total_parcial=number_format($sumador_total,2,'.','');
	$sumador_descuento=number_format($sumador_descuento,2,'.','');
	$total_neto=$total_parcial-$sumador_descuento;
	$total_neto=number_format($total_neto,2,'.','');
	
	$total_iva=($total_neto*$iva) / 100;
	$total_iva=number_format($total_iva,2,'.','');
	$total_cotizacion=$total_neto+$total_iva;

?>	
	<tr style="vertical-align: top">
			<td class="top" colspan=4 style="text-align:right">
				PARCIAL <?php echo $moneda;?>
			</td>
			<td class="top left " style="text-align:right">
			<?php echo number_format($total_parcial,$decimals,$dec_point,$thousands_sep);?>
			</td>
		</tr>
		<?php if ($sumador_descuento>0){?>
		<tr style="vertical-align: top">
			<td class="" colspan=4 style="text-align:right">
				DESCUENTO <?php echo $moneda;?>
			</td>
			<td class="left " style="text-align:right">
			<?php echo number_format($sumador_descuento,$decimals,$dec_point,$thousands_sep);?>
			</td>
		</tr>
		<?php }?>
		<tr style="vertical-align: top">
			<td class="" colspan=4 style="text-align:right">
				NETO <?php echo $moneda;?>
			</td>
			<td class="left " style="text-align:right">
			<?php echo number_format($total_neto,$decimals,$dec_point,$thousands_sep);?>
			</td>
		</tr>
		<tr style="vertical-align: top">
			<td class="" colspan=4 style="text-align:right">
				IVA <?php echo $iva."% ".$moneda;?>
			</td>
			<td class="left " style="text-align:right">
			<?php echo number_format($total_iva,$decimals,$dec_point,$thousands_sep);?>
			</td>
		</tr>
		<tr style="vertical-align: top">
			<td class="" colspan=4 style="text-align:right">
				TOTAL <?php echo $moneda;?>
			</td>
			<td class="left " style="text-align:right">
			<?php echo number_format($total_cotizacion,$decimals,$dec_point,$thousands_sep);?>
			</td>
		</tr>	
	 </table>
    
	
	<?php if (!empty($notas)){?>
		<p>
			<strong>NOTA:</strong><br>
			<?php echo $notas;?>
		</p>
	<?php }?>
	
	<br>
          <table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
            <tr>
                <td style="width:50%;text-align:right">Condiciones de pago: </td>
                <td style="width:50%; ">&nbsp;<?php echo $condiciones; ?></td>
            </tr>
			<tr>
                <td style="width:50%;text-align:right">Validez de la oferta: </td>
                <td style="width:50%; ">&nbsp;<?php echo $validez; ?></td>
            </tr>
			<tr>
                <td style="width:50%;text-align:right">Tiempo de entrega: </td>
                <td style="width:50%; ">&nbsp;<?php echo $entrega; ?></td>
            </tr>
        </table>
    <br><br><br><br>
	
	
	  <table cellspacing="10" style="width: 100%; text-align: left; font-size: 11pt;">
			<tr>
                <td style="width:33%;text-align: center;border-top:solid 0px"><?php echo $full_name;?></td>
               <td style="width:33%;text-align: center;border-top:solid 0px"></td>
               <td style="width:33%;text-align: center;border-top:solid 0px"></td>
            </tr>
			 <tr>
                <td style="width:33%;text-align: center;border-top:solid 1px">Vendedor</td>
               <td style="width:33%;text-align: center;border-top:solid 1px">Cotizado</td>
               <td style="width:33%;text-align: center;border-top:solid 1px">Aceptado Cliente</td>
            </tr>
        </table>

</page>

<?php
$date=date("Y-m-d H:i:s");
$insert=mysqli_query($con,"INSERT INTO estimates (id_cotizacion, numero_cotizacion,fecha_cotizacion,condiciones,validez, entrega, status, notas, id_empleado, id_cliente, total_neto, total_iva,currency_id,id_contact) 
VALUES (NULL,'$numero_cotizacion','$date','$condiciones','$validez','$entrega','0','$notas','$user_id','$id_cliente','$total_neto','$total_iva','$id_moneda','$id_contact')");
$delete=mysqli_query($con,"DELETE FROM tmp_estimate WHERE session_id='".$session_id."'");
?>