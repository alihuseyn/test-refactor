<?php


use Laravel\Lumen\Testing\DatabaseMigrations;

class PermissionTest extends TestCase
{
    use DatabaseMigrations;



    public function testPermissionAgentGETValue()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'AGENT']);
        $this->get("api/v1/value?email={$user->email}&token={$user->token}")
                ->assertResponseStatus(403);
        $this->seeJson(['code' => 'ERR-002']);
    }

    public function testPermissionAgentPOSTValue()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'AGENT']);
        $this->post("api/v1/value?email={$user->email}&token={$user->token}",['value'=>'hello'])
            ->assertResponseStatus(403);
        $this->seeJson(['code' => 'ERR-002']);
    }

    public function testPermissionUserGetWaiting()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $this->get("api/v1/waiting?email={$user->email}&token={$user->token}")
            ->assertResponseStatus(403);
        $this->seeJson(['code' => 'ERR-002']);
    }

    public function testPermissionUserPostTranslate()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $this->post("api/v1/translate?email={$user->email}&token={$user->token}",[
            'value'=>'hello',
            'translation' => 'selam',
            'from' => 'ENG',
            'to' => 'TR'
        ])->assertResponseStatus(403);
        $this->seeJson(['code' => 'ERR-002']);
    }

}