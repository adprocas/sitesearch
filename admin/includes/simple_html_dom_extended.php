

<?php
session_start();
require_once('simple_html_dom.php');
require_once('../includes/db_class.php');

class simple_html_dom_extended extends simple_html_dom {

	// load html from file
	public function load_file()
	{
	    $args = func_get_args();
	    $this->load(call_user_func_array('file_get_contents', $args), true);
	    // Throw an error if we can't properly load the dom.
	    if (($error=error_get_last())!==null) {
	        $this->clear();
	        return false;
	    }
	    return true;
	}

	public function removeNode($selector)
	{
	    foreach ($this->find($selector) as $node)
	    {
	        $node->outertext = '';
	    }

	    $this->load($this->save());        
	}

}


?>