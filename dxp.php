<?php
	date_default_timezone_set("UTC");


	$desde = $_POST ['fdesde'];
	$hasta = $_POST ['fhasta'];
	$cedula = $_POST['ci'];	

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
		$pdf->cell(0,7,"Devolucion por Clientes",0,1,'C','true');
	
	//Mostrar tabla
		$pdf->Setmargins(5,5);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "10");
		$pdf->cell(20,5,"Devolucion",1,0,'C');
		$pdf->cell(30,5,"Factura Afectada",1,0,'C');
		$pdf->cell(20,5,"Fecha",1,0,'C');
		$pdf->cell(25,5,"C.I./ Rif",1,0,'C');
		$pdf->cell(65,5,"Nombre",1,0,'C');		
		$pdf->cell(12,5,"Vend.",1,0,'C');
		$pdf->cell(20,5,"Neto Dev.",1,0,'C');
	 	$pdf->cell(20,5,"Iva Dev.",1,0,'C');
		$pdf->cell(25,5,"Total Dev.",1,0,'C');
		$pdf->cell(30,5,"Factura Afectada",1,1,'C');
		

	//Consulta a Mysql

	$sql= "SELECT devolucion.num_dev, devolucion.num_afe, devolucion.fec_emi, devolucion.cod_cli, clientes.nom_cli, devolucion.cod_ven, devolucion.tot_net, devolucion.tot_isv, devolucion.tot_ven, devolucion.mon_faf FROM devolucion INNER JOIN clientes ON clientes.cod_cli=devolucion.cod_cli WHERE devolucion.cod_cli ='$cedula' order by devolucion.fec_emi"; 
	//$sql= "SELECT * from ventas";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec))
	{
		$pdf->SetFont("Times", "", "10");
		$pdf->Setmargins(5,5);
		$pdf->cell(20,5,$row['num_dev'],1,0,'D');
		$pdf->cell(30,5,$row['num_afe'],1,0,'D');
		$pdf->cell(20,5,$row['fec_emi'],1,0,'D');
		$pdf->cell(25,5,$row['cod_cli'],1,0,'D');
		$pdf->cell(65,5,$row['nom_cli'],1,0,'D');
		$pdf->cell(12,5,$row['cod_ven'],1,0,'D');
		$pdf->cell(20,5,$row['tot_net'],1,0,'R');
		$pdf->cell(20,5,$row['tot_isv'],1,0,'R');
		$pdf->settextcolor(10,26,198);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(25,5,$row['tot_ven'],1,0,'R','true');
		$pdf->settextcolor(250,100,100);
		$pdf->cell(30,5,$row['mon_faf'],1,1,'R','true');
		$pdf->SetTextColor(0,0,0);
		
	}
		$sql="SELECT SUM(tot_ven) AS totaldev FROM devolucion WHERE cod_cli='$cedula' order by fec_emi";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec)){
       
       	$pdf->Setmargins(180,2);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "10");
		$pdf->SetTextColor(0,0,0);
		$pdf->cell(31,5,"Total Devoluciones:",1,0,'L');
		$pdf->settextcolor(10,26,198);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(30,5,$row['totaldev'],1,1,'R');

	}

	//MOSTRAR ARCHIVO 
	$pdf->output();

//By Sara

?>


