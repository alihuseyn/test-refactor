# Task 2 API
-- --------------------------------
With this API the simple translation operation between agency and internal application performed. 

### Endpoints
There are 5 endpoints on this API. They are as below:
```sh
   GET   api/v1/waiting     
   POST`  api/v1/translate
   GET   api/v1/value
   GET   api/v1/:value
   POST  api/v1/value
```

### Usage of API
Each time for sending and receiving info the simple authentication will require. The simple authentication will check whether user exists or not with email and token information. Each request will be send with information about email and token on the requested url path.
`GET  api/v1/waiting?email=alihuseyn13@gmail.com&token=Ax159R5986Er598x`

#### GET api/v1/waiting
```sh
Request:
GET api/v1/waiting?email=alihuseyn13@gmail.com&token=Ax159R5986Er598x
Response Content:
{
  "data": [
    {
      "value": "hello",
      "language": {
        "from": "ENG",
        "to": "TR"
      }
    }
  ],
  "version": "v1.0",
  "status": true
}
```
| #     | Key   | Explanation |
|:-----:|:-----:|:-----------:|
| 1     | `data`  | In the successful response the all required content stored inside of the data tag.|
|2      | `value` | The value which requested for translate by agency.|
|3      | `language` | Keep language information for request. From which language to other translation is requested.|
|4      | `from`    | From language. Default is ENG (English)|
|5      | `to`      | To Language. Default is TR (Turkish) |
|6      | `version` | The version information of API |
|7      | `status`  | Define success or fail response result|

#### POST api/v1/translate
```sh
Request:
POST api/v1/translate?email=alihuseyn13@gmail.com&token=Ax159R5986Er598x
Request Body:
-----------------------
value : hello
translate : selam
from : ENG
to : TR
--------------------------
Response:
{
  "data": {
    "message": "New translation is added for given value"
  },
  "version": "v1.0",
  "status": true
}
```
| #     | Type  | Key   | Explanation |
|:-----:|:--------:|:-------:|:-----------:|
| 1 |Request|`value`  | Value information which translated |
| 2 |Request|`translation` | Translation content for given value|
| 3 |Request| `from`| From Language information. Default is ENG|
| 4 |Response| `to` | To Language Inforamtion. Default is TR|
|5  |Response| `message`| Short explanation of the operation result|

*Note:* The remaining tag same as mentioned above.

#### GET api/v1/value
```sh
Request:
GET api/v1/value?email=alihuseyn13@gmail.com&token=Ax159R5986Er598x

Response:
{
  "data": [
    {
      "ENG": [
        "hello"
      ],
      "TR": [
        "selam"
      ]
    }
  ],
  "version": "v1.0",
  "status": true
}
```
*Note:* Data content contains from object as seen from response.Each object keep translation information for values which translated with their languages. 

#### GET api/v1/value/:id
```sh
Request:
GET api/v1/value/hello?email=alihuseyn13@gmail.com&token=Ax159R5986Er598x
Response Content:
{
  "data": {
    "value": "hello",
    "language": "ENG",
    "ENG": [
      "hello"
    ],
    "TR": [
      "selam"
    ]
  },
  "version": "v1.0",
  "status": true
}
```
| #     | Key   | Explanation |
|:-----:|:-----:|:-----------:|
| 1     | `value`  | Value Information|
|2      | `language` | The language of value|

*Note:* Other tags are mentioned above

#### POST api/v1/value
```sh
Request:
POST api/v1/value?email=alihuseyn13@gmail.com&token=Ax159R5986Er598x
Request Body:
-----------------------
value : hello
from : ENG
to : TR
--------------------------
Response:
{
  "data": {
    "message": "Addition of request for translation of a value is completed"
  },
  "version": "v1.0",
  "status": true
}
```

##### Note:
The permission for each endpoint is different. For sending request `api/v1/translate` and `api/v1/waiting` endpoints, the user profile must be **AGENT**.
For other remaining endpoints the profile must be **USER**

--- --------------------------------------
### Configuration
#### Requirements for Enviroment
* PHP 5.6
* MySQL
* Linux Enviroment *(Ubuntu 16.04 LTS used in development procedure)*
#### Database Configuration
The database configuration are kept under `.env` file. To change it to your own database information pleade change contents of `.env`
```sh
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=root
DB_PASSWORD=alihuseyn
```

The migrations files are under `database/migrations` path. For executing migration type the below stated command in the shell
```sh
$ php artisan migrate
```
For Rollback:
```sh
$ php artisan migrate:rollback
```
Type the below stated command for filling database with seed information in the testing proces.
```sh
$ php artisan db:seed
```
##### Note
This seed operation will fill users and requests table. In the users table there are will be 2 user. One of them profile will be *AGENT* and second one profile will be *USER*. 

#### Starting API Server
For starting API service type the command in shwon below.
```sh
$ php -S 0.0.0.0:8000 -t public/
```
After this command API will start to listen from localhost:8000.For each request path the url must be added.
`http://localhost:8000/api/v1/value/hello?email=alihuseyn13@gmail.com&token=Ax159R5986Er598x`
#### Test Information
Test contents are stored under `tests` folder. The coverage report html content is stored under `report` folder.