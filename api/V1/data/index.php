<?php
require __DIR__."/../../../utils/init.php";


// 1. All request to /data/ must be in POST
if ($_SERVER['REQUEST_METHOD'] != 'POST')
    err('Invalid Request!');


if (empty($_POST))
    $_POST = json_decode(file_get_contents('php://input'), true);



check('func', 'Invalid Request!');

extract($_POST);
switch ($func){

    case "add_job":
        check(["title","sub_title","company_name","job_location","expiry_date","job_role","job_description","additional_details","contact_name","contact_number","contact_email","apply_link","website_link"]);
        extract($_POST);
        addJob($title,$sub_title,$company_name,$job_location,$expiry_date,$job_role,$job_description,$additional_details,$contact_name,$contact_number,$contact_email,$apply_link,$website_link);
        break;


    case "approve_job":
        check('job_id', 'Invalid Request!');
        extract($_POST);
        approveJob($job_id);
        break;

    case "reject_job":
        check('job_id', 'Invalid Request!');
        extract($_POST);
        rejectJob($job_id);
        break;


    case "approve_user":
        check('user_id', 'Invalid Request!');
        extract($_POST);
        approveUser($user_id);
        break;


    case "reject_user":
        check('user_id', 'Invalid Request!');
        extract($_POST);
        rejectUser($user_id);
        break;
        

    case "add_feedback":
        check(['subject','message','name'], 'Invalid Request!');
        extract($_POST);
        addFeedback($name,$subject,$message);
        break;

    case "get_comments":
        check('blog_id', 'Invalid Request!');
        extract($_POST);
        getComments($blog_id);
    break;  
        
    case "add_reply":
        check(['add_comment_id','blog_id'], 'Invalid Request!');
        check('add_comment_message','Comments Required !');
        extract($_POST);
        addReply($blog_id,$add_comment_id,$add_comment_message);
        break;

    // func=add_blog&blog_title=asa&blog_category=c4ca4238a0b923820dcc509a6f75849b&details=<p>asa</p>
    case "add_blog":
        check(['blog_title','blog_category','blog_detail'], 'Invalid Request!');
        extract($_POST);
        addBlog($blog_title,$blog_category,$blog_detail);
        break;
    default:
        err("Invalid Request !");
}