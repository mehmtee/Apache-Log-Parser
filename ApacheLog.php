<?php
class ApacheLog
{
    function __construct()
    {
        $this->path = '';
        $this->log_table = [];
        $this->count = 0;
    }

    function get()
    {
        return ($this->log_table);
    }

    function setRead($path)
    {
        //$path = access.log file path
        $this->path = $path;
    }

    function readFile()
    {
        foreach ($this->path as $line) {
            $this->parser($line);
        }
    }


    function logCount()
    {
        return count($this->log_table);
    }


    function parser($line)
    {
        $pattern = '/^(?P<IP>\S+)
        \ (?P<ident>\S)
        \ (?P<auth_user>.*?)
        \ (?P<date>\[[^]]+\])
        \ "(?P<method>.+ .+)"
        \ (?P<status_code>[0-9]+) 
        \ (?P<response_size>(?:[0-9]+|-))
        \ "(?P<referrer>.*)"
        \ "(?P<user_agent>.*)"$/x';
        error_reporting(0);
        preg_match_all($pattern, $line, $results);

        $results['IP'][0];
        $results['date'][0];
        $results['date'][0] = str_replace('[', '', $results['date'][0]);
        $results['date'][0] = str_replace(']', '', $results['date'][0]);
        $results['date'][0] = str_replace('+', ':', $results['date'][0]);
        $results['date'][0] = explode(':', $results['date'][0]);
        $date = $results['date'][0][0];
        $hour = $results['date'][0][1];
        $minute = $results['date'][0][2];
        $second = $results['date'][0][3];

        $results['method'][0];
        $results['status_code'][0];
        $results['response_size'][0];
        $results['referrer'][0];
        $results['user_agent'][0];
        $data = [
            'ip' => $results['IP'][0],
            'date' => $date,
            'hour' => $hour,
            'minute' => $minute,
            'second' => $second,
            'method' =>  $results['method'][0],
            'status_code' => $results['status_code'][0],
            'response_size' => $results['response_size'][0],
            'referrer' => $results['referrer'][0],
            'user_agent' => $results['user_agent'][0]
        ];
        $this->log_table[$this->count] = $data;
        $this->count++;
    }



    function run()
    {
        foreach (file('access.log') as $line) {
            $this->parser($line);
        }
    }
}