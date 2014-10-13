<?php

/**
 * Language
 *
 * A single language object
 */
class Language extends Obj {

  public function __construct($site, $lang) {

    $this->site    = $site;
    $this->code    = $lang['code'];
    $this->name    = $lang['name'];
    $this->locale  = $lang['locale'];
    $this->default = (isset($lang['default']) and $lang['default']);
    $this->url     = isset($lang['url']) ? $lang['url'] : $lang['code'];

  }

  public function url() {
    return url::makeAbsolute($this->url, $this->site->url());
  }

}


