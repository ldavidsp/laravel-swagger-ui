<?php

namespace NextApps\SwaggerUi\Test;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use NextApps\SwaggerUi\SwaggerUiServiceProvider;
use Orchestra\Testbench\TestCase;

class SwaggerUiRouteTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();

        config()->set('swagger-ui.file', __DIR__ . '/testfiles/openapi.json');

        Gate::define('viewSwaggerUI', fn (?Authenticatable $user) => true);
    }

    protected function getPackageProviders($app) : array
    {
        return [SwaggerUiServiceProvider::class];
    }

    /** @test */
    public function it_provides_openapi_route_as_url()
    {
        config()->set('swagger-ui.oauth.client_id', 1);
        config()->set('swagger-ui.oauth.client_secret', 'foobar');

        $this->get('swagger')
            ->assertStatus(200)
            ->assertSee('url: \'' . route('swagger-openapi-json', [], false) . '\'', false);
    }

    /** @test */
    public function it_fills_oauth_client_id_and_secret_from_config()
    {
        config()->set('swagger-ui.oauth.client_id', 1);
        config()->set('swagger-ui.oauth.client_secret', 'foobar');

        $this->get('swagger')
            ->assertStatus(200)
            ->assertSee('clientId: \'1\',', false)
            ->assertSee('clientSecret: \'foobar\',', false);
    }
}
