<?php
	
  DEFINE('DB_USERNAME', 'root');
  DEFINE('DB_PASSWORD', 'root');
  DEFINE('DB_HOST', 'localhost');
  DEFINE('DB_DATABASE', 'crontest');

  $conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

  if (mysqli_connect_error()) {
    die('Connect Error ('.mysqli_connect_errno().') '.mysqli_connect_error());
  }


 
 $current_posts = 'SELECT * FROM wp_posts WHERE ID AND post_status ="publish" LIMIT 300';
 
 
 $current_postresult = $conn->query($current_posts);
 
 $current_postarray =array();
   
 while($liverow = $current_postresult->fetch_assoc()) {
        
 	$current_postarray[] = $liverow['ID'];
    
 }
		

$ids = join(',', array_map('intval', $current_postarray));  

$acfaddress = "SELECT * FROM wp_postmeta WHERE meta_key = 'address' AND post_id IN ($ids)";
	
$acfresult = $conn->query($acfaddress);


$address_array = array();
   
while($addressrow = $acfresult->fetch_assoc()) {
        
	//echo "<br/>Post ID: ".$addressrow["post_id"] . " Address: " . $addressrow["meta_value"]. "<br/>";
        
  $address_array[] = $addressrow["meta_value"];
    
}

//$address = join(',', $address_array);


// insert data into table
  

// batch geocode

$url = 'http://www.datasciencetoolkit.org/street2coordinates';

$ch = curl_init( $url );
# Setup request to send json via POST.
$payload = json_encode( $address_array );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Send request.
$result = curl_exec($ch);
curl_close($ch);
# Print response.
//echo $result;


// ^ any type of error logs or timeout issues?


$outputs= json_decode($result);

$outputs = (Array) $outputs; // already in an array here so try to chery pick from here instead of foreach below maybe? and also order by post id or something so they go back to the right places in db

foreach ( $outputs as $output ) {
			
	$latitude = $output->latitude;
	$longitude = $output->longitude;		
	$street_address = $output->street_address;
	$locality = $output->locality;
	$region = $output->region;
	
	echo $street_address."<br/>";
	echo $locality."<br/>";
	echo $region."<br/>";
	echo $latitude.", ";
	echo $longitude."<br/><br/>";		
	
								
}


// i could proably start with major cites with specific terms if the queries dont get too slow... at least for la, that way I could test one majr city and show results for an example


// acf might need keys too (long numbers) pull those those from origina and pass along to update for more accuracy, but these are on a different line...

// bring post_id, and meta_id

$sql = "UPDATE wp_postmeta SET  meta_value='g$$$' WHERE meta_id=32 AND meta_key='latitude' AND post_id='13'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}



$conn->close(); // this might need go to the end so mysql and insert in new data from geocode






// geocode

//$googleapi = 'AIzaSyBxrP4KivR3OsTV9Rvy8OuTv6PBJtjk4R4';

//echo $address."<br/><br/><br/>";

//$googlelist = str_replace([' ',', '], '+', $address);

//$url = 'https://maps.google.com/maps/api/geocode/json?address='.$googlelist.'&key='.$googleapi.'';




//echo $url;

/*
$geocode = file_get_contents($url);

if(google.maps.GeocoderStatus.OK){
  
	$output= json_decode($geocode);
	$latitude = $output->results[0]->geometry->location->lat;
	$longitude = $output->results[0]->geometry->location->lng; 
	
	// return the cleaned u address and replace maybe, also get single acfs to enter
	
	// thought:  I dont think I want to replace addresses, what if it returns a completely wrong address? how would I check for that

	echo $latitude.", ".$longitude;   
         
}
 
else {
  echo "<strong>ERROR: {$resp['status']}</strong>";
  return false;
}
*/



//$batch = '["'.$googlelist.'"]';

//echo $batch;


?>

