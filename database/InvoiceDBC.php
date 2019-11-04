<?php

namespace database;

use entities\Invoice;
use database\TripDBC;

/**
 * Provides secure access to the database of {@link Invoice} releated queries
 * <ul>
 * <li>{@link createInvoice($invoice)}</li>
 * <li>{@link deleteInvoice($invoice)}</li>
 * <li>{@link findInvoiceById($invoiceId, $close)}</li>
 * <li>{@link findTripInvoices($tripId, $checkInvoicesRegistered)}</li>
 * </ul>
 *
 * @author Lukas
 */
class InvoiceDBC extends DBConnector {
    
    /**
     * Stores the {@link Invoice} into the database
     * @param Invoice $invoice
     * @return boolean|int
     */
    public function createInvoice($invoice){
        //Validation if the Trip was booked
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM trip WHERE id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $tripId);
        $tripId = $invoice->getFkTripId();
        $stmt->execute();
        $success = $stmt->get_result()->fetch_object("entities\Trip");
        $stmt->close();
        if(!$success){
            return false;
        }
        
        //Storage of the Invoice
        $stmt = $this->mysqliInstance->prepare("INSERT INTO invoice VALUES (NULL, ?, ?, ?, ?, ?, ?)");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('sdsssi', $description, $price, $date, $type, $pdfPath, $fk_trip_id);
        $description = $invoice->getDescription();
        $price = $invoice->getPrice();
        $date = $invoice->getDate();
        $type = $invoice->getType();
        $pdfPath = $invoice->getPdfPath();
        $fk_trip_id = $invoice->getFkTripId();
        return $this->executeInsert($stmt);
    }
    
    /** 
     * Deletes the {@link Invoice} from the database
     * @param Invoice $invoice
     * @return boolean
     */
    public function deleteInvoice($invoice){
        $stmt = $this->mysqliInstance->prepare("DELETE FROM invoice WHERE id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $invoice->getId();
        return $this->executeDelete($stmt);
    }
    
    /**
     * Finds the {@link Invoice} by the given id
     * @param int $invoiceId
     * @param boolean $close
     * @return boolean|Invoice
     */
    public function findInvoiceById($invoiceId, $close = true){
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM invoice where id = ?");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $invoiceId;
        $stmt->execute();
        $invoiceObj = $stmt->get_result()->fetch_object("entities\Invoice");
        
        if($close){
            $stmt->close();
        }
        
        //checks whether the Invoice exists
        if($invoiceObj){
            return $invoiceObj;
        }else{
            return false;
        }
    }
    
    /**
     * Finds all {@link Invoice} according to the given tripId
     * @param int $tripId
     * @param boolean $checkInvoicesRegistered
     * @return boolean|array
     */
    public function findTripInvoices($tripId, $checkInvoicesRegistered = false){
        //Checks if all Invoices are recorded
        if($checkInvoicesRegistered){
            $tripDBC = new TripDBC();
            //shallow request
            $trip = $tripDBC->findTripById($tripId, true);
            if(!$trip->getInvoicesRegistered()){
                //here, not all Invoices are registered
                return false;
            }
        }
        
        //Gets all the Invoices from the Trip
        $stmt = $this->mysqliInstance->prepare("SELECT * FROM invoice WHERE fk_trip_id = ? ORDER BY type ASC");
        if(!$stmt){
            return false;
        }
        $stmt->bind_param('i', $id);
        $id = $tripId;
        $stmt->execute();
        $invoices = array();
        $result = $stmt->get_result();
        while($invoice = $result->fetch_object("entities\Invoice")){
            array_push($invoices, $invoice);
        }

        $stmt->close();
        return $invoices;
    }
    
}
