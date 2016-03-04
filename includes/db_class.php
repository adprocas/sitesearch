<?php

require_once('mysql_driver.php');
class db_class {

	private $db_driver;

	public function __construct() {
		global $config;
		if($config["database"] == "mysql") {			
			$this->db_driver = new mysql_driver();
			$this->db_driver->connect();
			$this->setup_tables();
		}

	}

	public function set_config($base_url, $admin_url) {
		$this->db_driver->set_config($base_url, $admin_url);
	}

	public function get_config() {
		//needs to be in configname => value format
		return $this->db_driver->get_config();
	}

	public function check_crawl_status($url) {
		return $this->db_driver->check_crawl_status($url);
	}

	public function reset_password($email) {
		return $this->db_driver->reset_password($email);
	}

	public function account_exists($email) {
		return $this->db_driver->account_exists($email);
	}

	public function set_allowed_domain($domain) {
		return $this->db_driver->set_allowed_domain($domain);
	}

	public function get_allowed_domain($domain_check=false) {
		return $this->db_driver->get_allowed_domain($domain_check);
	}

	public function get_to_crawl_list() {
		$result = $this->db_driver->get_to_crawl_list();
		return $result;
	}

	public function is_crawl_table_empty() {
		return $this->db_driver->is_crawl_table_empty();
	}

	public function setup_tables() {
		$this->db_driver->create_database_tables();
	}

	public function install_databse() {
		$this->db_driver->create_database_tables();
	}

	public function insert_url_for_crawl($url) {
		$result = true;

		return $result;
	}

	public function insert_url_for_search($url, $content, $title) {
		$result = true;
		$this->db_driver->insert_url_for_search($url, $content, $title);
		// echo "URL to insert = ".$url."<br>";
		// echo "Content to insert = ".$content."<br>";
		return $result;
	}

	public function search_pages_for_text($search_term, $limit=2, $offset=0) {
		//note that this should return an array with the following
		//array( url => "url", content => "content" )
		$result = $this->db_driver->search_text($search_term, $limit, $offset);
		for($i=0; $i<count($result); $i++) {
			 $result[$i]["content"] = $this->cut_content($result[$i]["content"]) . "...";
		}
		return $result;
	}

	public function get_calc_found_rows() {
		return $this->db_driver->get_calc_found_rows();
		// $this->conn->query('SELECT FOUND_ROWS()')->fetchColumn();
	}

	private function cut_content($content) {
		return substr($content, 0, 280);
	}

	public function add_url_to_crawl_list($url, $crawled=0, $insert_only=true) {
		//crawled = 2
		//crawled but didn't work = 3
		//crawled = 4 -- deleted
		//recrawl = 1
		//first crawl = 1
		$this->db_driver->add_url_to_crawl_list($url, $crawled, $insert_only);
	}

	public function remove_from_pages_table($url) {
		$this->db_driver->remove_from_pages_table($url);
	}

	public function check_table_not_empty($table) {
		return $this->db_driver->check_table_not_empty($table);
	}

	public function create_admin($username, $password, $email) {
		$this->db_driver->create_admin($username, $password, $email);
	}

	public function login($username, $password) {
		return $this->db_driver->login($username, $password);
	}

	public function get_crawled_list() {
		return $this->db_driver->get_crawled_list();
	}

	public function get_status_list() {
		return $this->db_driver->get_status_list();
	}

	public function get_url_for_id($id) {
		return $this->db_driver->get_url_for_id($id);
	}
	
	

}


?>