		$(document).ready(function(){
			load(1);
					
		});

		function load(page){
			var q= $("#q").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/buscar_clientes.php?action=ajax&page='+page+'&q='+q,
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
					
					
				}
			})
		}

	
		
			function eliminar (id)
		{
			var q= $("#q").val();
		if (confirm("Realmente deseas eliminar el cliente")){	
		$.ajax({
        type: "GET",
        url: "./ajax/buscar_clientes.php",
        data: "id="+id,"q":q,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		load(1);
		window.setTimeout(function() {
				$(".alert").fadeTo(500, 0).slideUp(500, function(){
				$(this).remove();
				
				});
					}, 5000); 
		}
			});
		}
		}

		function remove_alert(){
				window.setTimeout(function() {
					$(".alert").fadeTo(500, 0).slideUp(500, function(){
					$(this).remove();
				});
				}, 5000); 
			}
  
$( "#guardar_cliente" ).submit(function( event ) {
  $('#guardar_datos').attr("disabled", true);
  
 var parametros = $(this).serialize();
	 $.ajax({
			type: "POST",
			url: "ajax/nuevo_cliente.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados_ajax").html("Mensaje: Cargando...");
			  },
			success: function(datos){
			$("#resultados_ajax").html(datos);
			$('#guardar_datos').attr("disabled", false);
			load(1);
			remove_alert();
		  }
	});
  event.preventDefault();
})

$( "#guardar_contacto" ).submit(function( event ) {
  $('.guardar_datos').attr("disabled", true);
  
 var parametros = $(this).serialize();
	 $.ajax({
			type: "POST",
			url: "ajax/nuevo_contacto.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados_ajax_2").html("Mensaje: Cargando...");
			  },
			success: function(datos){
			$("#resultados_ajax_2").html(datos);
			$('.guardar_datos').attr("disabled", false);
			load(1);
			remove_alert();
		  }
	});
  event.preventDefault();
  
})

$( "#editar_contacto" ).submit(function( event ) {
  $('.actualizar_datos').attr("disabled", true);
  
 var parametros = $(this).serialize();
	 $.ajax({
			type: "POST",
			url: "ajax/editar_contacto.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados_ajax_3").html("Mensaje: Cargando...");
			  },
			success: function(datos){
			$("#resultados_ajax_3").html(datos);
			$('.actualizar_datos').attr("disabled", false);
			load(1);
			remove_alert();
		  }
	});
  event.preventDefault();
})

$( "#editar_cliente" ).submit(function( event ) {
  $('#actualizar_datos').attr("disabled", true);
  
 var parametros = $(this).serialize();
	 $.ajax({
			type: "POST",
			url: "ajax/editar_cliente.php",
			data: parametros,
			 beforeSend: function(objeto){
				$("#resultados_ajax2").html("Mensaje: Cargando...");
			  },
			success: function(datos){
			$("#resultados_ajax2").html(datos);
			$('#actualizar_datos').attr("disabled", false);
			load(1);
			remove_alert();
		  }
	});
  event.preventDefault();
})

	function obtener_datos(id){
			var cliente = $("#cliente"+id).val();
			var nombre_comercial = $("#nombre_comercial"+id).val();
			var nombre_producto = $("#nombre_producto"+id).val();
			var numero_identificacion = $("#numero_identificacion"+id).val();
			var giro = $("#giro"+id).val();
			var direccion = $("#direccion"+id).val();
			var web = $("#web"+id).val();
			var email = $("#email"+id).val();
			var movil = $("#movil"+id).val();
			var fijo = $("#fijo"+id).val();
			var contacto = $("#contacto"+id).val();
		
			$("#mod_id").val(id);
			$("#mod_cliente").val(cliente);
			$("#mod_nombre_comercial").val(nombre_comercial);
			$("#mod_registro").val(numero_identificacion);
			$("#mod_giro").val(giro);
			$("#mod_direccion").val(direccion);
			$("#mod_web").val(web);
			$("#mod_email").val(email);
			$("#mod_fijo").val(fijo);
			$("#mod_movil").val(movil);
			$("#mod_contacto").val(contacto);
			
			
		}      
	
		function eliminar_contacto(id_contact){
			var q= $("#q").val();
			if (confirm("Realmente deseas eliminar el contacto")){	
			
				$.ajax({
				type: "GET",
				url: "./ajax/buscar_clientes.php",
				data: "id_contact="+id_contact,"q":q,
				 beforeSend: function(objeto){
					$("#resultados").html("Mensaje: Cargando...");
				  },
				success: function(datos){
				$("#resultados").html(datos);
				load(1);
				window.setTimeout(function() {
						$(".alert").fadeTo(500, 0).slideUp(500, function(){
						$(this).remove();
						
						});
							}, 5000); 
				}
			});
			
			}
		}	