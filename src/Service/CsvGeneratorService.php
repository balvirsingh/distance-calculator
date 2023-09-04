<?php

namespace App\Service;

use App\Interface\CsvGeneratorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CsvGeneratorService implements CsvGeneratorInterface
{
    public function __construct(private ParameterBagInterface $params)
    {
    }

    public function generateCsvFile(string $csvContent, string $filename): string
    {
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
