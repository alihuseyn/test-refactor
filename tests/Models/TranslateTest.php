<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class TranslateTest extends TestCase
{
    use DatabaseMigrations;

    public function testMethodAdd()
    {
        $user = factory(\App\Model\User::class)->create(['profile'=>'AGENT']);
        $result = \App\Model\Translate::add([
            'value' => 'hello',
            'translation' => 'selam',
            'from' => 'ENG',
            'to' => 'TR',
            'user_id' => $user->id
        ]);
        $this->assertEquals(4, $result); // Not Allow Addition
    }

    public function testMethodExistCase1()
    {
        \App\Model\Translate::$allow_translation_if_request_not_exists = true;
        $result = \App\Model\Translate::exists([
            'value'       =>'hello',
            'translation' =>'selam',
            'from'        => 'ENG',
            'to'          => 'TR'
        ]);

        $this->assertFalse($result);
    }

    public function testMethodExistCase2()
    {
        \App\Model\Translate::$allow_translation_if_request_not_exists = false;
        $result = \App\Model\Translate::exists([
            'value'       =>'hello',
            'translation' =>'selam',
            'from'        => 'ENG',
            'to'          => 'TR'
        ]);

        $this->assertEquals(4,$result);
    }

    public function testMethodExistCase3()
    {
        \App\Model\Translate::$allow_translation_if_request_not_exists = true;
        $user = factory(\App\Model\User::class)->create(['profile'=>'AGENT']);
        \App\Model\Translate::add([
            'value' => 'hello',
            'translation' => 'selam',
            'from' => 'ENG',
            'to' => 'TR',
            'user_id' => $user->id
        ]);
        $result = \App\Model\Translate::exists([
            'value'       =>'hello',
            'translation' =>'selam',
            'from'        => 'ENG',
            'to'          => 'TR'
        ]);

        $this->assertEquals(3,$result);
    }

    public function testRequestExistence()
    {
        $result = \App\Model\Translate::requestExistence([
            'value'       =>'hello',
            'translation' =>'selam',
            'from'        => 'ENG',
            'to'          => 'TR'
        ]);

        $this->assertFalse($result);
    }

    public function testMethodFetch()
    {
        $result = \App\Model\Translate::fetch();
        $this->assertEquals(['message' => 'Not any result found for request'], $result);
    }
}