<?php
	/*-------------------------
	Autor: Obed Alvarado
	Web: obedalvarado.pw
	Mail: info@obedalvarado.pw
	---------------------------*/
	// get the HTML
     ob_start();
	session_start();
	if (!isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] != 1) {
        header("location: ../../login.php");
		exit;
        }
	/* Connect To Database*/
	include("../../config/db.php");
	include("../../config/conexion.php");
	$daterange= $_GET['daterange'];
	$id_vendedor= intval($_GET['id_vendedor']);
	$q=$_GET['q'];
	require_once(dirname(__FILE__).'/../html2pdf.class.php');
		

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
	// escaping, additionally removing everything that could be (html/javascript-) code
        $q = mysqli_real_escape_string($con,(strip_tags($_REQUEST['q'], ENT_QUOTES)));
		$id_vendedor=intval($_REQUEST['id_vendedor']);
		$daterange = mysqli_real_escape_string($con,(strip_tags($_REQUEST['daterange'], ENT_QUOTES)));
		$estado = mysqli_real_escape_string($con,(strip_tags($_REQUEST['estado'], ENT_QUOTES)));
		if (!empty($daterange)){
		list ($f_inicio,$f_final)=explode(" - ",$daterange);//Extrae la fecha inicial y la fecha final en formato español
		list ($dia_inicio,$mes_inicio,$anio_inicio)=explode("/",$f_inicio);//Extrae fecha inicial 
		$fecha_inicial="$anio_inicio-$mes_inicio-$dia_inicio 00:00:00";//Fecha inicial formato ingles
		list($dia_fin,$mes_fin,$anio_fin)=explode("/",$f_final);//Extrae la fecha final
		$fecha_final="$anio_fin-$mes_fin-$dia_fin 23:59:59";
		} else {
			$fecha_inicial=date("Y-m")."-01 00:00:00";
			$fecha_final=date("Y-m-d H:i:s");
		}
		
		 $sTable = "estimates, clients, users";
		 $sWhere = "where estimates.id_cliente=clients.id and estimates.id_empleado=users.user_id"; 
	     $sWhere .= " and (clients.contacto like '%$q%' or clients.nombre_comercial like '%$q%')";
		 if ($id_vendedor>0){
			$sWhere .= " and estimates.id_empleado='$id_vendedor'"; 
		 }
		 if ($estado!=""){
			 $sWhere .= " and estimates.status='$estado'"; 
		 }
		 $sWhere .= " and estimates.fecha_cotizacion between '$fecha_inicial' and '$fecha_final' "; 
		 $sWhere.=" order by id_cotizacion desc";
		 //main query to fetch the data
		$sql="SELECT * FROM  $sTable $sWhere";
		$query = mysqli_query($con, $sql);
		
    
     include(dirname('__FILE__').'/res/reporte_cotizacion.php');
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
