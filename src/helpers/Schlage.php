<?


class Schlage extends CURL {

	private $username;
	private $password;
	private $action;
	
	public $auth_token;
	public $houses;
	private $house;
	
	const ROOT_URL = 'https://www.schlagelink.com/';
	
	public function Schlage($username, $password) {
	
		$this->SetCookiePath($_SERVER['DOCUMENT_ROOT'] . '/cookie.txt');
		$this->username = $username;
		$this->password = $password;
		$this->houses = array();
	}
	
	public static function GetInstance() {
		return new Schlage(schlage_username(), schlage_password());
	}
	
	public function Revert($url) {
		parent::Revert($url);
		$this->SetHeader('Accept', 'application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5');
		$this->SetHeader('User-Agent', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.10 (KHTML, like Gecko) Chrome/8.0.552.231 Safari/534.10');
		$this->SetHeader('Accept-Language', 'en-US,en;q=0.8');
		$this->SetHeader('Accept-Encoding', 'gzip,deflate,sdch');
	}
	
	
	
	public function Ajax() {
		$this->SetHeader('X-Requested-With', 'XMLHttpRequest');
		$this->SetHeader('Accept', 'text/javascript, application/javascript, */*, text/javascript');
	}
	
	public function TransactionWait($transaction, $timeout = 30, $sleep = .5) {
		if ($transaction instanceof Transaction) $transaction = $transaction->key;
		$this->Revert(self::ROOT_URL . 'transactions/' . $transaction);
		$update = new TransactionUpdate();
		$update->updated = false;
		$time = time();
		while (time() < $time + $timeout) {
			$result = json_decode($this->Get(), true);
			if ($result) {
				$update->status = $result['status'];
				$update->duration = time() - $time;
				$update->updated = true;
				return $update;
				break;
			}
			sleep($sleep);
		}
		return $update;
	}
	
	
	
	public function AuthToken($a = false) {
		if ($a) {
			$this->auth_token = $a;
			return;
		}
		if ($this->auth_token) $this->AddVar('authenticity_token', $this->auth_token);
	}
	
	public function Login() {
		$this->Revert(self::ROOT_URL . 'login');
		$data = $this->Get();
		$dom = new SimpleHTMLDom($data);
		$this->AuthToken($dom->find('[name=authenticity_token]', 0)->value);
		$dom->clear();
		
		$this->Revert(self::ROOT_URL . 'session');
		$this->AddVar('login', $this->username);
		$this->AddVar('password', $this->password);
		$this->SetMethod(POST);
		$this->AuthToken();
		$data = $this->Get();
		$dom = new SimpleHTMLDom($data);
		$error = $dom->find('[class=error-box]', 0);
		if ($error && !preg_match('~none~', $error)) return false;
		$houses = $dom->find('[class=house]');
		foreach ($houses as $house) {
			$match = false;
			if (preg_match('~/houses/([0-9]+)/.+~', $house->href, $match)) {
				$house = new House($match[1], $house->text);
				array_push($this->houses, $house);
				if (!$this->house) $this->house = $house;
			}
		}
		$dom->clear();
		return true;
		
	}
	
	public function GetLights() {
		$this->Revert(self::ROOT_URL . 'houses/' . $this->house->id . '/lighting?format=json');
		$data = json_decode($d = $this->Get(), true);
		$this->house->lights = array();
		foreach ($data as $light) {
			array_push($this->house->lights, new Light($light));
		}
		return $this->house->lights;
	}
	
	public function GetLightsFull() {
		
	
	}
	
	public function GetLocks() {
		$this->Revert(self::ROOT_URL . 'houses/' . $this->house->id . '/security?format=json');
		$data = json_decode($this->Get(), true);
		$this->house->locks = array();
		foreach ($data as $lock) {
			array_push($this->house->locks, new Lock($lock));
		}
		return $this->house->locks;
	}
	
	public function GetLocksFull() {
		$this->Revert(self::ROOT_URL . 'houses/' . $this->house->id . '/security');
	}
	
	public function ModifyLight($light, $value) {
		if ($light instanceof Light) $light = $light->id;
		if ($value > 99) $value = 99;
		if ($value < 0) $value = 0;
		$value = intval($value);
		$this->Revert(self::ROOT_URL . 'houses/' . $this->house->id . '/lighting/set/' . $light);
		$this->AuthToken();
		$this->Ajax();
		$this->AddVar('device[value]', $value);
		$this->SetMethod(POST);
		$this->Get();
		return true;
	}
	
	public function Activate($lock) {
		if ($lock instanceof Lock) $lock = $lock->id;
		$this->Revert(self::ROOT_URL . 'locks/' . $lock . '/activate');
		$this->AuthToken();
		$this->AddVar('_method', 'put');
		$this->SetMethod(POST);
		$this->AuthToken();
		return $this->CreateTransaction($this->Get());
	}
	
	
	private function CreateTransaction($result) {
		if ($d = json_decode($result, true)) {
			return new Transaction($d);
		}
		return null;
	}

}

class House {
	
	public $id;
	public $name;
	public $locks;
	public $lights;
	
	public function House($id, $name) {
		$this->id = $id;
		$this->name = $name;
		$this->locks = array();
		$this->lights = array();
	}
	
	public function Locks() {
		return $this->locks;
	}
	
	public function Lights() {
		return $this->lights;
	}
}

class Transaction {
	public $id;
	public $type;
	public $value;
	public $key;
	public $time;
	
	public function __construct($array) {
		foreach($array as $key => $value) {
			$this->$key = $value;
		}
		$this->time = time();
	}
}

class TransactionUpdate {
	public $status;
	public $transaction;
	public $duration;
	public $updated = false;
}

class Device {
	public $id;
	public $value;
	
	public function __construct($array) {
		foreach($array as $key => $value) {
			$this->$key = $value;
		}
	}
}

class Light extends Device {
	
}

class Lock extends Device {
	public $lock_type;
}


?>