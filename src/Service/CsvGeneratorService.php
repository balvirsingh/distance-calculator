<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CsvGeneratorService
{
    public function __construct(private ParameterBagInterface $params)
    {
    }

    public function generateCsvFile(array $data, string $filename): string
    {
        // Create CSV content
        $csvContent = '';
        foreach ($data as $row) {
            $csvContent .= '"' . implode('","', $row) . "\"\n";
        }

        // Define the file path in the public folder
        $filePath = $this->params->get('kernel.project_dir') . '/public/' . $filename;

        try {
            if (file_put_contents($filePath, $csvContent) === false) {
                throw new \RuntimeException('Failed to write CSV file.');
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('Error while generating and saving CSV file: ' . $e->getMessage());
        }

        return $filePath;
    }
}
