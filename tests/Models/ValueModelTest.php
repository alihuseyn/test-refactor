<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class ValueModelTest extends TestCase
{
    use DatabaseMigrations;

    public function testMethodAdd()
    {
        $user = factory(\App\Model\User::class)->create(['profile'=>'USER']);
        $result = \App\Model\Request::add([
            'value' => 'hello',
            'from' => 'ENG',
            'to' => 'TR',
            'user_id' => $user->id
        ]);
        $this->assertEquals(1,$result);
    }

    public function testMethodExistCase1()
    {
        $result = \App\Model\Request::exists([
            'value'       =>'hello',
            'from'        => 'ENG',
            'to'          => 'TR',
        ]);

        $this->assertFalse($result);
    }


    public function testMethodExistCase2()
    {
        $user = factory(\App\Model\User::class)->create(['profile'=>'USER']);
        \App\Model\Request::add([
            'value' => 'hello',
            'from' => 'ENG',
            'to' => 'TR',
            'user_id' => $user->id,
        ]);
        $result = \App\Model\Request::exists([
            'value'       =>'hello',
            'from'        => 'ENG',
            'to'          => 'TR',
        ]);

        $this->assertEquals(2, $result);
    }

    public function testTranslationExistence()
    {
        $result = \App\Model\Request::translateExistence([
            'value'       =>'hello',
            'from'        => 'ENG',
            'to'          => 'TR'
        ]);

        $this->assertFalse($result);
    }

    public function testMethodFetch()
    {
        $result = \App\Model\Request::fetch();
        $this->assertEquals([],$result);
    }

    public function testSetTranslated()
    {
        $user = factory(\App\Model\User::class)->create(['profile'=>'USER']);
        \App\Model\Request::add([
            'value' => 'hello',
            'from' => 'ENG',
            'to' => 'TR',
            'user_id' => $user->id,
        ]);
        \App\Model\Request::setTranslated([
            'value' => 'hello',
            'from' => 'ENG',
            'to' => 'TR',
        ]);

        $this->assertEquals(1,\App\Model\Request::find(1)->situation);
    }
}