<?php


use entities\Trip;

include("fpdf/fpdf.php");

$trip = Trip::findTrip();

if(!$trip){
    exit();
}
ob_start();


    
 /*
  * Creates the final settlement for a selected trip.
  * All calculated and actual costs are displayed as well as all revenues.
  * 
 * @author Vanessa Cajochen
 */
class PDF extends \FPDF
{
    
// Page header
function Header(){      
            
    // Logo
    $this->Image('pdf/logo.jpeg',90,6,30);
    $this->Ln(30);
    
    $this->Line(10, 40, 200, 40);
    $this->Line(10, 55, 200, 55);
    
    $this->SetFont('Helvetica','I',33);
    $this->SetTextColor(233, 156, 28);
    $this->Cell(55,15,'FINAL SETTLEMENT',0,0);
         
    $this->Ln(31);    
    
    $this->Line(10, 75, 50, 75);
    $this->Line(60, 75, 100, 75);
    $this->Line(110, 75, 150, 75);
    $this->Line(160, 75, 200, 75);   

    $this->SetTextColor(0,0,0);
    
    // Trip details
    $this->SetFont('Arial','BI',8);
    $this->Cell(30,4,'Trip #',0,0);
    $this->Cell(20);
    $this->Cell(30,4,'Booking date',0,0);
    $this->Cell(20);
    $this->Cell(30,4,'Departure Date',0,0);
    $this->Cell(20);
    $this->Cell(30,4,'Trip Name',0,1);
    $this->Ln(1);        
    $this->SetFont('Arial','',8);
    
    
    // Invoice ID
    $this->Cell(30,4,'#'.$this->tripID,0,0);
    $this->Cell(20);    
    // Booking date
    $this->Cell(30,4,''.$this->bookingDate,0,0);
    $this->Cell(20);
    // Booking date + 30 days
    $this->Cell(30,4,''.$this->departureDate,0,0);
    $this->Cell(20);    
    // Trip name
    $this->Cell(30,4,''.$this->tripName,0,1);     
    
    $this->Ln(20); 
}


// Page footer
function Footer()
{
    // Position at 2.5 cm from bottom
    $this->SetY(-25);
    $this->SetFont('Arial','B',8);
    $this->SetTextColor(0,0,0);
    $this->Cell(45,4,'Dream Trips',0,1);    
    
    // Company details
    $this->SetFont('Arial','',8);
    $this->Cell(25,4,'Bahnstrasse 1',0,0);
    $this->Cell(48,4,'IBAN: CH8209000000603591064',0,0);
    $this->Cell(45,4,'P: +41 56 424 24 24',0,1);
    $this->Cell(25,4,'3008 Bern',0,0);
    $this->Cell(48,4,'BIC: POFICHBEXXX',0,0);
    $this->Cell(45,4,'M: info@dreamtrips.ch',0,1);
        
    // Thank you
    $this->SetY(-27);
    $this->Cell(119);
    $this->SetFont('Helvetica','I',32);
    $this->SetTextColor(233, 156, 28);
    $this->Cell(70,15,'THANK YOU!',0,0);
    
    $this->Line(10, 270, 200, 270);
    $this->Line(10, 285, 200, 285);

}

// Creates the table which contains the costs and the revenues
function CreateTable($trip)
{
    $this->SetY(100);
    $this->SetFont('Helvetica','I',15);
    $this->SetTextColor(233, 156, 28);
    $this->Cell(110,15,'Costs',0,0);
    $this->Cell(50,15,'Revenue',0,0);
    
    $this->SetY(116);
    $this->SetTextColor(0,0,0);
    $this->Line(10, 120, 110, 120);
    $this->Line(120, 120, 200, 120);
    
    
    // column names
    $this->SetFont('Arial','BI',8);
    $this->Cell(22,4,'Description',0,0);
    $this->Cell(30,4,'Calculated costs',0,0,'R');
    $this->Cell(30,4,'Actual costs',0,0,'R');
    $this->Cell(18,4,'Delta',0,0,'R');
    $this->Cell(10);
    $this->Cell(30,4,'Description',0,0);
    $this->Cell(30,4,'Revenue',0,1,'R');
    
    
    // Row with hotel costs and revenues
    $this->SetFont('Arial','',8);
    $this->Cell(22,5,'Hotel',0,0);
    $this->Cell(30,5,'CHF '.$this->hotelCalcCost,0,0,'R');
    $this->Cell(30,5,'CHF '.$this->hotelActualCost,0,0,'R');
    
    // If delta is positive the font color becomes green, if delta is negative the font color becomes red.
    if ($this->hotelDelta < 0){
        $this->SetTextColor(255, 0, 0);
    } else if ($this->hotelDelta > 0){
        $this->SetTextColor(0, 153, 0);
    }
    $this->Cell(18,5,''.$this->hotelDelta.'%',0,0,'R');
    $this->SetTextColor(0,0,0);
    $this->Cell(10);
    $this->Cell(30,5,'Hotel',0,0);
    $this->Cell(30,5,'CHF '.$this->hotelRevenue,0,1,'R');
    
    $this->SetDrawColor(217, 217, 217);
    $this->Line(10, 125, 110, 125);    
    $this->Line(120, 125, 200, 125);
 
    
    //Row with bus costs and revenues
    $this->SetFont('Arial','',8);
    $this->Cell(22,5,'Bus',0,0);
    $this->Cell(30,5,'CHF '.$this->busCalcCost,0,0,'R');
    $this->Cell(30,5,'CHF '.$this->busActualCost,0,0,'R');
    
    // If delta is positive the font color becomes green, if delta is negative the font color becomes red.
    if ($this->busDelta < 0){
        $this->SetTextColor(255, 0, 0);
    } else if ($this->busDelta > 0){
        $this->SetTextColor(0, 153, 0);
    }
    $this->Cell(18,5,''.$this->busDelta.'%',0,0,'R');
    $this->SetTextColor(0,0,0);
    $this->Cell(10);
    $this->Cell(30,5,'Bus',0,0);
    $this->Cell(30,5,'CHF '.$this->busRevenue,0,1,'R');
    
    $this->Line(10, 130, 110, 130);
    $this->Line(120, 130, 200, 130);
    
    
    //Row with insurance costs and revenues if insurance was booked
    $this->Cell(22,5,$this->insuranceText,0,0);
    $this->Cell(30,5,'CHF '.$this->insuranceCalcCost,0,0,'R');
    $this->Cell(30,5,'CHF '.$this->insuranceActualCost,0,0,'R');
    
    // If delta is positive the font color becomes green, if delta is negative the font color becomes red.
    if ($this->insuranceDelta < 0){
        $this->SetTextColor(255, 0, 0);
    } else if ($this->insuranceDelta > 0){
        $this->SetTextColor(0, 153, 0);
    }
    $this->Cell(18,5,''.$this->insuranceDelta.'%',0,0,'R');
    $this->SetTextColor(0,0,0);
    $this->Cell(10);
    $this->Cell(30,5,'Insurance',0,0);
    $this->Cell(30,5,'CHF '.$this->insuranceRevenue,0,1,'R');    
    
    
    // Row with other costs and if additional costs were recorded
    $this->Cell(22,5,'Other',0,0);
    $this->Cell(30,5,'CHF '.$this->otherCalcCost,0,0,'R');
    $this->Cell(30,5,'CHF '.$this->otherActualCost,0,0,'R');    
    $this->Cell(18,5,''.$this->otherDelta.'%',0,0,'R');
    $this->Ln(5);   
 
    $this->Line(10, 130, 110, 130);
    $this->Line(10, 135, 110, 135);
    
    $this->SetDrawColor(0, 0, 0);
    $this->Line(10, 140, 110, 140);
    $this->Line(120, 140, 200, 140);    
    $this->Ln(5);    
    $this->Line(10, 150, 110, 150);  
    $this->Line(120, 150, 200, 150); 
    
    
    
    // Row with subtotals    
    $this->SetFont('Arial','BI',8);
    $this->Cell(22,5,'Total',0,0);
    $this->SetFont('Arial','',8);
    $this->Cell(30,5,'CHF '.$this->calcCostTotal,0,0,'R');
    $this->Cell(30,5,'CHF '.$this->actualCostTotal,0,0,'R');
    
    // If delta is positive the font color becomes green, if delta is negative the font color becomes red.
    if ($this->totalDelta < 0){
        $this->SetTextColor(255, 0, 0);
    } else if ($this->totalDelta > 0){
        $this->SetTextColor(0, 153, 0);
    }
    $this->Cell(18,5,''.$this->totalDelta.'%',0,0,'R');
    $this->SetTextColor(0,0,0);
    $this->Cell(10);
    $this->SetFont('Arial','BI',8);
    $this->Cell(30,5,'Total',0,0);
    $this->SetFont('Arial','',8);
    $this->Cell(30,5,'CHF '.$this->totalRevenue,0,1,'R');

    
    // Row with gross profit  
    $this->SetY(160);
    $this->Line(10, 165, 50, 165);
    $this->SetFont('Arial','BI',8);
    $this->Cell(30,5,'Gross profit',0,0);
    $this->Ln(3);    
    $this->SetFont('Helvetica','I',15);
    $this->SetTextColor(233, 156, 28);
    $this->Cell(80,15,'CHF '.$this->grossProfit,0,0);   

}
}

