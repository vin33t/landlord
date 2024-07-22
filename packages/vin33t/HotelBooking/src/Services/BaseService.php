<?php

namespace vin33t\HotelBooking\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

abstract class BaseService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }


    public function XMlToJSON($return)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($return);
        $json = new \FluentDOM\Serializer\Json\RabbitFish($dom);
        return json_decode($json, true);
    }

    protected function sendRequest(string $endpoint, string $xmlRequest)
    {
        try {
            $ch = curl_init($endpoint);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, "xml=" . $xmlRequest);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $response = curl_exec($ch);
            curl_close($ch);

            return $this->parseXmlResponse($response);
        } catch (RequestException $e) {
            // Handle error
            throw new \Exception("Error processing request: " . $e->getMessage());
        }
    }

    protected function parseXmlResponse(string $response)
    {
        $xml = simplexml_load_string($response);
        return json_decode(json_encode($xml), true);
    }

    protected function buildXmlRequest(array $data)
    {
        $xml = new \SimpleXMLElement('<Request/>');
        $this->arrayToXml($data, $xml);
        return $xml->asXML();
    }

    protected function arrayToXml(array $data, \SimpleXMLElement &$xml)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xml->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }
}
