<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use App\Model\GeoLocationResult;

class GeoLocationService
{
    public function __construct(private HttpService $httpService, private ParameterBagInterface $params)
    {
    }

    public function calculateDistances(): array
    {
        $addresses = AddressService::getAddresses();
        $apiKey = $this->params->get('geolocation_api_key');
        $geolocationApiUrl = $this->params->get('geolocation_api_url');
        $hqLatitude = $this->params->get('hq_address_latitude');
        $hqLongitude = $this->params->get('hq_address_longitude');

        $result = $errors = [];
        foreach ($addresses as $address) {
            $data = [
                'url' => $geolocationApiUrl,
                'query' => ['access_key' => $apiKey, 'query' => $address->getAddress()],
            ];
            
            $response = $this->httpService->requestApi($data);
            if (200 === $response['statusCode']) {
                $content = $response['content']['data'][0] ?? null;
                $distance = $this->calculateDistance($hqLatitude, $hqLongitude, $content['latitude'], $content['longitude']);
                $result[] = new GeoLocationResult($address->getName(), $address->getAddress(), $distance);
            } else {
                $errors[] = 'Error while fetch address for '.$address->getName().' :: ' . $response['error'];
            }
        }
        
        // Sort by distance
        usort($result, function ($a, $b) {
            return $a->getDistance() <=> $b->getDistance();
        });

        return [$result, $errors];
    }

    /**
     * @param float $lat1
     * @param float $lon1
     * @param float $lat2
     * @param float $lon2
     *
     * @return float|int
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float|int
    {
        $earthRadius = 6371;
    
        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);
    
        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;
    
        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLon / 2) * sin($deltaLon / 2);
    
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        $distance = $earthRadius * $c;
    
        return round($distance, 2);
    }

}