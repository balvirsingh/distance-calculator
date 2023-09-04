<?php

namespace App\Interface;

interface CsvGeneratorInterface
{
    /**
     * @param string $csvContent
     * @param string $filename
     *
     * @return string
     */
    public function generateCsvFile(string $csvContent, string $filename): string;
}
