window.onload = function () {
    google.accounts.id.initialize({
        client_id: GSIGN_CLIENT_ID,
        callback: handleGoogleSign
    });
    google.accounts.id.renderButton(
        document.getElementById("g-signin2"), {
            theme: "outline",
            size: "large"
        }
    );

}


// To Make Google Sign in
function handleGoogleSign(n) {
    ajax(AUTH_API, {
        "token": n.credential,
        "func":"glogin"
    }, function (s) {
        window.location.reload();
    }, function () {
        swal({
                title: "Not Authorized!",
                text: "Please register using your email ID",
                icon: "warning",
            })
            .then(() => {
                window.location.replace("logout/index.php");
            })
    });

}





$(document).on('click', "#primary_login", function() {
    var login_email = $('#login_email').val();
    var login_pwd = $('#login_pwd').val();

    $('.error').hide();
    var clr = 0;

    if (login_email == "") {
        $('#help_login_email').html('<span class="error"> Email ID Required</span>');
        clr = 1;
    }


    if (login_pwd == "") {
        $('#help_login_pwd').html('<span class="error"> Password Required</span>');
        clr = 1;
    }

    if (clr == 0) {
        ajax(AUTH_API, {
                "mailId": login_email,
                "password": login_pwd,
                "func": "login"
            },
            function(s) {
                window.location = BASE_URL;
        },function (){
            swal({
                title: "Invalid Credentials!",
                text: "Email ID or Password is Incorrect!",
                icon: "warning",
            }).then(() => {
                window.location = BASE_URL;
            });
        });
    }
});



$(document).on('click', "#new_reg", function() {

    var first_name = $('#first_name').val();
    var last_name = $('#last_name').val();
    var email_id = $('#email_id').val();
    var pwd = $('#pwd').val();
    var conf_pwd = $('#conf_pwd').val();
    var course_name = $('#course_name option:selected').val();
    var batch_number = $('#batch_number').val();
    var pg_yr = $("#pg_yr option:selected").val();
    var roll_number = $("#roll_number").val();


    $('.error').hide();
    var clr = 0;

    if (first_name == "") {

        $('#help_title').html('<span class="error"> Name Required</span>');
        clr = 1;
    }


    if (email_id == "") {

        $('#help_email').html('<span class="error"> Email Required</span>');

        clr = 1;
    } else {
        const regex = /^((?!\.)[\w\-_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/;
        var chekmail = regex.test(email_id);
        if (chekmail == false) {
            $('#help_email').html('<span class="error"> Not a valid Email Id</span>');
            clr = 1;
        }
    }



    if (pwd == "") {

        $('#help_pwd').html('<span class="error"> Password Required</span>');
        clr = 1;
    }
    if (conf_pwd == "") {

        $('#help_conf_pwd').html('<span class="error"> Confirm Password Required</span>');
        clr = 1;
    }
    if (course_name == "") {

        $('#help_course_name').html('<span class="error"> Course Name Required</span>');
        clr = 1;
    }
    if ((pg_yr == "") || (pg_yr == undefined)) {

        $('#help_py_yr').html('<span class="error"> Graduation Year Required</span>');
        clr = 1;
    }
    if (roll_number == "") {

        $('#help_roll_number').html('<span class="error"> Roll Number Required</span>');
        clr = 1;
    }
    if (conf_pwd != pwd) {
        $('#help_conf_pwd').html('<span class="error"> Password Mismatch</span>');
        clr = 1;
    }


    if (clr == 0) {
        ajax(AUTH_API, "func=register&first_name=" + first_name +
        "&last_name=" + last_name + "&email_id=" + email_id +
        "&pwd=" + pwd + "&conf_pwd=" + conf_pwd +
        "&course_name=" + course_name + "&graduated_yr=" + pg_yr + "&roll_number=" + roll_number,
        function(s) {
            swal({
                title: "Registered Successfully!",
                            text: "You will receive an email after approval!",
                        icon: "success",
            }).then(() => {
                window.location = BASE_URL;
            });
        },function (err){
            swal({
                title: "Error!",
                text: err,
                icon: "warning",
            }).then(() => {
                window.location = BASE_URL;
            });
        });
    }
});
$(document).on('click', "#update_form", function() {
    var first_name = $('#address').val();
    var last_name = $('#add_address').val();
    var course_name = $('#birthday').val();



    $('.error').hide();
    var clr = 0;


    if (course_name == "")  {

        $('#help_birthday').html('<span class="error"> DOB Required</span>');
        clr = 1;
    }



    if (clr == 0) {
        ajax(AUTH_API, "func=update_form&first_name=" + first_name +
        "&last_name=" + last_name + "&course_name=" + course_name,
        function(s) {
            swal({
                title: "Updated Successfully!",
                            text: "Your Personal Information had been updated",
                        icon: "success",
            }).then(() => {
                window.location = BASE_URL;
            });
        },function (err){
            swal({
                title: "Error!",
                text: err,
                icon: "warning",
            }).then(() => {
                window.location = BASE_URL;
            });
        });
    }
});



// Switch to Login Tab
$(document).on('click', ".login", function () {

    $("#register_section").hide();
    $("#login_section").show();
});


// Switch to Register Tab
$(document).on('click', ".register", function () {

    $("#register_section").show();
    $("#login_section").hide();
});


jQuery(document).ajaxStart(function () {
    NProgress.start();
});

jQuery(document).ajaxStop(function () {
    NProgress.done();
});

/*******For 15mins session timeout---START*******/
// setInterval(function () {
//     window.location.href = LOGOUT_API;
// }, 900000);
/*******For 15mins session timeout---END********/
