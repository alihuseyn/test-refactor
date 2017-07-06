<?php
use Illuminate\Support\Facades\Artisan;

/**
 * Class TranslateModelTest - This Test class test only fetch methods of Translate Model class (Look app/Model/Translate).
 * @date 04.07.2017 14:43
 * @author Alihuseyn Gulmammadov <alihuseyn13@gmail.com>
 */
class TranslateModelTest extends PHPUnit\Framework\TestCase
{

    /**
     * Set Up Function
     */
    public function setUp()
    {
        parent::setUp();
        $this->prepareDatabase();
    }

    /**
     * Check whether array is associate array or not
     * @param array $arr
     * @return bool
     */
    function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Migrate database migration file to memory (Look config/database.php)
     */
    public function prepareDatabase()
    {
        Artisan::call('migrate:refresh');
    }

    /**
     * Data Provider for fetch function test
     * @return array
     */
    public function getFetchDataProvider()
    {
        return [
            [
                [
                    'value' => 'selam'    // value will be fetched
                ],
                [                       // the input which will be added to DB
                    'value' => 'hello',
                    'translation' => 'selam',
                    'from' => 'ENG',
                    'to' => 'TR'
                ],
                [                       // result part
                    'value' => 'selam',
                    'language' => 'TR',
                    'ENG' => [
                        'hello'
                    ],
                    'TR' => [
                        'selam'
                    ]
                ]
            ],
            [
                null,
                [
                    'value' => 'hello',
                    'translation' => 'selam',
                    'from' => 'ENG',
                    'to' => 'TR'
                ],
                [
                    [
                        'ENG' => [
                            'hello'
                        ],
                        'TR' => [
                            'selam'
                        ]
                    ]
                ]
            ],
            [
                null,
                null,
                [
                    'message' => 'Not any result found for request'
                ]
            ],
            [
                [
                    'value' => null
                ],
                [
                    'value' => 'hello',
                    'translation' => 'selam',
                    'from' => 'ENG',
                    'to' => 'TR'
                ],
                [
                    'message' => 'Not any result found for request'
                ]
            ],
            [
                [
                    'value' => 'selam'
                ],
                [
                    [
                        'value' => 'hello',
                        'translation' => 'selam',
                        'from' => 'ENG',
                        'to' => 'TR'
                    ],
                    [
                        'value' => 'hi',
                        'translation' => 'selam',
                        'from' => 'ENG',
                        'to' => 'TR'
                    ],
                    [
                        'value' => 'selam',
                        'translation' => 'privet',
                        'from' => 'TR',
                        'to' => 'RU'
                    ]
                ],
                [
                    'value' => 'selam',
                    'language' => 'TR',
                    'ENG' => [
                        'hello',
                        'hi'
                    ],
                    'TR' => [
                        'selam'
                    ],
                    'RU' => [
                        'privet'
                    ]
                ]
            ],
            [
                null,
                [
                    [
                        'value' => 'selam',
                        'translation' => 'hello',
                        'from' => 'TR',
                        'to' => 'ENG'
                    ],
                    [
                        'value' => 'hi',
                        'translation' => 'selam',
                        'from' => 'ENG',
                        'to' => 'TR'
                    ],
                    [
                        'value' => 'selam',
                        'translation' => 'privet',
                        'from' => 'TR',
                        'to' => 'RU'
                    ],
                    [
                        'value' => 'good morning',
                        'translation' => 'selam',
                        'from' => 'ENG',
                        'to' => 'TR'
                    ],
                    [
                        'value' => 'hello',
                        'translation' => 'privet',
                        'from' => 'ENG',
                        'to' => 'RU'
                    ],
                    [
                        'value' => 'hello',
                        'translation' => 'hallo',
                        'from' => 'ENG',
                        'to' => 'SP'
                    ],
                    [
                        'value' => 'krasivaya',
                        'translation' => 'beautiful',
                        'from' => 'RU',
                        'to' => 'ENG'
                    ]
                ],
                [
                    [
                        'ENG' => [
                            'hello',
                            'hi',
                            'good morning'
                        ],
                        'TR' => [
                            'selam'
                        ],
                        'RU' => [
                            'privet'
                        ],
                        'SP' => ['hallo']
                    ],
                    [
                        'ENG' => [
                            'beautiful'
                        ],
                        'RU' => ['krasivaya']
                    ]
                ]
            ],
            [
                null,
                [
                    [
                        'value' => 'selam',
                        'translation' => 'hello',
                        'from' => 'TR',
                        'to' => 'ENG'
                    ],
                    [
                        'value' => 'selam',
                        'translation' => 'hi',
                        'from' => 'TR',
                        'to' => 'ENG'
                    ],
                ],
                [
                    [
                        'ENG' => [
                            'hello',
                            'hi'
                        ],
                        'TR' => [
                            'selam'
                        ]
                    ]
                ]
            ],

        ];
    }

