# Apache-Log-Parser

### Example
![img](https://i.ibb.co/wsqhNnT/Screenshot-6.png)

### Usage

Include file
 ```php
include('ApacheLog.php');

```

Create and Set access.log path 
 ```php
$log = new ApacheLog;
$log->setRead('access.log');

```

Run
 ```php
$log->run();

```

Return parse array
 ```php
$log->get() 

/*

Array
(
    [0] => Array
        (
            [ip] => 123.32.33.144
            [date] => 11/Feb/2021
            [hour] => 00
            [minute] => 03
            [second] => 29 
            [method] => GET / HTTP/1.1
            [status_code] => 200
            [response_size] => 6758
            [referrer] => https://example.com
            [user_agent] => Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36
        )
        .
        .
        .
      */

```


