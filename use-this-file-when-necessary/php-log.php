<?php session_start();
if(!isset($_SESSION) && !isset($_SESSION['last_i'])){ $_SESSION['last_i'] = 0; }
$last_i = $_SESSION['last_i'];
?><!DOCTYPE html>
<html>
    <head>
        <title>PHP exceptions logger for WP</title>
        <meta charset="UTF-8">
        <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
        <script>
            $(function(){
                $("tbody").each(function(){
                    var arr = $.makeArray($("tr",this).detach());
                    arr.reverse();
                    $(this).append(arr);
                });
            });
        </script>
        <style>
            body{ font-family: helvetica; font-size: 13px; }
            td { padding: 3px; max-width: 450px; }
            .font{ font-family:courier; }
            .center{ text-align: center; }
            .bg555{ background-color: #555; color: #FFF; }
            .bg{ background-color:#ccc; }
            .r{ color:red; }
            .o{ color:orange; }
            .y{ color:yellow; }
        </style>
    </head>
    <body>
        <table>
            <thead class="bg555">
                <th>TYPE</th>
                <th>WHEN</th>
                <th>TYPE</th>
                <th>DESCRIPTION</th>
                <th>LINE</th>
                <th>FILE</th>
            </thead>
            <tbody>
                <?php
                $log = file_get_contents('log/logs/php-exception.log');
                $invalid_characters = array("$", "%", "#", "<", ">");
                $log = str_replace($invalid_characters, "", $log);

                $lines = explode('!', $log);
                $i = 0;
                foreach ($lines as $l){
                    if($l !== ''){
                        if($_SESSION['last_i'] > $i){ echo '<tr class="bg">'; }

                        $columns = explode('|', $l);
                        //TYPE	WHEN	TYPE	DESCRIPTION	LINE	FILE
                        echo '<td>'.$columns[0].'</td>'; //TYPE
                        echo '<td>'.date('d/M/Y H:i:s', intval($columns[1])).'</td>'; //WHEN
                        echo '<td class="font">'.getErrorType($columns[2]).'</td>'; //TYPE
                        echo '<td class="font">'.$columns[3].'</td>'; //DESCRIPTION
                        echo '<td class="center">'.$columns[4].'</td>'; //LINE
                        echo '<td>'.$columns[5].'</td>'; //FILE
                        echo '</tr>';
                        $i++;
                    }
                }
                $_SESSION['last_i'] = $i;
                ?>
            </tbody>
        </table>
    </body>
</html><?php
function getErrorType($code){
    $php_error_codes = array(
        '1' => '<span class="r">E_ERROR</span>',
        '2' => '<span class="o">E_WARNING</span>',
        '4' => 'E_PARSE',
        '8' => '<span class="y">E_NOTICE</span>',
        '16' => 'E_CORE_ERROR',
        '32' => 'E_CORE_WARNING',
        '64' => 'E_COMPILE_ERROR',
        '128' => 'E_COMPILE_WARNING',
        '256' => 'E_USER_ERROR',
        '512' => 'E_USER_WARNING',
        '1024' => 'E_USER_NOTICE',
        '2048' => 'E_STRICT',
        '4096' => 'E_RECOVERABLE_ERROR',
        '8192' => 'E_DEPRECATED',
        '16384' => 'E_USER_DEPRECATED',
        '32767' => 'E_ALL'
    );
    return $php_error_codes[$code];
}