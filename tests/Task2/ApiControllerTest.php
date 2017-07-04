<?php

/**
 * Class ApiTest - This Test class test all required and used methods of API class (Look app/Http/Controllers).
 * @date 04.07.2017 13:53
 * @author Alihuseyn Gulmammadov <alihuseyn13@gmail.com>
 */
class ApiControllerTest extends PHPUnit\Framework\TestCase
{

    public function getDataProvider()
    {
        return [
            [['email' => 'alihuseyn13@gmail.com' , 'token' => 'zgzv5HgaMK'], ['email' => 'alihuseyn13@gmail.com' , 'token' => 'zgzv5HgaMK']],
           // [['email' => null , 'token' => null], ['email' => null , 'token' => null]],
          //  [['email' => '' , 'token' => ''], ['email' => null , 'token' => null]],
            [[], ['email' => null , 'token' => null]],
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
     * @dataProvider getDataProvider
     * @param $request
     * @param $correct
     */
    public function testGetAuthInfo($request, $correct)
    {
        $requestMock = $this->getMockBuilder(Illuminate\Http\Request::class)
                            ->getMock();

        if(!empty($request)) {
            $requestMock->merge(['email' => $request['email'], 'token' => $request['token']]);
            var_dump($requestMock);
        }



        $result = App\Http\Controllers\API::getAuthInfo($requestMock);
        $this->assertEquals($correct,$result);
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
     */
    public function testGetErrorContent()
    {
        $this->markTestIncomplete();
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
     */
    public function testGetSuccessContent()
    {
        $this->markTestIncomplete();
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
     */
    public function testGetBodyParams()
    {
        $this->markTestIncomplete();
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
     *
     */
    public function testPrettyResponse()
    {
        $this->markTestIncomplete();
    }

    /**
     * This function just validate given params and return array as an output or boolean true.
     *
     */
    public function testValidator()
    {
        $this->markTestIncomplete();
    }
}
