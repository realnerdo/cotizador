	<!-- Modal -->
	<div class="modal fade " id="addModalProduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nuevo producto</h4>
		  </div>
		  <div class="modal-body">

			<form class="form-horizontal" method="post" id="guardar_producto" name="guardar_producto" enctype="multipart/form-data">

			<div class="row">
				<div class="col-md-6">
					<label for="codigo" class="control-label">SKU</label>
					<input type="text" class="form-control" id="codigo" name="codigo" placeholder="" required>
				</div>
				<div class="col-md-6">
					<label for="foto_producto" class="control-label">Foto</label>
					<input type="file" name="foto_producto" id="foto_producto">
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<label for="nombre" class="control-label">Nombre o descripción del producto</label>
					<textarea  id="nombre" name="nombre"></textarea >
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<label for="marca" class="control-label">Marca</label>
					<select class="form-control" id="marca" name="marca">
					<option value="">-- Selecciona --</option>
					<?php
					$sql_fabricante=mysqli_query($con,"select * from ctlg_categorias where tipo='brand' order by titulo");
					while ($rw_fab=mysqli_fetch_array($sql_fabricante)){
						?>
						<option value="<?php echo $rw_fab['idCat'];?>"><?php echo $rw_fab['titulo'];?></option>
						<?php
					}
					?>
				  </select>
				</div>
				<div class="col-md-6">
					<label for="precio" class="control-label">Precio</label>
					<input type="text" class="form-control" id="precio" name="precio" placeholder="" required pattern="^[0-9]{1,11}(\.[0-9]{0,2})?$" title="Ingresa sólo números con 0 ó 2 decimales" maxlength="13">
				</div>
			</div>

		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" class="btn btn-primary" id="guardar_datos">Guardar datos</button>
		  </div>
		  </form>
		</div>
	  </div>
	</div>
