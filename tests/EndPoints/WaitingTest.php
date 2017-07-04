<?php


use Laravel\Lumen\Testing\DatabaseMigrations;

class WaitingTest extends TestCase
{
    use DatabaseMigrations;

    public function testGetWaitingList()
    {
        $user = factory(\App\Model\User::class)->create(['profile'=>'AGENT']);
        $user->request()->save(factory(App\Model\Request::class)->make([
            'user_id' => $user->id,
            'value'   => 'hello',
        ]));

        $this->get("api/v1/waiting?email={$user->email}&token={$user->token}")
                ->assertResponseStatus(200);
        $this->seeJson([
            'status' => true,
            'value' => 'hello',
            'from' => 'ENG',
            'to' => 'TR'
        ]);
    }

    public function testGetEmptyWaitingList()
    {
        $user = factory(\App\Model\User::class)->create(['profile'=>'AGENT']);

        $this->get("api/v1/waiting?email={$user->email}&token={$user->token}")
            ->assertResponseStatus(200);
        $this->seeJson([
            'data' => []
        ]);
    }

    public function testGetEmptyWaitingListWhileSituationSet()
    {
        $user = factory(\App\Model\User::class)->create(['profile'=>'AGENT']);
        $user->request()->save(factory(App\Model\Request::class)->make([
            'user_id' => $user->id,
            'value'   => 'hello',
            'situation' => 1,
        ]));

        $this->get("api/v1/waiting?email={$user->email}&token={$user->token}")
            ->assertResponseStatus(200);
        $this->seeJson([
            'data' => []
        ]);
    }

}