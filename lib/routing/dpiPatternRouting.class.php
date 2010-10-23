<?php
/**
 * @see: http://particul.es/blog/index.php?post/How-to-change-backend.php-to-admin-prefix-routes-automatically-symfony
 */
class dpiPatternRouting extends sfPatternRouting {

  /**
   *
   * @param sfEventDispatcher $dispatcher
   * @param sfCache $cache
   * @param array $options
   */
  public function initialize(sfEventDispatcher $dispatcher, sfCache $cache = null, $options = array()) {
    if (isset($options['prefix'])) {
      $options['contenxt']['prefix'] = $options['prefix'];
    } else {
      $options['context']['prefix'] = '';
    }

    parent::initialize($dispatcher, $cache, $options);
  }

  /**
   *
   * @param string $url
   * @return boolean
   */
  protected function getRouteThatMatchesUrl($url) {
    if (isset($this->options['context']['prefix'])) {
      $url = substr($url, strlen($this->options['context']['prefix']));
    }
    
    if ($url == '') {
      $url = '/';
    }

    return parent::getRouteThatMatchesUrl($url);
  }

}