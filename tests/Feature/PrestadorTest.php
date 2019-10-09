<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Prestador;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PrestadorTest extends TestCase
{
    use RefreshDatabase;

    public static function auth()
    {
        $prestador = factory(Prestador::class)->create();
        $user = $prestador->cliente()->first();
        $token = auth()->fromUser($user);
        return [
            'Authorization' => "Bearer $token",
        ];
    }

    /**
     * TODO: remove me
     */
    public function testNothing()
    {
        $this->assertTrue(true);
    }
}
