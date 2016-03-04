<?php
require_once('../includes/config.php');
require_once('includes/simple_html_dom_extended.php');

class SiteSearchAdmin {
	private $html;
	public $domain = false;
	private $db;
	public $crawl_report;
	public $subtlds = "";
	private $_loggedin = false;

	public function __construct() {
		$this->create_simple_dom();
	}

	private function create_simple_dom() {
		if(!isset($this->_simple_dom)) {
			$this->html = new simple_html_dom_extended();
		}
	}

	public function check_url_timeout($url) {
		Global $config;
		$content_url = $config["settings"]["admin_url"];
		$content_url = rtrim($content_url, '/') . '/'  . 'check_open_timeframe.php?check_url='.urlencode($url);
		$return = file_get_contents($content_url);
		// echo $return . "<br><br><br>";
		return $return;
	}

	public function get_config() {
		$this->db_connect();
		return $this->db->get_config();
	}

	public function set_config($base_url, $admin_url) {
		$this->db_connect();
		$newPass = $this->db->set_config($base_url, $admin_url);
	}

	public function getLoginStatus() {
		return $this->_loggedin;
	}

	public function reset_password($email) {
		$this->db_connect();
		$newPass = $this->db->reset_password($email);
		$this->email_password($email, $newPass);
		//return $newPass;//this was used when mail() wasn't available for testing
	}

	public function email_password($email, $newPass) {
		mail($email, "Site Search Password Reset", "Your new password for Site Search is: ".$newPass);
	}

	public function account_exists($email) {
		$this->db_connect();
		return $this->db->account_exists($email);
	}

	public function db_connect() {
		$this->db = new db_class();
	}

	public function setup_tables() {
		$this->db_connect();
		$this->db->setup_tables();
	}

	public function set_allowed_domain($domain) {
		$this->db->set_allowed_domain($domain);
	}

	public function get_allowed_domain($domain_check=false) {
		if($this->domain == false && $domain_check == false) {
			$this->domain = $this->db->get_allowed_domain($domain_check);
		}
	}

	public function get_domain($url)
	{
	    $slds = "";
	    if($this->subtlds == "") {
	    	$this->set_slds();
	    }

	    preg_match('/^(https?:[\/]{2,})?([^\/]+)/i', $url, $matches);
	    //preg_match("/^(http:\/\/|https:\/\/|)[a-zA-Z-]([^\/]+)/i", $url, $matches);
	    $host = @$matches[2];
	    //echo var_dump($matches);

	    preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
	    foreach($this->subtlds as $sub) 
	    {
	        if (preg_match("/{$sub}$/", $host, $xyz))
	        preg_match("/[^\.\/]+\.[^\.\/]+\.[^\.\/]+$/", $host, $matches);
	    }
	    //$this->domain = $matches[0];
	    return @$matches[0];
	}

	public function process_url($url) {
	    $url = strtolower($url);
	    $url = preg_replace('/\?.*/', '', $url);
		return $url;
	}

	public function is_crawl_table_empty() {
		$this->db_connect();
		return $this->db->is_crawl_table_empty();
	}

	public function get_to_crawl_list() {
		$this->db_connect();
		return $this->db->get_to_crawl_list();
	}

	public function insert_url_for_search($url, $same_domain=true) {
		$this->get_allowed_domain();
		$url = $this->addScheme($url);
		$this->db_connect();
		$crawl_status = $this->db->check_crawl_status($url);
		if($crawl_status < 2)  {
			$content = $this->getUrlContent($url, $same_domain);
			if($content != false) {
				$title="";
				$title = array_shift($this->html->find('title'))->innertext;
				$this->db->insert_url_for_search($url, $content, $title);
			} else {
				//handle case where can't open url$this->add_url_to_crawl_list
				// echo $url;
				$this->add_url_to_crawl_list($url, 3, false);
			}
		}
	}

	public function update_url_to_crawl_list_by_id($id, $crawled=0) {
		$url = $this->db->get_url_for_id($id);
		$this->add_url_to_crawl_list($url, $crawled, $insert_only=false);
		if($crawled == 4 && isset($url)) {
			$this->db->remove_from_pages_table($url);
		}
	}

	public function add_url_to_crawl_list($url, $crawled=0, $insert_only=true) {
		$this->get_allowed_domain();
		if($this->is_crawl_table_empty()) {
			$this->set_allowed_domain($this->get_domain($url));
		}
		$url = $this->addScheme($url);
		$this->db_connect();
		$this->db->add_url_to_crawl_list($url, $crawled, $insert_only);
	}

	function addScheme($url, $scheme = 'http://')
	{
		if(substr($url, 0, 2) == "//") {
			$url = "http:" . $url;
		} else if(substr($url, 0, 1) == "/") {
			$url = ltrim($url, '/');
			$url = $this->domain . $url;
		} else if(substr($url, 0, 4) != "http") {
			$url = "http://" . $url;
		}
		$url = rtrim($url, '/') . '/';
		//echo $url . " -- Original";
		$url = parse_url($url, PHP_URL_HOST) === null ?
			$this->domain . $url : $url;
		//echo $url . " -- 2";
	 	$url = parse_url($url, PHP_URL_SCHEME) === null ?
	    	$scheme . $url : $url;
	    //echo $url . " -- 3";
	 	$url = preg_replace('/\?.*/', '', $url);
	 	return $url;
	}

