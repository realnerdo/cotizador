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
	$active_inicio="active";
	$active_cotizaciones="";
	$active_productos="";
	$active_fabricantes="";
	$active_monedas="";
	$active_usuarios="";
	$active_empresa="";	
	$active_clientes="";
	$active_contactos="";
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
				<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
				<li class="active">Inicio</li>
			</ol>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Panel de Control</h1>
			</div>
		</div><!--/.row-->
		
		<div class="row">
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-blue panel-widget ">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<i class='fa fa-shopping-cart fa-4x'></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo counter('estimates');?></div>
							<div class="text-muted">Cotizaciones</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-orange panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<i class='fa fa-line-chart fa-4x'></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo counter('products');?></div>
							<div class="text-muted">Productos</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-teal panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<i class='fa fa-tags fa-4x'></i>
							
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo counter('manufacturers');?></div>
							<div class="text-muted">Fabricantes</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-red panel-widget">
					<div class="row no-padding">
						<div class="col-sm-3 col-lg-5 widget-left">
							<i class='fa fa-users fa-4x'></i>
						</div>
						<div class="col-sm-9 col-lg-7 widget-right">
							<div class="large"><?php echo counter('clients');?></div>
							<div class="text-muted">Clientes</div>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
		
		
		
		<div class="row">
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Nuevas cotizaciones</h4>
						<?php
						$new_cot=counterNew('estimates');
						$cot=counter('estimates');
						$percent_ct=@($new_cot / $cot) * 100;
						$percent_ct=intval($percent_ct);
						?>
						<div class="easypiechart" id="easypiechart-blue" data-percent="<?php echo $percent_ct;?>" ><span class="percent"><?php echo $percent_ct;?>%</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Nuevos productos </h4>
						<?php
						$new_cot=counterNew('products');
						$cot=counter('products');
						$percent_ct=@($new_cot / $cot) * 100;
						$percent_ct=intval($percent_ct);
						?>
						<div class="easypiechart" id="easypiechart-orange" data-percent="<?php echo $percent_ct;?>" ><span class="percent"><?php echo $percent_ct;?>%</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Nuevos fabricantes</h4>
						<?php
						$new_cot=counterNew('manufacturers');
						$cot=counter('manufacturers');
						$percent_ct=@($new_cot / $cot) * 100;
						$percent_ct=intval($percent_ct);
						?>
						<div class="easypiechart" id="easypiechart-teal" data-percent="<?php echo $percent_ct;?>" ><span class="percent"><?php echo $percent_ct;?>%</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-md-6 col-lg-3">
				<div class="panel panel-default">
					<div class="panel-body easypiechart-panel">
						<h4>Nuevos clientes</h4>
						<?php
						$new_cot=counterNew('clients');
						$cot=counter('clients');
						$percent_ct=@($new_cot / $cot) * 100;
						$percent_ct=intval($percent_ct);
						?>
						<div class="easypiechart" id="easypiechart-red" data-percent="<?php echo $percent_ct;?>" ><span class="percent"><?php echo $percent_ct;?>%</span>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.row-->
								
		
	</div>	<!--/.main-->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	</body>
</html>
