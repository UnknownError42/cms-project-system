<?php
namespace Core;

/**
 * Class Layout
 * @package Core
 
 */
class Layout {

    /**
     * holds page charset
     *
     * @var string
     */
    public $charset     = "UTF-8";

    /**
     * holds page language
     *
     * @var string
     */
    public $lang        = "de";

    /**
     * holds page title
     *
     * @var string
     */
    public $title       = "";

    /**
     * holds content
     *
     * @var string
     */
    public $content     = "";

    /**
     * holds meta rows
     *
     * @var array
     */
    public $meta        = array();

    /**
     * holds all js-files
     *
     * @var array
     */
    public $js          = array();

    /**
     * holds all css-files
     *
     * @var array
     */
    public $css         = array();

    /**
     * holds all js-scripts
     *
     * @var array
     */
    public $script      = array();

    /**
     * holds static cache status
     *
     * @var bool
     */
    protected $_cacheStatic = false;

    protected $_scriptsAtEnd = false;

    /**
     * Enable static file cache
     *
     * @return $this
     */
    public function enableStaticCache() {
        $this->_cacheStatic = true;
        return $this;
    }

    /**
     * Disable static file cache
     *
     * @return $this
     */
    public function disableStaticCache() {
        $this->_cacheStatic = false;
        return $this;
    }

    /**
     * Include scripts at end
     *
     * @return $this
     */
    public function scriptsAtEnd() {
        $this->_scriptsAtEnd = true;
        return $this;
    }

    /**
     * Include scripts at head
     *
     * @return $this
     */
    public function scriptsAtTop() {
        $this->_scriptsAtEnd = false;
        return $this;
    }

    /**
     * Add custom script
     * @param $script
     * @return $this
     */
    public function addScript($script) {
        $this->script[] = $script;
        return $this;
    }

    /**
     * Add javascript file
     *
     * @param $file
     * @return $this
     */
    public function addJs($file) {
        if (false === $this->hasFile($file)) {
            $this->js[] = $file;
        }
        return $this;
    }

    /**
     * Add stylesheet file
     *
     * @param $file
     * @return $this
     */
    public function addCss($file) {
        if (false === $this->hasFile($file)) {
            $this->css[] = $file;
        }
        return $this;
    }

    /**
     * Remove asset file
     *
     * @param $filename
     * @return $this
     */
    public function removeFile($filename) {
        foreach ($this->css as $i => $file) {
            if ($file === $filename) {
                unset($this->css[$i]);
                return $this;
            }
        }
        foreach ($this->js as $i => $file) {
            if ($file === $filename) {
                unset($this->js[$i]);
                return $this;
            }
        }
        return $this;
    }

    /**
     * Check if files has been added
     *
     * @param string $file
     * @return bool
     */
    public function hasFile($file) {
        foreach ($this->css as $i => $path) {
            if ($file === $path) {
                return true;
            }
        }
        foreach ($this->js as $i => $path) {
            if ($file === $path) {
                return true;
            }
        }
        return false;
    }

    /**
     * Set language
     *
     * @param $lang
     * @return $this
     */
    public function setLang($lang) {
        $this->lang = $lang;
        return $this;
    }

    /**
     * Set charset
     *
     * @param $charset
     * @return $this
     */
    public function setCharset($charset) {
        $this->charset = $charset;
        return $this;
    }

    /**
     * Set meta data
     *
     * @param $name
     * @param $content
     * @return $this
     */
    public function setMeta($name, $content) {
        $this->meta[$name] = $content;
        return $this;
    }

    /**
     * Set title
     *
     * @param $title
     * @return $this
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * Prepend title string
     *
     * @param $prepend
     * @return $this
     */
    public function prependTitle($prepend) {
        $title = $prepend . ' / ' . $this->title;
        return $this->setTitle($title);
    }

    /**
     * Append title string
     *
     * @param $append
     * @return $this
     */
    public function appendTitle($append) {
        $title = $this->title . ' / ' . $append;
        return $this->setTitle($title);
    }

