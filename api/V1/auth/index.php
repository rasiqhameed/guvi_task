<?php


// 1. All request to /data/ must be in POST
if ($_SERVER['REQUEST_METHOD'] != 'POST')
    err('Invalid Request!');

require __DIR__."/../../../utils/init.php";



check("func","Invalid Request");
extract($_POST);

if($func=="glogin"){
    check("token","","Invalid Request");
    extract($_POST);
    $js  = json_decode(file_get_contents('https://www.googleapis.com/oauth2/v2/tokeninfo?id_token=' . $token), true);
    if (($js["audience"] == GSIGN_CLIENT_ID) && ( $js["issued_to"] == GSIGN_CLIENT_ID)) {
        $mailId = $js["email"];
        if($data = $DB->select("SELECT  us.`user_role`,us.id,us.user_name  FROM users us WHERE  us.`email`=? AND  user_status = 'active'",[$mailId])){ 
            $_SESSION["user"] = $mailId;
            $_SESSION["user_role"]  = $data[0]["user_role"];
            $_SESSION["user_id"]  = $data[0]["id"];
            $_SESSION["user_name"]  = $data[0]["user_name"];
            complete(true);
        } else {
            err("User Not Found !");
        }
    }
}

if($func=="login"){
    check(["mailId","password"],"Invalid Request");
    extract($_POST);
    if($data = $DB->select("SELECT  us.`user_role`,us.id,us.user_name FROM users us WHERE  us.`email`=? and  us.password =md5(?) AND  user_status = 'active'",[$mailId,$password])){ 
        $_SESSION["user"] = $mailId;
        $_SESSION["user_role"]  = $data[0]["user_role"];
        $_SESSION["user_id"]  = $data[0]["id"];
        $_SESSION["user_name"]  = $data[0]["user_name"];
        complete(true);
    } else {
        err("Invalid Credentials !");
    }
}


if($func=="register"){
    
    //require __DIR__."/../../../utils/email/comman_mail.php";
    check(["first_name","last_name","email_id","pwd","conf_pwd","course_name","graduated_yr","roll_number"],"Invalid Request");
    extract($_POST);
    if($pwd!=$conf_pwd)
        err("Both Password Should be Same !");
    if($data = $DB->insert("INSERT INTO  users (user_name, email, password, user_role, course_name, user_status,graduated_yr,roll_number) VALUE (?,?,md5(?),'alumni',?,'active',?,?)",[$first_name,$email_id,$pwd,$course_name,$graduated_yr,$roll_number])){
        complete(true);
    }
    else 
        err("User Already with same Mail Exist !");
}

if($func=="update_form") {

    //require __DIR__."/../../../utils/email/comman_mail.php";
    check(["first_name", "last_name", "course_name"], "Invalid Request");
    extract($_POST);
     $mailId = $_SESSION["user_id"];
    if ($data = $DB->update("UPDATE users SET date_of_birth = '$course_name', current_location = '$first_name', designation = '$last_name' WHERE id = '$mailId'")){
    complete(true);
    }
    else
            err("User Already with same Mail Exist !");


}