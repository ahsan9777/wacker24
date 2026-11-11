<?php 
function getKeycloakToken()
{
    static $tokenMemoryCache = null;
    $now = time();

    if ($tokenMemoryCache !== null && isset($tokenMemoryCache['expires_at']) && $now < $tokenMemoryCache['expires_at']) {
        return $tokenMemoryCache['response'];
    }

    $cacheFile = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR . 'wacker_pbs_token_cache.json';
    $lockFile = $cacheFile . '.lock';
    $lockFp = @fopen($lockFile, 'c+');
    if ($lockFp) {
        @flock($lockFp, LOCK_EX);
    }

    if (file_exists($cacheFile)) {
        $raw = @file_get_contents($cacheFile);
        if ($raw !== false) {
            $cached = json_decode($raw, true);
            if (is_array($cached) && isset($cached['expires_at']) && time() < $cached['expires_at'] && isset($cached['response'])) {
                $tokenMemoryCache = $cached;
                if ($lockFp) {
                    @flock($lockFp, LOCK_UN);
                    @fclose($lockFp);
                }
                return $cached['response'];
            }
        }
    }

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
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($ch, CURLOPT_TIMEOUT, 4);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $err = curl_error($ch);
        curl_close($ch);
        $failResp = [
            'status' => false,
            'error' => $err
        ];
        $tokenMemoryCache = [
            'expires_at' => time() + 30,
            'response' => $failResp
        ];
        @file_put_contents($cacheFile, json_encode($tokenMemoryCache), LOCK_EX);
        if ($lockFp) {
            @flock($lockFp, LOCK_UN);
            @fclose($lockFp);
        }
        return $failResp;
    }

    curl_close($ch);

    $result = json_decode($response, true);
    if (!is_array($result) || empty($result['access_token'])) {
        $failResp = [
            'status' => false,
            'error' => 'Invalid token response'
        ];
        $tokenMemoryCache = [
            'expires_at' => time() + 30,
            'response' => $failResp
        ];
        @file_put_contents($cacheFile, json_encode($tokenMemoryCache), LOCK_EX);
        if ($lockFp) {
            @flock($lockFp, LOCK_UN);
            @fclose($lockFp);
        }
        return $failResp;
    }

    $ttl = isset($result['expires_in']) ? max(30, (int)$result['expires_in'] - 30) : 240;
    $successResp = [
        'status' => true,
        'data' => $result
    ];
    $tokenMemoryCache = [
        'expires_at' => time() + $ttl,
        'response' => $successResp
    ];
    @file_put_contents($cacheFile, json_encode($tokenMemoryCache), LOCK_EX);
    if ($lockFp) {
        @flock($lockFp, LOCK_UN);
        @fclose($lockFp);
    }

    return $successResp;
}

function getprice($gtin)
{
    $gtin = trim((string)$gtin);
    if ($gtin === '' || $gtin === '0') {
        return [
            'status' => true,
            'data' => ''
        ];
    }

    $batch = getprices_batch([$gtin]);
    return isset($batch[$gtin]) ? $batch[$gtin] : [
        'status' => false,
        'data' => ''
    ];
}

function getprices_batch(array $gtins)
{
    static $priceMemoryCache = [];
    $now = time();
    $cacheTtl = 600; // 10 minutes cache
    $tempDir = rtrim(sys_get_temp_dir(), '/\\') . DIRECTORY_SEPARATOR;

    $results = [];
    $missingGtins = [];

    foreach ($gtins as $g) {
        $g = trim((string)$g);
        if ($g === '' || $g === '0') {
            $results[$g] = ['status' => true, 'data' => ''];
            continue;
        }
        if (isset($priceMemoryCache[$g]) && $now < $priceMemoryCache[$g]['expires_at']) {
            $results[$g] = $priceMemoryCache[$g]['response'];
            continue;
        }
        $safeKey = preg_replace('/[^a-zA-Z0-9_-]/', '', $g);
        $cacheFile = $tempDir . 'wacker_pbs_price_' . $safeKey . '.json';
        if (file_exists($cacheFile)) {
            $raw = @file_get_contents($cacheFile);
            if ($raw !== false) {
                $cached = json_decode($raw, true);
                if (is_array($cached) && isset($cached['expires_at']) && $now < $cached['expires_at'] && isset($cached['response'])) {
                    $priceMemoryCache[$g] = $cached;
                    $results[$g] = $cached['response'];
                    continue;
                }
            }
        }
        $missingGtins[$g] = $g;
    }

    if (empty($missingGtins)) {
        return $results;
    }

    $getKeycloakToken = getKeycloakToken();
    if (empty($getKeycloakToken['status']) || empty($getKeycloakToken['data']['access_token'])) {
        foreach ($missingGtins as $g) {
            $results[$g] = [
                'status' => false,
                'data' => '',
                'message' => isset($getKeycloakToken['error']) ? $getKeycloakToken['error'] : 'Token unavailable'
            ];
        }
        return $results;
    }

    $accessToken = $getKeycloakToken['data']['access_token'];
    $headers = [
        "Authorization: Bearer " . $accessToken,
        "Content-Type: application/json"
    ];

    $mh = curl_multi_init();
    $handles = [];

    foreach ($missingGtins as $g) {
        $url = "https://reseller.cntr.pbsnetwork.eu/reseller/api/v3/catalogs/4388888002610/items/" . rawurlencode($g);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_multi_add_handle($mh, $ch);
        $handles[$g] = $ch;
    }

    $running = null;
    do {
        $status = curl_multi_exec($mh, $running);
        if ($running) {
            curl_multi_select($mh, 0.5);
        }
    } while ($running > 0 && $status === CURLM_OK);

    foreach ($handles as $g => $ch) {
        if (curl_errno($ch)) {
            $resp = [
                'status' => false,
                'data' => '',
                'message' => curl_error($ch)
            ];
        } else {
            $body = curl_multi_getcontent($ch);
            $decoded = json_decode($body, true);
            $resp = [
                'status' => true,
                'data' => !empty($decoded['prices']) ? $decoded['prices'] : ""
            ];
            $cacheEntry = [
                'expires_at' => $now + $cacheTtl,
                'response' => $resp
            ];
            $priceMemoryCache[$g] = $cacheEntry;
            $safeKey = preg_replace('/[^a-zA-Z0-9_-]/', '', $g);
            @file_put_contents($tempDir . 'wacker_pbs_price_' . $safeKey . '.json', json_encode($cacheEntry), LOCK_EX);
        }
        $results[$g] = $resp;
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }

    curl_multi_close($mh);

    return $results;
}
?>