<?php
class Engine_Memcached extends Engine_Interface {
	private $memdata = array();
	private $mid;
	private $cache;
    private $timeLife = 2592000; // 1 месяц
	
	public function __construct() {
		parent::__construct();
		
		if ($this->registry["memc"]) {
			$this->cache = new Memcache();
			$this->cache->connect($this->registry["memc_adres"], $this->registry["memc_port"]);
		}
	}
	
	public function set($key) {
		$this->mid = $key;
	}
	
	public function get() {
		return $this->memdata;
	}

	public function load() {
		if ($this->registry["memc"]) {
			if ( ($this->memdata = $this->cache->get($this->mid)) === false ) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}
	
	public function save($data) {
		if ($this->registry["memc"]) {
			$this->cache->set($this->mid, $data, false, $this->timeLife);
		} else {
			return false;
		}
	}
	
	public function saveTime($data, $time) {
		if ($this->registry["memc"]) {
			$this->cache->set($this->mid, $data, false, $time);
		} else {
			return false;
		}
	}
	
	public function delete() {
		if ($this->registry["memc"]) {
			$this->cache->delete($this->mid, 0);
		} else {
			return false;
		}		
	}

	public function __destruct() {
		if ($this->registry["memc"]) {
			$this->cache->close();
		} else {
			return false;
		}
	}
}
?>