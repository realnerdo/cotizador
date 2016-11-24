	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Agregar nuevo cliente</h4>
		  </div>
		  <div class="modal-body">
			<form class="form-horizontal" method="post" id="guardar_cliente" name="guardar_cliente">
			<div id="resultados_ajax"></div>
			  <div class="form-group">
				<label for="cliente" class="col-sm-4 control-label">Nombre del cliente</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="cliente" name="cliente" placeholder="" required>
				</div>
			  </div>
			  <div class="form-group">
				<label for="nombre_comercial" class="col-sm-4 control-label">Nombre comercial</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="nombre_comercial" name="nombre_comercial" placeholder="" >
				</div>
			  </div>
			  <div class="form-group">
				<label for="registro" class="col-sm-4 control-label">Nº Identificación</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="registro" name="registro" placeholder="" >
				</div>
			  </div>
			  <div class="form-group">
				<label for="giro" class="col-sm-4 control-label">Giro</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="giro" name="giro" placeholder="" >
				</div>
			  </div>
			  <div class="form-group">
				<label for="direccion" class="col-sm-4 control-label">Dirección</label>
				<div class="col-sm-8">
				 <input type="text" class="form-control" id="direccion" name="direccion" placeholder="" >
				</div>
			  </div>
			
			  
			  <div class="form-group">
				<label for="email" class="col-sm-4 control-label">Correo electrónico</label>
				<div class="col-sm-8">
					<input type="email" class="form-control" id="email" name="email" placeholder="" >
				</div>
				  
			  </div>
			  <div class="form-group">
				<label for="fijo" class="col-sm-4 control-label">Teléfono empresa</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="fijo" name="fijo" placeholder="" >
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