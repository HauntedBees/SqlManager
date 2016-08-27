<?
class ErrorLog { public static function AddError($type, $text) { error_log("$type Error: $text"); } }
class SQLManager {
	private $pdo;
	function __construct() {
		try {
			$c = parse_ini_file("config.ini", true);
			$cdb = $c["database"];
			$this->pdo = new PDO("mysql:host=".$cdb["host"].";dbname=".$cdb["schema"], $cdb["username"], $cdb["password"]);
			$this->pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_TO_STRING);
		} catch(PDOException $e) { ErrorLog::AddError("SQLManager->__construct", $e->message); }
	}
	public static function ToSQLDate($date) { return $date->format("Y-m-d H:i:s"); }
	public function Query($sql, $valsObj = []) {
		try {
			$STH = $this->pdo->prepare($sql);
			$STH->execute((array)$valsObj);
			return $STH;
		} catch(PDOException $e) { ErrorLog::AddError("SQLManager->Query", $e->message); }
	}
	public function QueryRow($sql, $valsObj = []) {
		try {
			$STH = $this->pdo->prepare($sql);
			$STH->execute((array)$valsObj);
			if($STH->rowCount() == 1) { return $STH->fetch(PDO::FETCH_ASSOC); }
			return null;
		} catch(PDOException $e) { ErrorLog::AddError("SQLManager->QueryRow", $e->message); }
	}
	public function QueryVal($sql, $valsObj = []) {
		try {
			$STH = $this->pdo->prepare($sql);
			$STH->execute((array)$valsObj);
			if($STH->rowCount() == 1) { return $STH->fetchColumn(); }
		} catch(PDOException $e) { ErrorLog::AddError("SQLManager->QueryVal", $e->message); }
		return null;
	}
	public function QueryCount($sql, $valsObj = []) { return intval($this->QueryVal($sql, $valsObj)); }
	public function QueryExists($sql, $valsObj = []) { return $this->QueryCount($sql, $valsObj) > 0; }
	public function GetLastInsertId() { return $this->pdo->lastInsertId(); }
	public function InsertAndReturn($sql, $valsObj) {
		$this->Query($sql, $valsObj);
		return $this->GetLastInsertId();
	}
}
?>