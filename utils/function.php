<?php

// Get all Jobs 
function getAllJobs()
{
    return $GLOBALS["DB"]->select("SELECT SHA1(jr.`id`) jid ,jr.* FROM job_requests jr WHERE job_status = 'approved' ORDER BY posted_at DESC ");
}

function getJob($id)
{
    return $GLOBALS["DB"]->select("SELECT SHA1(jr.`id`) jid,jr.* FROM job_requests jr where SHA1(jr.`id`) =? ", [test_input($id)]);
}

require_once __DIR__ . "/../utils/email/comman_mail.php";

function addJob($tilte,$sub_title, $company_name, $job_location, $expiry_date, $job_role, $job_description, $additional_details, $contact_name, $contact_number, $contact_email, $apply_link, $website_link)
{

    $job_date = date("Y-m-d h:i:s");
    if($GLOBALS["DB"]->insertAndGetAutoId("INSERT INTO  job_requests (company_name, title, location, job_date, job_mode, job_description, contact_email, contact_no, apply_link, job_status, expiry_date, job_primary_link, additional_details, sub_title, posted_by, posted_at,contact_person) 
    value(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", [
        $company_name, $tilte, $job_location, $job_date, $job_role, $job_description, $contact_email, $contact_number, $apply_link, 'pending', $expiry_date, '',   $additional_details, $sub_title, $_SESSION["user"], $job_date, $contact_name
    ])) {

        foreach ($GLOBALS["DB"]->select("select user_name,email from users where user_role = 'admin' AND user_status='active'") as $result) {
            $data      = array('site_url' => MAIN_BASE_URL, 'to_name' => $result['user_name'], 'to_email' => strtolower($result['email']), 'posted_by' => $_SESSION["user"]);
            direct_registration($data);
        }
        complete(true);
    }
    err("Unable Add Job");
}


function getAllPendingJobs()
{
    return $GLOBALS["DB"]->select("SELECT  SHA1(jr.`id`) jid,jr.* FROM job_requests jr where job_status = 'pending'");
}


function approveJob($id)
{
    if ($GLOBALS["DB"]->update("UPDATE job_requests SET job_status = 'approved',approved_by = ?, approved_at =? where SHA1(id) = ?", [$_SESSION['user'], date("Y-m-d h:i:s"), $id]))
        complete(true);

    err("Error In Approving Job");
}


function rejectJob($id)
{
    if ($GLOBALS["DB"]->update("UPDATE job_requests SET job_status = 'rejected',approved_by = ?, approved_at =? where SHA1(id) = ?", [$_SESSION['user'], date("Y-m-d h:i:s"), $id]))
        complete(true);

    err("Error In Rejecting Job");
}


function getAllPendingUsers()
{
    return $GLOBALS["DB"]->select("SELECT * , SHA1(`id`) uid FROM users WHERE user_status = 'pending'");
}

function approveUser($id)
{

    if (!$GLOBALS["DB"]->insert("UPDATE users SET user_status = 'active',approved_at = ? , approved_by = ? where sha1(id) = ?", [date("Y-m-d h:i:s"), $_SESSION['user'], $id]))
        err("Error in Accepting User !");


    foreach ($GLOBALS["DB"]->select("select user_name,email,roll_number from users where sha1(id) = ?", [$id]) as $result) {
        $data      = array('site_url' => MAIN_BASE_URL, 'to_name' => $result['user_name'], 'to_email' => strtolower($result['email']), 'roll_number' => $result['roll_number']);
        approve_user($data);
    }

    complete(true);
}

function rejectUser($id)
{

    if (!$GLOBALS["DB"]->insert("UPDATE users SET user_status = 'rejected',approved_at = ? , approved_by = ? where sha1(id) = ?", [date("Y-m-d h:i:s"), $_SESSION['user'], $id]))
        err("Error in Rejecting User !");

    complete(true);
}

function getAllPendingFeedback()
{
    return $GLOBALS["DB"]->select("SELECT * FROM contact_us ORDER BY updated_at");
}


function addFeedback($name, $subject, $message)
{
    if (!$GLOBALS["DB"]->insert("INSERT INTO  contact_us (name, subject, message, updated_by) VALUES(?,?,?,?)", [$name, $subject, $message, $_SESSION['user']]))
        err("Error in Adding you Feedback !");
    complete(true);
}


