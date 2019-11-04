<?php

/**
 * @author Adrian Mathys
 */
use views\TemplateView;

isset($this->users) ? $users = $this->users : $users = array();
?>


<body style="background-size: cover;background-repeat: no-repeat;background-position: center;background-color: rgb(241,247,252);">
    <h1 style="font-family: Capriola, sans-serif;padding: 20px;background-position: top;margin-bottom: 0px;">Administration of users</h1>
    <section>
        <div id="wrapper">
            <div id="sidebar-wrapper" style="font-family: Capriola, sans-serif;">
                <ul class="sidebar-nav">
                    <li class="sidebar-brand"> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin"><strong>Administration main</strong></a></li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/tripTemplates">Trip templates</a></li>
                    <li> </li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/users" style="background-color: rgba(255,255,255,0.2);">Users</a></li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/hotels" >Hotels</a></li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/buses" >Buses</a></li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/insurances">Insurances</a></li>
                </ul>
            </div>
            <div class="page-content-wrapper">
                <div class="container-fluid" style="background-image: url(&quot;assets/img/spanish%20beach.png&quot;);background-position: center;background-size: cover;background-repeat: no-repeat;margin-bottom: 0px;padding-bottom: 40px;min-height: 100vh;"><a class="btn btn-link bg-light" role="button" href="#menu-toggle" id="menu-toggle"><i class="fa fa-bars"></i></a>
                    <h2 class="text-center" style="font-family: Capriola, sans-serif;color: #000000;margin-bottom: 30px;"><strong>Overview of added users.</strong><br></h2><!DOCTYPE html>
                    <html lang="en">
                        <head>
                            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                        </head>
                        <body>

                            <div class="container" style="font-family: Capriola, sans-serif;">

                                <input class="form-control" id="myInput" type="text" placeholder="Search users...">
                                <br>

                                <button class="btn btn-success" id="download_csv" style="margin-bottom: 10px;">Download CSV <img src="assets/img/download.png" style="width:20px; padding-left: 2px;"><span class="glyphicon glyphicon-download"></span></button>

                                <div style="overflow-x: auto;">
                                    <table id="userAdminTable" class="tableStyle">
                                        <thead>
                                            <tr>
                                                <th>Firstname</th>
                                                <th>Lastname</th>
                                                <th>Email</th>
                                                <th>Admin</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody id="myTable">
                                            <?php foreach ($users as $user): ?>
                                                <tr>
                                                    <td><?php echo TemplateView::noHTML($user->getFirstname()); ?></td>
                                                    <td><?php echo TemplateView::noHTML($user->getLastname()); ?></td>
                                                    <td><?php echo TemplateView::noHTML($user->getEmail(), false); ?> </td>
                                                    <td><form id="changeRole<?php echo $user->getId(); ?>" action="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/users/<?php echo $user->getId(); ?>" method="post">
                                                            <input type="hidden" name="_method" value="PUT"><input type="checkbox" <?php if ($user->getRole() == "admin") {
                                                echo 'checked';
                                            }; ?> class="adminCheckboxes"  onclick="onClickHandler(<?php echo $user->getId(); ?>)" id="userRole<?php echo $user->getId(); ?>" /></form></td>
                                                    <td><form id="deleteUser<?php echo $user->getId(); ?>" action="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/users/<?php echo $user->getId(); ?>" method="post">
                                                            <input type="hidden" name="_method" value="DELETE"><img src="assets/img/Recycle_Bin.png" alt="Remove"  border=3 height=20 width=20 onclick="deleteHandler(<?php echo $user->getId(); ?>)"></form></td>
                                                </tr>
<?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!--Make the table searchable-->
                            <script>
                                $(document).ready(function () {
                                    $("#myInput").on("keyup", function () {
                                        var value = $(this).val().toLowerCase();
                                        $("#myTable tr").filter(function () {
                                            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                                        });
                                    });
                                });

                                // remove user
                                function deleteHandler(userId) {
                                    var c = confirm("Do you want to delete this user?\n\nThe user will no longer be able to log in after deletion.\n\nPlease note that it is not possible to delete the last remaining administrator.");
                                    if (c) {
                                        $("#deleteUser" + userId).submit();
                                    }
                                }

                                // Add/remove admin right from/to users
                                function onClickHandler(userId) {

                                    var id = "userRole" + userId;
                                    if (!document.getElementById(id).checked) { // if the user was already admin and therefore now unchecked the checkbox
                                        document.getElementById(id).checked = true; // temporarily recheck the checkbox
                                        var c = confirm("Do you want to remove admin rights from this user?\n\nNormal user rights will be assigned to the admin.\n\nPlease note that it is not possible to remove the admin role from the last remaining administrator.");
                                        if (c) {
                                            document.getElementById(id).checked = false;
                                            $("#changeRole" + userId).submit();
                                        }
                                    } else { // if the user wasn't admin yet and therefore just checked the checkbox
                                        document.getElementById(id).checked = false; // temoporarily uncheck the checkbox again
                                        var c = confirm("Do you want to assign admin rights to this user?\n\nThe user will be able to administer trip templates, trips, users, insurances, buses and hotels.");
                                        if (c) {
                                            document.getElementById(id).checked = true;
                                            $("#changeRole" + userId).submit();
                                        }
                                    }
                                }

                                // Allow table export in CSV format
                                document.querySelector("#download_csv").addEventListener("click", function () {
                                    var html = document.querySelector("#userAdminTable").outerHTML;
                                    export_table_to_csv(html, "userOverview.csv");
                                });


                            </script>

                        </body>
                    </html>
                </div>
            </div>
        </div>
    </section>

    <script src="assets/js/Sidebar-Menu.js"></script>
    <script src="assets/js/exportTable.js"></script>
