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
		$currency_id=intval($_GET['id']);
		$query=mysqli_query($con, "select * from estimates where currency_id='".$currency_id."'");
		$count=mysqli_num_rows($query);
		if ($count==0){
			if ($delete1=mysqli_query($con,"DELETE FROM currencies WHERE id='".$currency_id."'")){
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Aviso!</strong> Datos eliminados exitosamente.
			</div>
			<?php 
		}else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> Lo siento algo ha salido mal intenta nuevamente.
			</div>
			<?php
			
		}
			
		} else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> No se pudo eliminar ésta moneda. Existen cotizaciones vinculadas a ésta moneda. 
			</div>
			<?php
		}
		
		
		
	}
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		 $aColumns = array('name');//Columnas de busqueda
		 $sTable = "currencies";
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
		$sWhere.=" order by id";
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
		$reload = './fabricantes.php';
		//main query to fetch the data
		$sql="SELECT * FROM  $sTable $sWhere LIMIT $offset,$per_page";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			
			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="warning">
					<th>ID</th>
					<th>Nombre</th>
					<th>Símbolo</th>
					<th>Precisión</th>
					<th>Separador de millar</th>
					<th>Separador de decimales</th>
					<th>Código</th>
					<th><span class="pull-right">Acciones</span></th>
					
				</tr>
				<?php
				while ($row=mysqli_fetch_array($query)){
						$id_moneda			=$row['id'];
						$nombre_moneda		=$row['name'];
						$symbol				=$row['symbol'];
						$precision			=$row['decimals'];
						$thousand_separator	=$row['thousand_separator'];
						if ($thousand_separator==","){$millar="Coma ($thousand_separator)";}
						else{$millar="Punto ($thousand_separator)";}
						$decimal_separator	=$row['decimal_separator'];
						if ($decimal_separator==","){$decimal="Coma ($decimal_separator)";}
						else{$decimal="Punto ($decimal_separator)";}
						$code				=$row['code'];
						
					?>
		
					<tr>
						<td><?php echo $id_moneda; ?></td>
						<td><?php echo $nombre_moneda;?></td>
						<td><?php echo $symbol;?></td>
						<td><?php echo $precision;?></td>
						<td><?php echo $millar;?></td>
						<td><?php echo $decimal;?></td>
						<td><?php echo $code;?></td>
						
					<td ><span class="pull-right">
					<a href="#" class='btn btn-default' title='Editar moneda'  data-toggle="modal" data-target="#myModal2" data-nombre="<?php echo $nombre_moneda;?>" data-simbolo="<?php echo $symbol;?>" data-precision="<?php echo $precision;?>" data-millar="<?php echo $thousand_separator;?>" data-decimal="<?php echo $decimal_separator;?>" data-codigo="<?php echo $code;?>" data-id="<?php echo $id_moneda;?>"><i class="glyphicon glyphicon-edit"></i></a> 
					<a href="#" class='btn btn-default' title='Borrar moneda' onclick="eliminar('<?php echo $id_moneda; ?>')"><i class="glyphicon glyphicon-trash"></i> </a></span></td>
						
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=8><span class="pull-right"><?
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>