// Calculates how many 0 should be appended before the trip id. Trip id should always have 8 digits.
function serializeTripID($tripID) {
    while (strlen($tripID) < 8) {
        $tripID = "0".$tripID;
    }
    return $tripID;
}



$pdf = new PDF();

// Filling Variables with values from the Database
$hotelCalcCost = ($trip->getHotelPrice());
$hotelActualCost = ($trip->getInvoicePrice("hotel"));
$hotelDelta = (($hotelActualCost - $hotelCalcCost)/($hotelCalcCost/-100));
$busCalcCost = ($trip->getTripTemplate()->getBusPrice());
$busActualCost = ($trip->getInvoicePrice("bus"));
$busDelta = (($busActualCost - $busCalcCost)/($busCalcCost/-100));
$hotelRevenue = $trip->getTripTemplate()->getCustomerHotelPricePerPerson() * ($trip->getNumOfParticipation());
$busRevenue = ($trip->getTripTemplate()->getCustomerBusPrice());
$otherCalcCost = 0;
$otherActualCost = 0;


// Checks whether insurance has been booked. If there is none, the text is adjusted and the subtotal and gross profit are calculated differently.
if($trip->getInsurance()){    
    $insuranceRevenue = ($trip->getInsuranceCustomerPrice());
    $totalRevenue = $hotelRevenue + $busRevenue + $insuranceRevenue;
    $insuranceCalcCost = ($trip->getInsurancePrice());
    $insuranceActualCost = ($trip->getInvoicePrice("insurance"));
    $insuranceDelta = (($insuranceActualCost - $insuranceCalcCost)/($insuranceCalcCost/-100));
    $calcCostTotal = $hotelCalcCost + $busCalcCost + $insuranceCalcCost;
    $actualCostTotal = $hotelActualCost + $busActualCost + $insuranceActualCost;
    
    $pdf->insuranceRevenue = number_format($insuranceRevenue,2);
    $pdf->insuranceCalcCost = number_format($insuranceCalcCost,2);
    $pdf->insuranceActualCost = number_format($insuranceActualCost,2);
    $pdf->insuranceDelta = number_format($insuranceDelta,1);
    $pdf->insuranceText = "Insurance";
} else{
    $insuranceDelta = "-";
    $calcCostTotal = $hotelCalcCost + $busCalcCost;
    $actualCostTotal = $hotelActualCost + $busActualCost;
    $insuranceRevenue = "-";
    $totalRevenue = $hotelRevenue + $busRevenue;
    
    $pdf->insuranceRevenue = $insuranceRevenue;
    $pdf->insuranceCalcCost = "-";
    $pdf->insuranceActualCost = "-";
    $pdf->insuranceDelta = "-";
    $pdf->insuranceText = "No insurance";
}


