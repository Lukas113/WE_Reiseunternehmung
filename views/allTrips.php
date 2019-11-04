<?php
/**
 * @author Adrian Mathys
 */
use views\TemplateView;
use helpers\DefaultPath;
use entities\TripTemplate;
use entities\Trip;

isset($this->tripTemplates) ? $tripTemplates = $this->tripTemplates : $tripTemplates = array();
isset($this->trips) ? $trips = $this->trips : $trips = array();
?>


<body style="background-color: rgb(241,247,252);font-family: Capriola, sans-serif;padding-bottom: 0px;">
    <div style="min-height: 100vh;">
        <ul class="nav nav-tabs" style="margin-top: 15px;margin-bottom: 15px; font-family: Capriola">
            <li class="nav-item"><a class="nav-link active" role="tab" data-toggle="tab" href="#tab-1">
                <?php if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
                            echo "All trip templates";
                        }else{
                            echo "All trips";
                        }
                ?></a></li>
            <?php if(isset($_SESSION['login'])) : ?>
            <li class="nav-item"><a class="nav-link" role="tab" data-toggle="tab" href="#tab-2">Booked trips</a></li>
            <?php endif; ?>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" role="tabpanel" id="tab-1">
                <div class="container" style="font-family: Capriola, sans-serif;padding-top: 25px;padding-bottom: 0px;margin-top: 0px;margin-bottom: 120px;">
                    <div class="heading">
                        <h2 style="margin-bottom: 19px;">All available trips.</h2>
                    </div>
                    <div class="row">
                        <?php foreach ($tripTemplates as $tripTemplate) : ?>
                        <?php if(!$tripTemplate->getBus()){continue;};//Ensures that just Trips with a Bus are shown ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0"><a href="<?php
                                    if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
                                        $adminTemplatePath = $GLOBALS['ROOT_URL']."/admin/tripTemplates/package/".$tripTemplate->getId();
                                        echo $adminTemplatePath;
                                    }else{
                                        $userTemplatePath = $GLOBALS['ROOT_URL']."/packageOverview/package/".$tripTemplate->getId();
                                        echo $userTemplatePath;
                                    }
                                    ?>"><img src="<?php if(file_exists($tripTemplate->getPicturePath())){echo TemplateView::noHTML($tripTemplate->getPicturePath());}else{echo DefaultPath::getTripTemplate();} ?>" alt="Card Image" class="card-img-top scale-on-hover"></a>
                                <div class="card-body">
                                    <h6><a href="<?php
                                    if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
                                        echo $adminTemplatePath;
                                    }else{
                                        echo $userTemplatePath;
                                    }
                                    ?>"><?php echo TemplateView::noHTML($tripTemplate->getName()); ?></a></h6>
                                    <p class="text-muted card-text"><?php echo TemplateView::noHTML($tripTemplate->getDescription()); ?></p>
                                    
                                    <strong style="border-radius: 5px; padding: 5px; padding-left: 0px; color: black;"class="priceTag">from</strong>
                                    <strong style="border-radius: 5px; padding: 5px; background-color: royalblue; color: white; border: 1px solid white;"class="priceTag">CHF &nbsp <?php echo TemplateView::noHTML(number_format($tripTemplate->getCustomerPrice(),2)); ?></strong></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane" role="tabpanel" id="tab-2">
                <div class="container" style="font-family: Capriola, sans-serif;padding-top: 25px;padding-bottom: 0px;margin-top: 0px;margin-bottom: 40px;">
                    <div class="heading">
                        <h2 style="margin-bottom: 19px;"><?php 
                        if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
                            echo "All booked trips.";
                        }else{
                            echo "All trips you have booked.";
                        }
                            ?></h2>
                    </div>
                    <div class="row">
                        <?php foreach ($trips as $trip) : ?>
                        <?php if(!$trip->getTripTemplate()){continue;};//Ensures that just Trips with a TripTemplate are shown ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0"><a href="<?php
                                    if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
                                        $adminTripPath = $GLOBALS['ROOT_URL']."/admin/bookedTrips/detail/".$trip->getId();
                                        echo $adminTripPath;
                                    }else{
                                        $userTripPath = $GLOBALS['ROOT_URL']."/bookedTrips/detail/".$trip->getId();
                                        echo $userTripPath;
                                    }
                                    ?>"><img src="<?php if(file_exists($trip->getTripTemplate()->getPicturePath())){echo TemplateView::noHTML($trip->getTripTemplate()->getPicturePath());}else{echo DefaultPath::getTrip();} ?>" alt="Card Image" class="card-img-top scale-on-hover"></a>
                                <div class="card-body">
                                    <h6><a href="<?php
                                    if(isset($_SESSION['role']) and $_SESSION['role'] == "admin"){
                                        echo $adminTripPath;
                                    }else{
                                        echo $userTripPath;
                                    }
                                    ?>"><?php echo TemplateView::noHTML($trip->getTripTemplate()->getName()); ?></a></h6>
                                    <p class="text-muted card-text"><?php echo TemplateView::noHTML($trip->getTripTemplate()->getDescription()); ?></p>
                                    <p style="padding-bottom: 0px; margin-bottom: 0px;"class="d-lg-flex justify-content-lg-end align-items-lg-end priceTag">Booked by: &nbsp<?php echo TemplateView::noHTML($trip->getUser()->getFirstName()." ".$trip->getUser()->getLastName()); ?></p>
                                    <p style="padding-bottom: 0px; margin-bottom: 20px;" class="d-lg-flex justify-content-lg-end align-items-lg-end priceTag">Departure: &nbsp<?php echo TemplateView::noHTML($trip->getDepartureDate()); ?></p>
                                    <strong style="border-radius: 5px; padding: 5px; background-color: royalblue; color: white; border: 1px solid white;" class="priceTag">CHF &nbsp<?php echo TemplateView::noHTML(number_format($trip->getCustomerPrice(),2)); ?></strong>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
