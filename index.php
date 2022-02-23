<?php
$menu = 1;
require __DIR__ . "/includes/init.php";

?>


<section class="banner" id="top" style="background-image: url(<?php echo MAIN_BASE_URL; ?>/assets/img/1.jpg);"
         xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="banner-caption">
                    <div class="line-dec"></div>
                    <h2>Welcome to Guvi .</h2>
                </div>
            </div>
        </div>
    </div>
</section>

<main>
    <section class="our-services" style="padding: 0px;">
        <form class="login100-form validate-form"  id="register_section" style="width: 100% !important;">
            <span class="login100-form-title p-b-43">Personal Information </span>


            <div class="row">
                <div class="col-sm-6 ">
                    <label class="form-label" for="first_name" disabled="disabled">First Name<font class="redFont">*</font></label>
                    <input type="text" id="first_name" class="form-control " />
                    <span class="help-inline" id="help_title"></span>

                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="last_name" disabled="disabled">Last Name</label>
                    <input type="text" id="last_name" class="form-control" />

                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label class="form-label">Email ID <font class="redFont">*</font></label>
                    <input type="email" class="form-control" name="email_id" id="email_id" />
                    <span class="help-inline" id="help_email"></span>

                </div>
                <div class="col-sm-6">
                    <label class="form-label">Date of birth <font class="redFont">*</font></label>
                    <input class="form-control date-content" type="date" id="birthday" name="birthday">
                    <span class="help-inline" id="help_birthday"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                <label class="form-label" for="last_name" >Address</label>
                    <textarea type="" id="address" class="form-control" ></textarea>
                </div>
                <div class="col-sm-6">
                    <label class="form-label" for="last_name" >Additional Details</label>
                    <textarea type="" id="add_address" class="form-control" ></textarea>
                </div>
            </div>




            <div class="row">

                <div class="col-sm-6">
                    <a href="javascript:void(0);" class="btn btn-success btn-lg mb-1 update_form" id="update_form" style="float: right;border-radius: 11px;">Update</a>
                </div>

            </div>
        </form>
    </section>



</main>