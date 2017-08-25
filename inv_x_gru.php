<?php
	date_default_timezone_set("UTC");


	$desde = $_POST ['fdesde'];
	$hasta = $_POST ['fhasta'];
	$opcion = $_POST['grupos'];	

	require "fpdf.php";
	class PDF extends FPDF
	{
	}

	//DECLARACION DE LA HOJA
	$pdf=new PDF('L', 'mm', 'letter');
	$pdf->Setmargins(5,5);
	$pdf->SetAutoPageBreak(true,5);
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
	mysql_select_db(antonio);

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
		$pdf->cell(0,7,"Compra de articulos por Grupo: $opcion, desde $desde hasta $hasta",0,1,'C','true');
	
	//Mostrar tabla
		$pdf->Setmargins(5,5);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "8");
		$pdf->cell(15,5,"Documento",1,0,'C');
		$pdf->cell(15,5,"Origen",1,0,'C');
		$pdf->cell(17,5,"Fecha",1,0,'C');
		$pdf->cell(17,5,"Codigo",1,0,'C');
		$pdf->cell(70,5,"Descripcion",1,0,'C');
		$pdf->cell(15,5,"Grupo",1,0,'C');		
		$pdf->cell(15,5,"Cantidad",1,0,'C');
		$pdf->cell(25,5,"Existencia Actual",1,0,'C');
		$pdf->cell(15,5,"Iva Costo",1,0,'C');
	 	$pdf->cell(20,5,"Costo unitario",1,0,'C');
	 	$pdf->cell(20,5,"Precio Final",1,0,'C');
		$pdf->cell(20,5,"Total Cancelado",1,1,'C');
		

	//Consulta a Mysql

	
	$sql = "SELECT lininv.num_doc, lininv.ori_mov, lininv.fec_mov, lininv.cod_art, lininv.des_art, lininv.gru_art, lininv.cantidad, lininv.mon_isv, lininv.cos_uni, lininv.pre_fin, lininv.total, arti.exi_act FROM lininv INNER JOIN arti ON arti.cod_art=lininv.cod_art WHERE lininv.fec_mov>='$desde' AND lininv.fec_mov<='$hasta' AND lininv.gru_art='$opcion' ORDER BY lininv.fec_mov";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec))
	{
		$pdf->SetFont("Times", "", "8");
		$pdf->Setmargins(5,5);
		$pdf->cell(15,5,$row['num_doc'],1,0,'D');
		$pdf->cell(15,5,$row['ori_mov'],1,0,'D');
		$pdf->cell(17,5,$row['fec_mov'],1,0,'D');
		$pdf->cell(17,5,$row['cod_art'],1,0,'D');
		$pdf->cell(70,5,$row['des_art'],1,0,'D');
		$pdf->cell(15,5,$row['gru_art'],1,0,'D');
		$pdf->cell(15,5,$row['cantidad'],1,0,'R');
		$pdf->cell(25,5,$row['exi_act'],1,0,'R');
		$pdf->cell(15,5,$row['mon_isv'],1,0,'R');
		$pdf->cell(20,5,$row['cos_uni'],1,0,'R');
		$pdf->settextcolor(10,26,198);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(20,5,$row['pre_fin'],1,0,'R');
		$pdf->cell(20,5,$row['total'],1,1,'R','true');
		$pdf->SetTextColor(0,0,0);
		
	}

$pdf->Output();


//By Sarita

?>


