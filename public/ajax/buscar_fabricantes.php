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
		$id_marca=intval($_GET['id']);

		$sql = 'SELECT
			e.`idEntrada` AS id_producto,
			e.`titulo` AS titulo_producto,
			e.`sku` AS sku_producto,
			e.`precio` AS precio_producto,
			c.`titulo` AS marca_producto
			FROM `ctlg_entradas` e
			INNER JOIN `ctlg_cats_entradas` p
			ON e.`idEntrada`=p.`idEntrada`
			INNER JOIN `ctlg_categorias` c
			ON c.`idCat`=p.`idCat`
			WHERE e.`tipo`="producto"
			AND c.`tipo`="brand"
			AND c.`idCat`="'.$id_marca.'"';

		$query=mysqli_query($con, $sql);
		$count=mysqli_num_rows($query);
		if ($count==0){
			if ($delete1=mysqli_query($con,"DELETE FROM ctlg_categorias WHERE idCat='".$id_marca."'")){
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
			  <strong>Error!</strong> No se pudo eliminar éste  fabricante. Existen productos vinculados a éste fabricante.
			</div>
			<?php
		}



	}
	if($action == 'ajax'){
		// escaping, additionally removing everything that could be (html/javascript-) code
         $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		 $aColumns = array('titulo');//Columnas de busqueda
		 $sTable = "manufacturers";

		 $sql = "SELECT * FROM ctlg_categorias";
		 $sWhere = "";
		if ( $_GET['q'] != "" )
		{
			$sWhere = " WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".$q."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ') AND tipo="brand"';
		}else{
			$sWhere.=" WHERE tipo='brand'";
		}
		$sWhere.=" order by idCat desc";

		$sql.=$sWhere;
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
		$reload = './fabricantes.php';
		//main query to fetch the data
		$sql.=" LIMIT $offset,$per_page";
		$query = mysqli_query($con, $sql);
		//loop through fetched data
		if ($numrows>0){

			?>
			<div class="table-responsive">
			  <table class="table">
				<tr  class="warning">
					<th>ID</th>
					<th>Marca</th>
					<th>Nº productos</th>
					<th><span class="pull-right">Acciones</span></th>

				</tr>
				<?php
				while ($row=mysqli_fetch_array($query)){
						$id_marca=$row['idCat'];
						$fabricante=$row['titulo'];
						$estado=$row['estatus'];
						$count=mysqli_query($con,"select count(*) AS num_prod from ctlg_cats_entradas where idCat='".$id_marca."'" );
						$rw_count=mysqli_fetch_array($count);
						$num_prod=$rw_count['num_prod'];
					?>


					<input type="hidden" value="<?php echo $fabricante;?>" id="fabricante<?php echo $id_marca;?>">
					<input type="hidden" value="<?php echo $estado;?>" id="estado<?php echo $id_marca;?>">

					<tr>
						<td><?php echo $id_marca; ?></td>
						<td><?php echo $fabricante;?></td>
						<td><?php echo number_format($num_prod,2);?></td>

					<td ><span class="pull-right">
					<a href="#" class='btn btn-default' title='Editar fabricante' onclick="obtener_datos('<?php echo $id_marca;?>');" data-toggle="modal" data-target="#myModal2"><i class="glyphicon glyphicon-edit"></i></a>
					<a href="#" class='btn btn-default' title='Borrar fabricante' onclick="eliminar('<?php echo $id_marca; ?>')"><i class="glyphicon glyphicon-trash"></i> </a></span></td>

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
