<form class="form-horizontal" method="post" id="editar_item" name="editar_item">
	<!-- Modal -->
	<div class="modal fade " id="editModalItem" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class='fa fa-edit'></i> Editar </h4>
		  </div>
		  <div class="modal-body">
			<div class="row">
				<div class="col-md-8">
					<label for="codigo_item" class="control-label">Código</label>
					<input type="text" class="form-control" id="codigo_item" name="codigo_item" placeholder="" disabled required>
					<input type="hidden" class="form-control" id="id_tmp" name="id_tmp" >
				</div>
				<div class="col-md-4">
					<label for="cantidad_item" class="control-label">Cantidad</label>
					<input type="text" class="form-control" id="cantidad_item" name="cantidad_item" placeholder="" required>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<label for="descripcion_item" class="control-label">Descripción</label>
					<input type="text" class="form-control" name="descripcion_item" id="descripcion_item" disabled>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<label for="precio_item" class="control-label">Precio</label>
					<div class="input-group">
						<div class="input-group-addon"><i class="fa fa-usd"></i></div>
						<input type="text" class="form-control" name="precio_item" id="precio_item" required>
					</div>
				</div>
				<div class="col-md-6">
					<label for="descuento_item" class="control-label">Descuento</label>
					<br>
					<div class="input-group" id="descuento">
						<input type="text" name="desbloquear_descuento" class="form-control" placeholder="Código de aprobación" id="desbloquear_descuento_codigo">
						<button type="button" class="btn btn-primary" id="desbloquear_descuento">Añadir descuento</button>
					</div>
				</div>

			</div>
		</div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" class="btn btn-primary">Actualizar datos</button>
		  </div>
		</div>
	  </div>
	</div>
</form>
