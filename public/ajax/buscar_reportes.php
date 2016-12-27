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

    if($action == 'vendedores'){

        include 'pagination.php'; //include pagination file
        //pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM users");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './buscar_reportes.php';

        $sql = "SELECT * FROM users LIMIT $offset,$per_page";
        $query = mysqli_query($con, $sql);

        ?>
        <div class="table-responsive">
          <table class="table">
            <tr  class="warning">
                <th>Vendedor</th>
                <th># Cotizaciones Pendientes</th>
                <th># Cotizaciones Aceptadas</th>
                <th># Cotizaciones Rechazadas</th>
            </tr>
        <?php
        while ($row=mysqli_fetch_array($query)) {
            $nombre_vendedor = $row['firstname'] . " " . $row['lastname'];
            $id_vendedor = $row['user_id'];

            $sql_pendientes = "SELECT COUNT(*) AS numrows FROM (SELECT * FROM estimates WHERE id_vendedor='$id_vendedor' AND status=0) t";
            $query_pendientes = mysqli_query($con, $sql_pendientes);
            $fetch_pendientes = mysqli_fetch_array($query_pendientes);
            $pendientes = $fetch_pendientes['numrows'];

            $sql_aceptadas = "SELECT COUNT(*) AS numrows FROM (SELECT * FROM estimates WHERE id_vendedor='$id_vendedor' AND status=1) t";
            $query_aceptadas = mysqli_query($con, $sql_aceptadas);
            $fetch_aceptadas = mysqli_fetch_array($query_aceptadas);
            $aceptadas = $fetch_aceptadas['numrows'];

            $sql_rechazadas = "SELECT COUNT(*) AS numrows FROM (SELECT * FROM estimates WHERE id_vendedor='$id_vendedor' AND status=2) t";
            $query_rechazadas = mysqli_query($con, $sql_rechazadas);
            $fetch_rechazadas = mysqli_fetch_array($query_rechazadas);
            $rechazadas = $fetch_rechazadas['numrows'];

            ?>
            <tr>
                <td><?php echo $nombre_vendedor; ?></td>
                <td><?php echo $pendientes; ?></td>
                <td><?php echo $aceptadas; ?></td>
                <td><?php echo $rechazadas; ?></td>
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

    if($action == 'productos'){

        $sql = 'SELECT
            e.`idEntrada` AS id_producto,
            e.`titulo` AS titulo_producto,
            e.`sku` AS sku_producto,
            i.`idImagen` AS id_foto,
            i.`nomArchivo` AS nombre_foto,
            COUNT(*) AS most
            FROM `detail_estimate` d
            INNER JOIN `ctlg_entradas` e
            ON d.`id_producto`=e.`idEntrada`
            INNER JOIN `estimates` s
            ON d.`numero_cotizacion`=s.`numero_cotizacion`
            INNER JOIN `ctlg_imagenes` i
            ON e.`idEntrada`=i.`idEntrada`
            WHERE i.`tipo`="featurePage"
            GROUP BY e.`idEntrada`
            ORDER BY most DESC';

        include 'pagination.php'; //include pagination file
        //pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM ($sql) t");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './buscar_reportes.php';

        $sql .= " LIMIT $offset,$per_page";
        $query = mysqli_query($con, $sql);

        ?>
        <div class="table-responsive">
          <table class="table">
            <tr  class="warning">
                <th>Foto</th>
                <th>SKU</th>
                <th>Producto</th>
                <th># Veces Cotizado</th>
            </tr>
        <?php
        while ($row=mysqli_fetch_array($query)) {
            $foto_producto = $row['id_foto'] . "_image_" . $row['nombre_foto'];
            $sku_producto = $row['sku_producto'];
            $nombre_producto = $row['titulo_producto'];
            $most = $row['most'];

            ?>
            <tr>
                <td><img src="http://artificestore.mx/archivos/imagenes/thumbs/<?php echo $foto_producto; ?>" width="100px"></td>
                <td><?php echo $sku_producto; ?></td>
                <td><?php echo $nombre_producto; ?></td>
                <td><?php echo $most; ?></td>
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

	if($action == 'categorias'){

		$sql = 'SELECT
            e.`idEntrada` AS id_producto,
            e.`titulo` AS titulo_producto,
            e.`sku` AS sku_producto,
            i.`idImagen` AS id_foto,
            i.`nomArchivo` AS nombre_foto,
            c.`titulo` AS categoria_producto,
            COUNT(*) AS most
            FROM `detail_estimate` d
            INNER JOIN `ctlg_entradas` e
            ON d.`id_producto`=e.`idEntrada`
            INNER JOIN `estimates` s
            ON d.`numero_cotizacion`=s.`numero_cotizacion`
            INNER JOIN `ctlg_imagenes` i
            ON e.`idEntrada`=i.`idEntrada`
            INNER JOIN `ctlg_cats_entradas` p
			ON e.`idEntrada`=p.`idEntrada`
			INNER JOIN `ctlg_categorias` c
			ON c.`idCat`=p.`idCat`
            WHERE i.`tipo`="featurePage"
            AND c.`tipo`="section-catalog"
            GROUP BY categoria_producto
            ORDER BY most DESC';

		include 'pagination.php'; //include pagination file
        //pagination variables
		$page = (isset($_REQUEST['page']) && !empty($_REQUEST['page']))?$_REQUEST['page']:1;
		$per_page = 10; //how much records you want to show
		$adjacents  = 4; //gap between pages after number of adjacents
		$offset = ($page - 1) * $per_page;
		//Count the total number of row in your table*/
		$count_query   = mysqli_query($con, "SELECT count(*) AS numrows FROM ($sql) t");
		$row= mysqli_fetch_array($count_query);
		$numrows = $row['numrows'];
		$total_pages = ceil($numrows/$per_page);
		$reload = './buscar_reportes.php';

        $sql .= " LIMIT $offset,$per_page";
        $query = mysqli_query($con, $sql);

		?>
        <div class="table-responsive">
          <table class="table">
            <tr  class="warning">
                <th>Categoría</th>
                <th># Veces Cotizado</th>
            </tr>
		<?php
        while ($row=mysqli_fetch_array($query)) {
            $categoria_producto = $row['categoria_producto'];
            $most = $row['most'];

            ?>
            <tr>
                <td><?php echo $categoria_producto; ?></td>
                <td><?php echo $most; ?></td>
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
