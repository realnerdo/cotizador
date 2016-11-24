 
<form method="post" id="importar_datos" name="importar_datos" enctype="multipart/form-data">
<!-- Modal -->
<div class="modal fade " id="importar_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class='fa fa-file'></i> Importar datos</h4>
      </div>
      <div class="modal-body" >
		<div class="form-group">
			<label for="periodo_imp" class="control-label">Selecciona archivo</label>
			<input type="file" class='form-control' name="archivo" id="archivo" required>
			<p class="help-block">Carga tus productos desde una hoja de cálculo, ya sea en formato .xlsx ó .ods. Para realizar la importación de datos, asegúrate de usar el formato correcto. Puedes descargarlo aquí: <a href="dist/template/formato_importacion_productos.xlsx">Formato de importación de productos</a>  </p>
		</div>	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary importar_datos"> Importar datos</button>
      </div>
    </div>
  </div>
</div>
</form>