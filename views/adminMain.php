<?php

/**
 * @author Adrian Mathys
 */
use views\TemplateView;
?>

<body style="background-size: cover;background-repeat: no-repeat;background-position: center;background-color: rgb(241,247,252);">
    <h1 style="font-family: Capriola, sans-serif;padding: 20px;background-position: top;margin-bottom: 0px;">Administration</h1>
    <section>
        <div id="wrapper">
            <div id="sidebar-wrapper" style="font-family: Capriola, sans-serif;">
                <ul class="sidebar-nav">
                    <li class="sidebar-brand"> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin" style="background-color: rgba(255,255,255,0.2);"><strong>Administration main</strong></a></li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/tripTemplates">Trip templates</a></li>
                    <li> </li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/users">Users</a></li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/hotels">Hotels</a></li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/buses">Buses</a></li>
                    <li> <a href="<?php echo $GLOBALS['ROOT_URL'] ?>/admin/insurances">Insurances</a></li>
                </ul>
            </div>
            <div class="page-content-wrapper">
                <div class="container-fluid" style="background-image: url(&quot;assets/img/spanish%20beach.png&quot;);background-position: center;background-size: cover;background-repeat: no-repeat;margin-bottom: 0px;padding-bottom: 40px;min-height: 100vh;"><a class="btn btn-link bg-light" role="button" href="#menu-toggle" id="menu-toggle"><i class="fa fa-bars"></i></a>
                    <h2 class="text-center" style="font-family: Capriola, sans-serif;color: #000000;margin-top: 25px;"><strong>Welcome to the administration main page.</strong><br></h2>
                    <h4 class="text-center" style="font-family: Capriola, sans-serif;margin-top: 40px;">Please select a category in the sidebar on the left.</h4>
                </div>
            </div>
        </div>
    </section>
    
     <script src="assets/js/Sidebar-Menu.js"></script>
