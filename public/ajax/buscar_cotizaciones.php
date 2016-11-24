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
			  <strong>Error!</strong> No se pudo eliminar los datos
			</div>
			<?php
			
		}
	}
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		 $aColumns = array('atencion', 'empresa');//Columnas de busqueda
		 $sTable = "estimates";
		 $sWhere = "";
		if ( $_GET['q'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
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
			
			
			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="warning">
					<th>#</th>
					<th>Fecha</th>
					<th>Atención</th>
					<th>Empresa</th>
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
						$atencion=$row['atencion'];
						$tel1=$row['tel1'];
						$empresa=$row['empresa'];
						$tel2=$row['tel2'];
						$email=$row['email'];
						$sum=mysqli_query($con,"select sum(cantidad*precio_venta) as tot from detail_estimate where numero_cotizacion='$numero_cotizacion'");
						$rw_sum=mysqli_fetch_array($sum);
						$total=$rw_sum['tot'];
					?>
					<tr>
						<td><?php echo $numero_cotizacion; ?></td>
						<td><?php echo $fecha; ?></td>
						<td >
						<?php echo $atencion; ?><br>
						<small><i class='glyphicon glyphicon-phone'></i> <?php echo $tel1;?></small>
						</td>
						<td >
						<?php echo $empresa; ?><br>
						<?php if (!empty($tel2)){?><small><i class='glyphicon glyphicon-phone'></i> <?php echo $tel2;?></small><br><?php }?>
						<?php if (!empty($email)){?><small><i class='glyphicon glyphicon-envelope'></i> <?php echo $email;?></small><?php }?>
						</td>
						<td>
						<div class="pull-right">
						<?php echo number_format($total,2);?>
						</div>
						</td>
						
					<td ><span class="pull-right">
					<a href="#" class='btn btn-default' title='Descargar cotización' onclick="descargar('<?php echo $id_cotizacion;?>');"><i class="glyphicon glyphicon-download"></i></a> 
					<a href="#" class='btn btn-default' title='Borrar cotización' onclick="eliminar('<? echo $numero_cotizacion; ?>')"><i class="glyphicon glyphicon-trash"></i> </a></span></td>
						
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=6><span class="pull-right"><?
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>