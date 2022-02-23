<?php

function err(string $err): void
{
    $res = array();
    $res['ok'] = false;
    $res['err'] = $err;
    _finish($res);
}

function complete($data): void
{
    $res = array();
    $res['ok'] = true;
    $res['data'] = $data;
    _finish($res);
}

function _finish(array $res): void
{
    header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Pragma: no-cache');
    header('Content-Type: application/json');
    echo json_encode($res, JSON_PRETTY_PRINT);
    die();
}