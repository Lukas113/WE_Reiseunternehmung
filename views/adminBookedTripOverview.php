<?php

/**
 * @author Adrian Mathys
 */
use views\TemplateView;
use entities\Trip;
use entities\TripTemplate;
use entities\User;
use helpers\DefaultPath;

isset($this->trip) ? $trip = $this->trip : $trip = new Trip();
if (isset($this->trip) and $trip) {
    $tripTemplate = $trip->getTripTemplate();
} else {
    $tripTemplate = new TripTemplate();
}
if ($tripTemplate->getDayprograms()) {
    $dayprograms = $tripTemplate->getDayprograms();
} else {
    $dayprograms = array();
}
if (isset($this->trip) and $trip) {
    $participants = $trip->getParticipants();
} else {
    $participants = array();
}
if (isset($this->trip) and $trip) {
    $user = $trip->getUser();
} else {
    $user = new User();
}
if (isset($this->trip) and $trip) {
    $invoices = $trip->getInvoices();
} else {
    $invoices = array();
}
?>


<body>
    <div class="border rounded-0 register-photo" style="font-family: Capriola, sans-serif;background-size: auto;min-height: 100vh;padding-top: 0px;">
        <div style="padding-bottom: 52px;">
            <div class="container-fluid" style="margin-top: 81px;">
                <h2 class="text-center" style="margin-bottom: 16px;"><strong>
                        <?php echo TemplateView::noHTML($user->getFirstName() . " " . $user->getLastName() . " booked the trip \"" . $tripTemplate->getName() . "\"."); ?>
                    </strong></h2>
                <div class="scrollableDiv"><!DOCTYPE html>
                    <html lang="en">
                        <head>
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                        </head>
                        <body>

                            <div class="container" style="font-family: Capriola, sans-serif;">

                                <table id="tripOverviewTable" class="tableStyle">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Trip name</th>
                                            <th>Description</th>
                                            <th>Departure date</th>
                                            <?php if($trip->getInsurance()): ?><th>Insurance</th><?php endif; ?>
                                            <th>Bus</th>
                                            <th>Internal price from CHF</th>
                                            <th>Customer price from CHF</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tripTableBody">
                                        <tr>
                                            <td><a target="_blank" href="<?php if($tripTemplate){if(file_exists($tripTemplate->getPicturePath())){echo TemplateView::noHTML($tripTemplate->getPicturePath());}else{echo DefaultPath::getTripTemplate();}} ?>"><img src="<?php if($tripTemplate){if(file_exists($tripTemplate->getPicturePath())){echo TemplateView::noHTML($tripTemplate->getPicturePath());}else{echo DefaultPath::getTripTemplate();}} ?>" alt="Not available" border=3 width=150></a></td>
                                            <td><?php if($tripTemplate){echo TemplateView::noHTML($tripTemplate->getName());} ?></td>
                                            <td><?php if($tripTemplate){echo TemplateView::noHTML($tripTemplate->getDescription());} ?></td>
                                            <td><?php echo TemplateView::noHTML($trip->getDepartureDate()); ?></td>
                                            <?php if($trip->getInsurance()): ?><td><?php echo TemplateView::noHTML($trip->getInsurance()->getName()); ?></td><?php endif; ?>
                                            <td><?php if($tripTemplate and $tripTemplate->getBus()){echo TemplateView::noHTML($tripTemplate->getBus()->getName()) . " (seats: " . TemplateView::noHTML($tripTemplate->getBus()->getSeats()) . ")";} ?></td>
                                            <td><?php echo TemplateView::noHTML(number_format($trip->getPrice(),2)); ?></td>
                                            <td><?php echo TemplateView::noHTML(number_format($trip->getCustomerPrice(),2)); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </body>
                    </html>
                </div>

                <!-- Button to cancel a trip booking -->
                <div class="border rounded shadow d-flex d-sm-flex d-md-flex d-lg-flex justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-center" style="margin-top: 30px; margin-left: 15%; margin-right: 15%;">
                    <form action="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/bookedTrips/detail/<?php echo $trip->getId(); ?>" method="POST" class="d-md-flex justify-content-md-center" style="background-color: transparent; padding: 0px;">
                        <input type="hidden" name="_method" value="DELETE"><div class="text-center" >
                            <p style="margin-bottom: 20px;margin-top: 15px;color: crimson;">Do you want to cancel the customer's booking?</p>
                            <button class="btn btn-danger" type="submit" id="btnCancelBooking" style="margin-top: 0px;margin-bottom: 15px;">Cancel <?php echo TemplateView::noHTML($user->getFirstName() . "'s " . " trip booking"); ?></button></div>
                    </form>
                </div>

            </div>
            <div class="container-fluid text-center border rounded-0 border-dark" id="containerTripParticipants" style="margin-top: 50px;padding-top: 15px;padding-bottom: 15px;">
                <h4 class="text-center" style="margin-bottom: 16px;"><strong>User and participants</strong> of the selected trip.<br></h4>
                <div><a class="btn btn-secondary" data-toggle="collapse" aria-expanded="false" aria-controls="collapseParticipants" role="button" href="#collapseParticipants" style="margin-bottom: 10px;">Show/hide participants</a>
                    <div class="collapse" id="collapseParticipants">
                        <div>
                            <fieldset style="margin-bottom: 20px;margin-top: 10px;"><div><label style="color: darkorange;">Booked by</label></div>
                                <div style="overflow-x: auto;" ><textarea name="userName" value="" readonly="" style="margin-left: 10px; text-align: center; min-width: 350px; width: 400px;margin-right: 0px;min-height: 130px;"><?php
                                        echo TemplateView::noHTML($user->getFirstName() . " " . $user->getLastName() . "\n"
                                                . $user->getStreet() . "\n" . $user->getZipCode() . " " . $user->getLocation() . "\n" . $user->getEmail() . "\n" . $user->getBirthDate());
                                        ?></textarea></div></fieldset>
                        </div>
                        <div class="text-left">
                            <!DOCTYPE html>
                            <html lang="en">
                                <head>
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                                    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                                </head>
                                <body>

                                    <div class="container" style="font-family: Capriola, sans-serif;">

                                        <input class="form-control" id="tripParticipantInput" type="text" placeholder="Search participants...">
                                        <br>
                                        <table id="tripParticipantTable" class="tableStyle">
                                            <thead>
                                                <tr>
                                                    <th>First name</th>
                                                    <th>Last name</th>
                                                    <th>Birth date</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tripParticipantTableBody">
                                                <?php foreach ($participants as $participant): ?>
                                                    <tr>
                                                        <td><?php echo TemplateView::noHTML($participant->getFirstName()); ?></td>
                                                        <td><?php echo TemplateView::noHTML($participant->getLastName()); ?> </td>
                                                        <td><?php echo TemplateView::noHTML($participant->getBirthDate()); ?> </td>
                                                    <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <script>

                                        //Make the table searchable
                                        $(document).ready(function () {
                                            $("#tripParticipantInput").on("keyup", function () {
                                                var value = $(this).val().toLowerCase();
                                                $("#tripParticipantTableBody tr").filter(function () {
                                                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                                });
                                            });
                                        });

                                    </script>

                                </body>
                            </html></div>
                    </div>
                </div>
            </div>
            <div class="container-fluid text-center border rounded-0 border-dark" id="containerDayPrograms" style="margin-top: 50px;padding-top: 15px;padding-bottom: 15px;">
                <h4 class="text-center" style="margin-bottom: 16px;"><strong>Day programs</strong> of the selected trip.<br></h4>
                <div><a class="btn btn-secondary" data-toggle="collapse" aria-expanded="false" aria-controls="collapseDayPrograms" role="button" href="#collapseDayPrograms" style="margin-bottom: 10px;">Show/hide day programs</a>
                    <div class="collapse" id="collapseDayPrograms">
                        <div class="text-left d-xl-flex justify-content-xl-center"><!DOCTYPE html>
                            <html lang="en">
                                <head>
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                                    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                                </head>
                                <body>

                                    <div class="container" style="font-family: Capriola, sans-serif;">

                                        <table id="dayProgramsOverviewTable" class="tableStyle">
                                            <thead>
                                                <tr>
                                                    <th>Day</th>
                                                    <th>Image</th>
                                                    <th>Name</th>
                                                    <th style="max-width: 240px;">Description</th>
                                                    <th>Hotel name</th>
                                                    <th>Hotel image</th>
                                                    <th style="min-width: 170px;">Hotel description</th>
                                                    <th>Hotel price</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dayProgramsTableBody">
                                                <?php foreach ($dayprograms as $dayprogram): ?>
                                                    <tr>
                                                        <td><?php echo TemplateView::noHTML($dayprogram->getDayNumber()); ?></td>
                                                        <td><img src="<?php if($dayprogram){if(file_exists($dayprogram->getPicturePath())){echo TemplateView::noHTML($dayprogram->getPicturePath());}else{echo DefaultPath::getDayprogram();}} ?>" alt="Not available" border=3 width=150></td>
                                                        <td><?php echo TemplateView::noHTML($dayprogram->getName()); ?></td>
                                                        <td><?php echo TemplateView::noHTML($dayprogram->getDescription()); ?></td>
                                                        <td><?php if ($dayprogram->getHotel()){echo TemplateView::noHTML($dayprogram->getHotel()->getName());} ?></td>
                                                        <td><?php if ($dayprogram->getHotel()): ?><img src="<?php if(file_exists($dayprogram->getHotel()->getPicturePath())){echo TemplateView::noHTML($dayprogram->getHotel()->getPicturePath());}else{echo DefaultPath::getHotel();} ?>" alt="Not available" border=3 width=150><?php endif; ?></td>
                                                        <td><?php if ($dayprogram->getHotel()){echo TemplateView::noHTML($dayprogram->getHotel()->getDescription());} ?></td>
                                                        <td><?php if ($dayprogram->getHotel()){echo TemplateView::noHTML(number_format($dayprogram->getHotel()->getPricePerPerson(),2));} ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </body>
                            </html>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div role="tablist" id="accordionTripAdmin" style="margin-left: 0px;">
            <div class="card">
                <div class="card-header" role="tab">
                    <h5 class="mb-0"><a data-toggle="collapse" aria-expanded="false" aria-controls="accordionTripAdmin .item-1" href="div#accordionTripAdmin .item-1">Administration of trip invoices</a></h5>
                </div>
                <div class="collapse item-1 card-body" role="tabpanel" data-parent="#accordionTripAdmin">

                    <!-- Button to generate the final invoice -->
                    <div style="margin-left: 0%; margin-right: 40%"class="text-center border rounded shadow d-flex d-sm-flex d-md-flex d-lg-flex justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-center">
                        <form action="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/bookedTrips/detail/<?php echo $trip->getId(); ?>" method="POST" class="d-md-flex justify-content-md-center" style="background-color: transparent; margin: 10px; padding-top: 0px;padding-right: 0px;padding-bottom: 0px;padding-left: 0px;">
                            <input type="hidden" name="_method" value="PUT">
                            <div class="text-center" >
                                <p style="margin-bottom: 15px;margin-top: 15px;color: #000000;">
                                    <?php if ($trip->getInvoicesRegistered()): ?>
                                        Would you like to generate the final invoice?</p>
                                <?php endif; ?>

                                <?php if (!$trip->getInvoicesRegistered()): ?>
                                    Are there no more invoices to this trip?</p>
                                <?php endif; ?>
                                <button class="btn btn-info" type="submit" id="btnInvoicesComplete" style="margin-top: 0px;margin-bottom: 15px;">Prepare final invoice</button>
                            </div>
                        </form>
                    </div>

                    <!-- Button to view the customer invoice -->
                    <div style="margin-top: 15px; margin-left: 20%; margin-right: 20%"class="text-center border rounded shadow d-flex d-sm-flex d-md-flex d-lg-flex justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-center">
                        <form action="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/bookedTrips/detail/invoices/<?php echo $trip->getId(); ?>" method="GET" class="d-md-flex justify-content-md-center" style="background-color: transparent; margin: 10px; padding-top: 0px;padding-right: 0px;padding-bottom: 0px;padding-left: 0px;">
                            <div class="text-center">
                                <p style="margin-bottom: 15px;margin-top: 15px;color: #000000;">Would you like to view the customer invoice?</p>
                                <button class="btn btn-warning" type="submit" id="btnViewCustomerInvoice" style="color: white; margin-top: 0px;margin-bottom: 11px;">Show customer invoice</button>
                            </div> </form>
                    </div>

                    <!-- Button to add additional invoices after generating the final invoice -->
                    <?php if ($trip->getInvoicesRegistered()): ?>
                        <div style="margin-top: 15px; margin-left: 40%; margin-right: 0%" class="text-center border shadow d-flex d-sm-flex d-md-flex d-lg-flex justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-center">
                            <form action="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/bookedTrips/detail/<?php echo $trip->getId(); ?>/1" method="POST" class="d-md-flex justify-content-md-center" style="margin: 10px; background-color: transparent; padding-top: 0px;padding-right: 0px;padding-bottom: 0px;padding-left: 0px;">
                                <input type="hidden" name="_method" value="PUT"><div class="text-center" >
                                    <p style="margin-bottom: 15px;margin-top: 15px;color: #000000;">Are there more invoices?</p>
                                    <button class="btn btn-success" type="submit" id="btnInvoicesComplete" style="margin-top: 0px;margin-bottom: 11px;">Upload more invoices</button></div>
                            </form>
                        </div>

                    <?php endif; ?>
                    <div id="collapseInvoices" style="margin-bottom: 0px;padding-bottom: 0px;padding-top: 0px;"><a class="btn btn-secondary" data-toggle="collapse" aria-expanded="false" aria-controls="collapse-2" role="button" href="#collapse-2" style="margin-top: 30px;">Show/hide all uploaded invoices</a>
                        <div class="collapse" id="collapse-2"
                             style="margin-top: 0px;">
                            <div class="border rounded-0 border-dark" style="padding-left: 15px;max-width: 1100px;padding-top: 0px;padding-bottom: 0px;padding-right: 15px;background-color: rgba(255,255,255,0.61);">
                                <h4 class="text-left" style="margin-bottom: 16px;margin-top: 18px;min-width: 500px;"><strong>Already uploaded invoices.</strong><br></h4>
                                <div class="table-responsive" id="tableUploadedInvoices">
                                    <table class="table table-striped table-hover table-sm" style="min-width: 700px;">
                                        <thead>
                                            <tr>
                                                <th>Invoice type</th>
                                                <th>Description</th>
                                                <th>Date</th>
                                                <th>Amount in CHF</th>
                                                <th>Download PDF</th>
                                                <?php if (!$trip->getInvoicesRegistered()): ?>
                                                    <th>Delete</th>
                                                <?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($invoices as $invoice): ?>
                                                <tr>
                                                    <td><?php echo $invoice->getType(); ?></td>
                                                    <td><?php echo $invoice->getDescription(); ?></td>
                                                    <td><?php echo $invoice->getDate(); ?></td>
                                                    <td><?php echo (number_format($invoice->getPrice(),2)); ?></td>
                                                    <td><a href="<?php if(file_exists($invoice->getPdfPath())){echo TemplateView::noHTML($invoice->getPdfPath());}else{echo DefaultPath::getInvoice();} ?>" download="<?php if(file_exists($invoice->getPdfPath())){echo TemplateView::noHTML($invoice->getFileName());}else{echo DefaultPath::getInvoiceFileName();} ?>">
                                                            <img src="assets/img/paper-clip.png" alt="Download" width="25px" height="25px">
                                                        </a></td>
                                                    <?php if (!$trip->getInvoicesRegistered()): ?>    
                                                        <td><form style="background-color: transparent; padding: 0px; margin: 0px; min-width: 0px; max-width: 15px; min-height: 0px" id="deleteInvoice<?php echo $invoice->getId(); ?>" action="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/bookedTrips/detail/<?php echo $invoice->getId() . "/" . $trip->getId(); ?>" method="post">
                                                                <input type="hidden" name="_method" value="DELETE"><img src="assets/img/Recycle_Bin.png" alt="Remove"  border=3 height=20 width=20 onclick="deleteHandler(<?php echo $invoice->getId(); ?>)"></form></td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!$trip->getInvoicesRegistered()): ?>
                        <div style="overflow-x: auto;">    
                            <div class="border rounded-0 border-primary shadow form-container" style="margin-top: 30px; max-width: 500px;">
                                <h4 class="text-center" style="margin-bottom: 16px;margin-top: 18px;"><strong>Add a new invoice to the trip.</strong><br></h4>

                                <div style="margin: 15px; background-color: rgba(176,224,147,0.36); padding-right: 20px;">

                                    <form class="form-inline" target="_bottom" action="<?php echo $GLOBALS['ROOT_URL']; ?>/admin/bookedTrips/detail" method="post" enctype="multipart/form-data" id="invoiceForm" style="background-color: transparent; padding-right: 0px;padding-bottom: 30px;padding-top: 0px;padding-left: 30px;">
                                        <input type="hidden" name="tripId" value="<?php echo $trip->getId(); ?>">
                                        <div class="form-group" style=""><label class="labelsFormDayProgram" style="width: 300px; text-align: left; display:block;">Type of invoice</label><select class="form-control" name="type" required="" id="type" style="width: 400px;">
                                                <optgroup label="Select an invoice type">
                                                    <option value="hotel" selected="">Hotel</option>
                                                    <option value="insurance">Insurance</option>
                                                    <option value="bus">Bus</option>
                                                    <option value="other">Other</option></optgroup></select></div>
                                        <div
                                            class="form-group"><label class="labelsFormDayProgram" style="width: 300px; text-align: left; display:block;">Description of invoice</label><textarea class="form-control" name="description" required="" minlength="3" id="description" style="height: 100px; width: 400px;"></textarea></div>
                                        <div
                                            class="form-group"><label class="labelsFormDayProgram" style="width: 300px; text-align: left; display:block;">Date of invoice</label><input class="form-control" type="date" name="date" required="" id="date" style="width: 400px;"></div>
                                        <div class="form-group"><label class="labelsFormDayProgram" style="width: 300px; text-align: left; display:block;">Amount of invoice in CHF</label><input class="form-control" type="number" name="price" required="" min="0" step="0.05" id="price" style="width: 400px;"></div>
                                        <div class="form-group mt-auto"><label class="labelsFormDayProgram" style="width: 300px; text-align: left; display:block;">PDF of invoice</label><input type="file" name="invoice" required="" id="pdfPath" style="background: transparent; width: 400px;"></div>
                                        <button
                                            class="btn btn-primary btn-block" type="submit">Save</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    //Remove invoice
    function deleteHandler(invoiceId) {
        var c = confirm("Do you want to delete this invoice?");
        if (c) {
            $("#deleteInvoice" + invoiceId).submit();
        }
    }
</script>
<script src="assets/js/Sidebar-Menu.js"></script>
