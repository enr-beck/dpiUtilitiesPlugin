<?php
class dpiMemcache extends Memcache
{
  static private $instance = null;

  private function  __construct()
  {
    $this->initialize();
  }

  static public function getInstance()
  {
    if (!self::$instance)
    {
      self::$instance = new dpiMemcache();
    }

    return self::$instance;
  }

  private function initialize()
  {
    $this->options = sfConfig::get('app_memcache', array());

    if ($this->getOption('servers'))
    {
      foreach ($this->getOption('servers') as $server)
      {
        $port = isset($server['port']) ? $server['port'] : 11211;
        if (!$this->addServer($server['host'], $port, isset($server['persistent']) ? $server['persistent'] : true))
        {
          throw new sfInitializationException(sprintf('Unable to connect to the memcache server (%s:%s).', $server['host'], $port));
        }
      }
    }
    else
    {
      $method = $this->getOption('persistent', true) ? 'pconnect' : 'connect';
      if (!$this->$method($this->getOption('host', 'localhost'), $this->getOption('port', 11211), $this->getOption('timeout', 1)))
      {
        throw new sfInitializationException(sprintf('Unable to connect to the memcache server (%s:%s).', $this->getOption('host', 'localhost'), $this->getOption('port', 11211)));
      }
    }
  }

  protected function getOption($name, $default = null)
  {
    return isset($this->options[$name]) ? $this->options[$name] : $default;
  }

}