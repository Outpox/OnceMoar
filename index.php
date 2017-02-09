<?php
echo "<h2>Please remember this tool don't have access to your Once account (NO password) and nothing is saved.</h2>";
$token = isset($_GET['token']) ? $_GET['token'] : null;
if (isset($token) && $token != '') {
    $_SESSION['onceToken'] = $token;
    $opts = array(
        'http' => array(
            'method' => "GET",
            'header' => "Authorization: " . $token . "\r\n"
        )
    );
    $context = stream_context_create($opts);
    $file = file_get_contents("https://api.onceapi.com/v1/match/history", false, $context);
    $response = json_decode($file);
    $photosBaseUrl = $response->result->base_url;

    foreach ($response->result->matches as $match) {
        $firstName = $match->user->first_name;
        $photos = $photosBaseUrl . "/" . $match->user->id . "/" . $match->user->pictures[0]->original;
        $commonFriends = count($match->commons->facebook->friends);
        $commonLikes = count($match->commons->facebook->likes);
        $viewedMe = $match->viewed_me;
        $likedMe = $match->liked_me;
        $passedMe = $match->passed_me;
        $connected = $match->connected;
        $lastOnline = $match->user->last_online;

        echo "<img height='250px' src='" . $photos . "' alt='" . $firstName . "'/></br>";
        echo "<h3>Name : " . $firstName . "</h3></br>";
        echo "Facebook friends in common : " . $commonFriends . "</br>";
        echo "Facebook likes in common : " . $commonLikes . "</br>";
        echo "Viewed me : " . ($viewedMe ? "true" : "false") . "</br>";
        echo "Liked me : " . ($likedMe ? "true" : "false") . "</br>";
        echo "Passed me : " . ($passedMe ? "true" : "false") . "</br>";
        echo "Connected : " . ($connected ? "true" : "false") . "</br>";
        echo "Last online : " . (date(DATE_RFC2822, $lastOnline)) . "</br>";
        echo "<br><hr/><br>";
    }

//	var_dump($response);
} else {
    if (isset($_GET['error'])) {
        switch ($_GET['error']) {
            case "noCookie":
                echo "Error, no cookie found.<br>Please login on <a href='https://getonce.com/'>https://getonce.com/</a> in Chrome and reclick the extension.";
                break;
        }
    }
    else {
        echo "Please first install this Chrome extension : <a href='http://62.210.236.193/once/extension.zip'>extension.zip</a>.<br>
If you don't know how to install it follow <a href='http://techapple.net/2015/09/how-to-install-load-unpacked-extension-in-google-chrome-browser-os-chromebooks/' target='_blank'>this link</a>.<br>
Then click on the extension icon in the top right of Chrome.";
    }
}
