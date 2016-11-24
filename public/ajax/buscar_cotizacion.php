<?php

	/*-------------------------
	Autor: Obed Alvarado
	Web: obedalvarado.pw
	Mail: info@obedalvarado.pw
	---------------------------*/
	/* Connect To Database*/
	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if (isset($_GET['id'])){
		$numero_cotizacion=intval($_GET['id']);
		$del1="delete from estimates where numero_cotizacion='".$numero_cotizacion."'";
		$del2="delete from detail_estimate where numero_cotizacion='".$numero_cotizacion."'";
		if ($delete1=mysqli_query($con,$del1) and $delete2=mysqli_query($con,$del2)){
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Aviso!</strong> Datos eliminados exitosamente
			</div>
			<?php 
		}else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> No se puedo eliminar los datos
			</div>
			<?php
			
		}
	}
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
        $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		$id_vendedor=intval($_REQUEST['id_vendedor']);
		$daterange = mysqli_real_escape_string($con,(strip_tags($_REQUEST['daterange'], ENT_QUOTES)));
		$estado=mysqli_real_escape_string($con,(strip_tags($_REQUEST['estado'], ENT_QUOTES)));
		if (!empty($daterange)){
		list ($f_inicio,$f_final)=explode(" - ",$daterange);//Extrae la fecha inicial y la fecha final en formato español
		list ($dia_inicio,$mes_inicio,$anio_inicio)=explode("/",$f_inicio);//Extrae fecha inicial 
		$fecha_inicial="$anio_inicio-$mes_inicio-$dia_inicio 00:00:00";//Fecha inicial formato ingles
		list($dia_fin,$mes_fin,$anio_fin)=explode("/",$f_final);//Extrae la fecha final
		$fecha_final="$anio_fin-$mes_fin-$dia_fin 23:59:59";
		} else {
			$fecha_inicial=date("Y-m")."-01 00:00:00";
			$fecha_final=date("Y-m-d H:i:s");
		}
		
		 $sTable = "estimates, clients, users";
		 $sWhere = "where estimates.id_cliente=clients.id and estimates.id_empleado=users.user_id"; 
	     $sWhere .= " and (clients.contacto like '%$q%' or clients.nombre_comercial like '%$q%')";
		 if ($id_vendedor>0){
			$sWhere .= " and estimates.id_empleado='$id_vendedor'"; 
		 }
		 if ($estado!=""){
			 $sWhere .= " and estimates.status='$estado'"; 
		 }
		 $sWhere .= " and estimates.fecha_cotizacion between '$fecha_inicial' and '$fecha_final' "; 
		 $sWhere.=" order by id_cotizacion desc";
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM $sTable  $sWhere");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './buscar_cotizacion.php';
		//main query to fetch the data
		$sql="SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			function get_currency($id_moneda){
				global $con;
				$sql_currencies=mysqli_query($con,"SELECT * FROM currencies where id='$id_moneda'");
				$rw=mysqli_fetch_array($sql_currencies);
				return $rw;
			}
			 
			
			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="warning">
					<th>#</th>
					<th>Fecha</th>
					<th>Contacto</th>
					<th>Cliente</th>
					<th>Vendedor</th>
					<th>Estado</th>
					<th><span class="pull-right">Neto</span></th>
					<th><span class="pull-right">IVA</span></th>
					<th><span class="pull-right">Total</span></th>
					<th><span class="pull-right">Acciones</span></th>
					
				</tr>
				<?php
				while ($row=mysqli_fetch_array($query)){
						$id_cotizacion=$row['id_cotizacion'];
						$numero_cotizacion=$row['numero_cotizacion'];
						$fecha_cotizacion=$row['fecha_cotizacion'];
						list($date,$time)=explode(" ",$fecha_cotizacion);
						list($anio,$mes,$dia)=explode("-",$date);
						$fecha="$dia-$mes-$anio";
						$nombre_cliente=$row['nombre_cliente'];
						$empresa=$row['nombre_comercial'];
						$vendedor=$row['firstname']." ".$row['lastname'];
						$tel2=$row['movil'];
						$email=$row['email'];
						$total_neto=number_format($row['total_neto'],2,'.','');
						$total_iva=number_format($row['total_iva'],2,'.','');
						$monto_total=$total_neto+$total_iva;
						$status=$row['status'];
						if ($status==0){$estado="Pendiente";$label="label-warning";}
						else if ($status==1) {$estado="Aceptada";$label="label-success";}
						else {$estado="Rechazada";$label="label-danger";}
						$currency_id=$row['currency_id'];
						list($id,$name,$symbol,$decimals, $thousand_separator,$decimal_separator, $code)=get_currency($currency_id);
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
						<td><?php echo $numero_cotizacion; ?></td>
						<td><?php echo $fecha; ?></td>
						<td >
						<?php echo $nombre_contact; ?>
						<?php if (!empty($telefono_contact)){?>
						<br>
						<small><i class='glyphicon glyphicon-phone'></i> <?php echo $telefono_contact;?></small>
						<?php }?>
						<?php if (!empty($email_contact)){?>
						<br>
						<small><i class='fa fa-envelope'></i> <?php echo $email_contact;?></small>
						<?php }?>
						</td>
						<td >
						<?php echo $nombre_cliente; ?><br>
						<small><?php echo $empresa;if (!empty($empresa)){echo "<br>";}?></small>
						<?php if (!empty($tel2)){?><br><small><i class='glyphicon glyphicon-phone-alt'></i> <?php echo $tel2;?></small><br><?php }?>
						<?php if (!empty($email)){?><small><i class='glyphicon glyphicon-envelope'></i> <?php echo $email;?></small><?php }?>
						</td>
						<td><?php echo $vendedor;?></td>
						<td>
							<span class="label <?php echo $label;?>"><?php echo $estado;?></span>
								
						</td>
						<td><?php echo $symbol;?>
						<div class="pull-right">
						<?php echo number_format($total_neto,$decimals,$decimal_separator,$thousand_separator);?>
						</div>
						</td>
						<td><?php echo $symbol;?>
						<div class="pull-right">
						<?php echo number_format($total_iva,$decimals,$decimal_separator,$thousand_separator);?>
						</div>
						</td>
						<td><?php echo $symbol;?>
						<div class="pull-right">
						<?php echo number_format($monto_total,$decimals,$decimal_separator,$thousand_separator);?>
						</div>
						</td>
						
					<td ><span class="pull-right">
						<div class="dropdown">
							<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
								Acciones
							<span class="caret"></span>
							</button>
							  <ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu1">
								<li><a href="editar_cotizacion.php?id=<?php echo $numero_cotizacion;?>" title='Editar cotización'><i class="glyphicon glyphicon-edit"></i> Editar</a></li>
								<li><a href="#" title='Imprimir cotización' onclick="descargar('<?php echo $id_cotizacion;?>');"><i class="glyphicon glyphicon-print"></i> Imprimir</a></li>
								<li><a href="#" title='Enviar cotización' data-toggle="modal" data-target="#myModal"  data-number="<?php echo $id_cotizacion;?>" data-email="<?php echo $email;?>"><i class="glyphicon glyphicon-envelope"></i> Enviar Email</a></li>
								<li><a href="#" title='Borrar cotización' onclick="eliminar('<?php echo $numero_cotizacion; ?>')" ><i class="glyphicon glyphicon-trash"></i> Eliminar</a></li>
							  </ul>
						</div>
						</span></td>
						
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=10><span class="pull-right"><?
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>