<?php

/**
 * @author Adrian Mathys
 */
use views\TemplateView;
use entities\TripTemplate;
use helpers\DefaultPath;

isset($this->tripTemplate) ? $tripTemplate = $this->tripTemplate : $tripTemplate = new TripTemplate();
isset($this->hotels) ? $hotels = $this->hotels : $hotels = array();
if(isset($this->tripTemplate) and $this->tripTemplate and $tripTemplate->getDayprograms()){
    $dayprograms = $tripTemplate->getDayprograms(); 
}else{
    $dayprograms = array();
}    
?>


    <div class="border rounded-0 register-photo" style="font-family: Capriola, sans-serif;background-size: auto;min-height: 100vh;padding-top: 0px;">
        <div style="padding-bottom: 52px;">
            <div class="container-fluid" style="margin-top: 81px;">
                <h2 class="text-center" style="margin-bottom: 16px;"><strong>
                    Overview of the trip template "<?php echo TemplateView::noHTML($tripTemplate->getName() . "\"."); ?>
                    </strong></h2>
                <div class="scrollableDiv"><!DOCTYPE html>
                    <html lang="en">
                        <head>
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                        </head>
                        <body>

                            <div class="container" style="font-family: Capriola, sans-serif; overflow-x: auto;">
                                
                                <table id="tripOverviewTable" class="tableStyle">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Trip name</th>
                                            <th>Description</th>
                                            <th>Min. travelers</th>
                                            <th>Max. travelers</th>
                                            <th>Bus</th>
                                            <th>Internal price from CHF</th>
                                            <th>Customer price from CHF</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tripTableBody">
                                        <tr>
                                            <td><a target="_blank" href="<?php if(file_exists($tripTemplate->getPicturePath())){echo TemplateView::noHTML($tripTemplate->getPicturePath());}else{echo DefaultPath::getTripTemplate();} ?>"><img src="<?php if(file_exists($tripTemplate->getPicturePath())){echo TemplateView::noHTML($tripTemplate->getPicturePath());}else{echo DefaultPath::getTripTemplate();} ?>" alt="Not available" border=3 width=150></a></td>
                                            <td><?php echo TemplateView::noHTML($tripTemplate->getName()); ?></td>
                                            <td><?php echo TemplateView::noHTML($tripTemplate->getDescription()); ?></td>
                                            <td><?php echo TemplateView::noHTML($tripTemplate->getMinAllocation()); ?></td>
                                            <td><?php echo TemplateView::noHTML($tripTemplate->getMaxAllocation()); ?></td>
                                            <td><?php if($tripTemplate->getBus()){echo TemplateView::noHTML($tripTemplate->getBus()->getName()) . "</br>(seats: " . TemplateView::noHTML($tripTemplate->getBus()->getSeats()) . ")";} ?></td>
                                            <td><?php echo TemplateView::noHTML(number_format($tripTemplate->getPrice(),2)); ?></td>
                                            <td><?php echo TemplateView::noHTML(number_format($tripTemplate->getCustomerPrice(),2)); ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </body>
                    </html>
                </div>
            </div>
            <div class="container-fluid text-center border rounded-0 border-dark" id="containerDayPrograms" style="margin-top: 50px;padding-top: 15px;padding-bottom: 15px;">
                <h4 class="text-center" style="margin-bottom: 16px;">Already added <strong>day programs</strong> of the selected trip.<br></h4>
                <div><a class="btn btn-secondary" data-toggle="collapse" aria-expanded="true" aria-controls="collapseDayPrograms" role="button" href="#collapseDayPrograms" style="margin-bottom: 10px;">Show/hide day programs</a>
                    <div class="collapse show" id="collapseDayPrograms">
                        <div class="text-left d-xl-flex justify-content-xl-center"><!DOCTYPE html>
                            <html lang="en">
                                <head>
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                                    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                                </head>
                                <body>

                                    <div style="font-family: Capriola, sans-serif; overflow-x: auto;">

                                        <table id="dayProgramsOverviewTable" class="tableStyle" style="max-width:1200px">
                                            <thead>
                                                <tr>
                                                    <th>Day</th>
                                                    <th>Image</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Hotel name</th>
                                                    <th>Hotel image</th>
                                                    <th style="min-width: 200px;">Hotel description</th>
                                                    <th>Hotel price</th>
                                                    <?php if(!$tripTemplate->getBookable()): ?>
                                                    <th>Delete</th>
                                                    <?php endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody id="dayProgramsTableBody">
                                                <?php foreach ($dayprograms as $dayprogram): ?>
                                                    <tr>
                                                        <td><?php echo TemplateView::noHTML($dayprogram->getDayNumber()); ?></td>
                                                        <td><img src="<?php if(file_exists($dayprogram->getPicturePath())){echo TemplateView::noHTML($dayprogram->getPicturePath());}else{echo DefaultPath::getDayprogram();} ?>" alt="Not available" border=3 width=150></td>
                                                        <td><?php echo TemplateView::noHTML($dayprogram->getName()); ?> </td>
                                                        <td><?php echo TemplateView::noHTML($dayprogram->getDescription()); ?> </td>
                                                        <td><?php if($dayprogram->getHotel()){echo TemplateView::noHTML($dayprogram->getHotel()->getName());} ?> </td>
                                                        <td><?php if($dayprogram->getHotel()): ?><img src="<?php if(file_exists($dayprogram->getHotel()->getPicturePath())){echo TemplateView::noHTML($dayprogram->getHotel()->getPicturePath());}else{echo DefaultPath::getHotel();} ?>" alt="Not available" border=3 width=150><?php endif; ?></td>
                                                        <td style="min-width: 200px;"><?php if($dayprogram->getHotel()){echo TemplateView::noHTML($dayprogram->getHotel()->getDescription());} ?> </td>
                                                        <td><?php if($dayprogram->getHotel()){echo TemplateView::noHTML(number_format($dayprogram->getHotel()->getPricePerPerson(),2));} ?> </td>
                                                        <?php if(!$tripTemplate->getBookable()): ?>
                                                        <td><form style="background-color: transparent; padding: 0px; margin: 0px; min-width: 0px; min-height: 0px" id="deleteDayprogram<?php echo $dayprogram->getId(); ?>" action="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/tripTemplates/package/<?php echo $dayprogram->getId()."/".$tripTemplate->getId(); ?>" method="post">
                                                        <input type="hidden" name="_method" value="DELETE"><img src="assets/img/Recycle_Bin.png" alt="Remove"  border=3 height=20 width=20 onclick="deleteHandler(<?php echo $dayprogram->getId(); ?>)"></form></td>
                                                        <?php endif; ?>
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
        <?php if(!$tripTemplate->getBookable()): ?>
        <div role="tablist" id="accordionTripAdmin" style="margin-left: 0px;">
            <div class="card">
                <div class="card-header" role="tab">
                    <h5 class="mb-0"><a data-toggle="collapse" aria-expanded="false" aria-controls="accordionTripAdmin .item-1" href="div#accordionTripAdmin .item-1">Administration of day programs</a></h5>
                </div>
                <div class="collapse item-1" role="tabpanel" data-parent="#accordionTripAdmin">
                    <div class="card-body">
                        <div class="text-center border rounded border-info shadow d-flex d-sm-flex d-md-flex d-lg-flex justify-content-center justify-content-sm-center justify-content-md-center justify-content-lg-center">
                            <form action="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/tripTemplates/package/<?php echo $tripTemplate->getId(); ?>" method="POST" class="d-md-flex justify-content-md-center" style="padding-top: 0px;padding-right: 0px;padding-bottom: 0px;padding-left: 0px;">
                                <input type="hidden" name="_method" value="PUT">
                                <div class="text-center" >
                                    <p style="margin-bottom: 15px;margin-top: 15px;color: #000000;">Have you assigned all day programs to the trip?</p>
                                    <button class="btn btn-info" type="submit" id="btnDayProgramsComplete" style="margin-top: 0px;margin-bottom: 11px;">Make this trip bookable</button></div>
                            </form></div>
                        <div style="overflow-x: auto;">
                        <div class="border rounded-0 border-primary shadow form-container" style="margin-top: 30px; max-width: 600px;">
                            <h4 class="text-center" style="margin-bottom: 16px;margin-top: 18px;"><strong>Add a new day program to the trip.</strong><br></h4>
                            
                                <div style="margin: 15px; background-color: rgba(147,198,224,0.36); padding-right: 20px;">
                                <form class="form-inline" action="<?php echo $GLOBALS['ROOT_URL']; ?>/admin/tripTemplates/package" method="post" enctype="multipart/form-data" id="dayProgramForm" style="background-color: transparent; padding-right: 0px;padding-bottom: 30px;padding-top: 0px;padding-left: 30px;">
                                    <input type="hidden" name="tripTemplateId" value="<?php echo $tripTemplate->getId(); ?>">
                                    <div class="form-group"><label class="labelsFormDayProgram" style="width: 500px; text-align: left; display:block;">For which day of the trip would you like to add a day program?</label><input class="form-control" type="number" name="dayNumber" value="1" required="" min="1" max="7" step="1" id="dayNumber" style="width: 150px;"></div>
                                    <div
                                        class="form-group"><label class="labelsFormDayProgram" style="width: 500px; text-align: left; display:block;">Name of day program</label><textarea class="form-control" name="name" required="" minlength="3" id="name" style="width: 500px;min-height: 80px;"></textarea></div>
                                    <div
                                        class="form-group"><label class="labelsFormDayProgram" style="width: 500px; text-align: left; display:block;">Description of day program</label><textarea class="form-control" name="description" required="" minlength="3" id="description" style="width: 500px;min-height: 120px;"></textarea></div>
                                    <div
                                        class="form-group mt-auto"><label class="labelsFormDayProgram" style="width: 500px; text-align: left; display:block;">Picture of day program</label><input type="file" name="img" required="" id="image" style="width: 500px;background-color: transparent;"></div>
                                    <div
                                        class="form-group"><label class="labelsFormDayProgram" style="width: 500px; text-align: left; display:block;">Hotel (if applicable)</label>
                                        <select class="form-control" name="hotelId" id="hotelDropdown" style="width: 500px;"><optgroup label="Add a hotel">
                                                <?php foreach ($hotels as $hotel) :  ?>
                                                <option value="<?php echo $hotel->getId();  ?>" selected=""><?php echo TemplateView::noHTML($hotel->getName())." (price per person: ".TemplateView::noHTML($hotel->getPricePerPerson()).")"; ?></option>
                                                <?php endforeach;  ?>
                                                <option value="0" selected="">No hotel</option>
                                            </optgroup></select>
                                        
                                        <textarea id="txtAreaHotelDescription" readonly="" style="padding: 10px; min-height: 100px; width: 500px;"></textarea>
                                        
                                    </div>
                                    <button
                                        class="btn btn-primary btn-block" type="submit">Save</button>
                                </form>
                                </div>
                            
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <script src="assets/js/Sidebar-Menu.js"></script>
    <script>
        
        //Remove dayprogram
        function deleteHandler(dayprogramId){
            var c = confirm("Do you want to delete this day program?\n\nThe price of the trip will automatically be recalculated.");
            if(c){
                $( "#deleteDayprogram"+dayprogramId).submit();
            }
        }
        
        
        $(document).ready(function () {
    <?php

    // Get hotel descriptions 
    $hotelDescriptions = array();
    foreach ($hotels as $hotel) {
        array_push($hotelDescriptions, $hotel->getDescription());
    }

    $js_arrayHotelDescriptions = json_encode($hotelDescriptions);
    echo "var hotelDescriptions = " . $js_arrayHotelDescriptions;
    ?>
            
                    function getHotelDescription() {
                        // Add the description of the selected hotel to the textarea
                        var hotelDropdown = document.getElementById('hotelDropdown');
                        var index = hotelDropdown.selectedIndex;
                        document.getElementById("txtAreaHotelDescription").value = hotelDescriptions[index];
                        
                        // Get the selected hotel in plain text
                        selectedOptionAsText = $("#hotelDropdown :selected").text();
                        if (selectedOptionAsText === "No hotel"){
                            document.getElementById("txtAreaHotelDescription").value = "";
                        }
                    }

                    // When a new <option> is selected
                    hotelDropdown.addEventListener('change', function () {
                        getHotelDescription();
                    })

                });
            </script>
    </script>
