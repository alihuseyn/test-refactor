<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class ExceptionResponseTest extends TestCase
{
    use DatabaseMigrations;

    public function testExceptionNotFound()
    {
        $this->get('api/v1/test?email=alihuseyn13@gmail.com&token=1234567890')
            ->assertResponseStatus(404);
        $this->seeJson(['code' => 'ERR-003']);
    }

    public function testExceptionWrongMethod()
    {
        $this->post('api/v1/waiting')->assertResponseStatus(405);
        $this->seeJson(['code' => 'ERR-004']);
    }

    public function testExceptionOutApi()
    {
        $this->get('/')->assertResponseStatus(404);
    }
}