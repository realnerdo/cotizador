$(document).ready(function(){
    load(1);
});

function load(page){
    var parametros ={"action":"ajax","page":page};
    $("#loader").fadeIn('slow');
    $.ajax({
        url:'./ajax/buscar_correos.php',
        data: parametros,
         beforeSend: function(objeto){
         $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
      },
        success:function(data){
            $(".outer_div").html(data).fadeIn('slow');
            $('#loader').html('');

        }
    });
}
