<?php

require("include/fpdf.php");

class Invoice extends FPDF
{
	var $orderNum = 0;

	function Header()
	{
		global $config;
		$this->orderNum = ($this->orderNum < 1) ? $this->orderNum = time() : $this->orderNum;

		//...... Invoice Title
		$this->SetFont("Arial", "B", 20);
		$this->Cell(100,0,$config['company']['name']." #".$this->orderNum,0,1,'L',0);

		//...... Puts a black line
		$this->ln(12);
			
		//...... Drops in header image 400x100
		$this->Image("img/frameaddress.png",126,21.5,81,31);

		//...... Writes the business address
		$this->SetFont("Arial", "", 12);;
		$this->MultiCell($subWidth,5,"$this->bizName\n$this->bizAddr\n$this->bizPhone\n$this->bizWebsite",0,0,'R');


		//...... Drops in Barcode image
		$this->Image("P".$this->orderNum.".png",150,7);

		//...... Writes the user's address
		$this->SetFont("Arial", "", 12);;
		$this->setXY(10,22);
		$this->MultiCell($subWidth,5,$this->userAddr,0,'L');

		//...... Puts a black line
		$this->ln(10);
		$this->Cell($subWidth,$this->lineHeight+1,'',"B",1,'L',0);

		// Save the Y offset.  This is where the first block following the header will appear.
	}

} // End class

define('FPDF_FONTPATH', 'include/font/');

$Q="SELECT title,quantity,amount FROM orders,products 
	WHERE uniq_id='$_REQUEST[uniq_id]' 
	AND user_id='$_SESSION[id]' 
	AND orders.product_id=products.id";
$items = getResults($Q);

// Create a new PDF object
$pdf = new Invoice('P', 'mm', 'Letter');

$pdf->bizName		= $config['company']['name'];
$pdf->bizAddr		= <<<__EOT__
{$config['company']['address']}
{$config['company']['city']}, {$config['company']['state']}
{$config['company']['zipcode']}
{$config['company']['country']}
__EOT__;
$pdf->bizPhone		= $config['company']['phone'];
$pdf->bizWebsite	= $config['company']['website'];

$pdf->userAddr = <<<__EOT__
$_SESSION[firstname] $_SESSION[lastname]
$_SESSION[address]
$_SESSION[city], $_SESSION[state]
$_SESSION[zipcode]
$_SESSION[country]
$_SESSION[email]
__EOT__;
$pdf->orderNum = $_REQUEST['uniq_id'];

include_once("include/barcode.php");
mkBarCode("P".$pdf->orderNum);
/*
mkBarCode("S".$pdf->orderNum,10);
mkBarCode("R".$pdf->orderNum,10);
*/

// Create our first page
$pdf->Open();
$pdf->AddPage();

//...... Drops in header image 400x100
$pdf->Image("img/frameborder.png",9,55,219.5,150); 
foreach($items as $item)
{
	$totalQuan += $item['quantity'];
	$totalCost += $item['amount'] * $item['quantity'];
}

$pdf->ln(1);
$pdf->SetFont("Arial", "B", 15);
$pdf->SetFillColor('240','240','240');
$pdf->Cell(0,7,"# Items ordered: ".number_format($totalQuan)." Total Cost: $".number_format($totalCost,2),0,1,'L',1);

$pdf->ln(1);

$pdf->SetFillColor('255','255','255');
$pdf->SetFont("Arial", "B", 14);
$pdf->Cell(100,7,"Description",0,0,'L',1);
$pdf->Cell(50,7,"Quantity",0,0,'L',1);
$pdf->Cell(45,7,"Cost Ea",0,0,'L',1);


$pdf->ln(3);
$pdf->SetFont("Courier", '', 10);
foreach($items as $item)
{
	//..... Width between lines here
	$pdf->ln(5);
	($i++ %2 == 0) ? $pdf->SetFillColor('240','240','240') : $pdf->SetFillColor('255','255','255');
	$pdf->Cell(100,5,$item['title'],0,0,'L',1);
	$pdf->Cell(50,5,$item['quantity'],0,0,'L',1);
	$pdf->Cell(45,5,$item['amount'],0,0,'L',1);
}

$pdf->setXY(10,150);
$pdf->ln(25);

/*
//...... Puts a black line
$pdf->Cell($subWidth,1,'',"B",0,'L',0);

//...... Allows me to put biz addr in correct spot
$pageWidth = $pdf->getX();
$yLoc = $pdf->getY();
$pdf->ln(20);

//...... Shipped Flag BarCode
$pdf->Image("S".$pdf->orderNum.".png",10,$yLoc+10);
$pdf->ln(3);

$pdf->SetFont("Arial", "", 10);
$pdf->MultiCell($pageWidth / 2,5,"$pdf->bizName\n$pdf->bizAddr",0,'J');
$pdf->ln(5);

//...... Writes the "SHIP TO Declaration"
$pdf->SetFont("Arial", "B", 22);
$pdf->write(10,"Ship To:");

//...... Writes the user's address
$pdf->SetFont("Arial", "", 14);
$pdf->MultiCell($pageWidth / 2,7,$pdf->userAddr,0,'J');

//...... Write RMA BarCode
$pdf->Image("R".$pdf->orderNum.".png",$pageWidth / 2 + 10,$yLoc+10);

//...... Writes the business address
$pdf->setXY($pageWidth / 2 + 10,$yLoc + 25);

//...... Writes the user's address
$pdf->SetFont("Arial", "B", 22);
$pdf->write(10,"Ship To:");

$pdf->SetFont("Arial", "", 14);;
$pdf->MultiCell($pageWidth,7,"ATTN: RMA\n$pdf->bizName\n$pdf->bizAddr",0,'L');
//...... Drops in Barcode image
*/

// Output the finished product
$pdf->Output();

//...... don't need these any more.
/*
unlink("R".$pdf->orderNum.".png");
unlink("S".$pdf->orderNum.".png");
*/
unlink("P".$pdf->orderNum.".png");

exit();

?>
