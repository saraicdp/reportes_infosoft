<?php
	date_default_timezone_set("UTC");


	$desde = $_POST ['fdesde'];
	$hasta = $_POST ['fhasta'];
	$opcion = $_POST['grupos'];
	$move = $_POST['movimiento'];	

	require "fpdf.php";
	class PDF extends FPDF
	{
	}

	//DECLARACION DE LA HOJA
	$pdf=new PDF('P', 'mm', 'letter');
	$pdf->Setmargins(5,5);
	$pdf->SetAutoPageBreak(true,5);
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	//DATOS DEL TITULO
	$pdf->SetTextColor(0x00, 0x00,0x00);
	$pdf->SetFont("helvetica", "b", "9");
	$pdf->cell(0,5,"Fecha de Reporte: ".date("d/m/Y"),0,0,'L');
	$pdf->SetFont("Arial", "i", "7");
	$pdf->cell(0,5,"Reporte generado por INFOSOFT",0,1,'R');

	//DATOS DE LA CONEXION CON LA BASE DE DATOS 
	mysql_connect("localhost", "root", "superman");
	mysql_select_db(antonio);

	// CONSULTA PARA MEMBRETE

		$pdf->SetFont("Arial","i", "12");
		$pdf->SetFillColor(71, 187, 57);
		$pdf->SetTextColor(255, 255, 255);
		$pdf->cell(0,7,"Compra de articulos por Grupo: $opcion, desde $desde hasta $hasta",0,1,'C','true');
		$pdf->SetTextColor(0,0,0);
	
	//Mostrar tabla
		$pdf->Setmargins(5,5);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "8");
		$pdf->cell(15,5,"Grupo",1,0,'C');
		$pdf->cell(17,5,"Codigo",1,0,'C');
		$pdf->cell(70,5,"Descripcion",1,0,'C');
		$pdf->cell(17,5,"Compras",1,0,'C');
		$pdf->cell(17,5,"ventas",1,0,'C');
		$pdf->cell(25,5,"Existencia Actual",1,1,'C');
		

	//Consulta a Mysql
	$sql ="SELECT lininv.cod_art, lininv.des_art, lininv.cantidad, arti.exi_act, lininv.cod_gru,
		SUM(CASE WHEN ori_mov = 'CPA' THEN cantidad ELSE 0 END) as totalcompra, SUM(CASE WHEN ori_mov = 'PVE' THEN cantidad ELSE 0 END) as totalventa, lininv.ori_mov FROM lininv INNER JOIN arti ON arti.cod_art=lininv.cod_art WHERE lininv.fec_mov>='$desde' AND lininv.fec_mov<='$hasta' AND lininv.cod_gru='$opcion' GROUP BY lininv.cod_art ORDER BY lininv.des_art";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec))
	{
		$pdf->SetFont("Times", "", "8");
		$pdf->Setmargins(5,5);
		$pdf->cell(15,5,$row['cod_gru'],1,0,'D');
		$pdf->cell(17,5,$row['cod_art'],1,0,'D');
		$pdf->cell(70,5,$row['des_art'],1,0,'D');
		$pdf->cell(17,5,$row['totalcompra'],1,0,'D');
		$pdf->cell(17,5,$row['totalventa'],1,0,'D');
		$pdf->cell(25,5,$row['exi_act'],1,1,'R');
		$pdf->SetTextColor(0,0,0);
		
	}

$pdf->Output();


//By Sarita

?>


