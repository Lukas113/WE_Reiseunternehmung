<?php

/**
 * @author Adrian Mathys
 */
use views\TemplateView;
use entities\User;
use entities\TripTemplate;
use helpers\DefaultPath;

isset($this->tripTemplate) ? $tripTemplate = $this->tripTemplate : $tripTemplate = new TripTemplate();
isset($this->insurances) ? $insurances = $this->insurances : $insurances = array();
isset($this->user) ? $user = $this->user : $user = new User();
if (isset($this->user) and $this->user and $user->getParticipants()) {
    $participants = $user->getParticipants();
} else {
    $participants = array();
}
if (isset($this->tripTemplate) and $this->tripTemplate and $tripTemplate->getDayprograms()) {
    $dayprograms = $tripTemplate->getDayprograms();
} else {
    $dayprograms = array();
}
?>

<body>
    <div class="border rounded-0 register-photo" style="font-family: Capriola, sans-serif;background-size: auto;min-height: 100vh;padding-top: 0px;">
        <div style="padding-bottom: 52px;">
            <div class="container-fluid" style="margin-top: 81px;">
                <h2 class="text-center" style="margin-bottom: 16px;"><strong>
                    Overview of the trip "<?php echo TemplateView::noHTML($tripTemplate->getName() . "\"."); ?>
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
                                            <th>Min. travelers</th>
                                            <th>Max. travelers</th>
                                            <th>Bus</th>
                                            <th>From CHF</th>
                                            <th>Min. CHF per person</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tripTableBody">
                                        <tr>
                                            <td><a target="_blank" href="<?php if(file_exists($tripTemplate->getPicturePath())){echo TemplateView::noHTML($tripTemplate->getPicturePath());}else{echo DefaultPath::getTripTemplate();} ?>"><img src="<?php if(file_exists($tripTemplate->getPicturePath())){echo TemplateView::noHTML($tripTemplate->getPicturePath());}else{echo DefaultPath::getTripTemplate();} ?>" alt="Not available" border=3 width=150></a></td>
                                            <td><?php echo TemplateView::noHTML($tripTemplate->getName()); ?></td>
                                            <td><?php echo TemplateView::noHTML($tripTemplate->getDescription()); ?></td>
                                            <td><?php echo TemplateView::noHTML($tripTemplate->getMinAllocation()); ?></td>
                                            <td><?php echo TemplateView::noHTML($tripTemplate->getMaxAllocation()); ?></td>
                                            <td><img src="<?php if ($tripTemplate->getBus()){if(file_exists($tripTemplate->getBus()->getPicturePath())){echo TemplateView::noHTML($tripTemplate->getBus()->getPicturePath());}else{echo DefaultPath::getBus();}} ?>" alt="Not available" border=3 width=150></td>
                                            <td><?php echo TemplateView::noHTML(number_format($tripTemplate->getCustomerPrice(),2)); ?></td>
                                            <td><?php if($tripTemplate->getCustomerPrice()){echo TemplateView::noHTML($tripTemplate->getCustomerHotelPricePerPerson());} ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </body>
                    </html>
                </div>
            </div>
            <div class="container-fluid text-center border rounded-0 border-dark" id="containerDayPrograms" style="margin-top: 50px;padding-top: 15px;padding-bottom: 15px;">
                <h4 class="text-center" style="margin-bottom: 16px;"><strong>Day programs</strong> of the selected trip.<br></h4>
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
                                                    <th style="min-width: 190px;">Hotel description</th>
                                                </tr>
                                            </thead>
                                            <tbody id="dayProgramsTableBody">
                                                <?php foreach ($dayprograms as $dayprogram): ?>
                                                    <tr>
                                                        <td><?php echo TemplateView::noHTML($dayprogram->getDayNumber()); ?></td>
                                                        <td><img src="<?php if(file_exists($dayprogram->getPicturePath())){echo TemplateView::noHTML($dayprogram->getPicturePath());}else{echo DefaultPath::getDayprogram();} ?>" alt="Not available" border=3 width=150></td>
                                                        <td><?php echo TemplateView::noHTML($dayprogram->getName()); ?> </td>
                                                        <td><?php echo TemplateView::noHTML($dayprogram->getDescription()); ?> </td>
                                                        <td><?php if ($dayprogram->getHotel()) {echo TemplateView::noHTML($dayprogram->getHotel()->getName());} ?> </td>
                                                        <td><?php if ($dayprogram->getHotel()): ?><img src="<?php if(file_exists($dayprogram->getHotel()->getPicturePath())){echo TemplateView::noHTML($dayprogram->getHotel()->getPicturePath());}else{echo DefaultPath::getHotel();} ?>" alt="Not available" border=3 width=150><?php endif; ?></td>
                                                        <td><?php if ($dayprogram->getHotel()) {echo TemplateView::noHTML($dayprogram->getHotel()->getDescription());} ?> </td>
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
        <?php if (isset($_SESSION['login'])): ?>
        <div style="overflow-x: auto;">  
        <div class="border rounded-0 border-primary shadow form-container" id="divBookingForm" style="max-width: 650px;">
                <h2 class="text-center" style="margin-bottom: 16px;margin-top: 18px;"><strong>Book your trip.</strong><br></h2>
                <div style="margin-bottom: 15px; margin-right: 15px; margin-left: 15px; padding-right: 25px;padding-left: 25px;background-image: url(&quot;assets/img/spanish%20beach.png&quot;);background-position: center;background-size: cover;background-repeat: no-repeat;">
                    <form class="border-dark" action="<?php echo $GLOBALS['ROOT_URL'] ?>/packageOverview/package" method="post" id="tripBookingForm" style="background-color: transparent;padding-right: 25px;padding-left: 25px;">
                        <input type="hidden" name="tripTemplateId" value="<?php echo $tripTemplate->getId(); ?>">
                        <div class="form-group"><label style="color: #222222;"><strong>Departure date</strong></label><input style="margin-bottom: 15px;" class="form-control" type="date" name="departureDate" required=""></div>

                        <div
                            class="form-group"><label style="margin-top: 15px;color: #222222;"><strong>Participants <?php if($tripTemplate->getMinAllocation()){echo "(min. " . ($tripTemplate->getMinAllocation() - 1) . ", max. " . ($tripTemplate->getMaxAllocation() - 1) . ")";} ?></strong></label><select class="form-control" name="participants[]" required="" multiple="" id="selectedParticipants" style="min-height: 400px; min-width: 500px;background-color: #f7f9fc;max-width: 500px;"><optgroup label="Unselect or select multiple with CTRL">
                                        <?php foreach ($participants as $participant) : ?>
                                        <option selected="" value="<?php echo $participant->getId(); ?>"><?php echo TemplateView::noHTML($participant->getFirstName() . " " . $participant->getLastName()); ?> </option>
                                    <?php endforeach; ?>
                                </optgroup></select>

                            <label style="margin-top: 15px; margin-left: 35px; color: #222222;">Number of selected participants</label><input style="margin-bottom: 15px; margin-left: 35px; max-width: 465px;" class="form-control" value="" style="grey" type="text" readonly="" id="fieldNumParticipants">

                            <div class="form-group"><label style="margin-top: 15px;color: #222222;"><strong>Insurance (optional)</strong></label>
                                <select class="form-control" name="insurance" required="" id="insuranceDropdown" style="min-width: 500px;"><optgroup label="Select insurance">
                                        <?php foreach ($insurances as $insurance) : ?>
                                            <option value="<?php echo $insurance->getId(); ?>"><?php echo TemplateView::noHTML($insurance->getName()) . " (price per person: CHF " . $insurance->getCustomerPricePerPerson() . ")"; ?></option>
                                        <?php endforeach; ?><option name="insuranceId" value="0" selected="">No insurance</option>
                                    </optgroup></select>

                                <textarea name="insuranceDescription" id="txtAreaInsuranceDescription" readonly="" style="padding: 10px; min-width: 500px; min-height: 130px;"></textarea></div>


                            <div><label style="margin-left: 0px;margin-top: 15px;color: #222222;" for="tripPrice"><strong>Price in CHF</strong></label><input class="form-control" value="<?php
                                if ($tripTemplate->getCustomerHotelPricePerPerson() and $tripTemplate->getCustomerBusPrice()) {
                                    echo $tripTemplate->getCustomerHotelPricePerPerson() + $tripTemplate->getCustomerBusPrice();
                                }
                                ?>" style="grey" type="text" name="price" readonly="" id="price"></div>
                        </div><button id="bookTrip" disabled class="btn btn-primary" type="submit" style="margin-top: 21px;">Book your trip now</button></form>
                </div>
            </div>
        </div>
            <script>
                $(document).ready(function () {
    <?php
// Get insurance prices
    $insuranceCustomerPrices = array();
    foreach ($insurances as $insurance) {
        array_push($insuranceCustomerPrices, $insurance->getCustomerPricePerPerson());
    }
    
// Get the correct variables
    if($tripTemplate->getCustomerBusPrice(false)){
        $customerBusPrice = $tripTemplate->getCustomerBusPrice(false);
    }else{$customerBusPrice = 0;}
    if($tripTemplate->getCustomerHotelPricePerPerson(false)){
        $customerHotelPricePerPerson = $tripTemplate->getCustomerHotelPricePerPerson(false);
    }else{$customerHotelPricePerPerson = 0;}
    

// Get insurance descriptions 
    $insuranceDescriptions = array();
    foreach ($insurances as $insurance) {
        array_push($insuranceDescriptions, $insurance->getDescription());
    }
    $js_arrayInsuranceCustomerPrices = json_encode($insuranceCustomerPrices);
    echo "var insurancePrices = " . $js_arrayInsuranceCustomerPrices . ";\n";
    echo "var busPrice = " . $customerBusPrice . ";\n";
    echo "var hotelPricePerPerson = " . $customerHotelPricePerPerson . ";\n";
    echo "var maxAllocation = " . $tripTemplate->getMaxAllocation() . ";\n";
    echo "var minAllocation = " . $tripTemplate->getMinAllocation() . ";\n";

    $js_arrayInsuranceDescriptions = json_encode($insuranceDescriptions);
    echo "var insuranceDescriptions = " . $js_arrayInsuranceDescriptions;
    ?>

                    function updateStyle() {
                        document.getElementById("fieldNumParticipants").value = $('#selectedParticipants option:selected').length + " (of min. " + (minAllocation - 1) + " and max. " + (maxAllocation - 1) + ")";
                        if ($('#selectedParticipants option:selected').length + 1 > maxAllocation || $('#selectedParticipants option:selected').length + 1 < minAllocation) {
                            document.getElementById('bookTrip').disabled = true;
                            document.getElementById('fieldNumParticipants').style.color = "red";
                        } else {
                            document.getElementById('bookTrip').disabled = false;
                            document.getElementById('fieldNumParticipants').style.color = "green";
                        }
                    }

                    var insuranceDropdown = document.getElementById('insuranceDropdown');
                    var participantsChoice = document.getElementById('selectedParticipants');
                    
                    actualPriceCalculator();

                    function actualPriceCalculator() {
                        actualPrice = busPrice;
                        var index = insuranceDropdown.selectedIndex;
                        var count = $('#selectedParticipants option:selected').length + 1;//+1 to count the user

                        //ensures that the no insurance choice does not add any number or an invalid number to the actualPrice
                        if (!(index >= insurancePrices.length)) {
                            actualPrice += insurancePrices[index] * (count);
                        }

                        //adds the hotelPricePerPerson
                        actualPrice += hotelPricePerPerson * (count);

                        //Ensures that the trip booking is disabled until the minimum of travelers are selected
                        updateStyle();

                        actualPrice = (Math.round(actualPrice / 0.05) * 0.05).toFixed(2);

                        // Add that data to the input
                        document.getElementById("price").value = actualPrice;

                    }

                    function getInsuranceDescription() {
                        // Add the description of the selected insurance to the textarea
                        var index = insuranceDropdown.selectedIndex;
                        document.getElementById("txtAreaInsuranceDescription").value = insuranceDescriptions[index];
                        
                        // Get the selected insurance in plain text
                        selectedOptionAsText = $("#insuranceDropdown :selected").text();
                        if (selectedOptionAsText === "No insurance"){
                            document.getElementById("txtAreaInsuranceDescription").value = "";
                        }
                    }

                    // When a new <option> is selected
                    insuranceDropdown.addEventListener('change', function () {
                        actualPriceCalculator();
                        getInsuranceDescription();
                    })

                    participantsChoice.addEventListener('change', function () {
                        actualPriceCalculator();
                        document.getElementById("fieldNumParticipants").value = $('#selectedParticipants option:selected').length + " (of min. " + (minAllocation - 1) + " and max. " + (maxAllocation - 1) + ")";
                    })


                });
            </script>
        <?php endif; ?>
    </div>

    <script src="assets/js/Sidebar-Menu.js"></script>
