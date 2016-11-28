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
	$active_productos="active";
	$active_fabricantes="";
	$active_usuarios="";
	$active_empresa="";
	$active_clientes="";
	$active_contactos="";
	$active_monedas="";
	$active_reportes="";
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
<!-- include summernote css/js-->
<link href="dist/summernote.css" rel="stylesheet">
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
	<?php
	include("navbar.php");
	include("sidebar.php");
	include("modal/registro_productos.php");
	include("modal/editar_productos.php");
	include("modal/importar_productos.php");
	?>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#"><i class='fa fa-barcode'></i></a></li>
				<li class="active">Productos</li>
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
							  <li><a href="dist/template/formato_importacion_productos.xlsx" ><i class="fa fa-download"></i> Descargar formato</a></li>
							</ul>
						  </div>
						  <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addModalProduct"><i class="fa fa-plus"></i> Nuevo producto</button>
						</div>

						<h4><i class='glyphicon glyphicon-search'></i> Buscar Productos</h4>
					</div>
					<div class="panel-body">
						<form class="form-horizontal" role="form" id="datos_cotizacion">
							<div class="row" style="margin-bottom:10px">
								<div class="col-md-3">
									<input type="text" class="form-control" id="q" placeholder="Buscar por el código" onkeyup='load(1);'>
								</div>
								<div class="col-md-4">
									<input type="text" class="form-control" id="q2" placeholder="Buscar por el nombre" onkeyup='load(1);'>
								</div>
								<div class="col-md-4">
									<div class="input-group">
										<select class="form-control" id="q3" onchange="load(1);">
											<option value="">Selecciona fabricante</option>
											<?php
												$query=mysqli_query($con,"select * from manufacturers order by nombre_marca");
												while($rw=mysqli_fetch_array($query)){
													?>
													<option value="<?php echo $rw['id_marca'];?>"><?php echo $rw['nombre_marca'];?></option>
													<?php
												}
											?>
										</select>
										<span class="btn btn-default input-group-addon" onclick="load(1);"><i class="fa fa-search"></i></span>
									</div>
								</div>
								<div class="col-md-1">
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
	<script type="text/javascript" src="js/productos.js"></script>
	</body>
</html>
<script>

var guardar_producto_form = $( "#guardar_producto" );
guardar_producto_form.submit(function( event ) {
  $('#guardar_datos').attr("disabled", true);

  var fd = new FormData();
  var file_data = $('#foto_producto')[0].files;

	for(var i = 0;i<file_data.length;i++){
		fd.append("foto_"+i, file_data[i]);
	}

	var other_data = guardar_producto_form.serializeArray();

	$.each(other_data,function(key,input){
        fd.append(input.name,input.value);
    });

	 $.ajax({
			type: "POST",
			url: "ajax/nuevo_producto.php",
			data: fd,
			contentType: false,
        	processData: false,
			 beforeSend: function(objeto){
				$("#resultados").html("Mensaje: Cargando...");
			  },
			success: function(datos){
			$("#resultados").html(datos);
			$('#guardar_datos').attr("disabled", false);
			$("#addModalProduct").modal("hide");
			hide(".alert");//Funcion oculta los elementos con la clase alert
			load(1);
		  }
	});
  event.preventDefault();
});

var editar_producto_form = $( "#editar_producto" );
editar_producto_form.submit(function( event ) {
  $('#actualizar_datos').attr("disabled", true);

  var fd = new FormData();
  var file_data = $('#foto_producto_e')[0].files;

  for(var i = 0;i<file_data.length;i++){
	  fd.append("foto_"+i, file_data[i]);
  }

  var other_data = editar_producto_form.serializeArray();

  $.each(other_data,function(key,input){
		fd.append(input.name,input.value);
	});

	 $.ajax({
			type: "POST",
			url: "ajax/editar_producto.php",
			data: fd,
			contentType: false,
        	processData: false,
			 beforeSend: function(objeto){
				$("#resultados").html("Mensaje: Cargando...");
			  },
			success: function(datos){
			$("#resultados").html(datos);
			$('#actualizar_datos').attr("disabled", false);
			$("#editModalProduct").modal("hide");
			hide(".alert");//Funcion oculta los elementos con la clase alert
			load(1);
		  }
	});
  event.preventDefault();
})

	function obtener_datos(id){
			var codigo_producto = $("#codigo_producto"+id).val();
			// var modelo_producto = $("#modelo_producto"+id).val();
			var nombre_producto = $("#nombre_producto"+id).val();
			var fabricante = $("#fabricante"+id).val();
			var estado = $("#estado"+id).val();
			var precio_producto = $("#precio_producto"+id).val();
			var descripcion = $("#descripcion"+id).html();

			$("#mod_id").val(id);
			$("#mod_codigo").val(codigo_producto);
			// $("#mod_modelo").val(modelo_producto);
			$("#mod_nombre").val(nombre_producto);
			$("select#mod_fabricante option")
			.each(function() { this.selected = (this.text == fabricante); });
			$("select#mod_estado option")
			.each(function() { this.selected = (this.text == estado); });
			$("#mod_precio").val(precio_producto);

			$('#mod_nombre').summernote({
			  toolbar: [
				// [groupName, [list of button]]
				['style', ['bold', 'italic', 'underline']],

				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['link',['linkDialogShow', 'unlink']],


			  ],height: 100,
			});
			$('#mod_nombre').summernote('code', descripcion);
		}
</script>
<script src="dist/summernote.min.js"></script>
<script>
$(document).ready(function() {
$('#nombre').summernote({
  toolbar: [
    // [groupName, [list of button]]
    ['style', ['bold', 'italic', 'underline']],

    ['fontsize', ['fontsize']],
    ['color', ['color']],
    ['para', ['ul', 'ol', 'paragraph']],
	['link',['linkDialogShow', 'unlink']],


  ],height: 100,
});


});
</script>
<script>
$("#importar_datos" ).submit(function(event) {
	 $.ajax({
			type: "POST",
			url: "ajax/importar_productos.php",
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
