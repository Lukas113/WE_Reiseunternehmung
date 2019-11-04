<?php

/**
 * @author Adrian Mathys
 */
use views\TemplateView;

?>



<div class="register-photo" style="padding: 40px;font-family: Capriola, sans-serif;padding-bottom: 60px;min-height: 100vh;">
    <div class="form-container">
        <div class="image-holder" style="background-image: url(&quot;assets/img/Hiking.jpg&quot;);"></div>
        <form id="registrationForm" name="registrationForm" action="<?php echo $GLOBALS['ROOT_URL'] . "/registration" ?>" method="post" style="font-family: Capriola, sans-serif;">
            <h2 class="text-center"><strong>Create</strong> an account.</h2>
            <div class="form-group">
                <label>Name</label>    
                <select class="form-control" name="gender" required><optgroup label="Select gender"><option value="male" selected>Mr.</option><option value="female">Mrs.</option></optgroup></select>

                <input class="form-control" type="text" name="firstName" required placeholder="First name" maxlength="40" minlength="2" /><input class="form-control" type="text" name="lastName" required placeholder="Last name" maxlength="40"
                                                                                                                                                 minlength="2" /></div>
            <div class="form-group"><label>Address</label><input class="form-control" type="text" name="street" required placeholder="Street" maxlength="40" minlength="2" />
                <input class="form-control" type="number" name="zipCode" required placeholder="ZIP code" min="0"
                       /><input class="form-control" type="text" name="location" required placeholder="City or village" maxlength="40" minlength="2" /></div>
            <div class="form-group"><label>Email</label><input class="form-control" type="email" id="email" name="email" required placeholder="Email" /></div>
            <div class="form-group"><label id="lblbirthdate">Birth date</label><input class="form-control" type="date" id="birthDate" name="birthDate" required /></div>
            <div class="form-group"><label>Password</label>
                <input class="form-control" type="password" id="userPassword" name="password" required placeholder="Password" maxlength="40" minlength="5" />
                <input type="password" id="repeatedPassword" name="password-repeat" required placeholder="Password (repeat)" class="form-control" /></div>
            <div class="form-group"><button class="btn btn-primary btn-block" id="btnSubmit" disabled="true" type="submit" style="background-color: #f77f00;">Sign Up</button></div>
        </form>

        <script>

            $(document).ready(function () {

                // Validate if the repeated password matches the entered password
                function checkPasswords() {
                    var password = document.getElementById('userPassword').value;
                    var repeatedPassword = document.getElementById('repeatedPassword').value;

                    document.getElementById('btnSubmit').disabled = true;

                    if (password === repeatedPassword && password !== "" && repeatedPassword !== "") {
                        document.getElementById('btnSubmit').innerHTML = "Sign up";
                        document.getElementById('btnSubmit').disabled = false;
                    } else {
                        document.getElementById('btnSubmit').innerHTML = "Passwords are not equal";
                        document.getElementById('btnSubmit').disabled = true;
                    }
                }
                
                
                /**
                 * When the date field is left, it is checked whether the user is at least 16 years old.
                * 
                * @author Vanessa Cajochen
                */
                function checkBirthdate() {
                    var birthdate = new Date(document.getElementById('birthDate').value);
                    var minAge = new Date(new Date().setFullYear(new Date().getFullYear() - 16))
                    
                    
                    if (birthdate>minAge){
                        alert("Minimum age 16 years");
                        document.getElementById('btnSubmit').disabled = true;
                    } else if (birthdate<minAge) {
                        document.getElementById('btnSubmit').disabled = false;
                    }
                    
                }

                document.getElementById('repeatedPassword').addEventListener('keyup', function () {
                    checkPasswords();
                })

                document.getElementById('userPassword').addEventListener('keyup', function () {
                    checkPasswords();
                })
                
                document.getElementById('birthDate').addEventListener('blur', function () {
                    checkBirthdate();
                })

                document.getElementById('btnSubmit').addEventListener('click', function () {
                    var password = document.getElementById('userPassword').value;
                    var repeatedPassword = document.getElementById('repeatedPassword').value;

                    if (password !== repeatedPassword) {
                        alert("The first password you entered will be stored in our database.");
                    }
                })
                
                
                /**
                 * When the submit button is pressed, the system first checks whether the email already exists without sending the entire form to the server.
                * @author Vanessa Cajochen
                */
                $('#registrationForm').on('submit', function(event){
                                       
                    event.preventDefault();
                    var form = $("#registrationForm").serialize();
                    $.ajax({
                        type:'POST',
                        url:'ajaxEmail',
                        data: form,
                        success:function(data){
                            if(data.status == 'success'){
                                $('#registrationForm').unbind().submit();
                             }else if(data.status == 'error'){
                                document.getElementById('btnSubmit').innerHTML = "Email already exists";
                            }
                        }
                       });
                       
                   });
                
                
                
                
                
                
                
            });

        </script></div>
</div>

