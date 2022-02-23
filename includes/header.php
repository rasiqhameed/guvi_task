<!DOCTYPE html>
<html>

<head>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-C1W15NLCQG"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-C1W15NLCQG');
    </script>


    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>GUVI - Task Assignment</title>
    <link rel="shortcut icon" href="<?php echo MAIN_BASE_URL; ?>/assets/img/favicon.png">
    <meta name="description" content="">
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <meta name="google-signin-client_id" content="<?= GSIGN_CLIENT_ID ?>">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo MAIN_BASE_URL; ?>/assets/bootstrap%204/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo MAIN_BASE_URL; ?>/assets/custom/custom.css">
    <link rel="stylesheet" href="<?php echo MAIN_BASE_URL; ?>/assets/font-awesome-4.7.0/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo MAIN_BASE_URL; ?>/assets/css/util.css">
    <link rel="stylesheet" type="text/css" href="<?php echo MAIN_BASE_URL; ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?php echo MAIN_BASE_URL; ?>/assets/css/owl-carousel.css">
    <link rel="stylesheet" href="<?php echo MAIN_BASE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo MAIN_BASE_URL; ?>/assets/js/loader/nprogress.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <script src="<?php echo MAIN_BASE_URL; ?>/assets/js/core/jquery.min.js"></script>
    <script src="<?php echo MAIN_BASE_URL; ?>/assets/js/ajax.js"></script>
    <script src="<?php echo MAIN_BASE_URL; ?>/assets/js/loader/nprogress.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        var GSIGN_CLIENT_ID = "<?= GSIGN_CLIENT_ID ?>";
        var DATA_API = "<?= MAIN_BASE_URL ?>/api/V1/data/";
        var AUTH_API = "<?= MAIN_BASE_URL ?>/api/V1/auth/";
        var LOGOUT_API = "<?= MAIN_BASE_URL ?>/logout/";
        var BASE_URL="<?= MAIN_BASE_URL ?>";
    </script>
    <script src="https://accounts.google.com/gsi/client" defer></script>
    <script src="<?php echo MAIN_BASE_URL; ?>/assets/js/main.js"></script>
    <script src="<?php echo MAIN_BASE_URL; ?>/assets/js/auth.js"></script>
</head>

