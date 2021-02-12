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


$log = new ApacheLog;
$log->setRead('access.log');
$log->run();
//$log->get();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    #myInput {
  background-image: url('/css/searchicon.png'); /* Add a search icon to input */
  background-position: 10px 12px; /* Position the search icon */
  background-repeat: no-repeat; /* Do not repeat the icon image */
  width: 100%; /* Full-width */
  font-size: 16px; /* Increase font-size */
  padding: 12px 20px 12px 40px; /* Add some padding */
  border: 1px solid #ddd; /* Add a grey border */
  margin-bottom: 12px; /* Add some space below the input */
}

#myTable {
  border-collapse: collapse; /* Collapse borders */
  width: 100%; /* Full-width */
  border: 1px solid #ddd; /* Add a grey border */
  font-size: 18px; /* Increase font-size */
}

#myTable th, #myTable td {
  text-align: left; /* Left-align text */
  padding: 12px; /* Add padding */
}

#myTable tr {
  /* Add a bottom border to all table rows */
  border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
  /* Add a grey background color to the table header and on hover */
  background-color: #f1f1f1;
}
    </style>
</head>

<body>

        <h2>Apache Log</h2>
        <p style="color:red;float:right">Kayıt sayısı : <?php echo $log->logCount() ?></p>
   
        <input type="text" id="myInput" onkeyup="filterFunction()" placeholder="Search for names..">


                <table id="myTable">
                    <tr class="header">
                        <th>IP</th>
                        <th>Date</th>
                        <th>Hour</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Response Size</th>
                        <th>Url</th>
                        <th>User Agent</th>
                    </tr>
                    <?php for ($i = 0; $i < 3416; $i++) { ?>

                        <tr>
                            <td><?php echo $log->get()[$i]['ip'] ?></td>
                            <td><?php echo $log->get()[$i]['date'] ?></td>
                            <td><?php echo $log->get()[$i]['hour'] . ':' . $log->get()[$i]['minute'] .  ':' . $log->get()[$i]['second'] ?></td>
                            <td><?php echo $log->get()[$i]['method'] ?></td>
                            <td><?php echo $log->get()[$i]['status_code'] ?></td>
                            <td><?php echo $log->get()[$i]['response_size'] ?></td>
                            <td><?php echo $log->get()[$i]['referrer'] ?></td>
                            <td><?php echo $log->get()[$i]['user_agent'] ?></td>
                        </tr>

                    <?php } ?>
                </table>
</body>


<script>
    function filterFunction() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter)>-1) // Tip: the indexOf() method returns the indexes at which a given element can be found in the array, or -1 if it is not present. In other words, it checks wether the text in filter is also present in txtvalue - our array.
        
      {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
        //tip: If displays set to "none" then nothing will be shown.
        
      }
    }
  }
}
</script>