<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DistanceCalculatorService
{
    public function __construct(private HttpService $httpService, private ParameterBagInterface $params, private CsvGeneratorService $csvGeneratorService)
    {
    }
    
    /**
     * @return array
     */
    public function process() : array
    {
        try {
            $addresses = AddressService::getAddresses();
            $apiKey = $this->params->get('geolocation_api_key');
            $geolocationApiUrl = $this->params->get('geolocation_api_url');
            $hqLatitude = $this->params->get('hq_address_latitude');
            $hqLongitude = $this->params->get('hq_address_longitude');
            
            $result = $errors = [];
            foreach ($addresses as $name => $address) {
                $data = [
                    'url' => $geolocationApiUrl,
                    'query' => ['access_key' => $apiKey, 'query' => $address],
                ];
                
                $response = $this->httpService->requestApi($data);
                if (200 === $response['statusCode']) {
                    $content = $response['content']['data'][0] ?? null;
                    $distance = $this->calculateDistance($hqLatitude, $hqLongitude, $content['latitude'], $content['longitude']);
                    $result[] = [
                        'distance' => $distance,
                        'name' => $name,
                        'address' => $address,
                    ];
                } else {
                    $errors[] = $response['error'];
                }
            }
            
            // Sort by distance
            usort($result, function ($a, $b) {
                return $a['distance'] <=> $b['distance'];
            });

            //Csv generate Process
            $this->csvGenerateProcess($result);
            
            return !empty($result) ? $result : $errors;
        } catch (\Exception $e) {
            throw new \RuntimeException('Error: ' . $e->getMessage());
        }
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

    /**
     * @param array $result
     *
     * @return void
     */
    private function csvGenerateProcess($result = []): void
    {
        if (!empty($result)) {
            $headers = ['Distance', 'Name', 'Address'];
            array_unshift($result, $headers);
                    
            $this->csvGeneratorService->generateCsvFile($result, 'addresses.csv');
        }
    }
}
