<?php
/**
 * Copyright (c) 2018.  MyArtSide 
 */

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
    $config     = $data['core'];
    $serverName = $_SERVER['SERVER_NAME'];

    if ((strpos($serverName, '.udev.lan') > 0) OR ((strpos($serverName, 'localhost') > 0))) {
	    /*define('ENV', 'production');
		define('DEBUG', false);*/
        return 'development-home';
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        $config = mergeArr($config, $data[ENV]);
    } else if (strpos($serverName, '.kasserver') > 0) {
	    /*define('ENV', 'production');
		define('DEBUG', false);*/
        return 'testing';
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        $config = mergeArr($config, $data[ENV]);
    }else if (strpos($serverName, '.udev') > 0) {
	    /*define('ENV', 'production');
		define('DEBUG', false);*/
        return 'development';
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        $config = mergeArr($config, $data[ENV]);
    }else {
        /*define('ENV', 'production');
        define('DEBUG', false);*/
        return 'production';
        error_reporting(0);
        ini_set('display_errors', 'Off');
    }
}
	
	
function getAllPathByEnV(){
		$returnPaths = array();
		$PHPSELF = $_SERVER['PHP_SELF'];
		$ServName = $_SERVER['SERVER_NAME'];
		$ServSoft = $_SERVER['SERVER_SOFTWARE'];
		$ServPathInfo = $_SERVER['PATH_INFO'];
		if(!empty( $_SERVER['ORIG_PATH_INFO'])){
			$ServOrigPathInfo = $_SERVER['ORIG_PATH_INFO'];
		}else{
			$ServOrigPathInfo = null;
		}
		
		if(!empty($_SERVER['PATH_TRANSLATED'])){
			$ServPathTranslated = $_SERVER['PATH_TRANSLATED'];
		}else{
			$ServPathTranslated = null;
		}
		
		$ServReqUri = $_SERVER['REQUEST_URI'];
		$ServScriptFileName = $_SERVER['SCRIPT_NAME'];
		if(!empty( $_SERVER['SERVER_SIGNATURE'])){
			$ServSignature = $_SERVER['SERVER_SIGNATURE'];
		}else{
			$ServSignature = null;
		}
		
		
		$env = getEnvByNetwork();
		switch ($env) {
			case 'production':
				$returnPaths['production'] = [
					'user_homes' => '',
					'user_docroot' => '',
					'user_dir' => '',
					'ServerDocRoot' => $_SERVER['DOCUMENT_ROOT'],
					'ServerSELF' => $PHPSELF,
					'ServerName' => $ServName,
					'ServerSoftware' => $ServSoft,
					'ServerPathInfo' => $ServPathInfo,
					'ServerOrigPathInfo' => $ServOrigPathInfo,
					'ServerPathTranslated' => $ServPathTranslated,
					'ServerRequestURI' => $ServReqUri,
					'ServerScriptFilename' => $ServScriptFileName,
					'ServerSignature' => $ServSignature
				];
				return $returnPaths;
				break;
			case 'development':
				$returnPaths['development'] = [
					'user_homes' => '/home',
					'user_docroot' => 'cms-system/public',
					'user_dir' => '.public_html/',
					'ServerDocRoot' => $_SERVER['DOCUMENT_ROOT'],
					'ServerSELF' => $PHPSELF,
					'ServerName' => $ServName,
					'ServerSoftware' => $ServSoft,
					'ServerPathInfo' => $ServPathInfo,
					'ServerOrigPathInfo' => $ServOrigPathInfo,
					'ServerPathTranslated' => $ServPathTranslated,
					'ServerRequestURI' => $ServReqUri,
					'ServerScriptFilename' => $ServScriptFileName,
					'ServerSignature' => $ServSignature
				];
				return $returnPaths;
				break;
			case 'testing':
				$returnPaths['testing'] = [
					'user_homes' => '/home',
					'user_dir' => '.public_html/',
					'user_docroot' => 'cms-system/public',
					'ServerDocRoot' => $_SERVER['DOCUMENT_ROOT'],
					'ServerSELF' => $PHPSELF,
					'ServerName' => $ServName,
					'ServerSoftware' => $ServSoft,
					'ServerPathInfo' => $ServPathInfo,
					'ServerOrigPathInfo' => $ServOrigPathInfo,
					'ServerPathTranslated' => $ServPathTranslated,
					'ServerRequestURI' => $ServReqUri,
					'ServerScriptFilename' => $ServScriptFileName,
					'ServerSignature' => $ServSignature
				];
				return $returnPaths;
				break;
			case 'development-home':
				$returnPaths['development-home'] = [
					'user_homes' => '/home',
					'user_docroot' => 'cms-system/public',
					'user_dir' => '.public_html/',
					'ServerDocRoot' => $_SERVER['DOCUMENT_ROOT'],
					'ServerSELF' => $PHPSELF,
					'ServerName' => $ServName,
					'ServerSoftware' => $ServSoft,
					'ServerPathInfo' => $ServPathInfo,
					'ServerOrigPathInfo' => $ServOrigPathInfo,
					'ServerPathTranslated' => $ServPathTranslated,
					'ServerRequestURI' => $ServReqUri,
					'ServerScriptFilename' => $ServScriptFileName,
					'ServerSignature' => $ServSignature
				];
				return $returnPaths;
				break;
		}
}


function isGlobal(){
	$scriptPath = $_SERVER['PHP_SELF'];
	$serverName = $_SERVER['SERVER_NAME'];
	if (!(strpos($scriptPath, '~') > 0) AND (!(strpos($serverName, 'localhost') > 0))) {
		/* no tilde or localhost found - so we are on original udev */
		return true;
		
	}elseif ((strpos($scriptPath, '~') > 0) AND (!(strpos($serverName, 'localhost') > 0))){
		/* tilde from userdir found and not on localhost - its possible to be on udev/~username directory */
		return false;
		
	}elseif (!(strpos($scriptPath, '~') > 0) AND ((strpos($serverName, 'localhost') > 0))){
		/* no tilde found but on localhost - so we are on an other system */
		return true;
		
	}elseif ((strpos($scriptPath, '~') > 0) AND ((strpos($serverName, 'localhost') > 0))){
		/* tilde found and localhost found - so we are  on an other system in ~userdir */
		return false;
		
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