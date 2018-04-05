<?php

/**
 * Dump data pretty
 *
 * @param mixed $a
 */
function pre($a) {
    echo '<pre>';
    var_dump($a);
    echo '</pre>';
}

function highlight_line($code) {
    $code = highlight_string('<?php ' . $code, true);
    $code = str_replace(array("&lt;?php&nbsp;", PHP_EOL), "", $code);
    $code = strip_tags($code, "<span>");
    $code = trim(trim($code));
    return trim($code);
}


function getEnvByNetwork() {
    $data       = include '../data/config.php';
    $config     = $data['production'];
    $serverName = $_SERVER['SERVER_NAME'];

    if (strpos($serverName, '.udev.lan') > 0) {
        define('ENV', 'development-home');
        define('DEBUG', 'ON');
        return 'development-home';
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        $config = mergeArr($config, $data[ENV]);
    } else if (strpos($serverName, '.kasserver') > 0) {
        define('ENV', 'testing');
        define('DEBUG', 'ON');
        return 'testing';
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        $config = mergeArr($config, $data[ENV]);
    }else if (strpos($serverName, '.udev') > 0) {
        define('ENV', 'development');
        define('DEBUG', 'ON');
        return 'development';
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        $config = mergeArr($config, $data[ENV]);
    }else {
        define('ENV', 'production');
        define('DEBUG', 'OFF');
        return 'production';
        error_reporting(0);
        ini_set('display_errors', 'Off');
    }
}



/**
 * Highlight file
 *
 * @param $file
 * @return mixed|string
 */
function highlight($file) {
    if (is_file($file)) {
        $type = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $code = file_get_contents($file);
        switch ($type) {
            case 'xml':
            case 'html':
                $code = explode("\r\n", $code);
                $code = array_map('highlight_line', $code);
                $code = implode(PHP_EOL, $code);
                break;

            case 'php':
                $code = explode("\n", $code);
                $code = array_map('highlight_line', $code);
                $code = implode("<br>", $code);
                break;
        }
        $code = htmlspecialchars($code);
        $code = '<code>' . $code . '</code>';
        return '<pre>'.$code.'</pre>';
    }
    return 'no file to highlight';
}

/**
 * Check SSL
 *
 * @return bool
 */
function isSecure() {
    if (isset($_SERVER['HTTPS'])) {
        if ($_SERVER['HTTPS'] === 'on') {
            return true;
        }
        if ($_SERVER['HTTPS'] === 1) {
            return true;
        }
    }
    if ($_SERVER['SERVER_PORT'] === 443) {
        return true;
    }
    return false;
}

/**
 * Returns base url
 *
 * @return string
 */
function getBaseUrl() {
    $scheme = isSecure() ? 'https' : 'http';
    return $scheme . "://" . $_SERVER['SERVER_NAME'];
}

/**
 * Merges two arrays
 *
 * @param array $origin
 * @param array $new
 * @param bool $overwrite
 * @return mixed
 */
function mergeArr($origin, $new, $overwrite=true) {
    foreach($new as $key=>$val) {
        if(isset($origin[$key])) {
            if (is_array($val)) {
                $origin[$key] = mergeArr($origin[$key], $val);
            } elseif(is_array($origin[$key]) === false && $overwrite) {
                $origin[$key] = $val;
            }
        } else {
            $origin[$key] = $val;
        }
    }
    return $origin;
}

/**
 *
 *
 * @param $data
 * @param $sortKey
 * @param bool $isArray
 * @param int $sort_flags
 * @return array
 */
function SortByKeyValue($data, $sortKey, $isArray = false, $sort_flags=SORT_ASC) {
    if (empty($data) || empty($sortKey)) {
        return $data;
    }
    $ordered = array();
    foreach ($data as $key => $value) {
        if ($isArray) {
            $ordered[$value[$sortKey]] = $value;
        } else {
            $ordered[$value->$sortKey] = $value;
        }
    }
    ksort($ordered, $sort_flags);
    return array_values($ordered); // array_values() added for identical result with multisort
}

function errorHandler() {

}

function exceptionHandler() {

}