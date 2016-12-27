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
table.page_footer {width: 100%; border: none; background-color: white; padding: 2mm;border-collapse:collapse; border: none;}
-->
</style>
<page backtop="10mm" backbottom="10mm" backleft="10mm" backright="10mm" style="font-size: 12px; font-family: helvetica" >
	<?php 
		include("page_footer.php");
	?>
       <table style="width:100%">
        <tr style="vertical-align: top">
            
			<td style="width:100%;text-align:center">
				<span style="color:#555"><?php echo $nombre_empresa;?></span><br>
				<span style="color:#555">REPORTE DE COTIZACIONES</span><br>
				<span style="color:#555">
				<?php 
					if ($estado!=""){
					if ($estado==0){ $lbl_status= "COTIZACIONES PENDIENTES";}
					elseif ($estado==1){$lbl_status= "COTIZACIONES ACEPTADAS";}
					elseif ($estado==2){$lbl_status= "COTIZACIONES RECHAZADAS";}
					echo $lbl_status;
					}
				?>
				</span>
				
			</td>
            
            
        </tr>
        
    </table>


  
    <table class="table-bordered" style="width:100%;font-size:10px" cellspacing=0>
        <tr>
            <th class="bottom" style="width: 5%;text-align:center">#</th>
            <th class="bottom left" style="width: 8%;text-align:center">Fecha</th>
            <th class="bottom left" style="width: 20%;text-align:left">Contacto</th>
			<th class="bottom left" style="width: 20%;text-align:left">Cliente</th>
            <th class="bottom left" style="width: 15%;text-align:left">Vendedor</th>
			<th class="bottom left" style="width: 10%;text-align:right">Neto</th>
			<th class="bottom left" style="width: 8%;text-align:right">IVA</th>
            <th class="bottom left" style="width: 10%;text-align:right">Total</th>
            
        </tr>
	<?php
		$sumador_neto=0;
		$sumador_iva=0;
		while ($row=mysqli_fetch_array($query)){
			$numero_cotizacion=$row['numero_cotizacion'];
			$fecha_cotizacion=$row['fecha_cotizacion'];
			list($date,$time)=explode(" ",$fecha_cotizacion);
			list($anio,$mes,$dia)=explode("-",$date);
			$fecha="$dia-$mes-$anio";
			$nombre_cliente=$row['nombre_cliente'];
			$empresa=$row['nombre_comercial'];
			$tel2=$row['movil'];
			$vendedor=$row['firstname']." ".$row['lastname'];
			$total_neto=number_format($row['total_neto'],2,'.','');
			$total_iva=number_format($row['total_iva'],2,'.','');
			$monto_total=$total_neto+$total_iva;
			$sumador_neto+=$total_neto;
			$sumador_iva+=$total_iva;
			$id_contact=$row['id_contact'];
			//SQL contacto
				$sql_contacto=mysqli_query($con,"select * from contacts where id_contact='".$id_contact."'");
				$rw_contacto=mysqli_fetch_array($sql_contacto);
				$nombre_contact	= $rw_contacto['nombre_contact'];
				$telefono_contact	= $rw_contacto['telefono_contact'];
				$email_contact	= $rw_contacto['email_contact'];
			//Fin SQL contacto
			
			
			?>
		<tr>
            <td class="" style="width: 5%; text-align: center"><?php echo $numero_cotizacion;?></td>
            <td class="left" style="width: 8%; text-align: center"><?php echo $fecha;?></td>
            <td class="left" style="width: 20%;">
				<?php echo $nombre_contact;?><br>
				<?php if (!empty($telefono_contact)){?><small>Móvil: <?php echo $telefono_contact;?></small><?php }?>
			</td>
			<td class="left" style="width: 20%;">
				<?php echo $nombre_cliente;?><br>
				<small><?php echo $empresa;if (!empty($empresa)){echo "<br>";}?></small>
				<small>Tel.: <?php echo $tel2;?></small>
			</td>
            <td class="left" style="width: 15%;"><?php echo $vendedor;?></td>
			<td class="left" style="width: 10%;text-align:right"><?php echo number_format($total_neto,2);?></td>
			<td class="left" style="width: 8%;text-align:right"><?php echo number_format($total_iva,2);?></td>
			<td class="left" style="width: 10%;text-align:right"><?php echo number_format($monto_total,2);?></td>
            
        </tr>
			<?php
		}
		$sumador_total=$sumador_neto+$sumador_iva;
			
	?>
		<tr>
			<td class='top left' colspan=5>Totales </td>
			<td class='top left' style="text-align:right"><?php echo number_format($sumador_neto,2);?></td>
			<td class='top left' style="text-align:right"><?php echo number_format($sumador_iva,2);?></td>
			<td class='top left' style="text-align:right"><?php echo number_format($sumador_total,2);?></td>
		</tr>
			
	 </table>
    
	
	
	

</page>

