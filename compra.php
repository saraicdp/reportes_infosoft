<?php
	date_default_timezone_set("UTC");

	$desde = $_POST ['fdesde'];
	$hasta = $_POST ['fhasta'];

	require "fpdf.php";
	class PDF extends FPDF
	{
	}

	//DECLARACION DE LA HOJA
	$pdf=new PDF('P', 'mm', 'letter');
	$pdf->Setmargins(5,5);
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	//DATOS DEL TITULO
	$pdf->SetTextColor(0x00, 0x00,0x00);
	$pdf->SetFont("arial", "b", "9");
	$pdf->cell(0,5,"Fecha:".date("d/m/Y"),0,0,'L');
	$pdf->SetFont("arial", "b", "7");
	$pdf->cell(0,5,"Reporte generado por INFOSOFT",0,1,'R');

	//DATOS DE LA CONEXION CON LA BASE DE DATOS 
	mysql_connect("localhost", "root", "superman");
	mysql_select_db(ombracastillo);

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
		$pdf->SetFont("Arial", "bi", "10");
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
		$pdf->SetFont("Arial","bu", "12");
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(0,7,"LIBRO DE COMPRAS DESDE $desde HASTA $hasta",0,1,'C','true');
	
	//Mostrar tabla
		$pdf->Setmargins(25,5);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "11");
		$pdf->cell(15,5,"N#",1,0,'C');
		$pdf->cell(20,5,"Fecha",1,0,'C');
		$pdf->cell(20,5,"Factura #",1,0,'C');
		$pdf->cell(20,5,"Control #",1,0,'C');
		$pdf->cell(20,5,"Retención #",1,0,'C');
		$pdf->cell(20,5,"C.I./ Rif",1,0,'C');
		$pdf->cell(20,5,"Base Imponible",1,0,'C');
	 	$pdf->cell(20,5,"Iva",1,0,'C');
		$pdf->cell(30,5,"Total",1,1,'C');
		

	//Consulta a Mysql

	
	$sql = "SELECT compras.num_com, compras.fec_emi, compras.num_fac, compras.ref_com, compras.num_ret, compras.cod_pro, prove.nom_pro, prove.rif, compras.tot_net, compras.pag_iva, compras.tot_can FROM compras INNER JOIN prove ON compras.cod_pro = prove.cod_pro WHERE fec_emi BETWEEN '$desde' AND '$hasta' order by fec_emi";
	//$sql= "SELECT * from ventas";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec))
	{
		$pdf->SetFont("Times", "", "10");
		$pdf->Setmargins(25,5);
		$pdf->cell(15,5,$row['num_com'],1,0,'D');
		$pdf->cell(20,5,$row['fec_emi'],1,0,'D');
		$pdf->cell(20,5,$row['num_fac'],1,0,'D');
		$pdf->cell(20,5,$row['ref_com'],1,0,'D');
		$pdf->cell(20,5,$row['num_ret'],1,0,'D');
		$pdf->cell(20,5,$row['rif'],1,0,'R');
		$pdf->cell(20,5,$row['nom_pro'],1,0,'R');
		$pdf->settextcolor(10,26,198);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(30,5,$row['tot_net'],1,0,'R','true');
		$pdf->cell(30,5,$row['pag_iva'],1,0,'R','true');
		$pdf->cell(30,5,$row['tot_can'],1,1,'R','true');
		$pdf->SetTextColor(0,0,0);
		
	}
		$sql="SELECT SUM(tot_can) AS totalcompra, SUM(tot_net) AS totalneto, SUM(pag_iva) AS iva FROM compras WHERE fec_emi BETWEEN '$desde' AND '$hasta'";
		$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec)){
       
       	$pdf->Setmargins(110,5);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "15");
		$pdf->cell(40,5,"Total:",1,0,'L');
		$pdf->cell(40,5,$row['totalventas'],1,1,'L');
		$pdf->cell(40,5,"Neto:",1,0,'L');
		$pdf->cell(40,5,$row['totalneto'],1,1,'L');
	 	$pdf->cell(40,5,"Iva:",1,0,'L');
		$pdf->cell(40,5,$row['iva'],1,1,'L');
	}

	//MOSTRAR ARCHIVO 
	$pdf->output();

//By Sara

?>


