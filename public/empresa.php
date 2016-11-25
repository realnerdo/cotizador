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
	$active_clientes="";
	$active_productos="";
	$active_fabricantes="";
	$active_usuarios="";
	$active_empresa	="active";
	$active_contactos="";
	$active_monedas="";
	$active_reportes="";
	if (isset($_POST['guardar']))
		{
			include("./libraries/empresa.php");
		}
	/*Datos de la empresa*/
	$sql_empresa=mysqli_query($con,"SELECT * FROM empresa where id_empresa=1");
	$rw_empresa=mysqli_fetch_array($sql_empresa);
	$iva=$rw_empresa["iva"];
	$impuesto=($iva/100) + 1;
	$id_moneda=$rw_empresa["id_moneda"];
	$nrc=$rw_empresa["nrc"];
	$nombre_empresa=$rw_empresa["nombre"];
	$propietario=$rw_empresa["propietario"];
	$giro=$rw_empresa["giro"];
	$direccion=$rw_empresa["direccion"];
	$telefono=$rw_empresa["telefono"];
	$email=$rw_empresa["email"];
	$logo_url=$rw_empresa["logo_url"];
	/*Fin datos empresa*/
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
<link href="css/jasny-bootstrap.min.css" rel="stylesheet">
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
				<li><a href="#"><i class='fa fa-cog'></i> </a></li>
				<li class="active">Configuración</li>
			</ol>

		</div><!--/.row-->

		<div class="row">

			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4><i class='glyphicon glyphicon-cog'></i> Configurar datos de la empresa</h4>
					</div>
					<div class="panel-body">
						<?php
										if (isset($errors)){
											?>
										<div class="alert alert-danger">
											<button type="button" class="close" data-dismiss="alert">&times;</button>
											<strong>Error! </strong>
											<?php
											foreach ($errors as $error){
													echo $error;
												}
											?>
										</div>
											<?php
										}
									?>
									<?php
										if (isset($messages)){
											?>
										<div class="alert alert-success">
											<button type="button" class="close" data-dismiss="alert">&times;</button>
											<strong>Aviso! </strong>
											<?php
											foreach ($messages as $message){
													echo $message;
												}
											?>
										</div>
											<?php
										}
									?>
						<form  role="form" enctype="multipart/form-data" method="post" >
							<div class="row">
								<div class="col-xs-12 col-md-6 form-group">
									<label>Nombre o Razón Social</label>
									<input type="text" name="nombre" id="nombre"  class="form-control" maxlength="150" value="<?php echo $nombre_empresa;?>" required>
								</div>
								<div class="col-xs-12 col-md-6 form-group">
									<label>Actividad económica</label>
									<input class="form-control" name="giro" id="giro" type="text" value="<?php echo $giro;?>" maxlength="150" required/>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-md-6 form-group">
									<label>Propietario</label>
									<input class="form-control" name="propietario" id="propietario"  type="text" value="<?php echo $propietario;?>" maxlength="60" required/>
								</div>
								<div class="col-xs-12 col-md-6 form-group">
									<label>Nº de Registro</label>
									<input class="form-control" name="nrc" id="nrc" type="text" value="<?php echo $nrc?>" maxlength="15" required/>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-md-6 form-group">
									<label>Dirección</label>
									<textarea class="form-control" name="direccion" id="direccion" maxlength='255' required><?php echo $direccion;?></textarea>
								</div>
								<div class="col-xs-12 col-md-6 form-group">
									<label>IVA %</label>
									<input class="form-control" name="iva" id="iva" type="text" value="<?php echo $iva;?>" maxlength='2' required/>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-md-6 form-group">
									<label>Teléfono</label>
									<input class="form-control" name="telefono" id="telefono" type="text" value="<?php echo $telefono;?>" maxlength='30' required/>
								</div>
								<div class="col-xs-12 col-md-6 form-group">
									<label>Selecciona moneda</label>
									<select name="moneda" id="moneda" class='form-control'>
										<?php
											$sql_monedas=mysqli_query($con,"select id, name from currencies order by id");
											while ($rw=mysqli_fetch_array($sql_monedas)){
												?>
												<option value="<?php echo $rw['id']?>" <?php if($id_moneda==$rw['id']){echo "selected";}?>><?php echo $rw['name']?></option>
												<?php
											}
										?>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-md-6 form-group">
									<label>Correo electrónico</label>
									<input class="form-control" name="email" id="email" type="email" value="<?php echo $email;?>" maxlength='64' required/>
								</div>
								<div class="col-xs-12 col-md-6 form-group">
									<label style="display:block;">Logo</label>
									<div class="fileinput fileinput-new" data-provides="fileinput">
									  <div class="fileinput-new thumbnail" style="width: 100%;">
										<img src="<?php echo $logo_url;?>" />
									  </div>
									  <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
									  <div>
										<span class="btn btn-default btn-file"><span class="fileinput-new">Selecciona una imagen</span><span class="fileinput-exists">Cambiar</span><input type="file" name="imagefile"></span>
										<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Eliminar</a>
									  </div>
									</div>

								</div>
							</div>
							<div class="row">
								<div class="col-xs-12 col-md-6 form-group">

								</div>
								<div class="col-xs-12 col-md-6 form-group">

									<button type="submit" class="btn btn-primary" name="guardar">Guardar datos</button>
								</div>
							</div>
						</form>

					</div>
				</div>
			</div>
		</div><!-- /.row -->
	</div>	<!--/.main-->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jasny-bootstrap.min.js"></script>
	<script src="js/jquery.mockjax.min.js"></script>

	</body>
</html>
