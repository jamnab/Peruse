<?php
$scrape = addslashes( $_GET['scrape'] );

if( !$scrape )
{
   die( "You need to define a URL to process." );
}
else if( substr($scrape,0,7) != "http://" )
{
   $scrape = 'http://'.$scrape;
}

# Use the Curl extension to query Google and get back a page of results
$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, $scrape);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$html = curl_exec($ch);
curl_close($ch);

# Create a DOM parser object
$dom = new DOMDocument();

# Parse the HTML from Peruse|.
# The @ before the method call suppresses any warnings that
# loadHTML might throw because of invalid HTML in the page.
@$dom->loadHTML($html);

// INSERT DB CODE HERE e.g.
  $host = "localhost";
  $user = "root";
  $password = "";
  $database = "peruse";

  $db_conn = mysql_connect($host, $user, $password) or die('error');
  mysql_select_db($database, $db_conn) or die(mysql_error());

# Iterate over all the <a> tags
foreach($dom->getElementsByTagName('a') as $url) {

        # Show the <a href>
    		$title = $url->nodeValue; // text
        $title = (string) $title; // Casts to string
        $title = str_replace("'", "''", $title);
    		
        $link = $url->getAttribute('href');
        $link = (string) $link; // Casts to string
        


          if (substr($link,0,7) == 'http://' ) {
            echo $title;
            echo "<br>";
            echo $link;
            echo "<hr>";
            $sql = mysql_query("INSERT INTO coding(title, link) VALUES ('".$title."', '".$link."')") or die(mysql_error());

          }
           else if (substr($link,0,7) != 'http://' ) {
              if (strpos($scrape,'.com') ) {
                echo $title;
                echo "<br>";
                //$excess = substr($scrape, 0, strpos($scrape, ".com") );
                //echo $excess.".com".$link;
                $excess = strpos($scrape, '.com');
                $scrape = substr($scrape, 0, $excess+4);
                //echo $excess;
                //$link = $scrape."/".$link;
                $link = $scrape.$link;
                echo $link;
                echo "<hr>";
                $sql = mysql_query("INSERT INTO coding(title, link) VALUES ('".$title."', '".$link."')") or die(mysql_error());
              }
              else if (strpos($scrape,'.net/') ) {
                echo $title;
                echo "<br>";
                //$excess = substr($scrape, 0, strpos($scrape, ".com") );
                //echo $excess.".com".$link;
                $excess = strpos($scrape, '.net/');
                $scrape = substr($scrape, 0, $excess+5);
                //echo $excess;
                echo $scrape.$link;
                echo "<hr>";
                //$sql = mysql_query("INSERT INTO coding(title, link) VALUES ('".$title."', '".$link."')") or die(mysql_error());
              }
              else if (strpos($scrape,'.org') ) {
                echo $title;
                echo "<br>";
                //$excess = substr($scrape, 0, strpos($scrape, ".com") );
                //echo $excess.".com".$link;
                //$excess = strpos($scrape, '.org');
                //$scrape = substr($scrape, 0, $excess+4);
                //echo $excess;
                //$link = $scrape."/".$link;
                //$link = $scrape.$link;
                echo $link;
                echo "<hr>";
                //$sql = mysql_query("INSERT INTO coding(title, link) VALUES ('".$title."', '".$link."')") or die(mysql_error());
              }
        }
       
    }

?>