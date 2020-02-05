<?php

namespace Tests;

use App\Exceptions\Exception;
use App\Exceptions\ValidationException;
use Illuminate\Contracts\Console\Kernel;
use App\Exceptions\AuthorizationException;
use App\Exceptions\AuthenticationException;
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
        // limpa as autorizações
        auth()->check() && auth()->logout();
        auth()->guard()->unsetToken();
        auth('authorizer')->check() && auth('authorizer')->logout();
        auth('authorizer')->unsetToken();
        auth('device')->check() && auth('device')->logout();
        auth('device')->unsetToken();

        // limpa os unique do faker
        app('Faker\Generator')->unique(true);

        // limpa as instâncias
        $this->app->forgetInstance('system');
        $this->app->forgetInstance('settings');
        $this->app->forgetInstance('business');
        $this->app->forgetInstance('company');
        $this->app->forgetInstance('country');
        $this->app->forgetInstance('currency');

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
     * @return TestResponse
     */
    protected function graphql(string $query, array $variables = [], array $headers = []): TestResponse
    {
        $data = [
            'query' => $query,
            'variables' => json_encode($variables),
        ];

        $response = $this->post('/graphql', $data, $headers);
        if (is_array($response->json('errors'))) {
            throw $this->toException($response);
        }
        return $response;
    }

    /**
     * Transforma uma resposta em exceção
     *
     * @param TestResponse $response
     * @return \Exception
     */
    protected function toException($response)
    {
        if ($response->json('errors.0.extensions.category') == 'businessLogic') {
            $messages = array_map(function ($item) {
                return $item['message'];
            }, $response->json('errors'));
            return new ValidationException($messages);
        }
        if ($response->json('errors.0.extensions.category') == 'authentication') {
            return new AuthenticationException($response->json('errors.0.message'));
        }
        if ($response->json('errors.0.extensions.category') == 'authorization') {
            return new AuthorizationException($response->json('errors.0.message'));
        }
        error_log(print_r($response->json('errors.0'), true));
        return new Exception($response->json('errors.0.message'));
    }

    /**
     * Get resources path of tests
     *
     * @param string $join
     * @return string
     */
    public static function resourcePath($join = '')
    {
        return __DIR__ . '/resources' . $join;
    }

    /**
     * Retorna o conteudo do arquivo informado
     */
    public static function getResourceContent($name)
    {
        return file_get_contents(self::resourcePath("/$name"));
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
        $query = self::getResourceContent("$filename.gql");
        return $this->graphql($query, $variables, $headers);
    }
}
