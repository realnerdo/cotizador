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
          <td style="width:100%; background: black; text-align: center; font-size: 40px; padding: 30px;">
              <span style="color: white;">LOGO ARTÍFICE</span>
          </td>
      </tr>
    </table>
    <!-- <p style="width:100%;text-align:right;margin-right:10mm;margin-top:10px;margin-bottom:5px"><strong>Fecha:</strong> <?php echo $fecha_cotizacion;?></p> -->


	<table style="width:100%" class="table-bordered">
		<tr style="vertical-align: top">
            <td style="width:75%"><strong>Atención: </strong> <?php echo $nombre_contact; ?></td>
			<td style="width:25%;">
				<strong>Teléfono:</strong> <?php echo $telefono_contact; ?>

			</td>
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
            <th class="bottom" style="width: 10%; background:black;color:white;padding:5px;">IMAGEN</th>
            <th class="bottom" style="width: 10%; background:black;color:white;padding:5px;">NO. ART.</th>
            <th class="bottom left" style="width: 50%; background:black;color:white;padding:5px;">DESCRIPCION</th>
            <th class="bottom left" style="width: 14%;text-align:right; background:black;color:white;padding:5px;">P. UNITARIO</th>
            <th class="bottom left" style="width: 14%;text-align:right; background:black;color:white;padding:5px;">TOTAL</th>
        </tr>

<?php
$sumador_descuento=0;
$sumador_total=0;
$sql=mysqli_query($con, "select * from products, detail_estimate where products.id_producto=detail_estimate.id_producto and detail_estimate.numero_cotizacion='".$numero_cotizacion."'");
while ($row=mysqli_fetch_array($sql))
	{

	$id_producto=$row["id_producto"];
	$foto_producto=$row["foto_producto"];
	$codigo_producto=$row['codigo_producto'];
	$cantidad=$row['cantidad'];
	$porcentaje=$row['descuento'] / 100;
	$nombre_producto=html_entity_decode($row['nombre_producto']);
	$id_marca_producto=$row['id_marca_producto'];
	if (!empty($id_marca_producto))
	{
	$sql_marca=mysqli_query($con,"select nombre_marca from manufacturers where id_marca='$id_marca_producto'");
	$rw_marca=mysqli_fetch_array($sql_marca);
	$nombre_marca=$rw_marca['nombre_marca'];
	$marca_producto=" ".strtoupper($nombre_marca);
	}
	else {$marca_producto='';}
	$precio_unitario=number_format($row['precio_venta'],$decimals,'.','');

	$precio_total=$precio_unitario*$cantidad;
	$precio_total=number_format($precio_total,$decimals,'.','');

	$total_descuento=$precio_total*$porcentaje;//Total descuento
	$total_descuento=number_format($total_descuento,$decimals,'.','');
	$sumador_descuento+=$total_descuento;
	$sumador_total+=$precio_total;//Sumador

	$nombre_producto=str_replace("color=\"","style=\"color:",$nombre_producto);
	$nombre_producto=str_replace("font-family","familias",$nombre_producto);

	?>

        <tr>
            <td class="" style="width: 10%; text-align: center; padding:5px;"><img src="../../<?php echo $foto_producto; ?>" style="width:80px;"></td>
            <td class="" style="width: 10%; text-align: center; padding:5px;"><?php echo $cantidad; ?></td>
            <td class="left" style="width: 50%; text-align: left; padding:5px;"><?php echo $nombre_producto.$marca_producto;?></td>
            <td class="left" style="width: 14%; text-align: right; padding:5px;"><?php echo number_format($precio_unitario,$decimals, $dec_point, $thousands_sep);?></td>
            <td class="left" style="width: 14%; text-align: right; padding:5px;"><?php echo number_format($precio_total,$decimals, $dec_point, $thousands_sep);?></td>

        </tr>

	<?php
	}
	$total_parcial=number_format($sumador_total,$decimals,'.','');
	$sumador_descuento=number_format($sumador_descuento,$decimals,'.','');
	$total_neto=$total_parcial-$sumador_descuento;
	$total_neto=number_format($total_neto,$decimals,'.','');
	$total_iva=number_format($total_iva,$decimals,'.','');
	$total_cotizacion=$total_neto+$total_iva;

	$iva=($total_iva/$total_neto) * 100;
	$iva=ceil($iva);

