<?php
// API sleutel en base URL
define('API_URL', 'https://api.internship.mintyconnect.nl');
define('API_KEY', 'Basic 072dee999ac1a7931c205814c97cb1f4d1261559c0f6cd15f2a7b27701954b8d');

// Stuur een POST request naar de API met een lijst van domeinen
function zoekDomeinen($naam, $extensies) {
    $body = [];

    // Bouw de request op voor elke extensie
    foreach ($extensies as $ext) {
        $body[] = [
            "name" => $naam,
            "extension" => $ext
        ];
    }

    // cURL instellen
    $ch = curl_init(API_URL . '/domains/search');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: ' . API_KEY
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}