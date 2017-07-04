<?php


use Laravel\Lumen\Testing\DatabaseMigrations;

class TranslationTest extends TestCase
{
    use DatabaseMigrations;


    public function testEmptyTranslationAddition()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'AGENT']);
        $this->post("api/v1/translate?email={$user->email}&token={$user->token}")
                ->assertResponseStatus(400);
        $this->seeJson([
            'code' => 'ERR-005'
        ]);
        $this->seeJson([
            'code' => 'ERR-008'
        ]);

    }

    public function testEmptyFromValueAddition()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'AGENT']);
        $this->post("api/v1/translate?email={$user->email}&token={$user->token}",[
            'from' => "_",
            'to' => "_",
        ])->assertResponseStatus(400);
        $this->seeJson([
            'code' => 'ERR-006'
        ]);
        $this->seeJson([
            'code' => 'ERR-008'
        ]);

    }

    public function testAddTranslation()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'AGENT']);
        $this->post("api/v1/translate?email={$user->email}&token={$user->token}",[
            'value'=>'hello',
            'translation' => 'selam',
            'from' => 'ENG',
            'to' => 'TR'
        ])->assertResponseStatus(200);
        $this->seeJson(['message' => 'New translation is added for given value']);
    }

    public function testChangeOfSituationAfterTranslationOnRequest()
    {
        $user_P1 = factory(\App\Model\User::class)->create(['profile' => 'USER']);
        $user_P1->request()->save(factory(\App\Model\Request::class)->make([
            'user_id' => $user_P1->id,
            'value' => 'hello',
            'situation' => 0,
        ]));
        $user_P2 = factory(\App\Model\User::class)->create(['profile' => 'AGENT']);
        $this->post("api/v1/translate?email={$user_P2->email}&token={$user_P2->token}",[
            'value'=>'hello',
            'translation' => 'selam',
            'from' => 'ENG',
            'to' => 'TR'
        ])->assertResponseStatus(200);
        $this->assertEquals(1,\App\Model\Request::find(1)->situation);
        $this->assertEquals($user_P1->id,\App\Model\Request::find(1)->user_id);
    }

    public function testAddExistingTranslationWhichIsValue()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'AGENT']);
        $translation = new \App\Model\Translate;
        $translation->id = 1;
        $translation->agent_id = $user->id;
        $translation->value = 'hello';
        $translation->translation = 'selam';
        $translation->from = 'ENG';
        $translation->to = 'TR';
        $translation->save();

        $this->post("api/v1/translate?email={$user->email}&token={$user->token}",[
            'value'=>'selam',
            'translation' => 'hello',
            'from' => 'TR',
            'to' => 'ENG'
        ])->assertResponseStatus(200);
        $this->seeJson([
            'message' => 'Translation for given value has already added by other agent'
        ]);
    }

    public function testNotAllowedTranslationAdditionIfRequestNotExists()
    {
        $user = factory(\App\Model\User::class)->create(['profile' => 'AGENT']);
        \App\Model\Translate::$allow_translation_if_request_not_exists = false;
        $this->post("api/v1/translate?email={$user->email}&token={$user->token}",[
            'value'=>'selam',
            'translation' => 'hello',
            'from' => 'TR',
            'to' => 'ENG'
        ])->assertResponseStatus(200);
        $this->seeJson([
            'message' => 'Not any result found for request'
        ]);
    }

}