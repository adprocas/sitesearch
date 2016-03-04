<?php
require_once('password.php');
class mysql_driver {



	private $conn;
	private $user;
	private $pass;
	private $dbase;
	private $host;

	public function __construct() {
		global $config;
		$this->user = $config["mysql_username"];
		// echo $this->user . "<br>";
		$this->pass = $config["mysql_password"];
		// echo $this->pass . "<br>";
		$this->dbase = $config["mysql_database"];
		// echo $this->dbase . "<br>";
		$this->host = $config["mysql_host"];
		// echo $this->host . "<br>";
	}

	public function connect() {
		// Create connection
	try {
	    $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbase", $this->user, $this->pass, 
	    	array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
	    // set the PDO error mode to exception
	    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    //echo "Connected successfully"; 
	    }
	catch(PDOException $e)
	    {
	    	echo "Database Connection Not Working.<br><br>
	    	Please ensure you have edited the config.php file accordingly.";
	    }
		return true;
	}

	public function create_database_tables() {
		$sql = "CREATE TABLE IF NOT EXISTS crawlpages (
			  ID int(10) NOT NULL AUTO_INCREMENT,
			  url char(255) NOT NULL,
			  crawled tinyint(1) NOT NULL,
			  date_crawled datetime NOT NULL,
			  PRIMARY KEY (ID)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1";
		$result = $this->conn->query($sql);


		$sql = "CREATE TABLE IF NOT EXISTS pages (
			  id int(10) NOT NULL AUTO_INCREMENT,
			  url char(255) NOT NULL,
			  title char(255) NOT NULL,
			  content text NOT NULL,
			  PRIMARY KEY (id),
			  UNIQUE KEY url (url)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1";
		$result = $this->conn->query($sql);

		$sql = "CREATE TABLE IF NOT EXISTS users (
		  id int(2) NOT NULL AUTO_INCREMENT,
		  username varchar(15) NOT NULL,
		  password varchar(255) NOT NULL,
		  email varchar(50) NOT NULL,
		  PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci AUTO_INCREMENT=1";
		$result = $this->conn->query($sql);

		$sql = "CREATE TABLE IF NOT EXISTS alloweddomains (
		  id int(10) NOT NULL AUTO_INCREMENT,
		  domain varchar(40) NOT NULL,
		  active int(1) NOT NULL DEFAULT 1,
		  PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci";
		$result = $this->conn->query($sql);

		$sql = "CREATE TABLE IF NOT EXISTS ss_config (
		  id int(10) NOT NULL AUTO_INCREMENT,
		  name varchar(10) NOT NULL,
		  value varchar(255) NOT NULL,
		  PRIMARY KEY (id),
		  UNIQUE KEY name (name)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci";
		$result = $this->conn->query($sql);
	}