<body>
    <div>
        <?php
        if (!isset($_SESSION["user_role"])) {
        ?>
            <section class="vh-100" style="padding: 0px!important;">
                <div class="limiter">
                    <div class="container-login100">
                        <div class="wrap-login100">
                            <form class="login100-form validate-form" id="login_section">
                                <div class="row" style="justify-content: space-around;">
                                    <img src="<?php echo MAIN_BASE_URL; ?>/uploads/guvi-logo.png" class="text-center my-4" style="text-align:center;width: 50%;" />
                                </div>
                                <span class="login100-form-title p-b-43">
                                    Login Form
                                </span>


                                <div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
                                    <input class="input100" type="text" name="email" id="login_email">
                                    <span class="focus-input100"></span>
                                    <span class="label-input100">Email</span>

                                </div>
                                <span class="help_login_pwd" id="help_login_email"></span>


                                <div class="wrap-input100 validate-input" data-validate="Password is required">
                                    <input class="input100" type="password" name="pass" id="login_pwd">
                                    <span class="focus-input100"></span>
                                    <span class="label-input100">Password</span>

                                </div>
                                <span class="help_login_pwd" id="help_login_pwd"></span>

                                <div class="flex-sb-m w-full p-t-3 p-b-32">
                                    <div class="contact100-form-checkbox">

                                        <p>New user?<a class="small-text register" href="javascript:void(0);" style="text-decoration: none;color:#6675df;"> Register Here</a></p>
                                    </div>


                                </div>


                                <div class="container-login100-form-btn">
                                    <button type="button" class="login100-form-btn" id="primary_login">
                                        Login
                                    </button>
                                </div>
<!---->
<!--                                <div class="text-center p-t-40 p-b-20">-->
<!--                                    <span class="txt2">-->
<!--                                        Sign in using-->
<!--                                    </span>-->
<!--                                </div>-->
<!---->
<!--                                <div id="g-signin2" class="g-signin2  mt-4 " data-onsuccess="onSignIn" style="margin-top: 0px!important;display: flex;justify-content: center;align-items: center;">-->
<!---->
<!---->
<!--                                </div>-->
<!--                                <p class="txt2" style="text-align: center;padding-top: 2px; ">-->
<!--                                    (Accessible only for Krea and IFMR User accounts)-->
<!--                                </p>-->
                            </form>

                            <form class="login100-form validate-form" style="display: none;" id="register_section">
                                <span class="login100-form-title p-b-43">Registration Form </span>


                                <div class="row">
                                    <div class="col-sm-6 ">
                                        <label class="form-label" for="first_name">First Name<font class="redFont">*</font></label>
                                        <input type="text" id="first_name" class="form-control " />
                                        <span class="help-inline" id="help_title"></span>

                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label" for="last_name">Last Name</label>
                                        <input type="text" id="last_name" class="form-control" />

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="form-label">Email ID <font class="redFont">*</font></label>
                                        <input type="email" class="form-control" name="email_id" id="email_id" />
                                        <span class="help-inline" id="help_email"></span>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="form-label" for="pwd">Password <font class="redFont">*</font></label>
                                        <input type="password" id="pwd" class="form-control" />
                                        <span class="help-inline" id="help_pwd"></span>

                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label" for="conf_pwd">Confirm Password <font class="redFont">*</font></label>
                                        <input type="password" id="conf_pwd" class="form-control" />
                                        <span class="help-inline" id="help_conf_pwd"></span>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="form-label">Roll Number <font class="redFont">*</font></label>
                                        <input type="text" class="form-control" name="roll_number" id="roll_number" />
                                        <span class="help-inline" id="help_roll_number"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="form-label">Course </label>
                                        <select class="select form-control " id="course_name">
                                            <option value="" disabled>Course</option>
                                            <option value="PGDM (MBA) Full Time">PGDM (MBA) Full Time</option>
                                            <!--                                    <option value="3">Executive MBA</option>-->
                                            <!--                                    <option value="4">PhD </option>-->
                                        </select>
                                        <span class="help-inline" id="help_course_name"></span>


                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-sm-12">
                                        <label class="form-label">Graduation Year <font class="redFont">*</font></label>
                                        <select class="select form-control " id="pg_yr">
                                            <option value="">Select Year</option>
                                            <?php $years = range(1971, strftime("%Y", time())); ?>
                                            <?php foreach ($years as $year) : ?>
                                                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="help-inline" id="help_py_yr"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <a class="login btn btn-danger btn-lg mb-1 " href="#" style="text-align: right;text-decoration: none;border-radius: 11px;">Cancel</a>
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="javascript:void(0);" class="btn btn-success btn-lg mb-1 new_reg" id="new_reg" style="float: right;border-radius: 11px;">Register Here</a>
                                    </div>

                                </div>
                            </form>

                            <div class="login100-more" style="background-image: url('<?php echo MAIN_BASE_URL; ?>/assets/img/contact-image-1-1920x700.jpg');background-size: contain;">
                            </div>
                        </div>
                    </div>
                </div>
            </section>


    </div>


<?php
        } else {
?>
    <div class="main-page">
        <div class="wrap sticky-top">
            <header id="header">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <button id="primary-nav-button" type="button">Menu</button>
                            <a href="<?php echo MAIN_BASE_URL; ?>">
                                <div class="logo">
                                    <img src="<?php echo MAIN_BASE_URL; ?>/uploads/guvi-logo.png" alt="Logo" style="width: 50%;">
                                </div>
                            </a>
                            <nav id="primary-nav" class="dropdown cf">
                                <ul class="dropdown menu">
                                    <li class='<?php if ($menu == 1) {
                                                    echo "active";
                                                } else {
                                                    echo "";
                                                } ?>'><a href="<?php echo MAIN_BASE_URL; ?>">Home</a></li>
                                    <li><a href="<?php echo MAIN_BASE_URL; ?>/logout/"><i class="fa fa-power-off"><span style="font-family: Raleway;font-weight: bold;"> Logout </span></i></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>
        </div>

    <?php } ?>