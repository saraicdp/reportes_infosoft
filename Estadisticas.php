
<?php
//Historico por cliente
	
	date_default_timezone_set("UTC");

	$desde = $_POST ['fdesde'];
	$hasta = $_POST ['fhasta'];
	$fmt = new NumberFormatter('es_VE', NumberFormatter::CURRENCY);

	require "fpdf.php";
	class PDF extends FPDF
	{
	}

	//DECLARACION DE LA HOJA
	$pdf=new PDF('L', 'mm', 'letter');
	$pdf->Setmargins(5,5);
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	//DATOS DEL TITULO
	$pdf->SetTextColor(0x00, 0x00,0x00);
	$pdf->SetFont("helvetica", "b", "9");
	$pdf->cell(0,5,"Fecha:".date("d/m/Y"),0,0,'L');
	$pdf->SetFont("helvetica", "b", "7");
	$pdf->cell(0,5,"Reporte generado por INFOSOFT",0,1,'R');

	//DATOS DE LA CONEXION CON LA BASE DE DATOS 
	mysql_connect("localhost", "root", "superman");
	mysql_select_db(perca);

	// CONSULTA PARA MEMBRETE
	$sql="SELECT * FROM parametros";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec)) 
	{
		$pdf->SetFont("Arial", "bu", "18");
		$pdf->settextcolor(10,26,198);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(0,7,$row['nombre'],0,1,'L','true');
		$pdf->settextcolor(0,0,0);
		$pdf->SetFont("helvetica", "bi", "10");
		$pdf->cell(15,6,"Rif.:",0,0,'L');
		$pdf->cell(5,6,"   ",0,0,'L');
		$pdf->cell(30,6,$row['rif'],0,1,'L');
		$pdf->cell(15,6,"Direccion:",0,0,'L');
		$pdf->cell(5,6,"   ",0,0,'L');
		$pdf->MultiCell(100,6,$row['direccion']);
		$pdf->cell(15,6,"Ciudad:",0,0,'L');
		$pdf->cell(5,6,"   ",0,0,'L');
		$pdf->cell(15,6,$row['ciudad'],0,1,'L');
		$pdf->cell(15,6,"Telefono:",0,0,'L');
		$pdf->cell(5,6,"   ",0,0,'L');
		$pdf->cell(15,6,$row['telefonos'],0,1,'L');

	}
		$pdf->SetFont("helvetica","bu", "12");
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(0,7,"Reporte Estadistico hasta la fecha $hasta",0,1,'C','true');
	
	//Consulta 
//////////////////////////////COMPRAS ////////////////////////////////////////
	$sql="SELECT SUM(tot_can) as tot_compras from compras where fec_emi<='$hasta' ORDER BY fec_emi";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec)){

		$total = $fmt->formatCurrency($row['tot_compras'], 'Bs ');

		$totalcompras = $row['tot_compras'];
		
		$pdf->Setmargins(5,2);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "12");
		$pdf->SetTextColor(0,0,0);
		$pdf->cell(60,5,"Total Compras:",1,0,'L');
		$pdf->settextcolor(255, 0, 0);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(60,5,$total,1,1,'R');


	}	
////////////////////////////// VENTAS ////////////////////////////////////////
	$sql="SELECT SUM(tot_ven) as tot_ventas from ventas where fec_emi<='$hasta' ORDER BY fec_emi";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec)){

		$total = $fmt->formatCurrency($row['tot_ventas'], 'Bs ');

		$totalventas = $row['tot_ventas'];
		
		$pdf->Setmargins(5,2);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "12");
		$pdf->SetTextColor(0,0,0);
		$pdf->cell(60,5,"Total Ventas:",1,0,'L');
		$pdf->settextcolor(0, 37, 118);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(60,5,$total,1,1,'R');


	}

/////////////////////////////// TOTAL EN INVENTARIO ///////////////////////////
	$sql="SELECT SUM(pre_tot1) AS precio1 FROM arti WHERE exi_act>'0'";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec)){
		
		$total = $fmt->formatCurrency($row['precio1'], 'Bs ');

		$totalinventario = $row['precio1'];

		$pdf->Setmargins(5,2);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "12");
		$pdf->SetTextColor(0,0,0);
		$pdf->cell(60,5,"Total en Inventario:",1,0,'L');
		$pdf->settextcolor(0, 37, 118);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(60,5,$total,1,1,'R');

	}		
		
/////////////////////////////// DEVOLUCIONES ///////////////////////////////////

	$sql="SELECT SUM(tot_ven) AS totaldev FROM devolucion WHERE fec_emi<='$hasta' order by fec_emi";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec)){
		
		$total = $fmt->formatCurrency($row['totaldev'], 'Bs ');

		$totaldevoluciones = $row['totaldev'];

		$pdf->Setmargins(5,2);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "12");
		$pdf->SetTextColor(0,0,0);
		$pdf->cell(60,5,"Total Devoluciones:",1,0,'L');
		$pdf->settextcolor(255, 0, 0);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(60,5,$total,1,1,'R');

	}	
	//MOSTRAR ARCHIVO 
	$pdf->output();

//By Sara

?>


