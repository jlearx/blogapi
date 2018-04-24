<?php

class BlogAPI {
	// The HTTP method this request was made in, either GET, POST, PUT or DELETE
	protected $method = "";
	
	// The Model requested in the URI. eg: /posts
	protected $endpoint = "";
	
	// Stores the input of the POST request
	protected $post = Null;
		
	// Constructor
	public function __construct($request, $origin) {
		// Allow requests from any origin to be processed by this page
		header("Access-Control-Allow-Orgin: *");
		// Allow for any HTTP method to be accepted
		header("Access-Control-Allow-Methods: *");
		header("Content-Type: application/json");
		
		// Determine the enpoint specified in the request
        $req_arr = explode('/', rtrim($request, '/'));		
        $this->endpoint = array_shift($req_arr);
		
		// Determine the HTTP method. Some methods are hidden.
		$this->method = $_SERVER['REQUEST_METHOD'];
		
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new Exception("Unexpected Header");
            }
        }
		
		// Parse and clean the data source
        switch($this->method) {
			case 'DELETE':
			case 'POST':
				$this->request = $this->_cleanInputs($_POST);
				$this->post = file_get_contents("php://input");				
				break;
			case 'GET':
				$this->request = $this->_cleanInputs($_GET);
				break;
			case 'PUT':
				$this->request = $this->_cleanInputs($_GET);
				break;
			default:
				$this->_response('Invalid Method', 405);
				break;
        }
	}
	
	// Defines the post (POST request) endpoint
	protected function post() {
		if ($this->method == 'POST') {			
			$data = json_decode($this->post, true);

			// Check for JSON decode failure
			if ($data == NULL) {
				return Array('Result' => "Failure", 'Error' => "Post data not JSON encoded.");
			}
						
			// Make sure Title and Body are valid
			if (!array_key_exists('Title', $data)) {
				return Array('Result' => "Failure", 'Error' => "Title not set in Post data.");
			}
			
			if (!array_key_exists('Body', $data)) {
				return Array('Result' => "Failure", 'Error' => "Body not set in Post data.");
			}
			
			$title = $data['Title'];
			$body = $data['Body'];
			
			// Make sure Title and Body are not zero length
			if (empty($title)) {
				return Array('Result' => "Failure", 'Error' => "Title must not be empty in Post data.");
			}
			
			if (empty($body)) {
				return Array('Result' => "Failure", 'Error' => "Body must not be empty in Post data.");
			}
			
			// Open the database
			$blogdb = new BlogDB();
			
			// Exit if database not opened
			if (!$blogdb) {
				echo $blogdb->lastErrorMsg();
				exit;
			}
			
			// Insert into the database
			$sql =<<<EOF
				  INSERT INTO posts (title,body) VALUES ("$title","$body");
EOF;

			$ret = $blogdb->exec($sql);
			
			// Store the results
			$result = Array('Result' => "Success");
			
			if (!$ret) {
				$result = Array('Result' => "Failure", 'Error' => $blogdb->lastErrorMsg());			
			}
			
			// Close the database
			$blogdb->close();
			
			// Return the results
			return $result;
		} else {
			return Array('Result' => "Failure", 'Error' => "Only accepts POST requests.");
		}		
	}

	// Defines the posts (GET request) endpoint
	protected function posts() {
		if ($this->method == 'GET') {
			// Open the database
			$blogdb = new BlogDB();
			
			// Exit if database not opened
			if (!$blogdb) {
				echo $blogdb->lastErrorMsg();
				exit;
			}
			
			// Query the database
			$sql =<<<EOF
				SELECT * FROM posts;
EOF;
			
			$ret = $blogdb->query($sql);
			
			// Store the results
			$results = array();
			
			// For each database row, add it to the result set
			while($row = $ret->fetchArray(SQLITE3_ASSOC)) {
			  $results[] = $row;
			}
			
			// Close the database
			$blogdb->close();
			
			// Return the results
			return $results;
		} else {
			return Array('Result' => "Failure", 'Error' => "Only accepts GET requests.");
		}
	}
	
	// Calls the appropriate endpoint
    public function processAPI() {
        return $this->_response($this->{$this->endpoint}());	
    }
	
	// Returns the endpoint response to the client
    private function _response($data, $status = 200) {
		header('Content-type: application/json;charset=utf-8');
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
		
        return json_encode($data);
    }
	
	// Cleans the input data by stripping HTML and PHP tags
    private function _cleanInputs($data) {
        $clean_input = Array();
		
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
        } else {
            $clean_input = trim(strip_tags($data));
        }
		
        return $clean_input;
    }
	
	
	// Returns a text description based on the status code
    private function _requestStatus($code) {
        $status = array(  
            200 => 'OK',
            404 => 'Not Found',   
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
		
        return ($status[$code])?$status[$code]:$status[500]; 
    }	
}

class BlogDB extends SQLite3 {
	function __construct() {
		$this->open("blog.db");
	}
}

?>