?>
		<tr style="vertical-align: top">
			<td class="top" colspan=4 style="text-align:right;padding:5px;">
				PARCIAL <?php echo $moneda;?>
			</td>
			<td class="top left " style="text-align:right;padding:5px;">
			<?php echo number_format($total_parcial,$decimals, $dec_point, $thousands_sep);?>
			</td>
		</tr>
		<?php if ($sumador_descuento>0){?>
		<tr style="vertical-align: top">
			<td class="" colspan=4 style="text-align:right;padding:5px;">
				DESCUENTO <?php echo $moneda;?>
			</td>
			<td class="left " style="text-align:right;padding:5px;">
			<?php echo number_format($sumador_descuento,$decimals, $dec_point, $thousands_sep);?>
			</td>
		</tr>
		<?php }?>
		<tr style="vertical-align: top">
			<td class="" colspan=4 style="text-align:right;padding:5px;">
				NETO <?php echo $moneda;?>
			</td>
			<td class="left " style="text-align:right;padding:5px;">
			<?php echo number_format($total_neto,$decimals, $dec_point, $thousands_sep);?>
			</td>
		</tr>
		<tr style="vertical-align: top">
			<td class="" colspan=4 style="text-align:right;padding:5px;">
				IVA <?php echo $iva."% ".$moneda;?>
			</td>
			<td class="left " style="text-align:right;padding:5px;">
			<?php echo number_format($total_iva,$decimals, $dec_point, $thousands_sep);?>
			</td>
		</tr>
		<tr style="vertical-align: top">
			<td class="" colspan=4 style="text-align:right;padding:5px;">
				TOTAL <?php echo $moneda;?>
			</td>
			<td class="left " style="text-align:right;padding:5px;">
			<?php echo number_format($total_cotizacion,$decimals, $dec_point, $thousands_sep);?>
			</td>
		</tr>
	 </table>
    <?php if (!empty($notas)){?>
        <br><br><br><br>
		<p>
			<strong>NOTA:</strong><br>
			<?php echo $notas;?>
		</p>
	<?php }?>
	<br>
          <table cellspacing="0" style="width: 100%; text-align: left; font-size: 11pt;">
            <tr>
                <td style="width:70%;text-align:left; line-heigh: 2">
                    Observaciones:
                    EN PEDIDOS el anticipo ser&aacute; del 70% del TOTAL y el saldo el 30% contra entrega de la mercanc&iacute;a.
                    El tiempo de entrega de la mercanc&iacute;a depende de la disponibilidad de cada art&iacute;culo (de 6 a 8 semanas) y se confirmar&aacute; al momento de realizar el pedido.
                    Precios sujetos a cambios sin previo aviso.
                    Los precios son en USD y NO INCLUYEN IVA.
                    La mercanc&iacute;a bajo PEDIDO no participa en ninguna promoci&oacute;n.
                    NO se aceptan cambios ni devoluciones.
                </td>
                <td style="width:30%;text-align:center ">
                     Atte
                    Arq. Adriana Lizama artifice
                </td>
            </tr>
        </table>
    <br><br><br>
        <table style="width:100%">
            <tr>
                <td style="text-align:center; width:100%" colspan="3">
                     Plaza Solare. Local 201, Calle 49 No. 230 x 26 y 28 San Antonio Cucul, M&eacute;rida, Yucat&aacute;n.
                </td>
            </tr>
            <tr>
                <td style="text-align:center;">
                    tel: 54 1 (999) 6 88 02 40.
                </td>
                <td style="text-align:center;">
                    info@artificestore.mx
                </td>
                <td style="text-align:center;">
                    www.artificestore.mx
                </td>
            </tr>
        </table>
</page>
