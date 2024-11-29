<?php
session_start();

$url = 'https://order.drcash.sh/v1/order';
$name = trim($_POST['name']);
$phone = trim($_POST['phone']);

if (isset($_SESSION['phone']) && $_SESSION['phone'] == $phone) {                   //проверка на отправку нескольких заявок одним человеком,
    echo 'Вы уже оставили заявку, в ближайшее время она будет обработана.';     //сделана через номер телефона
    exit;
}

$data = [
    'stream_code' => 'vv4uf',
    'client' => [
        'phone' => $phone,
        'name' => $name
    ]
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer RLPUUOQAMIKSAB2PSGUECA'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

$responseData = json_decode($response, true);

if ($httpCode == 200) {
    $_SESSION['phone'] = $phone;
    header('Location: thank.html');
    exit;
} else {
    echo 'К сожалению произошлв ошибка на нашей стороне, приносим извинения';
}