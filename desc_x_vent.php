<?php
	date_default_timezone_set("UTC");

	$desde = $_POST ['fdesde'];
	$hasta = $_POST ['fhasta'];

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
	mysql_select_db(castillo);

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
		$pdf->cell(0,7,"DESCUENTOS POR VENTAS DESDE $desde HASTA $hasta",0,1,'C','true');
	
	//Mostrar tabla
		$pdf->Setmargins(5,5);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "8");
		$pdf->cell(15,5,"N#",1,0,'C');
		$pdf->cell(20,5,"Fecha",1,0,'C');
		$pdf->cell(17,5,"Factura",1,0,'C');
		$pdf->cell(20,5,"C.I./ Rif",1,0,'C');
		$pdf->cell(75,5,"Nombre",1,0,'C');		
		$pdf->cell(12,5,"Vend.",1,0,'C');
		$pdf->cell(20,5,"% Descontado",1,0,'C');
		$pdf->cell(20,5,"Descuento",1,0,'C');
		$pdf->cell(20,5,"Neto",1,0,'C');
	 	$pdf->cell(20,5,"Iva",1,0,'C');
		$pdf->cell(30,5,"Total Cancelado",1,1,'C');
		

	//Consulta a Mysql

	
	$sql = "SELECT ventas.num_ven, ventas.num_fis, ventas.fec_emi, ventas.cod_cli, clientes.nom_cli, ventas.cod_ven, ventas.por_des, ventas.mon_des, ventas.tot_net,ventas.tot_isv, ventas.tot_ven FROM ventas INNER JOIN clientes on clientes.cod_cli = ventas.cod_cli WHERE ventas.fec_emi BETWEEN '$desde' AND '$hasta' AND mon_des > 0.00 order by fec_emi";
	//$sql= "SELECT * from ventas";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec))
	{
		$pdf->SetFont("Times", "", "8");
		$pdf->Setmargins(5,5);
		$pdf->cell(15,5,$row['num_ven'],1,0,'D');
		$pdf->cell(20,5,$row['fec_emi'],1,0,'D');
		$pdf->cell(17,5,$row['num_fis'],1,0,'D');
		$pdf->cell(20,5,$row['cod_cli'],1,0,'D');
		$pdf->cell(75,5,$row['nom_cli'],1,0,'D');
		$pdf->cell(12,5,$row['cod_ven'],1,0,'D');
		$pdf->cell(20,5,$row['por_des'],1,0,'D');
		$pdf->cell(20,5,$row['mon_des'],1,0,'R');
		$pdf->cell(20,5,$row['tot_net'],1,0,'R');
		$pdf->cell(20,5,$row['tot_isv'],1,0,'R');
		$pdf->settextcolor(10,26,198);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(30,5,$row['tot_ven'],1,1,'R','true');
		$pdf->SetTextColor(0,0,0);
		
	}
		$sql="SELECT SUM(mon_des) AS totaldesc FROM ventas WHERE fec_emi BETWEEN '$desde' AND '$hasta'";
		$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec)){
       
       	$pdf->Setmargins(220,5);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "10");
		$pdf->settextcolor(10,26,198);
		$pdf->cell(35,5,"Total en Descuentos:",1,0,'L');
		$pdf->cell(20,5,$row['totaldesc'],1,1,'R');	 	
	}

	//MOSTRAR ARCHIVO 
	$pdf->output();

//By Sara

?>