function getAllBlogs($pageid)
{
    return $GLOBALS["DB"]->select("SELECT MD5(b.`blog_id`) bid,DATE_FORMAT(b.created_at,'%M %d,%Y') posted_at ,b.`title`,b.`image`,b.`description`,bc.`category_name` FROM blog b
    INNER JOIN `blog_category` bc ON bc.`category_id`=b.`category_id`
    WHERE b.`status`>0 ORDER BY b.created_at DESC LIMIT ?, 4 ", [$pageid]);
}

function getAllBlogsByCategory($categoryId,$pageid)
{
    return $GLOBALS["DB"]->select("SELECT MD5(b.`blog_id`) bid,DATE_FORMAT(b.created_at,'%M %d,%Y') posted_at ,b.`title`,b.`image`,b.`description`,bc.`category_name` FROM blog b
    INNER JOIN `blog_category` bc ON bc.`category_id`=b.`category_id`
    WHERE b.`status`>0 AND MD5(b.`category_id`)=? ORDER BY b.created_at  DESC LIMIT ?, 4", [$categoryId,$pageid]);
}

function getAllBlogsBySearch($search,$pageid)
{
    return $GLOBALS["DB"]->select("SELECT MD5(b.`blog_id`) bid,DATE_FORMAT(b.created_at,'%M %d,%Y') posted_at ,b.`title`,b.`image`,b.`description`,bc.`category_name` FROM blog b
    INNER JOIN `blog_category` bc ON bc.`category_id`=b.`category_id`
    WHERE b.`status`>0 AND(b.`description` LIKE '%".$search."%' OR b.`title` LIKE '%".$search."%' OR bc.`category_name` LIKE '%".$search."%' )  ORDER BY b.created_at  DESC LIMIT ?, 4", [$pageid]);
}

function getAllBlogDetails($id)
{
    return $GLOBALS["DB"]->select("SELECT MD5(b.`blog_id`) bid ,b.`title`,b.`image`,b.`description`,bc.`category_name`,DATE_FORMAT(b.created_at,'%M %d,%Y') posted_at,u.`user_name`  FROM blog b
    INNER JOIN `blog_category` bc ON bc.`category_id`=b.`category_id`
    INNER JOIN `users` u ON u.`id`=b.`created_by`
    WHERE b.`status`>0  and MD5(b.`blog_id`) = ?", [$id]);
}

function getLastestBLog()
{
    return $GLOBALS["DB"]->select("SELECT MD5(b.`blog_id`) bid,DATE_FORMAT(b.created_at,'%M %d,%Y') posted_at ,b.`title`,b.`image`,b.`description` FROM blog b
    WHERE b.`status`>0 ORDER BY b.created_at DESC LIMIT 0, 3 ");
}

function getComments($id)
{
    $blogs = $GLOBALS["DB"]->select("SELECT bc.`message`,bc.`comment_id`,bc.`blog_id`,u.`user_name`,u.id uid,reply_id,DATE_FORMAT(bc.created_at,'%M %d,%Y') commented_at FROM blog_comment bc
    INNER JOIN users u ON u.`id`=bc.`created_by`
     WHERE bc.`status`>0  AND MD5(bc.`blog_id`)=?", [$id]);

    complete(genrateComment(0,$blogs));
    
}


function genrateComment($pId,$comments){
    $result = array();

    foreach ($comments as $comment)
        if ($comment['reply_id'] == $pId)
            $result[] = $comment;

    $r = array();
    
    foreach ($result as $row) {
        $count = 0;
        $id = $row['comment_id'];
        foreach ($comments as $comment)
            if ($comment['reply_id'] == $id)
                $count += 1;

        if ($count > 0)
            $row['reply'] = genrateComment($id, $comments);
        unset($row['reply_id']);
        $r[] = $row;
    }

    return $r;
}




function addReply($blog_id,$add_comment_id,$add_comment_message){

    if($GLOBALS["DB"]->insert("INSERT INTO `blog_comment`(blog_id,reply_id,message,created_at,created_by) VALUE((SELECT blog_id FROM blog WHERE  MD5(`blog_id`)=?),?,?,?,?)",[
        $blog_id,$add_comment_id,$add_comment_message,$GLOBALS["CURRENT_DATE"],$_SESSION["user_id"]
    ]))
        complete(true);
    
    err("Unable add your reply !");
}


function addBlog($blog_title,$blog_category,$blog_detail){
    $img=uploadFile("blog_img");

    if($GLOBALS["DB"]->insert("INSERT INTO `blog`(title,description,category_id,image,created_by,created_at) VALUE(?,?,(SELECT bc.`category_id` FROM `blog_category` bc WHERE MD5(bc.`category_id`)=?),?,?,?)",
    [$blog_title,$blog_detail,$blog_category,$img,$_SESSION["user_id"],$GLOBALS["CURRENT_DATE"]]))
        complete(true);
    
    err("Unable add blog !");
}


function getAllCategorys()
{
    return $GLOBALS["DB"]->select("SELECT MD5(bc.`category_id`)cid , bc.`category_name`,(SELECT COUNT(b.`blog_id`) FROM blog b WHERE b.`status`>0 AND b.`category_id`= bc.`category_id`) len  FROM `blog_category` bc WHERE bc.`status`>0 ORDER BY bc.`created_at`");
}



function getBlogsLength()
{
    return $GLOBALS["DB"]->select("SELECT count(blog_id) c  FROM blog b WHERE  b.`status`>0")[0]['c'];
}


function getBlogsByCategoryLength($categoryId)
{
    return $GLOBALS["DB"]->select("SELECT count(blog_id) c  FROM blog b WHERE  b.`status`>0 AND MD5(b.`category_id`)=?",[$categoryId])[0]['c'];
}

function getBlogsBySearchLength($search)
{
    return $GLOBALS["DB"]->select("SELECT count(blog_id) c   FROM blog b INNER JOIN `blog_category` bc ON bc.`category_id`=b.`category_id` 
    WHERE b.`status`>0 AND(b.`description` LIKE '%".$search."%' OR b.`title` LIKE '%".$search."%' OR bc.`category_name` LIKE '%".$search."%' ) ")[0]['c'];
}



function uploadFile($name){
    $temp = explode(".", $_FILES[$name]["name"]);
    $path = round(microtime(true)) . '.' . end($temp);
    move_uploaded_file($_FILES[$name]["tmp_name"], __DIR__."/../uploads/" .  $path);
    return $path;
}