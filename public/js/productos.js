		$(document).ready(function(){
			load(1);
		});

		function load(page){
			var q= $("#q").val();
			var q2= $("#q2").val();
			var q3= $("#q3").val();
			parametros={"action":"ajax","q":q,"q2":q2,"q3":q3, "page":page};
			$.ajax({
				url:'./ajax/buscar_productos.php',
				data: parametros,
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="./img/ajax-loader.gif">');
			  },
				success:function(data){
					$(".outer_div").html(data);
					$('#loader').html('');

				}
			})
		}



			function eliminar (id)
		{
			var q= $("#q").val();
		if (confirm("Realmente deseas eliminar el producto")){
		$.ajax({
        type: "GET",
        url: "./ajax/buscar_productos.php",
        data: "id="+id,"q":q,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		load(1);
		}
			});
		}
		}


		function agregar_cotizacion(id_producto){
			var q= $("#q").val();
			var parametros={"q":q, "id_producto":id_producto};
			$.ajax({
				type: "GET",
				url: "./ajax/buscar_productos.php",
				data: parametros,
				 beforeSend: function(objeto){
					$("#resultados").html("Mensaje: Cargando...");
				  },
				success: function(datos){
				$("#resultados").html(datos);
				hide(".alert");
				load(1);
				}
			});


		}

		function hide(elemento){
			window.setTimeout(function() {
			$(elemento).fadeTo(500, 0).slideUp(500, function(){
			$(this).remove();});}, 5000);
		}
