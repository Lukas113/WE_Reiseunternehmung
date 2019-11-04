<?php

/**
 * @author Adrian Mathys
 */
use views\TemplateView;

isset($this->participants) ? $participants = $this->participants : $participants = array();
?>

<body>
    <div class="register-photo" style="font-family: Capriola, sans-serif;background-size: auto;min-height: 100vh;">
        <div class="form-container">
            <div class="image-holder" style="background-image: url(&quot;assets/img/travelGroup.jpg&quot;);background-position: center;background-size: cover;background-repeat: no-repeat;"></div>
            <form action="<?php echo $GLOBALS['ROOT_URL'] ?>/travelers" method="post">
                <h2 class="text-center"><strong>Add travelers </strong>to your profile.</h2>
                <div class="form-group"><label style="margin-bottom: 0px;">First name</label><input class="form-control" type="text" name="firstName" required="" minlength="3"></div>
                <div class="form-group"><label style="margin-bottom: 0px;">Last name</label><input class="form-control" type="text" name="lastName" required="" minlength="3"></div>
                <div class="form-group"><label style="margin-bottom: 0px;">Birth date</label><input class="form-control" type="date" name="birthDate" required=""></div>
                <div class="form-group"><button class="btn btn-primary btn-block" type="submit">Add person</button></div>
            </form>
        </div>
        <div class="container" style="margin-top: 81px;">
            <h2 class="text-center" style="margin-bottom: 16px;"><strong>Overview of your added travelers.</strong></h2>

            <!DOCTYPE html>
            <html lang="en">
                <head>
                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                </head>
                <body>

                    <div class="container" style="font-family: Capriola, sans-serif;">

                        <input class="form-control" id="participantInput" type="text" placeholder="Search travelers...">
                        <br>
                        <div style="overflow-x: auto;">
                        <table id="participantOverviewTable" class="tableStyle">
                            <thead>
                                <tr>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Birth date</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody id="participantTableBody">
                                <?php foreach ($participants as $participant): ?>
                                    <tr>
                                        <td><?php echo TemplateView::noHTML($participant->getFirstName()); ?></td>
                                        <td><?php echo TemplateView::noHTML($participant->getLastName()); ?> </td>
                                        <td><?php echo TemplateView::noHTML($participant->getBirthDate()); ?> </td>
                                        <td><form style="background-color: transparent; padding: 0px; margin: 0px; min-width: 0px; min-height: 0px; max-width: 20px" id="deleteParticipant<?php echo $participant->getId(); ?>" action="<?php echo $GLOBALS['ROOT_URL'] ?>/travelers/<?php echo $participant->getId(); ?>" method="post">
                                            <input type="hidden" name="_method" value="DELETE"><img src="assets/img/Recycle_Bin.png" alt="Remove"  border=3 height=20 width=20 onclick="deleteHandler(<?php echo $participant->getId(); ?>)"></form></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    </div>

                    <script>
                        
                        //Remove hotel
                        function deleteHandler(participantId){
                            var c = confirm("Do you want to delete this traveler?");
                            if(c){
                                $( "#deleteParticipant"+participantId).submit();
                            }
                        }

                        //Make the table searchable
                        $(document).ready(function () {
                            $("#participantInput").on("keyup", function () {
                                var value = $(this).val().toLowerCase();
                                $("#participantTableBody tr").filter(function () {
                                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                });
                            });
                        });

                    </script>

                </body>
            </html>
        </div>
    </div>
