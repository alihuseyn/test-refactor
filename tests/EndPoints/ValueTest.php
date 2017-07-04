<?php


use Laravel\Lumen\Testing\DatabaseMigrations;

class ValueTest extends TestCase
{
    use DatabaseMigrations;

    public function testEmptyValueAddition()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $this->post("api/v1/value?email={$user->email}&token={$user->token}")
                ->assertResponseStatus(400);
        $this->seeJson([
            'code' => 'ERR-005'
        ]);
    }

    public function testAddNewValue()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $this->post("api/v1/value?email={$user->email}&token={$user->token}",[
            'value' => 'selam'
        ])->assertResponseStatus(200);
        $this->seeJson([
            'message' => 'Addition of request for translation of a value is completed'
        ]);
    }

    public function testAddNewNotExistFromToValue()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $this->post("api/v1/value?email={$user->email}&token={$user->token}",[
            'value' => 'salam',
            'from'  => 'AZE',
            'to'    => 'UK'
        ])->assertResponseStatus(400);
        $this->seeJson([
            'code'		=> 'ERR-006',
        ]);
    }

    public function testAddExistingValue()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $user->request()->save(factory(\App\Model\Request::class)->make([
            'user_id' => $user->id,
            'value' => 'hello'
        ]));

        $this->post("api/v1/value?email={$user->email}&token={$user->token}",[
            'value' => 'hello',
        ])->assertResponseStatus(200);
        $this->seeJson([
            'message' => 'The translation for given value has already requested'
        ]);
    }

    public function testAddRequestedValueAgain()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $user->request()->save(factory(\App\Model\Request::class)->make([
            'user_id' => $user->id,
            'value' => 'hello',
            'situation' => 0
        ]));

        $this->post("api/v1/value?email={$user->email}&token={$user->token}",[
            'value' => 'hello',
        ])->assertResponseStatus(200);
        $this->seeJson([
            'message' => 'The translation for given value has already requested'
        ]);
    }

    public function testAddTranslatedValueAgain()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $translation = new \App\Model\Translate;
        $translation->id = 1;
        $translation->agent_id = $user->id;
        $translation->value = 'hello';
        $translation->translation = 'selam';
        $translation->from = 'ENG';
        $translation->to = 'TR';
        $translation->save();

        $this->post("api/v1/value?email={$user->email}&token={$user->token}",[
            'value' => 'hello',
        ])->assertResponseStatus(200);
        $this->seeJson([
            'message' => 'The given value has already translated'
        ]);
    }

    public function testGetNotExistValue()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $this->get("api/v1/value?email={$user->email}&token={$user->token}")
                ->assertResponseStatus(200);
        $this->seeJson([
            'message' => 'Not any result found for request'
        ]);
    }

    public function testGetExistValue()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $translation = new \App\Model\Translate;
        $translation->id = 1;
        $translation->agent_id = $user->id;
        $translation->value = 'hello';
        $translation->translation = 'selam';
        $translation->from = 'ENG';
        $translation->to = 'TR';
        $translation->save();

        $this->get("api/v1/value/hello?email={$user->email}&token={$user->token}")
            ->assertResponseStatus(200);
        $this->seeJson([
            'value' => 'hello'
        ]);
    }

    public function testGetExistTranslationValue()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $translation = new \App\Model\Translate;
        $translation->id = 1;
        $translation->agent_id = $user->id;
        $translation->value = 'hello';
        $translation->translation = 'selam';
        $translation->from = 'ENG';
        $translation->to = 'TR';
        $translation->save();

        $this->get("api/v1/value/selam?email={$user->email}&token={$user->token}")
            ->assertResponseStatus(200);
        $this->seeJson([
            'value' => 'selam'
        ]);
    }
    public function testGetAllValue()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $translation = new \App\Model\Translate;
        $translation->id = 1;
        $translation->agent_id = $user->id;
        $translation->value = 'hello';
        $translation->translation = 'selam';
        $translation->from = 'ENG';
        $translation->to = 'TR';
        $translation->save();

        $this->get("api/v1/value?email={$user->email}&token={$user->token}")
            ->assertResponseStatus(200);
        $this->seeJson([
            'data'=>[
                [
                    'ENG' => ['hello'],
                    'TR'  => ['selam']
                ]
            ]
        ]);
    }

    public function testGetSameAllValue()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $translation = new \App\Model\Translate;
        $translation->id = 1;
        $translation->agent_id = $user->id;
        $translation->value = 'hello';
        $translation->translation = 'selam';
        $translation->from = 'ENG';
        $translation->to = 'TR';
        $translation->save();

        $translation_2 = new \App\Model\Translate;
        $translation_2->id = 2;
        $translation_2->agent_id = $user->id;
        $translation_2->value = 'selam';
        $translation_2->translation = 'hi';
        $translation_2->from = 'TR';
        $translation_2->to = 'ENG';
        $translation_2->save();

        $this->get("api/v1/value/selam?email={$user->email}&token={$user->token}")
            ->assertResponseStatus(200);
        $this->seeJson([
            'ENG' => ['hello','hi'],
            'TR'  => ['selam']
        ]);
    }
}