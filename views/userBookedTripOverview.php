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
if(isset($this->trip) and $trip){
    $tripTemplate = $trip->getTripTemplate();
}else{
    $tripTemplate = new TripTemplate();
}
if($tripTemplate->getDayprograms()){
    $dayprograms = $tripTemplate->getDayprograms();
}else{
    $dayprograms = array();
}
if(isset($this->trip) and $trip){
    $participants = $trip->getParticipants();
}else{
    $participants = array();
}
if(isset($this->trip) and $trip){
    $user = $trip->getUser();
}else{
    $user = new User();
}

?>

<body>
    <div class="border rounded-0 register-photo" style="font-family: Capriola, sans-serif;background-size: auto;min-height: 100vh;padding-top: 0px;">
        <div style="padding-bottom: 52px;">
            <div class="container-fluid" style="margin-top: 81px;">
                <h2 class="text-center" style="margin-bottom: 16px;"><strong>
                    Overview of your booked trip "<?php echo TemplateView::noHTML($tripTemplate->getName() . "\"."); ?>
                    </strong><br></h2>
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
                                            <th>Price in CHF</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tripTableBody">
                                        <tr>
                                            <td><a target="_blank" href="<?php if($tripTemplate){if(file_exists($tripTemplate->getPicturePath())){echo TemplateView::noHTML($tripTemplate->getPicturePath());}else{echo DefaultPath::getTrip();}} ?>"><img src="<?php if($tripTemplate){if(file_exists($tripTemplate->getPicturePath())){echo TemplateView::noHTML($tripTemplate->getPicturePath());}else{echo DefaultPath::getTrip();}} ?>" alt="Not available" border=3 width=150></a></td>
                                            <td><?php if($tripTemplate){echo TemplateView::noHTML($tripTemplate->getName());} ?></td>
                                            <td><?php if($tripTemplate){echo TemplateView::noHTML($tripTemplate->getDescription());} ?></td>
                                            <td><?php echo TemplateView::noHTML($trip->getDepartureDate()); ?></td>
                                            <?php if($trip->getInsurance()): ?><td><?php echo TemplateView::noHTML($trip->getInsurance()->getName()); ?></td><?php endif; ?>
                                            <td><img src="<?php if($tripTemplate and $tripTemplate->getBus()){if(file_exists($tripTemplate->getBus()->getPicturePath())){echo TemplateView::noHTML($tripTemplate->getBus()->getPicturePath());}else{echo DefaultPath::getBus();}} ?>" alt="Not available" border=3 width=150></td>
                                            <td><?php echo TemplateView::noHTML(number_format($trip->getCustomerPrice(),2)); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </body>
                    </html>
                </div>
            </div>
            <div class="container-fluid text-center border rounded-0 border-dark" id="containerTripParticipants" style="margin-top: 50px;padding-top: 15px;padding-bottom: 15px;">
                <h4 class="text-center" style="margin-bottom: 16px;"><strong>Participants</strong> of the trip.<br></h4>
                <div><a class="btn btn-secondary" data-toggle="collapse" aria-expanded="false" aria-controls="collapseParticipants" role="button" href="#collapseParticipants" style="margin-bottom: 10px;">Show/hide participants</a>
                    <div class="collapse" id="collapseParticipants">
                        <div>
                            <fieldset style="margin-bottom: 20px;margin-top: 10px;"><label>User</label><input type="text" name="userName" value="<?php echo TemplateView::noHTML($user->getFirstName()." ".$user->getLastName()); ?>" disabled="" readonly="" style="margin-left: 10px;min-width: 263px;"></fieldset>
                        </div>
                        <div class="text-left scrollableDiv">
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
                <h4 class="text-center" style="margin-bottom: 16px;"><strong>Day programs</strong> of the trip.<br></h4>
                <div><a class="btn btn-secondary" data-toggle="collapse" aria-expanded="false" aria-controls="collapseDayPrograms" role="button" href="#collapseDayPrograms" style="margin-bottom: 10px;">Show/hide day programs</a>
                    <div class="collapse" id="collapseDayPrograms">
                        <div class="text-left d-xl-flex justify-content-xl-center scrollableDiv"><!DOCTYPE html>
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
                                                    <th>Description</th>
                                                    <th>Hotel name</th>
                                                    <th>Hotel image</th>
                                                    <th style="min-width: 200px;">Hotel description</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dayProgramsTableBody">
                                                <?php foreach ($dayprograms as $dayprogram): ?>
                                                    <tr>
                                                        <td><?php echo TemplateView::noHTML($dayprogram->getDayNumber()); ?></td>
                                                        <td><img src="<?php if(file_exists($dayprogram->getPicturePath())){echo TemplateView::noHTML($dayprogram->getPicturePath());}else{echo DefaultPath::getDayprogram();} ?>" alt="Not available" border=3 width=150></td>
                                                        <td><?php echo TemplateView::noHTML($dayprogram->getName()); ?></td>
                                                        <td><?php echo TemplateView::noHTML($dayprogram->getDescription()); ?></td>
                                                        <td><?php if($dayprogram->getHotel()){echo TemplateView::noHTML($dayprogram->getHotel()->getName());} ?></td>
                                                        <td><?php if($dayprogram->getHotel()): ?><img src="<?php if(file_exists($dayprogram->getHotel()->getPicturePath())){echo TemplateView::noHTML($dayprogram->getHotel()->getPicturePath());}else{echo DefaultPath::getHotel();} ?>" alt="Not available" border=3 width=150><?php endif; ?></td>
                                                        <td><?php if($dayprogram->getHotel()){echo TemplateView::noHTML($dayprogram->getHotel()->getDescription());} ?></td>
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
            <div class="container-fluid text-center" id="containerInvoice" style="margin-top: 50px; max-width: 1100px;">
                <h4 class="text-center" style="margin-bottom: 16px;"><strong>Invoice </strong>for the trip.<br></h4>
                <div class="border rounded-0 border-dark scrollableDiv" style="padding-left: 15px;padding-top: 0px;padding-bottom: 0px;padding-right: 15px;background-color: rgba(255,255,255,0.61);">
                    <h4 class="text-left" style="margin-bottom: 16px;margin-top: 18px;min-width: 400px;"><strong>Your invoice.</strong><br></h4>
                    <div class="table-responsive text-left" id="tableFinalInvoice">
                        <table class="table table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Show Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Final invoice for trip "<?php echo $trip->getTripTemplate()->getName(); ?>"</td>
                                    <td><?php echo $trip->getDepartureDate(); ?></td>
                                    <td><?php echo (number_format($trip->getCustomerPrice(),2))." CHF"; ?></td>
                                    <td><!--<a href="assets/img/Beach.jpg" download="Name of file">--><a href="<?php echo $GLOBALS['ROOT_URL'] ?>/bookedTrips/detail/invoices/<?php echo $trip->getId(); ?>" >
                                            <img src="assets/img/paper-clip.png" alt="Download" width="25px" height="25px">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/Sidebar-Menu.js"></script>
