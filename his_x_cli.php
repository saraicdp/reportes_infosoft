
<?php
//Historico por cliente
	
	date_default_timezone_set("UTC");

	$desde = $_POST ['fdesde'];
	$hasta = $_POST ['fhasta'];
	$cedula = $_POST['ci'];	
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
		$pdf->cell(0,7,"Ventas por Cliente $cedula",0,1,'C','true');
	
	//Ventas
		$pdf->Setmargins(5,5);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "8");
		$pdf->cell(10,5,"N#",1,0,'C');
		$pdf->cell(20,5,"Fecha",1,0,'C');
		$pdf->cell(17,5,"Factura",1,0,'C');
		$pdf->cell(30,5,"C.I./ Rif",1,0,'C');
		$pdf->cell(55,5,"Nombre",1,0,'C');		
		$pdf->cell(12,5,"Vend.",1,0,'C');
		$pdf->cell(30,5,"Neto",1,0,'C');
	 	$pdf->cell(30,5,"Iva",1,0,'C');
		$pdf->cell(30,5,"Total Cancelado",1,1,'C');
		

	//Consulta a Mysql

	
	$sql = "SELECT ventas.num_ven, ventas.num_fis, ventas.fec_emi, ventas.cod_cli, clientes.nom_cli, ventas.cod_ven, ventas.tot_net,ventas.tot_isv, ventas.tot_ven FROM ventas INNER JOIN clientes on clientes.cod_cli = ventas.cod_cli WHERE ventas.cod_cli='$cedula' order by fec_emi";
	//$sql= "SELECT * from ventas";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec))
	{
		$fiva = $fmt->formatCurrency($row['tot_isv'], 'Bs ');
 		$fneto = $fmt->formatCurrency($row['tot_net'], 'Bs ');
 		$fventas = $fmt->formatCurrency($row['tot_ven'], 'Bs '); 

		$pdf->SetFont("Times", "", "8");
		$pdf->Setmargins(5,5);
		$pdf->cell(10,5,$row['num_ven'],1,0,'D');
		$pdf->cell(20,5,$row['fec_emi'],1,0,'D');
		$pdf->cell(17,5,$row['num_fis'],1,0,'D');
		$pdf->cell(30,5,$row['cod_cli'],1,0,'D');
		$pdf->cell(55,5,$row['nom_cli'],1,0,'D');
		$pdf->cell(12,5,$row['cod_ven'],1,0,'D');
		$pdf->cell(30,5,$fneto,1,0,'R');
		$pdf->cell(30,5,$fiva,1,0,'R');
		$pdf->settextcolor(10,26,198);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(30,5,$fventas,1,1,'R','true');
		$pdf->SetTextColor(0,0,0);
		
	}

		$pdf->Ln();
		$pdf->Ln();
		$pdf->SetFillColor(205,205,205);
		$pdf->SetFont("helvetica","bu", "12");
		$pdf->cell(0,2,"Devoluciones por Cliente $cedula",0,1,'C','true');
		$pdf->Ln();

		$pdf->Setmargins(5,5);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "8");
		$pdf->cell(15,5,"N# Dev.",1,0,'C');
		$pdf->cell(25,5,"Mon Afectado",1,0,'C');
		$pdf->cell(20,5,"Fecha",1,0,'C');
		$pdf->cell(20,5,"C.I./ Rif",1,0,'C');
		$pdf->cell(50,5,"Nombre",1,0,'C');		
		$pdf->cell(12,5,"Vend.",1,0,'C');
		$pdf->cell(30,5,"Neto Dev.",1,0,'C');
	 	$pdf->cell(30,5,"Iva Dev.",1,0,'C');
		$pdf->cell(30,5,"Total Dev.",1,0,'C');
		$pdf->cell(30,5,"Fac. Afectada",1,1,'C');
		
		//Consulta a Mysql
		//Devolucion

	$sql= "SELECT devolucion.num_dev, devolucion.num_afe, devolucion.fec_emi, devolucion.cod_cli, clientes.nom_cli, devolucion.cod_ven, devolucion.tot_net, devolucion.tot_isv, devolucion.tot_ven, devolucion.mon_faf FROM devolucion INNER JOIN clientes ON clientes.cod_cli=devolucion.cod_cli WHERE devolucion.cod_cli ='$cedula' order by devolucion.fec_emi"; 
	//$sql= "SELECT * from ventas";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec))
	{
		$bneto = $fmt->formatCurrency($row['tot_net'], 'Bs ');
		$biva = $fmt->formatCurrency($row['tot_isv'], 'Bs ');
		$bventas = $fmt->formatCurrency($row['tot_ven'], 'Bs ');
		$bafe = $fmt->formatCurrency($row['mon_faf'], 'Bs ');
		
		$pdf->SetFont("Times", "", "8");
		$pdf->Setmargins(5,5);
		$pdf->cell(15,5,$row['num_dev'],1,0,'D');
		$pdf->cell(25,5,$row['num_afe'],1,0,'D');
		$pdf->cell(20,5,$row['fec_emi'],1,0,'D');
		$pdf->cell(20,5,$row['cod_cli'],1,0,'D');
		$pdf->cell(50,5,$row['nom_cli'],1,0,'D');
		$pdf->cell(12,5,$row['cod_ven'],1,0,'D');
		$pdf->cell(30,5,$bneto,1,0,'R');
		$pdf->cell(30,5,$biva,1,0,'R');
		$pdf->settextcolor(10,26,198);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(30,5,$bventas,1,0,'R','true');
		$pdf->settextcolor(164,2,2);
		$pdf->cell(30,5,$bafe,1,1,'R','true');
		$pdf->SetTextColor(0,0,0);
		
	}

		$sql="SELECT SUM(tot_ven) AS totalventas, SUM(tot_net) AS totalneto, SUM(tot_isv) AS iva  FROM ventas WHERE cod_cli='$cedula'";
		$rec=mysql_query($sql);
			while ($row=mysql_fetch_array($rec))
	{

  		$piva = $fmt->formatCurrency($row['iva'], 'Bs ');
 		$pneto = $fmt->formatCurrency($row['totalneto'], 'Bs ');
 		$pventas = $fmt->formatCurrency($row['totalventas'], 'Bs '); 
 		$totalventas = $row['totalventas'];

       	$pdf->Setmargins(200,5);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "10");
 		$pdf->cell(35,5,"Iva:",1,0,'L');
		$pdf->cell(30,5,$piva,1,1,'R');
		$pdf->cell(35,5,"Neto:",1,0,'L');
		$pdf->cell(30,5,$pneto,1,1,'R');
		$pdf->cell(35,5,"Gran Total Ventas:",1,0,'L');
		$pdf->cell(30,5,$pventas,1,1,'R');	
	}

		$sql="SELECT SUM(tot_ven) AS totaldev FROM devolucion WHERE cod_cli='$cedula' order by fec_emi";
	$rec=mysql_query($sql);
	while ($row=mysql_fetch_array($rec)){
		
		$total = $fmt->formatCurrency($row['totaldev'], 'Bs ');

		$totaldevoluciones = $row['totaldev'];

		$pdf->Setmargins(200,2);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "10");
		$pdf->SetTextColor(0,0,0);
		$pdf->cell(35,5,"Total Devoluciones:",1,0,'L');
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(30,5,$total,1,1,'R');

	}

		$rsul = $fmt->formatCurrency(($totalventas - $totaldevoluciones), 'Bs ');

		$pdf->Setmargins(200,2);
		$pdf->Ln();
		$pdf->SetFont("Times", "b", "12");
		$pdf->SetTextColor(0,0,0);
		$pdf->cell(30,5,"Diferencia:",1,0,'L');
		$pdf->Settextcolor(10,26,198);
		$pdf->SetFillColor(205,205,205);
		$pdf->cell(35,5,$rsul,1,1,'R','true');



			
	//MOSTRAR ARCHIVO 
	$pdf->output();

//By Sara

?>


