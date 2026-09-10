<?php

function createDHLReturnOrder($data)
{
    //$url = "https://api-sandbox.dhl.com/parcel/de/shipping/returns/v1/orders";
    //$url = "https://api-eu.dhl.com/parcel/de/shipping/returns/v1/orders";
    $url = config_site_dhl_link;

    $headers = [
        "Content-Type: application/json",
        "Accept: application/json",
        "DHL-API-Key: " . trim(config_site_dhl_apikey),
        "Authorization: Basic " . base64_encode(trim(config_site_dhl_username) . ":" . trim(config_site_dhl_password))
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_VERBOSE => true
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curlError = curl_error($curl);

    curl_close($curl);

    return [
        'status'   => $httpCode,
        'response' => json_decode($response, true),
        'raw'      => $response,
        'error'    => $curlError
    ];
}


function trackDHLShipment($apiKey, $apiSecret, $trackingNumber)
{
    $url = 'https://api-eu.dhl.com/parcel/de/tracking/v0/shipments';

    // Build DHL XML request
    $xml = '<?xml version="1.0" encoding="UTF-8"?>'
         . '<data'
         . ' appname="' . htmlspecialchars(config_site_dhl_username, ENT_XML1, 'UTF-8') . '"'
         . ' password="' . htmlspecialchars(config_site_dhl_password, ENT_XML1, 'UTF-8') . '"'
         . ' request="d-get-piece-detail"'
         . ' language-code="en"'
         . ' piece-code="' . htmlspecialchars($trackingNumber, ENT_XML1, 'UTF-8') . '"'
         . '/>';

    // XML must be sent through the xml query parameter
    $url .= '?xml=' . urlencode($xml);

    // API Key + API Secret
    $authorization = base64_encode(
        trim($apiKey) . ':' . trim($apiSecret)
    );

    $headers = [
        'DHL-API-Key: ' . trim($apiKey),
        'Authorization: Basic ' . $authorization,
        'Accept: application/xml'
    ];

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CUSTOMREQUEST => 'GET'
    ]);

    $response = curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {

        $error = curl_error($ch);

        curl_close($ch);

        return [
            'success' => false,
            'http_code' => $httpCode,
            'error' => $error
        ];
    }

    curl_close($ch);

    /*
     * Convert XML response
     */
    $xmlResponse = simplexml_load_string($response);

    if ($xmlResponse === false) {

        return [
            'success' => false,
            'http_code' => $httpCode,
            'error' => 'Invalid XML response from DHL',
            'raw' => $response
        ];
    }

    /*
     * DHL API error
     */
    $rootAttributes = $xmlResponse->attributes();

    if ((string)$rootAttributes['code'] != '0') {

        return [
            'success' => false,
            'http_code' => $httpCode,
            'error' => (string)$rootAttributes['code'],
            'message' => (string)$rootAttributes['name'],
            'raw' => $response
        ];
    }

    /*
     * Shipment
     */
    $shipment = $xmlResponse->data;

    $shipmentAttributes = $shipment->attributes();

    /*
     * Events
     */
    $events = [];

    if (isset($shipment->data->data)) {

        foreach ($shipment->data->data as $event) {

            $eventAttributes = $event->attributes();

            $events[] = [
                'timestamp' => (string)$eventAttributes['event-timestamp'],
                'status' => (string)$eventAttributes['event-status'],
                'short_status' => (string)$eventAttributes['event-short-status'],
                'text' => (string)$eventAttributes['event-text'],
                'location' => (string)$eventAttributes['event-location'],
                'country' => (string)$eventAttributes['event-country'],
                'standard_event_code' => (string)$eventAttributes['standard-event-code'],
                'return' => (string)$eventAttributes['ruecksendung']
            ];
        }
    }

    /*
     * Final clean response
     */
    return [
        'success' => true,
        'http_code' => $httpCode,

        'tracking_number' => (string)$shipmentAttributes['piece-code'],

        'status' => (string)$shipmentAttributes['status'],

        'short_status' => (string)$shipmentAttributes['short-status'],

        'status_timestamp' => (string)$shipmentAttributes['status-timestamp'],

        'event_location' => (string)$shipmentAttributes['event-location'],

        'event_country' => (string)$shipmentAttributes['event-country'],

        'recipient' => [
            'name' => (string)$shipmentAttributes['pan-recipient-name'],
            'street' => (string)$shipmentAttributes['pan-recipient-street'],
            'city' => (string)$shipmentAttributes['pan-recipient-city'],
            'address' => (string)$shipmentAttributes['pan-recipient-address'],
            'postal_code' => (string)$shipmentAttributes['pan-recipient-postalcode']
        ],

        'shipper' => [
            'name' => (string)$shipmentAttributes['shipper-name'],
            'street' => (string)$shipmentAttributes['shipper-street'],
            'city' => (string)$shipmentAttributes['shipper-city'],
            'address' => (string)$shipmentAttributes['shipper-address']
        ],

        'product' => [
            'code' => (string)$shipmentAttributes['product-code'],
            'key' => (string)$shipmentAttributes['product-key'],
            'name' => (string)$shipmentAttributes['product-name']
        ],

        'weight' => (string)$shipmentAttributes['shipment-weight'],

        'destination_country' => (string)$shipmentAttributes['dest-country'],

        'origin_country' => (string)$shipmentAttributes['origin-country'],

        'leitcode' => (string)$shipmentAttributes['leitcode'],

        'pslz_number' => (string)$shipmentAttributes['pslz-nr'],

        'return_shipment' => (string)$shipmentAttributes['ruecksendung'],

        'delivery_event' => (string)$shipmentAttributes['delivery-event-flag'],

        'events' => $events,

        'raw' => $response
    ];
}

function createDHLShipment($apiKey, $data)
{
    $url = config_site_dhl_link;

    $shipmentData = [
        'shipments' => [
            [
                'product' => $data['product'],

                'billingNumber' => $data['billingNumber'],

                'refNo' => $data['reference'],

                'shipper' => [
                    'name1'         => $data['sender']['name1'],
                    'addressStreet' => $data['sender']['street'],
                    'addressHouse'  => $data['sender']['house'],
                    'postalCode'    => $data['sender']['postalCode'],
                    'city'          => $data['sender']['city'],
                    'country'       => $data['sender']['country']
                ],

                'consignee' => [
                    'name1'         => $data['receiver']['name1'],
                    'addressStreet' => $data['receiver']['street'],
                    'addressHouse'  => $data['receiver']['house'],
                    'postalCode'    => $data['receiver']['postalCode'],
                    'city'          => $data['receiver']['city'],
                    'country'       => $data['receiver']['country']
                ],

                'details' => [
                    'weight' => [
                        'uom'   => 'g',
                        'value' => (int) $data['weight']
                    ]
                ]
            ]
        ]
    ];

    $jsonData = json_encode(
        $shipmentData,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'dhl-api-key: ' . trim($apiKey),
        'Authorization: Basic ' . base64_encode(
            trim(config_site_dhl_username) . ':' .
            trim(config_site_dhl_password)
        )
    ];

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $jsonData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_TIMEOUT        => 60
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);

        throw new Exception('DHL Shipment CURL Error: ' . $error);
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $result = json_decode($response, true);

    if ($httpCode < 200 || $httpCode >= 300) {
        throw new Exception(
            'DHL Shipment Error (' . $httpCode . '): ' . $response
        );
    }

    return $result;
}
?>