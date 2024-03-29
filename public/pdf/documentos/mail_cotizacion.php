<?php
	/*-------------------------
	Autor: Obed Alvarado
	Web: obedalvarado.pw
	Mail: info@obedalvarado.pw
	---------------------------*/
	session_start();
	/* Connect To Database*/
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: ../../login.php");
		exit;
        }
	include("../../config/db.php");
	include("../../config/conexion.php");
	$id_cotizacion= intval($_REQUEST['quote_id']);

	require_once(dirname(__FILE__).'/../html2pdf.class.php');
	require_once(dirname(__FILE__).'/../../classes/PHPMailerAutoload.php');


	$sql_cotizacion=mysqli_query($con, "select * from estimates, clients, users where estimates.id_empleado=users.user_id and estimates.id_cliente=clients.id and id_cotizacion='".$id_cotizacion."' ");
	$rwC=mysqli_fetch_array($sql_cotizacion);
	$numero_cotizacion=$rwC['numero_cotizacion'];
	$fecha_cotizacion=date('d/m/Y', strtotime($rwC['fecha_cotizacion']));
	$atencion=$rwC['contacto'];
	$tel1=$rwC['movil'];
	$empresa=$rwC['nombre_comercial'];
	$tel2=$rwC['fijo'];
	$email=$rwC['email'];
	$condiciones=$rwC['condiciones'];
	$validez=$rwC['validez'];
	$entrega=$rwC['entrega'];
	$full_name=$rwC['firstname'].' '.$rwC['lastname'] ;
	$total_iva=$rwC['total_iva'];
	$id_contact=$rwC['id_contact'];
	//SQL contacto
	$sql_contacto=mysqli_query($con,"select * from contacts where id_contact='".$id_contact."'");
	$rw_contacto=mysqli_fetch_array($sql_contacto);
	$nombre_contact	= $rw_contacto['nombre_contact'];
	$telefono_contact	= $rw_contacto['telefono_contact'];
	$email_contact	= $rw_contacto['email_contact'];
	//Fin SQL contacto


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
	$email_empresa=$rw_empresa["email"];
	/*Fin datos empresa*/


	/*Datos de la moneda*/
	$sql_currencies=mysqli_query($con,"SELECT * FROM currencies, estimates where currencies.id=estimates.currency_id and estimates.numero_cotizacion='$numero_cotizacion'");
	$rw_currency=mysqli_fetch_array($sql_currencies);
	$moneda=$rw_currency['symbol'];
	$decimals=$rw_currency['decimals'];
	$dec_point=$rw_currency['decimal_separator'];
	$thousands_sep=$rw_currency['thousand_separator'];
	/*Fin datos moneda*/


    // get the HTML
	ob_start();
	include(dirname('__FILE__').'/res/ver_cotizacion_html.php');
	$content = ob_get_clean();

    try
    {
        // init HTML2PDF
        $html2pdf = new HTML2PDF('P', 'LETTER', 'es', true, 'UTF-8', array(0, 0, 0, 0));
        // display the full page
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));

		$to = strip_tags($_REQUEST['sendto']);
		$from = $email_empresa;
		$subject = strip_tags($_REQUEST['subject']);
		$message = strip_tags($_REQUEST['message']);
		$separator = md5(time());
		$eol = PHP_EOL;
		$filename = "Cotización.pdf";
		$pdfdoc = $html2pdf->Output('', 'S');
		$attachment = chunk_split(base64_encode($pdfdoc));

		$headers = "From: ".$from.$eol;
		$headers .= "MIME-Version: 1.0".$eol;
		$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"".$eol.$eol;


		$body="";
		$body .= "Content-Transfer-Encoding: 7bit".$eol;
		$body .= "This is a MIME encoded message.".$eol; //had one more .$eol


		$body .= "--".$separator.$eol;
		$body .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
		$body .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
		$body .= $message.$eol;


		$body .= "--".$separator.$eol;
		$body .= "Content-Type: application/octet-stream; name=\"".$filename."\"".$eol;
		$body .= "Content-Transfer-Encoding: base64".$eol;
		$body .= "Content-Disposition: attachment".$eol.$eol;
		$body .= $attachment.$eol;
		$body .= "--".$separator."--";

		$fecha = date("Y-m-d H:i:s");

		$mail = new PHPMailer;
		$mail->IsMail();
		$mail->Mailer = "smtp";
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = 'asaelx@gmail.com';
		$mail->Password = 'udzkaramuzka1';
		$mail->SMTPSecure = 'tls';
		$mail->Port = 587;
		$mail->setFrom($from, 'Artifice');
		$mail->addAddress($to);
		$mail->addAttachment($pdfdoc);
		$mail->isHTML(true);
		$mail->Subject = $subject;
		$mail->Body    = $message;

		if(!$mail->send()) {
		    echo "<p style='color:red'>Ocurrió un error. Mensaje no enviado.</p>";
		    echo 'Error: ' . $mail->ErrorInfo;
		} else {
			$sql = "INSERT INTO emails (email, asunto, mensaje, fecha_envio, id_cotizacion) VALUES ('$to', '$subject', '$message', '$fecha', '$id_cotizacion')";
			$query = mysqli_query($con, $sql);
		    echo "<p style='color:green'>¡Mensaje enviado!</p>";
		}
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
