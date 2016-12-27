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
	$active_correos="";
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
						<h4><i class='glyphicon glyphicon-user'></i> Reporte de cotizaciones por vendedor</h4>
					</div>

					<div class="panel-body">
						<div id="resultados"></div><!-- Carga los datos ajax -->
						<div class='outer_div_vendedores' ></div><!-- Carga los datos ajax -->
					</div><!-- /.panel-body -->
				</div><!-- /.panel -->
			</div><!-- /.col-md-12 -->

		</div><!-- /.row -->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4><i class='glyphicon glyphicon-barcode'></i> Reporte de productos más cotizados</h4>
					</div>

					<div class="panel-body">
						<div id="resultados"></div><!-- Carga los datos ajax -->
						<div class='outer_div_productos' ></div><!-- Carga los datos ajax -->
					</div><!-- /.panel-body -->
				</div><!-- /.panel -->
			</div><!-- /.col-md-12 -->

		</div><!-- /.row -->

		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4><i class='glyphicon glyphicon-barcode'></i> Reporte de categorías más cotizadas</h4>
					</div>

					<div class="panel-body">
						<div id="resultados"></div><!-- Carga los datos ajax -->
						<div class='outer_div_categorias' ></div><!-- Carga los datos ajax -->
					</div><!-- /.panel-body -->
				</div><!-- /.panel -->
			</div><!-- /.col-md-12 -->

		</div><!-- /.row -->
	</div>	<!--/.main-->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/VentanaCentrada.js"></script>
	<script type="text/javascript" src="js/reportes.js"></script>
	<script>

	</script>
</body>
</html>
