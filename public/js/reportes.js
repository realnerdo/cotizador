$(document).ready(function(){
    load_vendedores(1);
    load_productos(1);
    load_categorias(1);
});

function load_vendedores(page){
    var parametros ={"action":"vendedores","page":page};
    $("#loader").fadeIn('slow');
    $.ajax({
        url:'./ajax/buscar_reportes.php',
        data: parametros,
         beforeSend: function(objeto){
         $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
      },
        success:function(data){
            $(".outer_div_vendedores").html(data).fadeIn('slow');
            $('#loader').html('');

        }
    });
}

function load_productos(page){
    var parametros ={"action":"productos","page":page};
    $("#loader").fadeIn('slow');
    $.ajax({
        url:'./ajax/buscar_reportes.php',
        data: parametros,
         beforeSend: function(objeto){
         $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
      },
        success:function(data){
            $(".outer_div_productos").html(data).fadeIn('slow');
            $('#loader').html('');

        }
    });
}

function load_categorias(page){
    var parametros ={"action":"categorias","page":page};
    $("#loader").fadeIn('slow');
    $.ajax({
        url:'./ajax/buscar_reportes.php',
        data: parametros,
         beforeSend: function(objeto){
         $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
      },
        success:function(data){
            $(".outer_div_categorias").html(data).fadeIn('slow');
            $('#loader').html('');

        }
    });
}
