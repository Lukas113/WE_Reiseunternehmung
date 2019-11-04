<?php

/**
 * @author Adrian Mathys
 */
use views\TemplateView;
?>


<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <title>Reiseunternehmen</title>
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Aclonica">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Capriola">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css">
        <link rel="stylesheet" href="assets/css/Basic-fancyBox-Gallery.css">
        <link rel="stylesheet" href="assets/css/Footer-Basic.css">
        <link rel="stylesheet" href="assets/css/Good-login-dropdown-menu-1.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.6.1/css/pikaday.min.css">
        <link rel="stylesheet" href="https://unpkg.com/@bootstrapstudio/bootstrap-better-nav/dist/bootstrap-better-nav.min.css">
        <link rel="stylesheet" href="assets/css/Navigation-with-Button.css">
        <link rel="stylesheet" href="assets/css/Navigation-with-Search.css">
        <link rel="stylesheet" href="assets/css/Projects-Clean.css">
        <link rel="stylesheet" href="assets/css/Registration-Form-with-Photo-1.css">
        <link rel="stylesheet" href="assets/css/Registration-Form-with-Photo.css">
        <link rel="stylesheet" href="assets/css/RegistrationForm.css">
        <link rel="stylesheet" href="assets/css/Sidebar-Menu-1.css">
        <link rel="stylesheet" href="assets/css/Sidebar-Menu.css">
        <link rel="stylesheet" href="assets/css/styles.css">
        <link rel="stylesheet" href="assets/css/topnavLogin.css">
        <link rel="stylesheet" href="assets/css/userAdminTable.css">
        <link rel="apple-touch-icon" sizes="57x57" href="assets/img/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="assets/img/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="assets/img/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="assets/img/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="assets/img/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="assets/img/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="assets/img/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="assets/img/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon-16x16.png">
        <link rel="manifest" href="assets/img/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="assets/img/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
    </head>

    <body>
        <div>
            <nav class="navbar navbar-light navbar-expand-md navigation-clean-button">
                <div class="container-fluid"><a class="navbar-brand" href="<?php echo $GLOBALS['ROOT_URL'] ?>/login" data-bs-hover-animate="tada"></a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">noggle navigation</span><span class="navbar-toggler-icon"></span></button>
                    <div
                        class="collapse navbar-collapse" id="navcol-1">
                        <ul class="nav navbar-nav mr-auto" style="font-family: Capriola">
                            <li class="nav-item" role="presentation"><a class="nav-link" href="<?php echo $GLOBALS['ROOT_URL'] ?>/packageOverview" style="color: #000000;">Trip overview</a></li>
                        </ul><span class="ml-auto navbar-text actions"> <div class="topnav">
                                <div class="login-container" style="font-family:Capriola">
                                    <form id="loginForm" action="<?php echo $GLOBALS['ROOT_URL'] ?>/login" method="post">
                                        <input type="email" required placeholder="Email" name="email">
                                        <input type="password" required placeholder="Password" name="password">
                                        <button id=loginButton type="submit">Login</button>
                                    </form>
                                </div>
                            </div><a class="btn btn-link btn-sm border rounded border-primary" role="button" href="<?php echo $GLOBALS["ROOT_URL"]; ?>/registration" style="background-color: #ffffff;color: #0080f7;padding-top: 2px;padding-bottom: 2px;margin-top: 4px; font-family:Capriola">Sign up</a></span>
                    </div>
                </div>
            </nav>
        </div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.pack.js"></script>
        <script src="assets/js/Basic-fancyBox-Gallery.js"></script>
        <script src="assets/js/bs-animation.js"></script>
        <script src="assets/js/formCheck.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pikaday/1.6.1/pikaday.min.js"></script>
        <script src="https://unpkg.com/@bootstrapstudio/bootstrap-better-nav/dist/bootstrap-better-nav.min.js"></script>
        <script src="assets/js/Sidebar-Menu.js"></script>
        
        <script>
            
            /**
                 * When the submit button is pressed, the system first checks whether the email already exists and if the login information is correct without sending the entire form to the server.
                * @author Vanessa Cajochen
                */
            $('#loginForm').on('submit', function(event){
                    
                    event.preventDefault();
                    var form = $("#loginForm").serialize();
                    $.ajax({
                        type:'POST',
                        url:'ajaxLogin',
                        data: form,
                        success:function(data){
                            if(data.status == 'success'){
                                $('#loginForm').unbind().submit();
                             }else if(data.status == 'error'){
                                alert("Wrong email or password")
                            }
                        }
                       });                       
                   });         
            
        </script>
        
        
        
        <noscript style="background-color: red; color: white; margin:10px;">Turn on your damned JavaScript! What is this, 1999?</noscript>
