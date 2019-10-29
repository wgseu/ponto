<?php

namespace Tests;

use Exception;
use App\Models\Sistema;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Preserved application state.
     *
     * @var \Illuminate\Foundation\Application
     */
    public static $application = null;

    /**
     * Indicates if the test database has been migrated.
     *
     * @var bool
     */
    public static $migrated = false;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        // usa a mesma aplicação para todos os testes
        if (self::$application) {
            return self::$application;
        }
        $app = require __DIR__ . '/../bootstrap/app.php';
        self::$application = $app;
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }
    
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshTestDatabase();
    }

    /**
     * Clean up the testing environment before the next test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        if ($this->app) {
            $this->callBeforeApplicationDestroyedCallbacks();
        }
        // limpa os unique do faker
        factory(Sistema::class)->make();
        $this->app = null;
        parent::tearDown();
    }

    /**
     * Refresh a conventional test database.
     *
     * @return void
     */
    protected function refreshTestDatabase()
    {
        if (!self::$migrated) {
            $this->artisan('migrate');
            $this->app[Kernel::class]->setArtisan(null);
            self::$migrated = true;
        }
        $this->beginDatabaseTransaction();
    }

    /**
     * Handle database transactions on the specified connections.
     *
     * @return void
     */
    public function beginDatabaseTransaction()
    {
        $database = $this->app->make('db');

        foreach ($this->connectionsToTransact() as $name) {
            $database->connection($name)->beginTransaction();
        }

        $this->beforeApplicationDestroyed(function () use ($database) {
            foreach ($this->connectionsToTransact() as $name) {
                $connection = $database->connection($name);
                $connection->rollBack();
            }
        });
    }

    /**
     * The database connections that should have transactions.
     *
     * @return array
     */
    protected function connectionsToTransact()
    {
        return property_exists($this, 'connectionsToTransact')
                            ? $this->connectionsToTransact : [null];
    }

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
