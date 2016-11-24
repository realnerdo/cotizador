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
	$active_clientes="";
	$active_empresa="";	
	$active_contactos="";	
	$active_monedas="";
	
	$numero_cotizacion=intval($_GET['id']);	
	$_SESSION['numero_cotizacion']=$numero_cotizacion;
	$sql_estimates=mysqli_query($con,"SELECT * FROM estimates, clients WHERE estimates.id_cliente=clients.id and estimates.numero_cotizacion='$numero_cotizacion'");
	$num_row=mysqli_num_rows($sql_estimates);
	$row=mysqli_fetch_array($sql_estimates);
	
	$cliente=$row['nombre_cliente'];
	$contacto=$row['contacto'];
	$movil=$row['movil'];
	$nombre_comercial=$row['nombre_comercial'];
	$fijo=$row['fijo'];
	$email=$row['email'];
	$id_cliente=$row['id_cliente'];
	$id_cotizacion=$row['id_cotizacion'];
	$validez=$row['validez'];
	$condiciones=$row['condiciones'];
	$entrega=$row['entrega'];
	$notas=$row['notas'];
	$status=$row['status'];
	$id_moneda=$row['currency_id'];
	$id_contact=$row['id_contact'];
	$email_contact="";
	$telefono_contact="";
	
	if (!isset($_GET['id']) or $num_row==0){
		header("location: cotizaciones.php");
		exit;
	}
	
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
	include("modal/buscar_productos.php");
	include("modal/editar_item.php");
	?>
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">			
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
						<h4><i class='glyphicon glyphicon-edit'></i> Editar Cotización</h4>
					</div>
					<div class="panel-body">
					
						<form class="form-horizontal" role="form" id="datos_cotizacion">
							<div class="form-group row">
							  <label for="nombre_cliente" class="col-md-2 control-label">Selecciona el cliente:</label>
							  <div class="col-md-3">
								  <input type="text" class="form-control input-sm" id="nombre_cliente" placeholder="" required value="<?php echo $cliente;?>">
								  <input id="id_cliente" type='hidden' value="<?php echo $id_cliente;?>">	
							 </div>
							  <label for="atencion" class="col-md-1 control-label">Atención:</label>
								<div class="col-md-2">
									<select class='form-control input-sm' id="atencion" name="atencion" onchange="update_cotizacion(8,this.value);">
										<option value="">Selecciona</option>
										<?php 
											$sql=mysqli_query($con,"select * from contacts where id_client='".$id_cliente."'");
											while($row=mysqli_fetch_array($sql)){
												?>
												<option value="<?php echo $row['id_contact'];?>" data-telefono="<?php echo $row['telefono_contact'];?>" data-email="<?php echo $row['email_contact'];?>" <?php if ($row['id_contact']==$id_contact){echo "selected";$telefono_contact=$row['telefono_contact'];$email_contact=$row['email_contact'];} ?>><?php echo $row['nombre_contact'];?></option>
												<?php
											}
										?>
										
									</select>
								</div>
								<div class='row'>
								<div class="col-xs-2">
									<input type="text" class="form-control input-sm" id="tel1" placeholder="" value="<?php echo $telefono_contact;?>" readonly>
								 </div>
								 <div class="col-xs-2">
									<input type="text" class="form-control input-sm" id="email_contact" placeholder="" value="<?php echo $email_contact;?>" readonly>
								 </div>
								
								</div>
							</div>
							<div class="form-group row">
								<label for="empresa" class="col-md-2 control-label">Empresa:</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" id="empresa" placeholder="" value="<?php echo $nombre_comercial;?>" readonly>
								</div>
								<label for="tel2" class="col-md-1 control-label">Teléfono:</label>
								<div class="col-md-2">
									<input type="text" class="form-control input-sm" id="tel2" placeholder="" value="<?php echo $fijo;?>" readonly>
								</div>
								<label for="email" class="col-md-1 control-label">Email:</label>
								<div class="col-md-3">
									<input type="email" class="form-control input-sm" id="email" placeholder="" value="<?php echo $email;?>" readonly>
								</div>
							</div>
							<div class="form-group row">
								<label for="condiciones" class="col-md-2 control-label">Condiciones de pago:</label>
								<div class="col-md-3">
									<select class="form-control input-sm" id="condiciones" onchange="update_cotizacion(2,this.value);">
										<option value='Contado' <?php if ($condiciones=="Contado"){echo "selected";}?>>Contado</option>
										<option value='Crédito 30 días' <?php if ($condiciones=="Crédito 30 días"){echo "selected";}?>>Crédito 30 días</option>
										<option value='Crédito 45 días' <?php if ($condiciones=="Crédito 45 días"){echo "selected";}?>>Crédito 45 días</option>
										<option value='Crédito 60 días' <?php if ($condiciones=="Crédito 60 días"){echo "selected";}?>>Crédito 60 días</option>
										<option value='Crédito 90 días' <?php if ($condiciones=="Crédito 90 días"){echo "selected";}?>>Crédito 90 días</option>
									</select>
								</div>
								<label for="validez" class="col-md-1 control-label">Validez:</label>
								<div class="col-md-2">
									<select class="form-control input-sm" id="validez" onchange="update_cotizacion(3,this.value);">
										<option value='5 días' <?php if ($validez=="5 días"){echo "selected";}?>>5 días</option>
										<option value='10 días' <?php if ($validez=="10 días"){echo "selected";}?>>10 días</option>
										<option value='15 días' <?php if ($validez=="15 días"){echo "selected";}?>>15 días</option>
										<option value='30 días' <?php if ($validez=="30 días"){echo "selected";}?>>30 días</option>
										<option value='60 días' <?php if ($validez=="60 días"){echo "selected";}?>>60 días</option>
									</select>
								</div>
								<label for="entrega" class="col-md-1 control-label">Tiempo:</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" id="entrega" placeholder="Tiempo de entrega" value="<?php echo $entrega;?>" onblur="update_cotizacion(4,this.value);">
								</div>
							</div>
							<div class="form-group row">
								<label for="condiciones" class="col-md-2 control-label">Nota:</label>
								<div class="col-md-3">
									<input type="text" class="form-control input-sm" id="notas" placeholder="Nota" maxlength='255' value="<?php echo $notas?>" onblur="update_cotizacion(5,this.value);">
								</div>
								<label for="validez" class="col-md-1 control-label">Estado:</label>
								<div class="col-md-2">
									<select class="form-control input-sm" id="status" required onchange="update_cotizacion(6,this.value);">
										<option value='0' <?php if($status==0){echo "selected";}?>>Pendiente</option>
										<option value='1' <?php if($status==1){echo "selected";}?>>Aceptada</option>
										<option value='2' <?php if($status==2){echo "selected";}?>>Rechazada</option>
									</select>
								</div>	
								
								<label for="moneda" class="col-md-1 control-label">Moneda:</label>
								<div class="col-md-3">
									<select name="moneda" id="moneda" class='form-control input-sm' onchange="update_cotizacion(7,this.value);">
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
								<div class="col-md-12">
									<div class="pull-right">
										<button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
										 <span class="glyphicon glyphicon-plus"></span> Agregar productos
										</button>
										<button type="button" class="btn btn-default" onclick="ver_cotizacion('<?php echo $id_cotizacion;?>');">
										  <span class="glyphicon glyphicon-print"></span> Imprimir
										</button>
									</div>	
								</div>
							</div>
						</form>	
						<div class="row">
							<div id="resultados"  class="col-md-12" style="margin-top:10px"></div><!-- Carga los datos ajax -->
						</div>
					</div>
				</div>
			</div>
		</div><!-- /.row -->
	</div>	<!--/.main-->
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/VentanaCentrada.js"></script>
	<script type="text/javascript" src="js/editar_cotizacion.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script>
		$(document).ready(function(){
			$("#resultados").load('./ajax/editar_cotizador.php');
		});
	</script>
	</body>
</html>
