<?php
	/*-------------------------
	Autor: Obed Alvarado
	Web: obedalvarado.pw
	Mail: info@obedalvarado.pw
	---------------------------*/
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: login.php");
		exit;
        }
	if (!file_exists ('config/db.php')){
		header("location: install/paso1.php");
		exit;
	}
	/* Connect To Database*/
	require_once ("config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("config/conexion.php");//Contiene funcion que conecta a la base de datos
	include("funciones.php");
	$active_inicio="";
	$active_cotizaciones="";
	$active_productos="";
	$active_fabricantes="";
	$active_usuarios="";
	$active_empresa="";
	$active_clientes="";
	$active_contactos="";
	$active_monedas="";
	$active_reportes="active";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cotizador - Panel de Control</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/datepicker3.css" rel="stylesheet">
<link href="css/styles.css" rel="stylesheet">
<link rel="icon" href="img/cart_icon.png">
<!--Icons-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->
</head>
<body>
	<?php
	include("navbar.php");
	include("sidebar.php");
	?>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><i class='fa fa-bar-chart'></i></a></li>
				<li class="active">Reportes</li>
			</ol>

		</div><!--/.row-->
		<div class="row">
			<div class="col-md-12">

				<div class="panel panel-default">

					<div class="panel-heading">
						<div class="btn-group pull-right">
							<button type='button' class="btn btn-info" data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-plus" ></span> Nuevo Cliente</button>
						</div>
						<h4><i class='glyphicon glyphicon-search'></i> Buscar Clientes</h4>
					</div>

					<div class="panel-body">
						<form class="form-horizontal" role="form" id="datos_cotizacion">
								<div class="form-group row">
									<label for="q" class="col-md-2 control-label">Nombre del cliente:</label>
									<div class="col-md-5">
										<div class="input-group">
											<input type="text" class="form-control" id="q"  onkeyup='load(1);'>
											<span class="btn btn-default input-group-addon" onclick="load(1);"><i class="glyphicon glyphicon-search"></i></span>
										</div>
									</div>
									<div class="col-md-3">
										<span id="loader"></span>
									</div>
								</div>
						</form>
						<div id="resultados"></div><!-- Carga los datos ajax -->
						<div class='outer_div'></div><!-- Carga los datos ajax -->
					</div>
				</div><!-- /.panel-default -->

			</div><!-- /.col-md-12 -->

		</div><!-- /.row -->
	</div>	<!--/.main-->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>

	</script>
</body>
</html>
