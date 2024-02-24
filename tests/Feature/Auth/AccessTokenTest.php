<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;
use App\Models\User;


class AccessTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_issue_access_token(): void
    {

        $this->withoutJsonApiDocumentFormatting();

        $user = User::factory()->create();

        $response = $this->postJson(route('api.v1.login'), [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'My device',
        ]);

        $token = $response->json('plain-text-token');
        $dbToken = PersonalAccessToken::findToken($token);
        $this->assertTrue($dbToken->tokenable->is($user));
    }
}
