<?php

/**
 * Class ApiTest - This Test class test all required and used methods of API class (Look app/Http/Controllers).
 * @date 04.07.2017 13:53
 * @author Alihuseyn Gulmammadov <alihuseyn13@gmail.com>
 */
class ApiControllerTest extends PHPUnit\Framework\TestCase
{

    /**
     * Data Provider for getAuthInfo function test
     * @return array
     */
    public function getAuthInfoDataProvider()
    {
        return [
            [
                ['email' => 'alihuseyn13@gmail.com', 'token' => 'zgzv5HgaMK'],
                ['email' => 'alihuseyn13@gmail.com', 'token' => 'zgzv5HgaMK']
            ],
            [
                ['email' => null, 'token' => null],
                ['email' => null, 'token' => null]
            ],
            [
                ['email' => '', 'token' => ''],
                ['email' => '', 'token' => null]
            ],
            [
                [],
                ['email' => null, 'token' => null]
            ],
        ];
    }

    /**
     * This test check whether the method getAuthInfo() works correctly for given
     * correct items.
     *
     * function getAuthInfo($request); -> $request - Illuminate\Http\Request;
     *
     * This function return array. Look example in the below
     * @example - function return
     *
     *  [
     *      'email' => 'alihuseyn13@gmail.com'
     *      'token' => 'zgzv5HgaMK'
     *  ]
     * @dataProvider getAuthInfoDataProvider
     * @param $request
     * @param $correct
     */
    public function testGetAuthInfo($request, $correct)
    {
        $illuminateRequest = \Illuminate\Http\Request::create('/', 'GET', $request);
        $this->assertEquals($correct, App\Http\Controllers\API::getAuthInfo($illuminateRequest));
    }

    /**
     * Data Provider for getErrorContent function text
     * @return array
     */
    public function getErrorContentDataProvider()
    {
        return [
            ['ERR-005', ['code' => 'ERR-005', 'message' => 'The <value> is not entered.', 'http_code' => '400'], false],
            ['NOT-500', \Exception::class, true],
            [null, \Exception::class, true]
        ];
    }

    /**
     * This test check whether the method getErrorContent() works correctly for given
     * inputs. In this situation the error code will be given and the array containing
     * brief information about error code is waited.
     *
     * function getErrorContent($code); -> $code - String;
     *
     * This function return array. Look example in the below
     * @example - function return
     *
     *  [
     *       'code'        => 'ERR-005',
     *       'message'    => 'The <value> is not entered.',
     *       'http_code' => '400',
     *  ]
     *
     * For null or empty case and the not stated error code function must throw exception
     *
     * @example Api::getErrorContent(null);
     *              -  Null error content for:
     *
     * @dataProvider getErrorContentDataProvider
     * @param $code
     * @param $correct
     * @param $exceptionExists
     */
    public function testGetErrorContent($code, $correct, $exceptionExists)
    {
        if (!$exceptionExists) {
            $this->assertEquals($correct, \App\Http\Controllers\API::getErrorContent($code));
        } else {
            $this->expectException($correct);
            \App\Http\Controllers\API::getErrorContent($code);
        }
    }

    /**
     * Data Provider for getErrorContent function test
     * @return array
     */
    public function getSuccessContentDataProvider()
    {
        return [
            ['MSG-002', ['message' => 'New translation is added for given value'], false],
            ['NOT-500', \Exception::class, true],
            [null, \Exception::class, true]
        ];
    }

    /**
     * This test check whether the method getSuccessContent() works correctly for given
     * inputs. In this situation the success code will be given and the array containing
     * brief information about success code is waited.
     *
     * function getSuccessContent($code); -> $code - String;
     *
     * This function return array. Look example in the below
     * @example - function return
     *
     *  [
     *       'message' => 'The given value has already translated'
     *  ]
     *
     * For null or empty case and the not stated success code function must throw exception
     *
     * @example Api::getSuccessContent(null);
     *              -  Null error content for:
     *
     * @dataProvider getSuccessContentDataProvider
     *
     * @param $code
     * @param $correct
     * @param $exceptionExists
     */
    public function testGetSuccessContent($code, $correct, $exceptionExists)
    {
        if (!$exceptionExists) {
            $this->assertEquals($correct, \App\Http\Controllers\API::getSuccessContent($code));
        } else {
            $this->expectException($correct);
            \App\Http\Controllers\API::getSuccessContent($code);
        }
    }

