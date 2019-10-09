<?php

namespace Tests;

use Exception;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * GraphQL POST request.
     *
     * @param  string $query
     * @param  array  $variables
     * @param  array  $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function graphql(string $query, array $variables = [], array $headers = []): TestResponse
    {
        $data = [
            'query' => $query,
            'variables' => json_encode($variables),
        ];

        $response = $this->post('/graphql', $data, $headers);
        if (is_array($response->json('errors'))) {
            throw new Exception($response->json('errors.0.message'));
        }
        return $response;
    }

    /**
     * GraphQL POST request using filename as query
     *
     * @param  string $filename
     * @param  array  $variables
     * @param  array  $headers
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function graphfl(string $filename, array $variables = [], array $headers = []): TestResponse
    {
        $query = file_get_contents(__DIR__ . "/resources/$filename.gql");
        return $this->graphql($query, $variables, $headers);
    }
}
