<?php

require __DIR__ . '/vendor/autoload.php';

$query = $argv[1];
$clientId = getenv('clientId');
$clientSecret = getenv('clientSecret');
$papago = new \Hansanghyeon\Papago($clientId, $clientSecret);

// 번역서비스 실행
$target = "ko";
$translate = "en";

if (preg_match('/^[a-z]/i', $query)) {
    $target = "en";
    $translate = "ko";
}

$translator = $papago->translate(htmlspecialchars_decode($query), $target, $translate);

$search = new \Hansanghyeon\Search();

// 출력
echo $search->search($query, $translator['data']->message->result->translatedText);
