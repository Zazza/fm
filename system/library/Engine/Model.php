<?php
class Engine_Model extends Engine_Interface {

    protected $memcached;
    
	//Переменные для пейджера
	public $totalPage;
	public $limit = 20; // Кол-во выводимых статей
	public $startRow = 0;
	public $curPage = 1;
    private $num = 5;
    public $links = "";
	public $pager = array();
    public $maxPage = 1; //Последняя страница пейджера
    public $sep = "&";
    
	//Антифлуд
	public $pause = FALSE;
	public $pause_time = 10;

	function __construct() {
		parent::__construct();
		    
        $this->memcached = $this->registry['memcached'];
	}

	//Установка выбранной страницы
	public function setPage($page) {
		if ( ($page > 0) and ($page < 1000000000) ) {
			$this->curPage = $page;
			$this->startRow = $this->limit * ($page-1);
            
			return TRUE;
		} else {
			return FALSE;
		};
	}

	//Пейджер
	public function Pager() {
		$numPage = ceil( $this->totalPage / $this->limit );
        $this->maxPage = $numPage;

		//Предыдущая страница
		$prev = ($this->curPage-1);
		if ($prev != 0) {
            $this->pager[0] = "<div class='pager_arr'><a onclick='pager()' class='pager' href='" . $this->registry["uri"] . $this->registry["action"] . $this->links . $this->sep . "page=" . $prev ."'>←</a></div>";
		};

		for ($i=1; $i <= $numPage; $i++) {
			if ($i == $this->curPage) {
				$this->pager[] = "<div class='pager_utext'>" . $i . " </div>";
			} else {
				// Выводим не более $this->num страниц вокруг выбранной
				if ( ($i >= $this->curPage - $this->num ) and ($i <= $this->curPage + $this->num) ) {
                    $this->pager[] = "<div class='pager_text'><a onclick='pager()' class='pager' href='" . $this->registry["uri"] . $this->registry["action"] . $this->links . $this->sep . "page=" . $i ."'>$i</a></div>";
				};
			};
		};

		//Следующая страница
		$next = ($this->curPage+1);
		if ($next <= $numPage) {
            $this->pager[$i+1] = "<div class='pager_arr'><a onclick='pager()' class='pager' href='" . $this->registry["uri"] . $this->registry["action"] . $this->links . $this->sep . "page=" . $next ."'>→</a></div>";
		};

		$this->pager;
	}

	public function editDate($date) {
		setlocale(LC_ALL, 'ru_RU.utf-8', 'rus_RUS.utf-8', 'ru_RU.utf8');

		if (substr($date, 11, 8) != "") {
			$date = strtotime($date);
			
			$mk = mktime(date("H", $date), date("i", $date), date("s", $date), date("m", $date), date("d", $date), date("Y", $date));
			$res = strftime("%H:%M:%S, %A, %d %B %Y", $mk);
		} else {
			$date = strtotime($date);
			
			$mk = mktime(0, 0, 0, date("m", $date), date("d", $date), date("Y", $date));
			$res = strftime("%A, %d %B %Y", $mk);
		}
		return $res;
	}
    
    public function checkDate($date) {
		$flag = false;
		for($i=0; $i<count($this->registry["calYear"]); $i++) {
			if ($this->registry["calYear"][$i] == date("Y", strtotime($date))) {
				$flag = true;
			}
		};

        return $flag;
    }
    
	public function initPause($type) {
		$this->memcached->mid = $type . $this->registry["ui"]["id"];
		if ($this->memcached->load()) {
			$this->pause = TRUE;
		} else {
			$this->pause = FALSE;
			$this->memcached->saveTime("pause", $this->pause_time);
		}
	}
}
?>
