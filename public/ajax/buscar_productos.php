<?php

	/*-------------------------
	Autor: Obed Alvarado
	Web: obedalvarado.pw
	Mail: info@obedalvarado.pw
	---------------------------*/
	session_start();
	$session_id= session_id();
	/* Connect To Database*/

	require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos
	include("../funciones.php");
	$action = (isset($_REQUEST['action'])&& $_REQUEST['action'] !=NULL)?$_REQUEST['action']:'';
	if (isset($_GET['id'])){
		$id_producto=intval($_GET['id']);
		$query=mysqli_query($con, "select * from detail_estimate where id_producto='".$id_producto."'");
		$count=mysqli_num_rows($query);
		if ($count==0){
			$delete1=mysqli_query($con,"DELETE FROM ctlg_cats_entradas WHERE idEntrada='".$id_producto."'");
			$delete2=mysqli_query($con,"DELETE FROM ctlg_entradas WHERE idEntrada='".$id_producto."'");
			$delete3=mysqli_query($con,"DELETE FROM ctlg_imagenes WHERE idEntrada='".$id_producto."'");
			if ($delete3){
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
			  <strong>Error!</strong> No se pudo eliminar éste  producto. Existen cotizaciones vinculadas a éste producto.
			</div>
			<?php
		}



	}
	if (isset($_GET['id_producto'])){
		$id_producto=intval($_GET['id_producto']);
		$agregar_cotizacion=agregar_cotizacion($id_producto,$session_id);
		if ($agregar_cotizacion==1){
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Bien hecho!</strong> El producto fue agregado a la cotización exitosamente.
			</div>
			<?php
		} else {
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> Lo siento algo ha salido mal intenta nuevamente.
			</div>
			<?php
		}
	}
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
        $q_sku = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		$q_titulo = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q2'], ENT_QUOTES)));
		$q_marca = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q3'], ENT_QUOTES)));

		$sql = 'SELECT
			e.`idEntrada` AS id_producto,
			e.`titulo` AS titulo_producto,
			e.`sku` AS sku_producto,
			e.`precio` AS precio_producto,
			c.`titulo` AS marca_producto,
			k.`titulo` AS categoria_producto,
			i.`idImagen` AS id_foto,
			i.`nomArchivo` AS nombre_foto
			FROM `ctlg_entradas` e
			INNER JOIN `ctlg_cats_entradas` p
			ON e.`idEntrada`=p.`idEntrada`
			INNER JOIN `ctlg_categorias` c
			ON c.`idCat`=p.`idCat`
			INNER JOIN `ctlg_cats_entradas` v
			ON e.`idEntrada`=v.`idEntrada`
			INNER JOIN `ctlg_categorias` k
			ON k.`idCat`=v.`idCat`
			INNER JOIN `ctlg_imagenes` i
			ON i.`idEntrada`=e.`idEntrada`
			WHERE e.`tipo`="producto"
			AND c.`tipo`="brand"
			AND k.`tipo`="section-catalog"
			AND i.`tipo`="featurePage"';

		 $sql .= " AND e.`sku` LIKE '%$q_sku%'";
		 $sql .= " AND e.`titulo` LIKE '%$q_titulo%'";

		 if ($q_marca!=""){
			 $sql .= " AND c.`titulo` = '$q_marca'";
		 }


		$sql.=" order by id_producto desc";
		include 'pagination.php'; //include pagination file
		//pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query  = mysqli_query($con, "SELECT count(*) AS numrows FROM ($sql) t");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './productos.php';
		//main query to fetch the data
		$sql.=" LIMIT $offset,$per_page";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){
			/*Datos de la empresa*/
				// $sql_empresa=mysqli_query($con,"SELECT * FROM currencies, empresa where currencies.id=empresa.id_moneda");
				// $rw_empresa=mysqli_fetch_array($sql_empresa);
				// $iva=$rw_empresa["iva"];
				// $moneda=$rw_empresa["symbol"];
				// $decimals=$rw_empresa["decimals"];
				// $thousand_separator=$rw_empresa["thousand_separator"];
				// $decimal_separator=$rw_empresa["decimal_separator"];
			/*Fin datos empresa*/

			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="warning">
					<th>Foto</th>
					<th>SKU</th>
					<th>Producto</th>
					<th>Marca</th>
					<th>Categoría</th>
					<th>Precio</th>
					<!-- <th>Agregado</th> -->
					<!-- <th><span class='pull-right'>Precio</span></th> -->
					<th><span class="pull-right">Acciones</span></th>

				</tr>
				<?php
				// while ($row=mysqli_fetch_array($query)){
				while ($row=mysqli_fetch_array($query)){
						$foto_producto=$row['id_foto'] . "_image_" . $row['nombre_foto'];
						$sku_producto=$row['sku_producto'];
						$id_producto=$row['id_producto'];
						$nombre_producto=$row['titulo_producto'];
						$marca_producto=$row['marca_producto'];
						$categoria_producto=$row['categoria_producto'];
						$precio_producto=$row['precio_producto'];
					?>

					<input type="hidden" value="<?php echo $sku_producto;?>" id="codigo_producto<?php echo $id_producto;?>">
					<input type="hidden" value="<?php echo $marca_producto;?>" id="marca_producto<?php echo $id_producto;?>">
					<input type="hidden" value="<?php echo $categoria_producto;?>" id="categoria_producto<?php echo $id_producto;?>">
					<input type="hidden" value="<?php echo htmlentities($nombre_producto);?>" id="nombre_producto<?php echo $id_producto;?>">
					<input type="hidden" value="<?php echo $marca_producto;?>" id="marca<?php echo $id_producto;?>">
					<input type="hidden" value="<?php echo number_format($precio_producto,2,'.','');?>" id="precio_producto<?php echo $id_producto;?>">
					<span id="descripcion<?php echo $id_producto;?>" style="display:none"><?php echo $nombre_producto;?><span>
					<tr>
						<td><img src="http://artificestore.mx/archivos/imagenes/thumbs/<?php echo $foto_producto; ?>" width="100px"></td>
						<td><?php echo $sku_producto; ?></td>
						<td ><?php echo $nombre_producto; ?></td>
						<td ><?php echo $marca_producto; ?></td>
						<td ><?php echo $categoria_producto; ?></td>
						<td ><?php echo $precio_producto; ?></td>
					<td ><span class="pull-right">
					<button type="button" class='btn btn-success' title='Agregar a cotización' onclick="agregar_cotizacion('<?php echo $id_producto;?>');" ><i class="fa fa-shopping-cart"></i></button>
					<a href="#" class='btn btn-info' title='Editar producto' onclick="obtener_datos('<?php echo $id_producto;?>');" data-toggle="modal" data-target="#editModalProduct"><i class="fa fa-edit"></i></a>
					<a href="#" class='btn btn-danger' title='Borrar producto' onclick="eliminar('<?php echo $id_producto; ?>')"><i class="glyphicon glyphicon-trash"></i> </a></span></td>

					</tr>
					<?php
				}
				?>
				<tr>
					<td colspan=9><span class="pull-right"><?
					 echo paginate($reload, $page, $total_pages, $adjacents);
					?></span></td>
				</tr>
			  </table>
			</div>
			<?php
		}
	}
?>
