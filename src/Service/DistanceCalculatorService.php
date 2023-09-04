<?php

namespace App\Service;

use App\Interface\CsvGeneratorInterface;

class DistanceCalculatorService
{
    public function __construct(private CsvGeneratorInterface $csvGeneratorInterface, private GeoLocationService $geoLocationService)
    {
    }
    
    /**
     * @return string
     */
    public function process() : string
    {
        list($result, $errors) = $this->geoLocationService->calculateDistances();
        
        //format data and Csv generate Process
        $result = $this->prepareDataAndGenerateCsv($result);
        
        return $this->returnResponse($result, $errors);
    }

    /**
     * @param array $result
     *
     * @return string | null
     */
    private function prepareDataAndGenerateCsv(array $result = []): string | null
    {
        $resultString = "";
        if (!empty($result)) {
            $resultString = "Sort number, Distance, Name, Address" . PHP_EOL;
            foreach ($result as $key => $data) {
                $sortNumber = $key + 1;
                $resultString .= "$sortNumber,\"{$data->getDistance()} km\",\"{$data->getName()}\",\"{$data->getAddress()}\"" . PHP_EOL;
            }

            // Generate the CSV file
            $this->csvGeneratorInterface->generateCsvFile($resultString, 'addresses.csv');
        }

        return $resultString;
    }

    /**
     * @param string $result
     * @param array $errors
     *
     * @return string
     */
    private function returnResponse(string $result, array $errors): string
    {
        if (!empty($result)) {
            return $result;
        }
    
        if (!empty($errors)) {
            return implode("\n", $errors);
        }
    
        return 'An unexpected error occurred.';
    }
}
