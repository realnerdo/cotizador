		$(document).ready(function(){
			load(1);
		});

		function load(page){
			var q= $("#q").val();
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'./ajax/buscar_monedas.php?action=ajax&page='+page+'&q='+q,
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
		if (confirm("Realmente deseas eliminar la moneda")){	
		$.ajax({
        type: "GET",
        url: "./ajax/buscar_monedas.php",
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
		
		$('#myModal2').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var nombre_moneda = button.data('nombre')
		  var simbolo = button.data('simbolo')
		  var precision = button.data('precision')
		  var millar = button.data('millar')
		  var decimal = button.data('decimal')
		  var codigo = button.data('codigo') 
		  var id = button.data('id') 
		  var modal = $(this)
		   modal.find('.modal-body #editar_moneda #nombre').val(nombre_moneda)
		   modal.find('.modal-body #editar_moneda #simbolo').val(simbolo)
		   modal.find('.modal-body #editar_moneda #precision').val(precision)
		   modal.find('.modal-body #editar_moneda #millar').val(millar)
		   modal.find('.modal-body #editar_moneda #decimal').val(decimal)
		   modal.find('.modal-body #editar_moneda #codigo').val(codigo)
		   modal.find('.modal-body #editar_moneda #mod_id').val(id)
		   
		   $(".alert").hide();
		})
				
		
		

