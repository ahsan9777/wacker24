<?php 
function getKeycloakToken()
{
    $url = "https://auth.pbsnetwork.eu/auth/realms/pbs/protocol/openid-connect/token";

    $postData = [
        'grant_type' => 'client_credentials',
        'client_id' => 'bde100203',
        'client_secret' => '463404d8-1f9d-4faa-afc1-6b7d8f32922e'
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return [
            'status' => false,
            'error' => curl_error($ch)
        ];
    }

    curl_close($ch);

    $result = json_decode($response, true);

    return [
        'status' => true,
        'data' => $result
    ];
}

function getprice($gtin)
{
    // API URL
    $url = "https://reseller.cntr.pbsnetwork.eu/reseller/api/v3/catalogs/4388888002610/items/".$gtin;

    $getKeycloakToken = getKeycloakToken();
    // Access Token
    $accessToken = $getKeycloakToken['data']['access_token'];

    $headers = [
        "Authorization: Bearer " . $accessToken,
        "Content-Type: application/json"
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    $response = curl_exec($ch);

    if (curl_errno($ch)) {

        return [
            'status' => false,
            'message' => curl_error($ch)
        ];
    }

    curl_close($ch);

    $result = json_decode($response, true);

    return [
        'status' => true,
        'data' => !empty($result['prices']) ? $result['prices'] : ""
    ];
}
?>