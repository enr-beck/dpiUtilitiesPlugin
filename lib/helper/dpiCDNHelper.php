<?php

/**
 *
 * @param string $css
 * @param string $position
 * @param array $options
 */
function dpi_cdn_use_stylesheet($css, $position = '', $options = array()) {
  $host = sfConfig::get('dpi_cdn_host_stylesheet', null);
  if (!$host) {
    $host = sfConfig::get('dpi_cdn_host_common', null);
  }

  if ($host) {
    $prefix = 'http://';
    $secure = sfContext::getInstance()->getResponse()->isSecure();
    if (sfConfig::get('dpi_cdn_host_ssl', false) && $secure) {
      $prefix = 'https://';
    }
    $css = $prefix . $host . $css;
  }

  use_stylesheet($css, $position, $options);
}

/**
 *
 * @param string $js
 * @param string $position
 * @param array $options
 */
function dpi_cdn_use_javascript($js, $position = '', $options = array()) {
  $host = sfConfig::get('dpi_cdn_host_javascript', null);
  if (!$host) {
    $host = sfConfig::get('dpi_cdn_host_common', null);
  }

  if ($host) {
    $prefix = 'http://';
    $secure = sfContext::getInstance()->getResponse()->isSecure();
    if (sfConfig::get('dpi_cdn_host_ssl', false) && $secure) {
      $prefix = 'https://';
    }
    $js = $prefix . $host . $js;
  }

  use_javascript($js, $position, $options);
}

/**
 *
 * @param string $source
 * @param array $options
 * @return string
 */
function dpi_cdn_image_tag($source, $options = array()) {
  $host = sfConfig::get('dpi_cdn_host_image', null);
  if (!$host) {
    $host = sfConfig::get('dpi_cdn_host_common', null);
  }

  if ($host) {
    $prefix = 'http://';
    $secure = sfContext::getInstance()->getResponse()->isSecure();
    if (sfConfig::get('dpi_cdn_host_ssl', false) && $secure) {
      $prefix = 'https://';
    }
    $source = $prefix . $host . $source;
  }

  return image_tag($source, $options);
}
