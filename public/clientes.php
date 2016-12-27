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
	$active_clientes="active";
	$active_contactos="";
	$active_monedas="";
	$active_reportes="";
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
	include("modal/registro_clientes.php");
	include("modal/registro_contactos.php");
	include("modal/editar_contactos.php");
	include("modal/editar_clientes.php");
	include("modal/importar_clientes.php");
	?>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><i class='fa fa-users'></i></a></li>
				<li class="active">Clientes</li>
			</ol>

		</div><!--/.row-->
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="btn-group pull-right">

						<div class="btn-group">
							<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-file"></i> Importar datos <span class="caret"></span></button>
							<ul class="dropdown-menu" role="menu">
							  <li><a href="#importar_modal" data-toggle="modal"><i class="fa fa-file-excel-o "></i> Hoja de cálculo</a></li>
							  <!-- <li><a href="dist/template/formato_importacion_productos.xlsx" ><i class="fa fa-download"></i> Descargar formato</a></li> -->
							</ul>
						  </div>
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
				</div>
			</div>
		</div><!-- /.row -->
	</div>	<!--/.main-->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/VentanaCentrada.js"></script>
	<script type="text/javascript" src="js/clientes.js"></script>
	<script>
		$('#agregar').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var cliente = button.data('cliente') // Extract info from data-* attributes
		  var id = button.data('id') // Extract info from data-* attributes
		  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		  var modal = $(this)
		  modal.find('.modal-title').text('Agregar contacto a: ' + cliente)
		  modal.find('.modal-body #guardar_contacto #id_client').val(id)
		})

		$('#edit').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var cliente = button.data('cliente') // Extract info from data-* attributes
		  var nombre_contact = button.data('nombre_contact') // Extract info from data-* attributes
		  var telefono_contact = button.data('telefono_contact') // Extract info from data-* attributes
		  var email_contact = button.data('email_contact') // Extract info from data-* attributes
		  var id_contact = button.data('id_contact') // Extract info from data-* attributes
		  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		  var modal = $(this)
		  modal.find('.modal-title').text('Editar contacto del cliente: ' + cliente)
		  modal.find('.modal-body #editar_contacto #id_contact').val(id_contact)
		  modal.find('.modal-body #editar_contacto #nombre_contact').val(nombre_contact)
		  modal.find('.modal-body #editar_contacto #telefono_contact').val(telefono_contact)
		  modal.find('.modal-body #editar_contacto #email_contact').val(email_contact)
		})

		$("#importar_datos" ).submit(function(event) {
			 $.ajax({
					type: "POST",
					url: "ajax/importar_clientes.php",
					data:  new FormData(this),
					contentType: false,
					cache: false,
					processData:false,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
						$(".importar_datos").html("<i class='fa fa-spinner fa-spin'></i> Importando...");
						$('.importar_datos').attr("disabled", true);
					  },
					success: function(datos){
					$("#resultados").html(datos);

					window.setTimeout(function() {
					$(".alert").fadeTo(500, 0).slideUp(500, function(){
					$(this).remove();});}, 5000);
					$('#importar_modal').modal('hide');
					$(".importar_datos").html("Importar datos");
					$('.importar_datos').attr("disabled", false);
					load(1);
				  }
			});
				event.preventDefault();


		});
	</script>
</body>
</html>
