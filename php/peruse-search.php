<?php
	//Prepare an array to hold data we are going to send back to the jQuery
	$data = array(
		'results' => array(),
		'success' => false,
		'error' => '' 
	);
	//Has the text been posted?
	if (isset($_POST['text'])) {
		
		//Connect to the database
		require_once('db.inc.php');
		
		//Escape the text to prevent SQL injection
		$text = $db->real_escape_string($_POST['text']); //prevent sql injection
		
		//prepare input for multi-word search
		//$text = explode(" ", $text); //split the words
		$text = explode(" ", strtolower($text));
		$field1 = "lower(`title`)"; //convert title to lowercase
		$field2 = "`link`"; //declare link field variable

		//Run a LIKE query to search for titles that are like the entered text with multi-word option
		$q = "SELECT * FROM `coding` WHERE $field1 LIKE '%" . implode("%' AND $field1 LIKE '%", $text) . "%' OR $field2 LIKE '%" . implode("%' AND $field2 LIKE '%", $text) . "%' "; 
		
		//Run a LIKE query to search for titles that are like the entered text
		//$q = "SELECT * FROM `coding` WHERE upper(`title`) LIKE upper('%{$text}%') OR (upper(`link`) LIKE upper('%{$text}%'))";
		
		$result = $db->query($q);
		
		//Did the query complete successfully?
		if (!$result) {
			
			//If not add an error to the data array
			$data['error'] = "Could not query database for search results, MYSQL ERROR: " . $db->error;
		} else {
			
			//Loop through the results and add to the results array
			while ($row = $result->fetch_assoc()) {
				$data['results'][] = array(
					'title' => $row['title'],
					'link' => $row['link']
				);
			}
			//Everything went to plan so set success to true
			$data['success'] = true;
		}
	}
	//Set the content type for a json object and ensure charset is UTF-8. NOt utf8 otherwise it will not work in IE (Darn IE! >.<)
	header("Content-Type: application/json; charset=UTF-8");
	
	//json encode the data and cast to an object so we can reference items like this.id in the javascript instead of this['id'] etc.
	echo json_encode((object)$data);

?>