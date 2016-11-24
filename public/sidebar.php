	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		
		<ul class="nav menu" >
			<li class="<?php echo $active_inicio;?>"><a href="index.php"><i class='fa fa-home'></i> Inicio</a></li>
			<li class="<?php echo $active_cotizaciones;?>"><a href="cotizaciones.php"><i class='fa fa-shopping-cart'></i> Cotizaciones</a></li>
			<li class="<?php echo $active_clientes;?>"><a href="clientes.php"><i class='fa fa-users'></i> Clientes</a></li>
			<li class="<?php echo $active_productos;?>"><a href="productos.php"><i class='fa fa-barcode'></i> Productos</a></li>
			<li class="<?php echo $active_fabricantes;?>"><a href="fabricantes.php"><i class='fa fa-tags'></i>  Fabricantes</a></li>
			<li class="<?php echo $active_monedas;?>"><a href="monedas.php"><i class='fa fa-usd'></i>  Monedas</a></li>
			<li class="<?php echo $active_usuarios;?>"><a href="usuarios.php"><i class='fa fa-user'></i> Usuarios</a></li>
			<li class="<?php echo $active_empresa;?>"><a href="empresa.php"><i class='fa fa-cog'></i> Configuración</a></li>
			
		</ul>
		<?php  @is_valid();?>
	</div><!--/.sidebar-->