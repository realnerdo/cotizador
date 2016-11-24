<?php
	/*-------------------------
	Autor: Obed Alvarado
	Web: obedalvarado.pw
	Mail: info@obedalvarado.pw
	---------------------------*/
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: ../../login.php");
		exit;
        }
	/* Connect To Database*/
	include("../../config/db.php");
	include("../../config/conexion.php");
	$session_id= session_id();
	$sql_count=mysqli_query($con,"select * from tmp_estimate where session_id='".$session_id."'");
	$count=mysqli_num_rows($sql_count);
	if ($count==0)
	{
	echo "<script>alert('No hay productos agregados a la cotizacion')</script>";
	echo "<script>window.close();</script>";
	exit;
	}
	$user_id=$_SESSION['user_id'];
	require_once(dirname(__FILE__).'/../html2pdf.class.php');
		
	//Variables por GET
	$id_cliente=intval($_GET['id_cliente']);
	$condiciones=mysqli_real_escape_string($con,(strip_tags($_GET["condiciones"],ENT_QUOTES)));
	$validez=mysqli_real_escape_string($con,(strip_tags($_GET["validez"],ENT_QUOTES)));
	$entrega=mysqli_real_escape_string($con,(strip_tags($_GET["entrega"],ENT_QUOTES)));
	$notas=mysqli_real_escape_string($con,(strip_tags($_GET["notas"],ENT_QUOTES)));
	$id_moneda=intval($_GET['moneda']);
	$id_contact=intval($_GET['id_contacto']);
	//Fin de variables por GET
	/*Datos cliente*/
	$sql_cliente=mysqli_query($con,"select * from clients where id='$id_cliente'");
	$rw_cliente=mysqli_fetch_array($sql_cliente);
	$atencion=$rw_cliente['contacto'];
	$tel1=$rw_cliente['movil'];
	$tel2=$rw_cliente['fijo'];
	$empresa=$rw_cliente['nombre_comercial'];
	$email=$rw_cliente['email'];
	/* Fin Datos cliente*/
	//SQL contacto
	$sql_contacto=mysqli_query($con,"select * from contacts where id_contact='".$id_contact."'");
	$rw_contacto=mysqli_fetch_array($sql_contacto);
	$nombre_contact	= $rw_contacto['nombre_contact'];
	$telefono_contact	= $rw_contacto['telefono_contact'];
	$email_contact	= $rw_contacto['email_contact'];
	//Fin SQL contacto
	/*Datos usuario*/
	$sql_user=mysqli_query($con,"select * from users where user_id='$user_id'");
	$rw_user=mysqli_fetch_array($sql_user);
	$full_name=$rw_user['firstname'].' '.$rw_user['lastname'] ;
	/* Fin Datos usurio*/
	$sql_cotizacion=mysqli_query($con, "select LAST_INSERT_ID(numero_cotizacion) as last from estimates order by id_cotizacion desc limit 0,1 ");
	$rwC=mysqli_fetch_array($sql_cotizacion);
	$numero_cotizacion=$rwC['last']+1;	
	/*Datos de la empresa*/
	$sql_empresa=mysqli_query($con,"SELECT * FROM empresa where id_empresa=1");
	$rw_empresa=mysqli_fetch_array($sql_empresa);
	$iva=$rw_empresa["iva"];
	$impuesto=($iva/100) + 1;
	
	$nrc=$rw_empresa["nrc"];
	$nombre_empresa=$rw_empresa["nombre"];
	$propietario=$rw_empresa["propietario"];
	$giro=$rw_empresa["giro"];
	$direccion=$rw_empresa["direccion"];
	$telefono=$rw_empresa["telefono"];
	$logo_url=$rw_empresa["logo_url"];
	/*Fin datos empresa*/
	/*Datos de la moneda*/
	$sql_currencies=mysqli_query($con,"SELECT * FROM currencies where id='$id_moneda'");
	$rw_currency=mysqli_fetch_array($sql_currencies);
	$moneda=$rw_currency['symbol'];
	$decimals=$rw_currency['decimals'];
	$dec_point=$rw_currency['decimal_separator'];
	$thousands_sep=$rw_currency['thousand_separator'];
	/*Fin datos moneda*/
    // get the HTML
     ob_start();
     include(dirname('__FILE__').'/res/cotizacion_html.php');
    $content = ob_get_clean();

    try
    {
        // init HTML2PDF
        $html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
        // display the full page
        $html2pdf->pdf->SetDisplayMode('fullpage');
        // convert
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
        // send the PDF
        $html2pdf->Output('Cotizacion.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
