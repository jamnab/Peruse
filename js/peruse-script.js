$(function(){
	
	//Hide the result list on load
	$('#headerright').hide();
	
	//Hide the result list on load
	$('#results-list').hide();
	
	//Click event when search button is pressed
	$('#search').click(function(){
		doSearch();
	});
	
	//Keypress event to see if enter was pressed in text input
	$('#text').keydown(function(e){
    	
    	if(e.keyCode == 13){
    		
    		doSearch();
			
			// Set it to an empty string
            $('#text').val("");
    	}
	});

	//$('#text').onwebkitspeechchange = function(e) {
    //console.log(e); // SpeechInputEvent
    	//doSearch();
	//};

});

function doSearch() {
	
	var searchText = $('#text').val();
	
	//Rehide the search results
	$('#headerright').hide();
	
	//Rehide the search results
	$('#results-list').hide();

	//resize maincont div
	//$('#maincont').hide();
	
	$.ajax({
		url: 'php/peruse-search.php',
		type: 'POST',
		data: {
			'text': searchText
		},
		beforeSend: function(){
			
			//Lets add a loading image
			$('#results-holder').addClass('loading');
			
		},
		success: function(data) {
			
			//Take the loading image away
			$('#results-holder').removeClass('loading');

			//change size of maincont
			var display = document.getElementById("maincont");
        	display.style.width = "calc( (100% / 3) * 2 )";
        	//display.style.display = "none";

			//Was everything successful, no errors in the PHP script
			if (data.success) {
				
				//Take the loading image away
				$('#blogs').empty();
				
				//Clear the results list
				$('#results-list').empty();

				//show the search results
				$('#headerright').show(250);

				//Check to see if there are any results to display
				if(data.results.length > 0) {
					
					//Display the last query
					$('#title').append("<a href=''><h1 id='topic_header'>"+searchText+"</h1></a><hr>");
				
				//Loop through each result and add it to the list
					$.each(data.results, function(){
						
						//Give the list element a rel with the data results ID incase we want to act on this later, like selecting from the list
						$('#results-list').append("<a href='" + this.link + "' target='display'><li><h2>" + this.title + "</h2></li></a><hr>");
					});
				
				} else {
					
					//If there are no results, inform the user - add 'no-results' class so we can style it differently
					$('#results-list').append("<li><h2>Your search did not return any results</h2></li>");
				}
				
				//Show the results list
				$('#results-list').fadeIn();
			
			} else {
				
				//Display the error message
				alert(data.error);
			}
		}
	});
}