    /**
     * This function help to select the required translated item from DB with its all
     * translation with other languages.
     *
     * function fetch(array $params);
     *
     * @dataProvider getFetchDataProvider
     *
     * @param $search
     * @param $input
     * @param $correct
     */
    public function testFetch($search, $input, $correct)
    {
        // If input not null then add db otherwise not add
        if (!is_null($input)) {
            // create test user from factory
            $user = factory(\App\Model\User::class)->create(['profile' => 'AGENT']);
            // input can be more than 1 and the check on this option done and
            // to db one by one or add only one in else part
            if (!$this->isAssoc($input)) {
                foreach ($input as $item) {
                    \App\Model\Translate::add(array_merge($item, ['user_id' => $user->id]));
                }
            } else {
                \App\Model\Translate::add(array_merge($input, ['user_id' => $user->id]));
            }
        }
        // call fetch function
        $result = \App\Model\Translate::fetch($search);
        $this->assertEquals($correct, $result);
    }

    /**
     * Data Provider for prettify function test
     * @return array
     */
    public function getPrettifyDataProvider()
    {
        return [
            [
                [],
                null,
                'ENG',
                [
                    'message' => 'Not any result found for request'
                ]
            ],
            [
                [
                    'ENG' => [
                        'hello',
                        'hi'
                    ],
                    'TR' => [
                        'selam'
                    ]
                ],
                null,
                null,
                [
                    'ENG' => [
                        'hello',
                        'hi'
                    ],
                    'TR' => [
                        'selam'
                    ]
                ]
            ],
            [
                [
                    'ENG' => [
                        'hello'
                    ],
                    'TR' => [
                        'selam'
                    ]
                ],
                'selam',
                'TR',
                [
                    'value' => 'selam',
                    'language' => 'TR',
                    'ENG' => [
                        'hello'
                    ],
                    'TR' => [
                        'selam'
                    ]
                ]
            ]
        ];
    }

    /**
     * This function test output correction of prettify function
     *
     * function prettify($result, $value = null, $language = null);
     *
     * @dataProvider getPrettifyDataProvider
     *
     * @param $result
     * @param $value
     * @param $lang
     * @param $correct
     */
    public function testPrettify($result, $value, $lang, $correct)
    {
        $this->assertEquals($correct, \App\Model\Translate::prettify($result, $value, $lang));
    }

    /**
     * Data Provider for mapSingle function test
     * @return array
     */
    public function getMapSingleDataProvider()
    {
        return [
            [
                'selam',
                [
                    (object)[
                        'to' => 'ENG',
                        'from' => 'TR',
                        'value' => 'selam',
                        'translation' => 'hello'
                    ]
                ],
                [
                    'value' => 'selam',
                    'language' => 'TR',
                    'ENG' => [
                        'hello'
                    ],
                    'TR' => [
                        'selam'
                    ]
                ]
            ],
            [
                'selam',
                [
                    (object)[
                        'to' => 'ENG',
                        'from' => 'TR',
                        'value' => 'selam',
                        'translation' => 'hello'
                    ],
                    (object)[
                        'to' => 'ENG',
                        'from' => 'TR',
                        'value' => 'selam',
                        'translation' => 'hi'
                    ]
                ],
                [
                    'value' => 'selam',
                    'language' => 'TR',
                    'ENG' => [
                        'hello',
                        'hi'
                    ],
                    'TR' => [
                        'selam'
                    ]
                ]
            ],
            [
                null,
                [
                    (object)[
                        'to' => 'ENG',
                        'from' => 'TR',
                        'value' => 'selam',
                        'translation' => 'hello'
                    ],
                    (object)[
                        'to' => 'ENG',
                        'from' => 'TR',
                        'value' => 'selam',
                        'translation' => 'hi'
                    ]
                ],
                [
                    'message' => 'Not any result found for request'
                ]
            ]
        ];
    }

    /**
     * This function test parseSingle function correction.
     *
     * function parseSingle($value, $translations);
     *
     * @dataProvider getMapSingleDataProvider
     *
     * @param $value
     * @param $translations
     * @param $correct
     */
    public function testParseSingle($value, $translations, $correct)
    {
        $this->assertEquals($correct, \App\Model\Translate::mapSingle($value, $translations));
    }


    /**
     * Data Provider for mapWhole function test
     * @return array
     */
    public function getMapWholeDataProvider()
    {
        return [
            [
                [
                    (object)[
                        'to' => 'ENG',
                        'from' => 'TR',
                        'value' => 'selam',
                        'translation' => 'hello'
                    ]
                ],
                [
                    [
                        'ENG' => [
                            'hello'
                        ],
                        'TR' => [
                            'selam'
                        ]
                    ]
                ]
            ],
            [
                [
                    (object)[
                        'to' => 'ENG',
                        'from' => 'TR',
                        'value' => 'selam',
                        'translation' => 'hello'
                    ],
                    (object)[
                        'to' => 'ENG',
                        'from' => 'TR',
                        'value' => 'selam',
                        'translation' => 'hi'
                    ]
                ],
                [
                    [
                        'ENG' => [
                            'hello',
                            'hi'
                        ],
                        'TR' => [
                            'selam'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * This function test mapWhole function correction.
     *
     * function function parseWhole($translations);
     *
     * @dataProvider getMapWholeDataProvider
     *
     * @param $translations
     * @param $correct
     *
     */
    public function testParseWhole($translations, $correct)
    {
        $this->assertEquals($correct, \App\Model\Translate::mapWhole($translations));
    }
}