    /**
     * Data Provider for getBodyParams function test
     * @return array
     */
    public function getBodyParamsDataProvider()
    {
        $user_1 = new \App\Model\User();
        $user_1->id = 5;

        $user_2 = new \App\Model\User();
        $user_2->id = 10;

        return [
            [['user' => $user_1, 'value' => 'hello', 'translation' => 'selam'], ['user_id' => '5', 'value' => 'hello', 'translation' => 'selam', 'from' => 'ENG', 'to' => 'TR']],
            [['user' => $user_2, 'value' => 'privet', 'translation' => 'selam', 'from' => 'RU', 'to' => 'TR'], ['user_id' => '10', 'value' => 'privet', 'translation' => 'selam', 'from' => 'RU', 'to' => 'TR']],
            [[], ['user_id' => null, 'value' => null, 'translation' => null, 'from' => 'ENG', 'to' => 'TR']],
            [['value' => 'privet', 'translation' => 'selam', 'from' => 'RU', 'to' => 'TR'], ['user_id' => null, 'value' => 'privet', 'translation' => 'selam', 'from' => 'RU', 'to' => 'TR']],
        ];
    }

    /**
     * This test check whether the method getBodyParams() works correctly for given
     * inputs. In this situation the $request (Illuminate\Http\Request) will be passed as input
     * and the result must be array containing required information
     *
     * function getBodyParams($request); -> $request - Illuminate\Http\Request;
     *
     * This function return array. Look example in the below
     * @example - function return
     *
     *   [
     *      'user_id' => '5',
     *      'value'   => 'hello',
     *      'translation' => 'selam',
     *      'from'    => 'ENG',
     *      'to'      => 'TR',
     *   ]
     *
     * For empty or null content function must return array again. Look below example
     *
     *    [
     *      'user_id' => null,
     *      'value'   => null,
     *      'translation' => null,
     *      'from'    => 'ENG',
     *      'to'      => 'TR',
     *   ]
     *
     * @dataProvider getBodyParamsDataProvider
     * @param $request
     * @param $correct
     */
    public function testGetBodyParams($request, $correct)
    {
        $illuminateRequest = \Illuminate\Http\Request::create('/', 'GET', $request);
        $api = new App\Http\Controllers\API();
        $this->assertEquals($correct, $api->getBodyParams($illuminateRequest));
    }

    /**
     * Data Provider for getBodyParams function test
     * @return array
     */
    public function getPrettyResponseDataProvider()
    {
        return [
            [['code' => 'ERR-005', 'message' => 'The <value> is not entered.', 'http_code' => '400'], true, ['errors' => ['code' => 'ERR-005', 'message' => 'The <value> is not entered.', 'http_code' => '400'], 'version' => 'v1.0', 'status' => false]],
            [['message' => 'Translation for given value has already added by other agent'], false, ['data' => ['message' => 'Translation for given value has already added by other agent'], 'version' => 'v1.0', 'status' => true]],
            [null, true, ['errors' => null, 'version' => 'v1.0', 'status' => false]],
        ];
    }

    /**
     * This function just prettify output for api. This test will check for whether the output is
     * correct for given inputs
     *
     * @example - function return
     *
     *   [
     *      'errors' => null,
     *      'version' => 'v1.0',
     *      'status' => false,
     *   ]
     *
     * @dataProvider getPrettyResponseDataProvider
     * @param $content
     * @param $error
     * @param $correct
     */
    public function testPrettyResponse($content, $error, $correct)
    {
        $this->assertEquals($correct, \App\Http\Controllers\API::prettyResponse($content, $error));
    }

    /**
     * Data Provider for validator function test
     * @return array
     */
    public function getValidatorDataProvider()
    {
        return [
            'success' => [['from' => 'ENG', 'to' => 'TR'], true],
            'fail_if_not_from' => [['from' => 'AZE', 'to' => 'ENG'], ['ERR-006']],
            'fail_if_not_to' => [['from' => 'ENG', 'to' => 'AZE'], ['ERR-007']],
            'fail_if_not_both' => [['from' => 'FR', 'to' => 'AZE'], ['ERR-006', 'ERR-007']],
        ];
    }

    /**
     * This function just validate given params and return array as an output or boolean true.
     * Validator check whether given from and to content exists or not
     *
     * @dataProvider getValidatorDataProvider
     *
     * @param $params
     * @param $correct
     */
    public function testValidator($params, $correct)
    {
        $api = new App\Http\Controllers\API();
        $this->assertEquals($correct, $api->validator($params));
    }
}
