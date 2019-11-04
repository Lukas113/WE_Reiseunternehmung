<?php

/**
 * @author Adrian Mathys
 */
use views\TemplateView;

isset($this->insurances) ? $insurances = $this->insurances : $insurances = array();
?>

<body style="background-size: cover;background-repeat: no-repeat;background-position: center;background-color: rgb(241,247,252);">
    <h1 style="font-family: Capriola, sans-serif;padding: 20px;background-position: top;margin-bottom: 0px;">Administration of insurances</h1>
    <section>
        <div id="wrapper">
            <div id="sidebar-wrapper" style="font-family: Capriola, sans-serif;">
                <ul class="sidebar-nav"> 
                    <li class="sidebar-brand"> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin"><strong>Administration main</strong></a></li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/tripTemplates">Trip templates</a></li>
                    <li> </li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/users">Users</a></li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/hotels" >Hotels</a></li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/buses" >Buses</a></li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/insurances" style="background-color: rgba(255,255,255,0.2);">Insurances</a></li>  
                </ul>
            </div>
            <div class="page-content-wrapper">
                <div class="container-fluid" style="background-image: url(&quot;assets/img/europe%20skyline%20uncut.png&quot;);background-position: center;background-size: cover;background-repeat: no-repeat;padding-bottom: 26px;min-height: 100vh;"><a class="btn btn-link" role="button" href="#menu-toggle" id="menu-toggle"><i class="fa fa-bars"></i></a>
                    <h2 class="text-center" style="font-family: Capriola, sans-serif;color: #000000;"><strong>Add a new insurance.</strong></h2>
                    <div style="overflow-x: auto;">
                    <form class="form-inline pulse animated" action="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/insurances" method="post" id="insuranceForm" style="background-color: rgba(255,255,255,0.77);margin: 20px;padding: 20px;font-family: Capriola, sans-serif;">
                        <div class="form-group" style="margin: 10px;width: 400px;"><label class="labelsFormTripTemplates">Insurance name</label><textarea class="form-control" name="name" required="" minlength="3" style="width: 400px;"></textarea></div>
                        <div class="form-group" style="margin: 10px;width: 400px;"><label class="labelsFormTripTemplates">Description</label><textarea class="form-control" name="description" required="" minlength="3" style="width: 400px;"></textarea></div>
                        <div class="form-group" style="margin: 10px;width: 400px;margin-right: 2000px;"><label class="labelsFormTripTemplates" style="width: 400px; text-align: left; display:block;">Price per person in CHF</label><input class="form-control" type="number" step="0.05" name="pricePerPerson" required="" min="1"></div>
                        <div class="form-group mt-auto" style="margin: 10px;width: 400px;"><button class="btn btn-primary btn-block" type="submit" style="width: 100px; margin-top: 20px;">Save</button></div>
                    </form>
                    </div>
                    <div style="font-family: Capriola, sans-serif;margin-bottom: 40px;padding-bottom: 20px;margin-top: 65px;background-color: rgba(255,255,255,0.77);margin-right: 0px;">
                        <h2 class="text-center" style="margin-bottom: 16px;padding: 0px;padding-top: 23px;"><strong>Overview of added insurances.</strong></h2><!DOCTYPE html>
                        <html lang="en">
                            <head>
                                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                            </head>
                            <body>

                                <div class="container" style="font-family: Capriola, sans-serif;">

                                    <input class="form-control" id="insuranceInput" type="text" placeholder="Search insurance...">
                                    <br>
                                    <div style="overflow-x: auto;">
                                    <table id="insuranceOverviewTable" class="tableStyle">
                                        <thead>
                                            <tr>
                                                <th>Insurance name</th>
                                                <th>Description</th>
                                                <th>Price per person in CHF</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody id="insuranceTableBody">
                                            <?php foreach ($this->insurances as $insurance): ?>
                                                <tr>
                                                    <td><?php echo TemplateView::noHTML($insurance->getName()); ?> </td>
                                                    <td><?php echo TemplateView::noHTML($insurance->getDescription()); ?> </td>
                                                    <td><?php echo TemplateView::noHTML(number_format($insurance->getPricePerPerson(),2)); ?> </td>
                                                    <td><form id="deleteInsurance<?php echo $insurance->getId(); ?>" action="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/insurances/<?php echo $insurance->getId(); ?>" method="post">
                                                            <input type="hidden" name="_method" value="DELETE"><img src="assets/img/Recycle_Bin.png" alt="Remove"  border=3 height=20 width=20 onclick="deleteHandler(<?php echo $insurance->getId(); ?>)"></form></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    </div>
                                </div>

                                <script>

                                    //Remove insurance
                                    function deleteHandler(insuranceId) {
                                        var c = confirm("Do you want to delete this insurance?\n\nIf you delete this insurance, trips containing it will no longer be displayed correctly.");
                                        if (c) {
                                            $("#deleteInsurance" + insuranceId).submit();
                                        }
                                    }

                                    //Make the table searchable
                                    $(document).ready(function () {
                                        $("#insuranceInput").on("keyup", function () {
                                            var value = $(this).val().toLowerCase();
                                            $("#insuranceTableBody tr").filter(function () {
                                                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                            });
                                        });
                                    });

                                    //Make the table scrollable
                                    $(document).ready(function () {
                                        $(".container").css("overflow-x", "auto");
                                    });



                                </script>

                            </body>
                        </html>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="assets/js/Sidebar-Menu.js"></script>
