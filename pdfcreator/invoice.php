<?php
	
	require('pdfcreator/fpdf.php');

	class PDF extends FPDF
	{
		// Page header
		function Header()
		{
		    // Logo
		    $this->Image('images/img_a.jpg',10,6,30);
		    // Arial bold 15
		    $this->SetFillColor(0,0,0);
		    $this->SetFont('Arial','',22);
		    // Move to the right
		    $this->Cell(80);
		    $this->SetTextColor(50,194,77);
		    // Title
		    $this->Cell(30,10,'uShopper',0,0,'C',false,'www.ushopper.web44.net');

		    $this->SetY(17);
		    $this->SetX(110);
		    $this->SetFont('Arial','',10);
		    $this->Cell(30,10,'City at your Home',0,0,'C');
		    
		    $this->Line(0, 30, $this->GetPageWidth(), 30);
		    // Line break
		    $this->Ln(20);
		    

		}

		function UserDetail(){
			$this->SetFont('Arial','',15);
			$this->Cell(30,10,'Name',0,0,'L');
			$this->Ln();
			$this->SetFontSize(14);
			$this->Cell(30,10,'Id',0,0,'L');
			$this->Ln();
			$this->Cell(30,10,'Phone',0,0,'L');
			$this->Line(100, 30, 100, 80);
			$this->Line(0, 80, $this->GetPageWidth(), 80);
			
		}

		function OrderDetail(){
			$this->Ln(25);
			$this->SetFont('Arial','',15);
			$this->Cell(30,10,'Order Detail',0,0,'L');
			$this->Ln(5);
		}

		function OrderTable(){
			$this->Line(10, 100, 200, 100);
			$this->SetFont('Arial','',15);
			$this->SetY(100);
			$this->Cell(30,10,'Order Id',0,0,'L');
			$this->Line(10, 110, 200, 110);
			$this->SetY(110);
		
			$this->SetFontSize(12);
			$this->Cell(100,10,'Item Name',0,0,'C');
			$this->Cell(25,10,'Quantity',0,0,'C');
			$this->Cell(30,10,'Price',0,0,'C');
			$this->Cell(35,10,'Total',0,0,'C');
			$this->Line(10, 120, 200, 120);
			$this->Line(110, 110, 110, 120);
			$this->Line(135, 110, 135, 120);
			$this->Line(165, 110, 165, 120);
			$this->Line(10, 100, 10, 120);
			$this->Line(200, 100, 200, 120);
		}

		function OrderItem($item){
			$y=120;
			$len=count($item);
			foreach ($item as $key => $value) {
				//$this->Write(15,$y);
				if($y>=270){
					$this->Line(10, $y, 200, $y);
					$y=40;
					$this->AddPage();
					$this->Line(10, $y, 200, $y);
				}
				$this->SetY($y);
				$this->Cell(100,15,$value["name"],0,0,'C');
				$this->Cell(25,15,$value["quantity"],0,0,'C');
				$this->Cell(30,15,$value["price"],0,0,'C');
				
				$this->SetDrawColor(0,0,0);
				$this->Line(10, $y, 10, $y+15);
				$this->Line(110, $y, 110, $y+15);
				$this->Line(135, $y, 135, $y+15);
				$this->Line(165, $y, 165, $y+15);
				$this->Line(200, $y, 200, $y+15);	

				$y=$y+15;
				if($len!=$key+1){
					$this->SetDrawColor(200,200,200);
					$this->Line(10, $y, 165, $y);
				}
			}
			$this->Line(10, $y, 200, $y);
		}

		function Signature(){
			$this->Ln(20);

			$this->Cell($this->GetPageWidth()-60,15,"Customer Sign:",0,0,'R');
			$this->Cell(40,15,"",1,0,'C');
		}

		// Page footer
		function Footer()
		{
		    // Position at 1.5 cm from bottom
		    $this->SetY(-15);
		    // Arial italic 8
		    $this->SetFont('Arial','I',8);
		    // Page number
		    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
		}
	}

		// Instanciation of inherited class
	$pdf = new PDF();
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->UserDetail();
	$pdf->OrderDetail();
	$pdf->OrderTable();
	$pdf->OrderItem(array
						(array("name"=>"Britannia Biscuit Britannia Biscuit Britannia Biscuit Britanni","quantity"=>"4","price"=>"80"),
							array("name"=>"Britannia Biscuit","quantity"=>"4","price"=>"80"),
							array("name"=>"Britannia Biscuit","quantity"=>"4","price"=>"80"),
							array("name"=>"Britannia Biscuit","quantity"=>"4","price"=>"80"),
							array("name"=>"Britannia Biscuit","quantity"=>"4","price"=>"80"),
							array("name"=>"Britannia Biscuit","quantity"=>"4","price"=>"80"),
							array("name"=>"Britannia Biscuit","quantity"=>"4","price"=>"80"),
							array("name"=>"Britannia Biscuit","quantity"=>"4","price"=>"80"),
							array("name"=>"Britannia Biscuit","quantity"=>"4","price"=>"80")
		));
	$pdf->Signature();
	$pdf->Output();

	
?>