	function getUrlContent($url, $same_domain) {
		$this->crawl_report = "";
		if($this->check_url_timeout($url) == "true") {
			if($this->html->load_file($url) != false) {
				//$html = str_get_html($html);
				$current_domain = $this->get_domain($url);
				/////////////////////////////////////////////////////////
				///////////////////
				///////////////////
				///////////////////
				/////////////////// Next would be to ensure all links have a domain at the beginning
				/////////////////// try using the parse_url function and see if a domain is listed
				/////////////////// if not, maybe try adding it and continuing
				///////////////////
				///////////////////
				///////////////////
				/////////////////////////////////////////////////////////
				$this->crawl_report .= "<p>Checking for valid urls to crawl</p>";
				foreach($this->html->find('a') as $element) {
					
					$element->href = $this->addScheme($element->href);
					// echo $element->href . '<br>';
					$check_domain = $this->get_domain($element->href);
					//traverse through links and find the ones for the currently selected domains
					///code on other usb key goes here
					////////////////////////////
					$for_crawl = false;
					$domains_allowed = Array($current_domain);//need to change this to grab the list of domains and check them accordingly

					if($same_domain == true) {
						foreach($domains_allowed as $domain) {
							
							
							//check to see if it is in the list
							
							
							if($domain == $check_domain) //if in the list, make sure we let it crawl it
							{
								$for_crawl = true;
								$this->crawl_report .= '<div class="container col-md-12 bg-success" style="border: 1px solid #ddd;">';
								$this->crawl_report .= "Adding to crawl list - " .$element->href. "";
								$this->crawl_report .= "</div>";
								break;
							} else {
								$this->crawl_report .= '<div class="container col-md-12 bg-danger" style="border: 1px solid #ddd;">';
								$this->crawl_report .= "Not adding to crawl list - " .$element->href. "";
								$this->crawl_report .= "</div>";
							}
							
						}
					} else {
						$for_crawl == true;
					}

					if($for_crawl == true) {
						// $this->addToCrawlList($url);
						//echo "<br>Continue to add to list of allowed in DB<br><br>";
						$this->add_url_to_crawl_list($element->href);
					}
				}
				$this->html->removeNode('a');
				$this->html->removeNode('nav');

				$comp = "";
				$page_text = $this->html->plaintext; 
				$page_text = preg_replace('/\s\s+/', ' ', $page_text);
				// $page_text = preg_replace('/\s\s+/', ' ', $page_text);

				$title = $this->html->find("title", 0);
				if(isset($title))
					$comp .= " " . $title->content;
				// try() {

				// } catch () {

				// }
				$descr = $this->html->find("meta[name=description]", 0);
				if(isset($descr))
					$comp .= " " . $descr->content;

				$comp .= " " . $page_text;

				$exploded = explode(' ', $comp);
				$full = "";
				foreach($exploded as $exp) {
					$exp = preg_replace('/\s\s+/', ' ', $exp);
					if(strlen($exp) > 1) {
						$full .= $exp . " ";
					}
					
				}

				return $full;
			}
		}
		return false;
	}

	public function create_admin($username, $password, $email) {
		$this->db_connect();
		$this->db->create_admin($username, $password, $email);
	}

	public function check_login() {
		if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
			return 2;
		}
		else {
			$this->db_connect();
			if($this->db->check_table_not_empty('users') == false) {
				return 0;
			} else if($this->getLoginStatus() == false) {
				return 1;
			} else {
				$_SESSION["loggedin"] = true;
				return 2;
			}
		}

	}

	public function login($username, $password) {
		$this->db_connect();
		if( $this->db->login($username, $password) == true ) {
			$_SESSION["loggedin"] = true;
			$this->_loggedin = true;
		}
	}

	public function get_crawled_list() {
		$this->db_connect();
		return $this->db->get_crawled_list();
	}

	public function get_status_list() {
		$this->db_connect();
		return $this->db->get_status_list();
	}
	

	public function set_slds() {

		$address = 'http://mxr.mozilla.org/mozilla-central/source/netwerk/dns/effective_tld_names.dat?raw=1';
        $content = file($address);
        foreach($content as $num => $line)
        {
            $line = trim($line);
            if($line == '') continue;
            if(@substr($line[0], 0, 2) == '/') continue;
            $line = @preg_replace("/[^a-zA-Z0-9\.]/", '', $line);
            if($line == '') continue;  //$line = '.'.$line;
            if(@$line[0] == '.') $line = substr($line, 1);
            if(!strstr($line, '.')) continue;
            $this->subtlds[] = $line;
            //echo "{$num}: '{$line}'"; echo "<br>";
        }
        $this->subtlds = array_merge(Array(
            'co.uk', 'me.uk', 'net.uk', 'org.uk', 'sch.uk', 'ac.uk', 
            'gov.uk', 'nhs.uk', 'police.uk', 'mod.uk', 'asn.au', 'com.au',
            'net.au', 'id.au', 'org.au', 'edu.au', 'gov.au', 'csiro.au',
            ),$this->subtlds);

        $this->subtlds = array_unique($this->subtlds);
	}
}