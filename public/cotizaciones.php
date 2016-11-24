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
	$active_cotizaciones="active";
	$active_clientes="";
	$active_productos="";
	$active_fabricantes="";
	$active_usuarios="";
	$active_empresa="";
	$active_contactos="";	
	$active_monedas="";	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cotizador - Panel de Control</title>

<link href="css/bootstrap.min.css" rel="stylesheet">
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
	include("modal/enviar_email.php");
	?>
		

		
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main" >			
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><i class='fa fa-shopping-cart'></i> </a></li>
				<li class="active">Cotizaciones</li>
			</ol>
			
		</div><!--/.row-->
		
		<div class="row">
			
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="btn-group pull-right">
							<a  href="#" onclick="reporte();" class="btn btn-default"><span class="glyphicon glyphicon-print" ></span> Reporte</a> 
							<a  href="nueva_cotizacion.php" class="btn btn-info"><span class="glyphicon glyphicon-plus" ></span> Nueva Cotización</a>
						</div>
						<h4><i class='glyphicon glyphicon-search'></i> Buscar Cotizaciones</h4>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" role="form" id="datos_cotizacion">
							<div class="form-group row">
								<label for="daterange" class="col-md-1 control-label input-sm">Fecha</label>
								<div class="col-md-3">
									<input type="text" name="daterange" id="daterange" value="" class="form-control input-sm" readonly />
								</div>
								
								<div class="col-md-2">
									<select class="form-control input-sm" id="id_vendedor" onchange="load(1);">
										<option value="">Vendedor</option>
									<?php
										$sql_user=mysqli_query($con,"select * from users");
										while ($rw_user=mysqli_fetch_array($sql_user)){
											$vendedor=$rw_user['firstname']." ".$rw_user['lastname'];
											$id_vendedor=$rw_user['user_id'];
										?>
										<option value="<?php echo $id_vendedor;?>"><?php echo $vendedor;?></option>
										<?php
										}
									?>
									</select>
								</div>
								<div class="col-md-2">
									<select class="form-control input-sm" id="estado" onchange="load(1);">
										<option value="">Estado</option>
										<option value="0">Pendiente</option>
										<option value="1">Aceptada</option>
										<option value="2"> Rechazada</option>
									</select>
								</div>
								<label for="q" class="col-md-1 control-label input-sm">Atención:</label>
								<div class="col-md-3">
									<div class="input-group">
										<input type="text" class="form-control input-sm" id="q" placeholder="Atención ó Empresa" onkeyup='load(1);'>
										<span class="btn btn-default input-group-addon" onclick="load(1);"><i class="glyphicon glyphicon-search"></i></span>
									</div>	
								</div>		
										
								</div>
								
								<div  style="position:absolute;"></div>
									
								<div style="position: absolute; left: 50%;">
									<div id="loader" style="position: relative; left: -50%;">
									
									</div>
								</div>
						</form>
						<div id="resultados"></div><!-- Carga los datos ajax -->
						<div class='outer_div' ></div><!-- Carga los datos ajax -->
					</div>
						
					</div>
				</div>
			</div>
			
    

		</div><!-- /.row -->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/VentanaCentrada.js"></script>
	<script type="text/javascript" src="js/cotizaciones.js"></script>
	<!-- Include Required Prerequisites -->
	<script type="text/javascript" src="js/datepicker/moment.min.js"></script>
	<!-- Include Date Range Picker -->
	<script type="text/javascript" src="js/datepicker/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="css/daterangepicker.css" />
	<script type="text/javascript">
	$(function() {
		$('input[name="daterange"]').daterangepicker(
	{
		locale: {
		  format: 'DD/MM/YYYY'//Fecha en español
		},
		startDate: '<?php echo "01/".date("m/Y");?>',//Fecha Inicial por defecto es el primer dia del mes y año  actual
		endDate: '<?php echo date("d/m/Y");?>'//Fecha Final por defecto es la fecha de hoy
	}
	
	);
	
	$('#daterange').on('apply.daterangepicker', function(ev, picker) {
	  //do something, enviar los datos via ajax con la funcion load()
	  load(1);
	});

	});
	</script>
	<script>
		$('#myModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var email = button.data('email') // Extract info from data-* attributes
		  var quote_id = button.data('number') // Extract info from data-* attributes
		  var modal = $(this)
		  modal.find('.modal-body #sendto').val(email)
		  modal.find('.modal-body #quote_id').val(quote_id)
		})
	</script>
	<script>
		$( "#enviar_cotizacion" ).submit(function( event ) {
		  $('#guardar_datos').attr("disabled", true);
		  
		 var parametros = $(this).serialize();
			 $.ajax({
					type: "POST",
					url: "./pdf/documentos/mail_cotizacion.php",
					data: parametros,
					 beforeSend: function(objeto){
						$(".resultados_ajax").html("Mensaje: Cargando...");
					  },
					success: function(datos){
					$(".resultados_ajax").html(datos);
					$('#guardar_datos').attr("disabled", false);
					
				  }
			});
		  event.preventDefault();
		})
	</script>
	
	</body>
</html>
