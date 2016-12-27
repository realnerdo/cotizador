<?php

/* Connect To Database*/
require_once ("../config/db.php");//Contiene las variables de configuracion para conectar a la base de datos
require_once ("../config/conexion.php");//Contiene funcion que conecta a la base de datos

if(isset($_POST['codigo_desbloqueo'])){
    $codigo = $_POST['codigo_desbloqueo'];

    if($codigo != ''){
        $sql = "SELECT codigo_descuento FROM users WHERE codigo_descuento = '$codigo'";
        $query = mysqli_query($con, $sql);
        $num_rows = mysqli_num_rows($query);

        if($num_rows > 0){
            ?>
            <div class="input-group-addon"><i class="fa fa-percent"></i></div>
            <input type="text" class="form-control" name="descuento_item" id="descuento_item" >
            <?php
        }else{
            ?>
            <input type="text" name="desbloquear_descuento" class="form-control" placeholder="Código de aprobación" id="desbloquear_descuento_codigo">
            <button type="button" class="btn btn-primary" id="desbloquear_descuento">Añadir descuento</button>
            <?php
        }
    }

}
?>
