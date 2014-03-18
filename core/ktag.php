<?php 

/**
 * Ktag
 *
 * @package   Kirby CMS
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      http://getkirby.com
 * @copyright Bastian Allgeier
 * @license   http://getkirby.com/license
 */
abstract class KtagAbstract {

  protected $page;
  protected $kt;
  protected $name;
  protected $html;
  protected $attr = array();

  public function __construct($kt, $name, $tag) {

    $this->page = $kt->field->page;
    $this->kt   = $kt;
    $this->name = $name;
    $this->html = kt::$tags[$name]['html'];

    // get a list with all attributes
    $attributes = isset(kt::$tags[$name]['attr']) ? (array)kt::$tags[$name]['attr'] : array();

    // add the name as first attribute
    array_unshift($attributes, $name);

    // extract all attributes
    $search = preg_split('!(' . implode('|', $attributes) . '):!i', $tag, false, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
    $num    = 0;
    
    foreach($search AS $key) {
    
      if(!isset($search[$num+1])) break;
      
      $key   = trim($search[$num]);
      $value = trim($search[$num+1]);

      $this->attr[$key] = $value;
      $num = $num+2;

    }

  }

  /**
   * Returns the parent active page
   * 
   * @return object Page
   */
  public function page() {
    return $this->page;
  }

  /**
   * Tries to find all related files for the current page
   * 
   * @return object Files
   */
  public function files() {
    return $this->page->files();
  }

  /**
   * Tries to find a file for the given url/uri
   * 
   * @param string $url a full path to a file or just a filename for files form the current active page
   * @return object File
   */
  public function file($url) {
    
    // if this is an absolute url cancel
    if(preg_match('!(http|https)\:\/\/!i', $url)) return false;
    
    // skip urls without extensions
    if(!preg_match('!\.[a-z]+$!',$url)) return false;

    // try to get all files for the current page
    $files = $this->files();
    
    // cancel if no files are available
    if(!$files) return false;

    // try to find the file
    return $files->find($url);
            
  }

  public function attr($key, $default = null) {
    return isset($this->attr[$key]) ? $this->attr[$key] : $default;
  }

  /**
   * Smart getter for the applicable target attribute. 
   * This will watch for popup or target attributes and return 
   * a proper target value if available. 
   * 
   * @return string 
   */
  public function target() {
    if(empty($this->attr['popup']) and empty($this->attr['target'])) return false;
    return empty($this->attr['popup']) ? $this->attr['target'] : '_blank';
  }

  public function html() {
    if(!is_callable($this->html)) {
      return (string)$this->html;
    } else {
      return call_user_func_array($this->html, array($this));
    }
  }

}