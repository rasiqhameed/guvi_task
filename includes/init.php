<?php

require __DIR__."/../utils/init.php";




function pagefooter(){
    require __DIR__."/footer.php";
}


if(isset($_SESSION['user_role'])&&!empty($_SESSION['user_role'])){
    $user_role = $_SESSION['user_role'];
    $email_id = $_SESSION['user'];
}else{ 
    require __DIR__."/header.php";
    die();
}
require __DIR__."/header.php";
register_shutdown_function("pagefooter");