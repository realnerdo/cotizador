	<!-- Modal -->
	<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class='glyphicon glyphicon-edit'></i> Editar cliente</h4>
		  </div>
		  <div class="modal-body">
			<form class="form-horizontal" method="post" id="editar_cliente" name="editar_cliente">
			<div id="resultados_ajax2"></div>
			  <div class="form-group">
				<label for="mod_cliente" class="col-sm-4 control-label">Nombre del cliente</label>
				<div class="col-sm-8">
				 	
				  <input type="text" class="form-control" id="mod_cliente" name="mod_cliente"  required>
					<input type="hidden" id="mod_id" name="mod_id">
				</div>
			  </div>
			  <div class="form-group">
				<label for="mod_nombre_comercial" class="col-sm-4 control-label">Nombre comercial</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_nombre_comercial" name="mod_nombre_comercial" placeholder="" >
				</div>
			  </div>
			  <div class="form-group">
				<label for="mod_registro" class="col-sm-4 control-label">Nº Identificación</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_registro" name="mod_registro" placeholder="" >
				</div>
			  </div>
			  <div class="form-group">
				<label for="mod_giro" class="col-sm-4 control-label">Giro</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" id="mod_giro" name="mod_giro" placeholder="" >
				</div>
			  </div>
			  <div class="form-group">
				<label for="mod_direccion" class="col-sm-4 control-label">Dirección</label>
				<div class="col-sm-8">
				 <input type="text" class="form-control" id="mod_direccion" name="mod_direccion" placeholder="" >
				</div>
			  </div>
			 
			  
			  <div class="form-group">
				<label for="mod_email" class="col-sm-4 control-label">Correo electrónico</label>
				<div class="col-sm-8">
					<input type="email" class="form-control" id="mod_email" name="mod_email" placeholder="" >
				</div>
			  </div>
			  <div class="form-group">
				<label for="mod_fijo" class="col-sm-4 control-label">Teléfono empresa</label>
				<div class="col-sm-8">
				  <input type="text" class="form-control" id="mod_fijo" name="mod_fijo" placeholder="" >
				</div>
			  </div>
			  
			  
			  
			 
			 
			
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			<button type="submit" class="btn btn-primary" id="actualizar_datos">Actualizar datos</button>
		  </div>
		  </form>
		</div>
	  </div>
	</div>