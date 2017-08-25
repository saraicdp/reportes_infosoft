
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
//////////////////////////////SALIDAS////////////////////////////////////////
		$pdf->Setmargins(5,5);
		$pdf->Ln();
		$pdf->SetFont("Arial", "", "8");
		$pdf->cell(15,5,"N#",1,0,'C');
		$pdf->cell(20,5,"Fecha",1,0,'C');
		$pdf->cell(15,5,"Articulo",1,0,'C');
		$pdf->cell(20,5,"Cantidad",1,0,'C');
		$pdf->cell(22,5,"cos. Unitario",1,0,'C');
		$pdf->cell(80,5,"Cos. Total",1,1,'C');




	$sql="SELECT num_doc, fec_mov, des_art, can_uni, cos_uni FROM lininv WHERE fec_mov<='$hasta' AND ori_mov='SIN' ORDER BY fec_mov";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec)){

		$pdf->SetFont("Times", "", "8");
		$pdf->Setmargins(5,5);
		$pdf->cell(15,5,$row['num_doc'],1,0,'D');
		$pdf->cell(20,5,$row['fec_mov'],1,0,'D');
		$pdf->cell(17,5,$row['des_art'],1,0,'D');
		$pdf->cell(20,5,$row['can_uni'],1,0,'D');
		$pdf->cell(50,5,$row['cos_uni'],1,0,'D');
		$pdf->cell(12,5,$row['cod_ven'],1,0,'D');
		$pdf->cell(20,5,$row['tot_net'],1,0,'R');
		$pdf->cell(20,5,$row['tot_isv'],1,0,'R');
		$pdf->settextcolor(10,26,198);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(30,5,$row['tot_ven'],1,1,'R','true');
		$pdf->SetTextColor(0,0,0);

	}	

	
	//MOSTRAR ARCHIVO 
	$pdf->output();

//By Sara

?>


