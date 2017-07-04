<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthTest extends TestCase
{
    use DatabaseMigrations;

    public function testAuthenticationFail()
    {
        $this->get('api/v1/waiting?email=alihuseyn13@gmail.com&token=1234567890')
            ->assertResponseStatus(401);

        $this->seeJson(['code' => 'ERR-001']);
    }

    public function testAuthenticationWithEmptyInput()
    {
        $this->get('api/v1/waiting')->assertResponseStatus(401);
        $this->seeJson(['code' => 'ERR-001']);
    }

    public function testAuthenticationWithCorrectInput()
    {
        $user = factory(App\Model\User::class)->create(['profile'=>'AGENT']);
        $this->get("api/v1/waiting?email={$user->email}&token={$user->token}")
            ->assertResponseStatus(200);

        $this->seeJson(['status'=>true]);
    }
}