// Checks whether other costs has been recorded. If there is none, the text is adjusted and the subtotal and gross profit are calculated differently.
if($trip->getInvoicePrice('other')){ 
    $otherActualCost = $trip->getInvoicePrice('other');
    $actualCostTotal = $actualCostTotal + $otherActualCost;
}


// Filling Variables with values from the Database
// Depends on the values that are filled in the if /else above, depending on whether it has insurance or not.
$totalDelta = (($actualCostTotal - $calcCostTotal)/($calcCostTotal/-100));
$grossProfit = $totalRevenue - $actualCostTotal;


// Writes the values to the table
$pdf->tripID = serializeTripID($trip->getId());
$pdf->tripName = $trip->getTripTemplate()->getName();
$pdf->bookingDate = $trip->getBookingDate();
$pdf->departureDate = $trip->getDepartureDate();

$pdf->hotelCalcCost = number_format($hotelCalcCost,2);
$pdf->hotelActualCost = number_format($hotelActualCost,2);
$pdf->hotelDelta = number_format($hotelDelta,1);

$pdf->busCalcCost = number_format($busCalcCost,2);
$pdf->busActualCost = number_format($busActualCost,2);
$pdf->busDelta = number_format($busDelta,1);

$pdf->otherCalcCost = number_format($otherCalcCost,2);
$pdf->otherActualCost = number_format($otherActualCost,2);
$pdf->otherDelta = "-";

$pdf->calcCostTotal = number_format($calcCostTotal,2);
$pdf->actualCostTotal = number_format($actualCostTotal,2);
$pdf->totalDelta = number_format($totalDelta,1);

$pdf->hotelRevenue = number_format($hotelRevenue,2);
$pdf->busRevenue = number_format($busRevenue,2);

$pdf->totalRevenue = number_format($totalRevenue,2);
$pdf->grossProfit = number_format($grossProfit,2);
         


$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->CreateTable($trip);
$pdf->SetTitle('Final settlement');


$pdf->Output();
ob_end_flush();