	public function login($username, $password) {

		// $stmt = $this->conn->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
		// $stmt->bindParam(':username', $username);
		// $stmt->bindParam(':password', $password);
		// $stmt->execute();
		// $result = $stmt->fetchAll();
		// if(count($result) > 0) {
		// 	return true;
		// }
		// return false;

		$stmt = $this->conn->prepare('
		  SELECT
		    password
		  FROM users
		  WHERE
		    username = :username
		  LIMIT 1
		  ');

		$stmt->bindParam(':username', $username);

		$stmt->execute();

		$user = $stmt->fetch(PDO::FETCH_OBJ);

		// Hashing the password with its hash as the salt returns the same hash
		if( password_verify($password, $user->password) ) {
		  // Ok!
			return true;
		}
		return false;
	}

	public function get_config() {
		$stmt = $this->conn->prepare("SELECT * FROM ss_config");
		$stmt->execute();
		$result = $stmt->fetchAll();
		if(count($result) <= 0) {
			return false;
		}
		foreach($result as $row) {
			$return[$row["name"]] = $row["value"];
		}
		return $return;
	}

	public function setup_config($base_url, $admin_url) {
		// echo "did this 1<br><br>";
		$stmt = $this->conn->prepare("INSERT INTO ss_config (name, value) VALUES ('base_url', :base_url)");
		$stmt->bindParam(':base_url', $base_url);
		$stmt->execute();
		// echo "did thisv2<br><br>";
		$stmt = $this->conn->prepare("INSERT INTO ss_config (name, value) VALUES ('admin_url', :admin_url)");
		$stmt->bindParam(':admin_url', $admin_url);
		$stmt->execute();
	}

	public function set_config($base_url, $admin_url) {
		if($this->is_config_table_empty() == true) {
			$this->setup_config($base_url, $admin_url);
		} else {
			$stmt = $this->conn->prepare("UPDATE ss_config SET value=:base_url WHERE name='base_url'");
			$stmt->bindParam(':base_url', $base_url);
			$stmt->execute();

			$stmt = $this->conn->prepare("UPDATE ss_config SET value=:admin_url WHERE name='admin_url'");
			$stmt->bindParam(':admin_url', $admin_url);
			$stmt->execute();
		}

	}

	public function is_config_table_empty() {
		$stmt = $this->conn->prepare("SELECT * FROM ss_config");
		$stmt->execute();
		$result = $stmt->fetchAll();
		// echo count($result) . "<br><br>";
		if(count($result) > 0) {
			// echo count($result) . "false<br><br>";
			return false;
		}
		// echo count($result) . "true<br><br>";
		return true;
	}

	public function get_allowed_domain($domain_check=false) {
		if($domain_check == false) {
			$stmt = $this->conn->prepare("SELECT * FROM alloweddomains");
		} else {
			$stmt = $this->conn->prepare("SELECT * FROM alloweddomains WHERE domain=:domain");
			$stmt->bindParam(':domain', $domain_check);
		}
		$stmt->execute();
		$result = $stmt->fetchAll();
		if(count($result) <= 0) {
			return false;
		}
		// print_r($result);
		// echo "<br><br>";
		return $result[0]["domain"];
	}

	public function set_allowed_domain($domain) {
		$insert = 0;
    	if($this->get_allowed_domain($domain) == false) {
			$stmt = $this->conn->prepare("INSERT INTO alloweddomains (domain) VALUES (:domain)");
	    	$stmt->bindParam(':domain', $domain);
	    	$stmt->execute();
    	}
	}

	public function is_crawl_table_empty() {
		$stmt = $this->conn->prepare("SELECT * FROM crawlpages");
		$stmt->execute();
		$result = $stmt->fetchAll();
		if(count($result) > 0) {
			return false;
		}
		return true;
	}

	public function check_crawl_status($url) {
		$stmt = $this->conn->prepare("SELECT * FROM crawlpages WHERE url=:url");
		$stmt->bindParam(':url', $url);
		$stmt->execute();
		$result = $stmt->fetchAll();
		if(count($result) > 0) {
			return false;
		}
		return $result[0]["crawled"];
	}

	public function get_to_crawl_list() {
		$stmt = $this->conn->prepare("SELECT * FROM crawlpages WHERE crawled=0 OR crawled=1");
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function remove_from_pages_table($url) {
		$stmt = $this->conn->prepare("DELETE FROM pages WHERE url=:url");
		$stmt->bindParam(':url', $url);
		$stmt->execute();
	}

	public function get_crawled_list() {
		$stmt = $this->conn->prepare("SELECT * FROM pages");
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function get_status_list() {
		$stmt = $this->conn->prepare("SELECT * FROM crawlpages");
		$stmt->execute();
		return $stmt->fetchAll();
	}

	public function check_table_not_empty($table) {
		$stmt = $this->conn->prepare("SELECT * FROM users");
		$stmt->execute();
		$result = $stmt->fetchAll();
		if(count($result) > 0) {
			return true;
		}
		return false;
	}

	public function create_admin($username, $password, $email) {
		$password = password_hash($password, PASSWORD_BCRYPT, array("cost" => 10));
		$stmt = $this->conn->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email)");
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':email', $email);
		$stmt->execute();
	}

	public function reset_password($email) {
		$passwordReg = $this->generate_random_password();
		$password = password_hash($passwordReg, PASSWORD_BCRYPT, array("cost" => 10));
		$stmt = $this->conn->prepare("UPDATE users SET password=:password WHERE email=:email");
		$stmt->bindParam(':password', $password);
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		return $passwordReg;
	}

	private function generate_random_password($length = 8) {
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$string = '';
		for ($i = 0; $i < $length; $i++) {
		    $string .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $string;
	}

	public function account_exists($email) {
		$stmt = $this->conn->prepare("SELECT * FROM users WHERE email=:email");
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		$result = $stmt->fetchAll();
		if(count($result) > 0) {
			return true;
		}
		return false;
	}

	public function add_url_to_crawl_list($url, $crawled=0, $insert_only=true) {
		$insert = 0;
		$stmt = $this->conn->prepare("SELECT * FROM crawlpages WHERE url=:url");
    	$stmt->bindParam(':url', $url);
    	$stmt->execute();
    	$result = $stmt->fetchAll();
    	if(count($result) > 0) {
    		$insert = 1;
    	}
    	if($insert == 0) {
			$stmt = $this->conn->prepare("INSERT INTO crawlpages (url, crawled, date_crawled) VALUES (:url, :crawled, NOW())");
	    	$stmt->bindParam(':url', $url);
			$stmt->bindParam(':crawled', $crawled);
	    	$stmt->execute();
    	} else if($insert == 1 && $insert_only != true) {
			$stmt = $this->conn->prepare("UPDATE crawlpages SET crawled=:crawled, date_crawled=NOW() WHERE url=:url");
	    	$stmt->bindParam(':url', $url);
	    	$stmt->bindParam(':crawled', $crawled);
	    	$stmt->execute();
    	}
	}

	public function insert_url_for_search($url, $content, $title) {
		$insert = 0;
		$this->add_url_to_crawl_list($url, 2, false);
		$stmt = $this->conn->prepare("SELECT * FROM pages WHERE url=:url");
    	$stmt->bindParam(':url', $url);
    	$stmt->execute();
    	$result = $stmt->fetchAll();
    	if(count($result) > 0) {
	    	if($result[0]["content"] != $content || $result[0]["title"] != $title) {
	    		$insert = 2;
	    		$update_id = $result[0]["id"];
	    	}
    	} else {
    		$insert = 1;
    	}
    	if($insert == 1) {
			$stmt = $this->conn->prepare("INSERT INTO pages (url, content, title) VALUES (:url, :content, :title)");
			$stmt->bindParam(':url', $url);
	    	$stmt->bindParam(':content', $content);
	    	$stmt->bindParam(':title', $title);
	    	$stmt->execute();
    	} else if($insert == 2) {
			$stmt = $this->conn->prepare("UPDATE pages SET content=:content, title=:title WHERE url=:url");
	    	$stmt->bindParam(':content', $content);
	    	$stmt->bindParam(':title', $title);
	    	$stmt->bindParam(':url', $url);
	    	$stmt->execute();
    	}
    	if($insert == 2 || $insert == 1) {
    		// $this->add_url_to_crawl_list($url, 3, false);
    	}
	}

	public function search_text($search_term, $limit, $offset) {
		if($search_term == "") {
			$stmt = $this->conn->prepare("SELECT SQL_CALC_FOUND_ROWS url, content, title FROM pages LIMIT $limit OFFSET $offset");
		} else {
			$search_term="%".$search_term."%";
			$stmt = $this->conn->prepare("SELECT SQL_CALC_FOUND_ROWS url, content, title FROM pages WHERE content LIKE :search_term LIMIT $limit OFFSET $offset");
			$stmt->bindParam(':search_term', $search_term);
		}
		$stmt->execute();
		$result = $stmt->fetchAll();
		return $result;
	}

	public function get_calc_found_rows() {
		return $this->conn->query('SELECT FOUND_ROWS()')->fetchColumn();
	}

	public function get_url_for_id($id) {
		$stmt = $this->conn->prepare("SELECT * FROM crawlpages WHERE id=:id");
    	$stmt->bindParam(':id', $id);
    	$stmt->execute();
    	$result = $stmt->fetchAll();
    	if(count($result) > 0) {
    		return $result[0]["url"];
    	}
	}
}

?>