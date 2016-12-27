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
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		 $aColumns = array('e.`sku`', 'e.`titulo`');//Columnas de busqueda
		 $sAnd = "";

		 $sql = 'SELECT
 			e.`idEntrada` AS id_producto,
 			e.`titulo` AS titulo_producto,
 			e.`sku` AS sku_producto,
 			e.`precio` AS precio_producto,
 			c.`titulo` AS marca_producto,
 			i.`idImagen` AS id_foto,
 			i.`nomArchivo` AS nombre_foto
 			FROM `ctlg_entradas` e
 			INNER JOIN `ctlg_cats_entradas` p
 			ON e.`idEntrada`=p.`idEntrada`
 			INNER JOIN `ctlg_categorias` c
 			ON c.`idCat`=p.`idCat`
 			INNER JOIN `ctlg_imagenes` i
 			ON i.`idEntrada`=e.`idEntrada`
 			WHERE e.`tipo`="producto"
 			AND c.`tipo`="brand"
 			AND i.`tipo`="featurePage"';

		if ( $_GET['q'] != "" )
		{
			$sAnd = "AND (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sAnd .= $aColumns[$i]." LIKE '%".$q."%' OR ";
			}
			$sAnd = substr_replace( $sAnd, "", -3 );
			$sAnd .= ')';
		}

		$sql.=$sAnd;

		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 5; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query  = mysqli_query($con, "SELECT count(*) AS numrows FROM ($sql) t");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './index.php';
		//main query to fetch the data
		$sql.=" LIMIT $offset,$per_page";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){

			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="warning">
					<th>Foto</th>
					<th>SKU</th>
					<th>Producto</th>
					<th>Marca</th>
					<th><span class="pull-right">Cant.</span></th>
					<!-- <th><span class="pull-right">% Desc.</span></th> -->
					<th><span class="pull-right">Precio</span></th>
					<th style="width: 36px;"></th>
				</tr>
				<?php

				while ($row=mysqli_fetch_array($query)){
					$id_producto=$row['id_producto'];
					$codigo_producto=$row['sku_producto'];
					$nombre_producto=$row['titulo_producto'];
					$nombre_marca=$row['marca_producto'];
					$foto_producto=$row['id_foto']."_image_".$row['nombre_foto'];
					$precio_venta=$row["precio_producto"];
					$precio_venta=number_format($precio_venta,2,'.','');
					?>
					<tr>
						<td><img src="http://artificestore.mx/archivos/imagenes/thumbs/<?php echo $foto_producto; ?>" width="60px"></td>
						<td><?php echo $codigo_producto; ?></td>
						<td><?php echo $nombre_producto; ?></td>
						<td ><?php echo $nombre_marca; ?></td>
						<td class='col-xs-1'>
							<div class="pull-right">
								<input type="text" class="form-control" style="text-align:right" id="cantidad_<?php echo $id_producto; ?>"  value="1" >
							</div>
						</td>
						<!-- <td class='col-xs-1'>
							<div class="pull-right">
								<input type="text" class="form-control" style="text-align:right" id="descuento_<?php echo $id_producto; ?>"  placeholder="%" maxlength="2">
							</div>
						</td> -->
						<td class='col-xs-2'>
						<div class="input-group pull-right">
							<div class="input-group-addon"><i class='fa fa-usd'></i></div>
							<input type="text" class="form-control" style="text-align:right" id="precio_venta_<?php echo $id_producto; ?>"  value="<?php echo $precio_venta;?>" >
						</div>
						</td>
						<td ><span class="pull-right"><a href="#" onclick="agregar('<?php echo $id_producto ?>')"><i class="glyphicon glyphicon-shopping-cart " style="font-size:24px;color: #5CB85C;"></i></a></span></td>
					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=7><span class="pull-right"><?php
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span>
					</td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>
