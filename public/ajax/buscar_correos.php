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
		$reload = './buscar_correos.php';

        $sql = "SELECT * FROM emails LIMIT $offset,$per_page";
        $query = mysqli_query($con, $sql);

        ?>
        <div class="table-responsive">
          <table class="table">
            <tr  class="warning">
                <th>Fecha de envío</th>
                <th>Cotización</th>
                <th>Estatus</th>
                <th>Vendedor</th>
                <th>Enviado a</th>
                <th>Asunto</th>
            </tr>
        <?php
        while ($row=mysqli_fetch_array($query)) {
            $fecha_envio = $row['fecha_envio'];
			$id_cotizacion = $row['id_cotizacion'];
			$email = $row['email'];
			$asunto = $row['asunto'];

            $sql_cotizacion = "SELECT * FROM estimates WHERE id_cotizacion='$id_cotizacion'";
            $query_cotizacion = mysqli_query($con, $sql_cotizacion);
            $fetch_cotizacion = mysqli_fetch_array($query_cotizacion);
            $numero_cotizacion = $fetch_cotizacion['numero_cotizacion'];
            $estatus_cotizacion = $fetch_cotizacion['status'];
            $id_vendedor = $fetch_cotizacion['id_vendedor'];
            $id_cliente = $fetch_cotizacion['id_cliente'];

			$sql_vendedor = "SELECT * FROM users WHERE user_id = '$id_vendedor'";
			$query_vendedor = mysqli_query($con, $sql_vendedor);
			$fetch_vendedor = mysqli_fetch_array($query_vendedor);
			$nombre_vendedor = $fetch_vendedor['firstname'] . " " . $fetch_vendedor['lastname'];

			if($estatus_cotizacion == 0) $estatus_cotizacion = 'Pendiente';
			if($estatus_cotizacion == 1) $estatus_cotizacion = 'Aceptada';
			if($estatus_cotizacion == 2) $estatus_cotizacion = 'Rechazada';

            ?>
            <tr>
                <td><?php echo $fecha_envio; ?></td>
                <td><a href="/editar_cotizacion.php?id=<?php echo $id_cotizacion; ?>"><?php echo $numero_cotizacion; ?></a></td>
                <td><?php echo $estatus_cotizacion; ?></td>
                <td><?php echo $nombre_vendedor; ?></td>
                <td><?php echo $email; ?></td>
                <td><?php echo $asunto; ?></td>
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