    /**
     * Set content
     *
     * @param $content
     * @return $this
     */
    public function setContent($content) {
        $this->content = $content;
        return $this;
    }

    /**
     * Returns content
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Renders layout
     *
     * @return string
     */
    public function render() {
        return $this->head() . $this->getContent() . $this->foot();
    }

    /**
     * Render header
     *
     * @return string
     */
    public function head() {
        $html  = '<!DOCTYPE html>' . PHP_EOL;
        $html .= '<html lang="' . $this->lang . '">' . PHP_EOL;
        $html .= '<head>' . PHP_EOL;
        $html .= '<meta charset="' . $this->charset . '">' . PHP_EOL;
        $html .= '<title>'.$this->title.'</title>' . PHP_EOL;
        $html .= '<meta http-equiv="X-UA-Compatible" content="IE=edge">' . PHP_EOL;
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1">' . PHP_EOL;
        $html .= $this->_getMetaTags();
        $html .= '<link rel="shortcut icon" href="/images/favicon.ico">' . PHP_EOL;
        $html .= $this->_mergeFiles($this->css, 'css', 'stylesheet.css');
        if ($this->_scriptsAtEnd === false) {
            $html .= $this->getAllScripts();
        }
        $html .= '</head>' . PHP_EOL;
        $html .= '<body>' . PHP_EOL;
        return $html;
    }

    /**
     * Returns footer
     *
     * @return string
     */
    public function foot() {
        $html = "";
        if ($this->_scriptsAtEnd === true) {
            $html .= $this->getAllScripts();
        }
        $html .= '</body>' . PHP_EOL . '</html>';
        return $html;
    }

    /**
     *
     *
     * @return string
     */
    public function getAllScripts() {
        $html = $this->_mergeFiles($this->js, 'js', 'javascript.js');
        $html .= $this->getScripts();
        return $html;
    }

    /**
     * Returns dynamically added js-scripts
     *
     * @return string
     */
    public function getScripts() {
        $data = '';
        if (count($this->script) > 0) {
            $data = '<script type="text/javascript">';
            foreach ($this->script as $script) {
                $data .= PHP_EOL . $script;
            }
            $data .= '</script>';
        }
        return $data;
    }

    /**
     * Returns collected meta da as html string
     *
     * @return string
     */
    protected function _getMetaTags() {
        $html = "";
        $template = '<meta name="%s" content="%s">' . PHP_EOL;
        foreach ($this->meta as $name => $content) {
            $html .= sprintf($template, $name, $content);
        }
        return $html;
    }

    /**
     * Merges and cleans files
     *
     * @param $files
     * @param $class
     * @return mixed|string
     */
    protected function _mergeDataFiles($files, $class) {
        $data = "";
        foreach ($files as $file) {
            $data .= file_get_contents(substr($file, 1));
        }
        $data = $class === 'js' ? Minify::js($data) : $data;
        $data = $class === 'css' ? Minify::css($data) : $data;
        return $data;
    }

    /**
     * Merges js and css files
     *
     * @param array $files
     * @param string $class
     * @param string $mergeFile
     * @return string
     */
    protected function _mergeFiles($files, $class, $mergeFile) {
        $newFile    = '/cache/' . $mergeFile;
        $cachedFile = substr($newFile, 1);
        $template   = $class === 'js' ? '<script type="text/javascript" src="%s"></script>' . PHP_EOL : null;
        $template   = $class === 'css' ? '<link rel="stylesheet" type="text/css" href="%s">' . PHP_EOL : $template;

        if ($this->_cacheStatic) {
            if (is_file($cachedFile)) {
                return sprintf($template, $newFile);
            }
            $data  = $this->_mergeDataFiles($files, $class);
            file_put_contents($cachedFile, $data);
            return sprintf($template, $newFile);
        }
        $html = "";
        foreach ($files as $file) {
            $html .= sprintf($template, $file);
        }
        return $html;
    }

}
