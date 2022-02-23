<?php

header("Access-Control-Allow-Origin: *");
date_default_timezone_set("Asia/Kolkata");

function check($query, $err = '',$html =true)
{
    if (is_array($query))
        foreach ($query as $q)
            _checkPostValue($q, $err,$html );
    else
        _checkPostValue($query, $err,$html );
}

function _checkPostValue(string $q, $err, $html)
{
    if (!isset($_POST[$q])) {
        if ($err == '')
            $err = str_replace('_', ' ', ucwords($q, '_')) . ' required';
        err($err, 400);
    }
    $_POST[$q] = test_input($_POST[$q], $html);
}

function test_input($data, $html=true)
{
	$data = trim($data);
	$data = stripslashes($data);
	if ($html) {
		$data = htmlspecialchars($data);
	}    
	return $data;
}