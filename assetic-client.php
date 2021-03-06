#!/usr/bin/env php
<?php

/*
 * This file is part of the assetic web api.
 *
 * (c) Pablo Godel <pablo@servergrove.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

function sendRequest($vars, $url)
{
    $ch = curl_init($url);

    $encoded = http_build_query($vars);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS,  $encoded);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);

    if (!$output) {
        die(1);
    }
    $decoded = json_decode($output);
    if (!$decoded) {
        die(1);
    }

    if ($decoded->result) {
        $result = $decoded->content;
    } else {
        return null;
    }

    return $result;
}

//mail('pablo@servergrove.com', 'assetic', 'assetic: '.print_r($_SERVER['argv'], true));
if (strpos($_SERVER['argv'][1], '-cp') !== false) {
    $content = file_get_contents($_SERVER['argv'][2]);

    $url = "http://assetic.servergrove.com/coffeescript.json";

    $vars = array(
      'content' => $content,
    );

    if (null === $result = sendRequest($vars, $url)) {
        $result = $content;
    }

    echo $result;
    die(0);
}
if (strpos($_SERVER['argv'][2], 'yuicompressor') !== false) {
    $content = file_get_contents($_SERVER['argv'][7]);

    $url = "http://assetic.servergrove.com/yuicompressor.json";

    $vars = array(
      'charset' =>   $_SERVER['argv'][4],
      'in' =>   $_SERVER['argv'][7],
      'out' =>  $_SERVER['argv'][6],
      'content' => $content,
    );

    if (null === $result = sendRequest($vars, $url)) {
        $result = $content;
    }

    file_put_contents($_SERVER['argv'][6], $result);
    die(0);
}