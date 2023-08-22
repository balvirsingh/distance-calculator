<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class HttpService
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }
    
    /**
     * To fetch data from third party api
     *
     * @param array $data
     *
     * @return array
     */
    public function requestApi(array $data): array
    {
        $response = $this->httpClient->request(
            $data['method']?? "GET",
            $data['url'],
            [
                'query' => $data['query'] ?? []
            ]
        );
        
        $statusCode = $response->getStatusCode();
        $result = ['statusCode' => $statusCode];
        if ($statusCode !== 200) {
            $errorMessage = $response->getInfo()['response_headers'][0] ?? 'Unknown error';
            
            $result['error'] = $errorMessage;
        } else {
            $result['content'] = $response->toArray();
        }
        
        return $result;
    }
}
