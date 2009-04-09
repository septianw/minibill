<?php
error_reporting('E_ALL ~E_NOTICE');


if ($argc)
{
	$_REQUEST['uniq_id'] = $argv[1];
	$_GET['user_id'] = $argv[2];
	$writeLocal			 = $argv[3];
	include("../config.php");
	include("../include/dbConnect.php");
	include("../include/functions.php");
}

require_once("../include/fpdf.php");

class Invoice extends FPDF
{
	var $orderNum = 0;

	function Header()
	{
		global $config;
		$this->orderNum = ($this->orderNum < 1) ? $this->orderNum = time() : $this->orderNum;

		//...... Drops in header image 400x100

		//...... Drops in Barcode image
		$this->Image("tmp/P".ltrim($this->orderNum,'0').".png",127,7);

		//...... Invoice Title
		/*
		$this->SetFont("Arial", "B", 20);
		$this->setXY(127,5);
		$this->Cell(100,5,$config['company']['name'],10,5,'L',0);
		*/

		$this->setXY(0,22);
		//...... Writes the business address
		$this->Image("../img/frameaddress.png",126,21.5,81,31);

		$this->setXY(127,22);
		$this->SetFont("Arial", "", 12);;
		//$this->MultiCell($subWidth,5,"$this->bizName\n$this->bizAddr\n$this->bizPhone\n$this->bizWebsite",0,0,'R');
		$this->MultiCell($subWidth,5,"$this->bizName\n$this->bizAddr\n$this->bizPhone\n$this->bizWebsite",0,'L');

		$this->SetFont("Arial", "B", 20);
		//$this->setXY(127,5);
		$this->setXY(5,15);
		$this->Cell(5,5,"Ship To:",10,5,'L',0);

		//...... Writes the user's address
		$this->SetFont("Arial", "", 12);;
		$this->setXY(10,22);
		$this->MultiCell($subWidth,5,$this->userAddr,0,'L');

		//...... Puts a black line
		//$this->ln(10);
		//$this->Cell($subWidth,$this->lineHeight+1,'',"B",1,'L',0);

		// Save the Y offset.  This is where the first block following the header will appear.
	}

} // End class

$Q="SELECT * FROM users,orders
        WHERE uniq_id='$_REQUEST[uniq_id]'
        AND users.id=user_id
        AND user_id='{$_GET['user_id']}'
        LIMIT 1";

list($user) = getResults($Q);

$Q="SELECT title,quantity,amount FROM orders,products
        WHERE uniq_id='$_REQUEST[uniq_id]'
        AND orders.product_id=products.id
        AND orders.user_id='{$_GET['user_id']}'";

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

include("../include/isoCodes.php");
$user['country'] = iso2str($user['country']);

$pdf->userAddr = <<<__EOT__
{$user['firstname']} {$user['lastname']}
{$user['email']}
{$user['address']}
{$user['city']}, {$user['state']}
{$user['zipcode']}
{$user['country']}
__EOT__;

$pdf->orderNum = $_REQUEST['uniq_id'];

include_once("../include/barcode.php");
mkBarCode("P".ltrim($pdf->orderNum,'0'),'tmp/');
/*
mkBarCode("S".$pdf->orderNum,10);
mkBarCode("R".$pdf->orderNum,10);
*/

// Create our first page
$pdf->Open();
$pdf->AddPage();

//...... Drops in header image 400x100
$pdf->Image("../img/frameborder.png",9,65,219.5,150); 
foreach($items as $item)
{
	$totalQuan += $item['quantity'];
	$totalCost += $item['amount'] * $item['quantity'];
}

$pdf->ln(15);
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
$pdf->ln(29);
$pdf->SetFont("Arial", "", 12);

$Q="SELECT content FROM templates WHERE title='{$config['mod_shipping']['policy_template']}'";
list($content) = mysql_fetch_row(mysql_query($Q));
$pdf->write(10,$content);

//$pdf->write(10,getcwd());

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
if (!isset($writeLocal))
	$pdf->Output($pdf->orderNum.".pdf",'D');
else
{
	// Output the finished product
	$pdf->Output("{$writeLocal}{$pdf->orderNum}.pdf");
}


//...... don't need these any more.
/*
unlink("R".$pdf->orderNum.".png");
unlink("S".$pdf->orderNum.".png");
*/
unlink("tmp/P".ltrim($pdf->orderNum,'0').".png");

unset($pdf);
if (!isset($writeLocal)) exit();

?>
