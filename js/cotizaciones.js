		$(document).ready(function(){
			load(1);
		});

		function load(page){
			var q= $("#q").val();
			var id_vendedor= $("#id_vendedor").val();
			var daterange= $("#daterange").val();
			var estado= $("#estado").val();
			var parametros ={"action":"ajax","page":page,"q":q,"id_vendedor":id_vendedor,"daterange":daterange,"estado":estado};
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/buscar_cotizacion.php',
				data: parametros,
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
		if (confirm("Realmente deseas eliminar la cotización")){	
		$.ajax({
        type: "GET",
        url: "./ajax/buscar_cotizacion.php",
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
		
		function descargar(id){
		 VentanaCentrada('./pdf/documentos/ver_cotizacion.php?id='+id,'Cotizacion','','1024','768','true');
	 	}
		function reporte(){
		var daterange=$("#daterange").val();
		var id_vendedor=$("#id_vendedor").val();
		var estado=$("#estado").val();
		var q=$("#q").val();
		 VentanaCentrada('./pdf/documentos/reporte_cotizacion.php?daterange='+daterange+"&id_vendedor="+id_vendedor+"&q="+q+"&estado="+estado,'Reporte cotizacion','','1024','768','true');
		}
