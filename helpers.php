<?php 

function snippet($file, $data = array(), $return = false) {
  return tpl::load(c::get('root.snippets') . DS . $file . '.php', $data, $return);
}

function css($url, $media = null) {

  if(is_array($url)) {
    $css = array();
    foreach($url as $u) $css[] = css($u);
    return implode(PHP_EOL, $css);
  }

  // auto template css files
  if($url == '@auto') {

    $site = kirby::site();
    $file = $site->page()->template() . '.css';
    $root = $site->options['root.auto.css'] . DS . $file;
    $url  = $site->options['url.auto.css'] . '/' . $file;

    if(!file_exists($root)) return false;

  }

  return '<link ' . html::attr(array(
    'rel'   => 'stylesheet',
    'href'  => url($url),
    'media' => $media
  )) . '>';

}

function js($src, $async = false) {

  if(is_array($src)) {
    $js = array();
    foreach($src as $s) $js[] = css($s);
    return implode(PHP_EOL, $js);
  }

  // auto template css files
  if($src == '@auto') {

    $site = kirby::site();
    $file = $site->page()->template() . '.js';
    $root = $site->options['root.auto.js'] . DS . $file;
    $src  = $site->options['url.auto.js'] . '/' . $file;

    if(!file_exists($root)) return false;

  }

  return '<script ' . html::attr(array(
    'src'   => url($src),
    'async' => $async
  )) . '></script>';

}

function kirbytext($field) {
  return (string)new Kt($field);
}

function site() {
  return kirby::site();
}

function page() {
  return call_user_func_array(array(kirby::site(), 'page'), func_get_args());
}

/**
 * Creates an excerpt without html and kirbytext
 * 
 * @param mixed $text Variable object or string
 * @param int $length The number of characters which should be included in the excerpt
 * @param array $params an array of options for kirbytext: array('markdown' => true, 'smartypants' => true)
 * @return string The shortened text
 */
function excerpt($text, $length = 140) {
  return str::excerpt(kirbytext($text), $length);
}
