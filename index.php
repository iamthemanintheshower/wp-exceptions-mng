<?php
/*
Plugin Name: itmits - PHP exceptions logger for WP
Plugin URI: http://www.imthemanintheshower.com/wp-exceptions-mng
Description: PHP exceptions logger for WP
Version: 0.1
Author: imthemanintheshower
Author URI: http://www.imthemanintheshower.com
License: MIT - https://opensource.org/licenses/mit-license.php
*/
/*
Copyright 2017 iamthemanintheshower@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of 
this software and associated documentation files (the "Software"), to deal in 
the Software without restriction, including without limitation the rights to use, 
copy, modify, merge, publish, distribute, sublicense, and/or sell copies 
of the Software, and to permit persons to whom the Software is furnished 
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in 
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
PARTICULAR PURPOSE AND NONINFRINGEMENT. 
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
DEALINGS IN THE SOFTWARE.
*/

include('_error.php');
wp_enqueue_style( 'err_mng',  plugin_dir_url( __FILE__ ) . 'style.css' );
$page_title = 'PHP exceptions logger for WP';
$menu_title = 'PHP exceptions logger for WP';
$capability = 'manage_options';
$menu_slug = 'err_mng_topmenu_handle';
$function = 'err_mng_view_item_page';
$icon_url = null;
$position = null;

add_action( 'admin_menu', 'err_mng_menu' );

function err_mng_menu() {
    global $page_title;
    global $menu_title;
    global $capability;
    global $menu_slug;
    global $function;
    global $icon_url;
    global $position;

    _add_menu_item($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position);
}

function err_mng_view_item_page() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    echo '<div class="wrap">';
    echo '<p>Here is where the form would go if I actually had options.</p>';
    echo '</div>';
    
    ?>
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
        $log = file_get_contents(plugin_dir_path(__FILE__).'log/logs/php-exception.log');
        $invalid_characters = array("$", "%", "#", "<", ">");
        $log = str_replace($invalid_characters, "", $log);
        $lines = explode('!', $log);
        $i = 0;
        foreach ($lines as $l){
            if($l !== ''){
                if($_SESSION['last_i'] > $i){
                    echo '<tr class="bg">';
                }

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
<?php
}

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
function _add_menu_item($page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position){
    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function);
}