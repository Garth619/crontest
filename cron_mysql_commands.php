<?php
	
	// creation
	
	// sql to create table
  
	$sql = "CREATE TABLE MyGuests (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	firstname VARCHAR(30) NOT NULL,
	lastname VARCHAR(30) NOT NULL,
	email VARCHAR(50),
	reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
	)";
	
	if ($conn->query($sql) === TRUE) {
	    echo "Table MyGuests created successfully";
	} else {
	    echo "Error creating table: " . $conn->error;
	}

	// insert data into table
  
  $sql = "INSERT INTO MyGuests (firstname, lastname, email)
VALUES ('Garrett', 'Cullen', 'garrett@example.com')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
} 

	// last id
	
	$sql = "INSERT INTO MyGuests (firstname, lastname, email)
VALUES ('John', 'Doe', 'john@example.com')";

if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo "New record created successfully. Last inserted ID is: " . $last_id;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// add multiple values

 $sql = "INSERT INTO MyGuests (firstname, lastname, email)
VALUES ('John', 'Doe', 'john@example.com');";
$sql .= "INSERT INTO MyGuests (firstname, lastname, email)
VALUES ('Mary', 'Moe', 'mary@example.com');";
$sql .= "INSERT INTO MyGuests (firstname, lastname, email)
VALUES ('Julie', 'Dooley', 'julie@example.com')";

if ($conn->multi_query($sql) === TRUE) {
    echo "New records created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


	// prepare and bind, for high efficiency 
 
	$stmt = $conn->prepare("INSERT INTO MyGuests (firstname, lastname, email) VALUES (?, ?, ?)");
	$stmt->bind_param("sss", $firstname, $lastname, $email);
	
	// set parameters and execute
	$firstname = "yoooo";
	$lastname = "Doe";
	$email = "john@example.com";
	$stmt->execute();
	
	$firstname = "booooo";
	$lastname = "Moe";
	$email = "mary@example.com";
	$stmt->execute();
	
	$firstname = "truuuu";
	$lastname = "Dooley";
	$email = "julie@example.com";
	$stmt->execute();
	
	echo "New records created successfully";
	
	$stmt->close();  
	
	
	// select
	
	
	$sql = "SELECT meta_id, meta_key, meta_value FROM wp_postmeta";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<br/> meta : " . $row["meta_id"]. " - meta_key: " . $row["mata_value"]. " " . $row["mata_value"]. "<br>";
    }
	} else {
    echo "0 results";
	}

	// getting certain IDs
	
	// Posts 
 
 $live_posts = 'SELECT * FROM wp_posts WHERE ID IN (1, 2, 3)';
 $live_postresult = $conn->query($live_posts);
 
 if ($live_postresult->num_rows > 0) {
    // output data of each row
    while($liverow = $live_postresult->fetch_assoc()) {
        echo "<br/>Post ID: ".$liverow["ID"]." Status: ". $liverow["post_status"] . "";
    }
	} else {
    echo "0 results";
	} 
	
	
	// delete needs where or all will be deleted
	
	$sql = "DELETE FROM MyGuests WHERE id=3";

if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
} else {
    echo "Error deleting record: " . $conn->error;
}


// update needs Where
	
	
	$sql = "UPDATE MyGuests SET lastname='Yooooooo' WHERE id=2";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}	

// offset

$sql = "SELECT * FROM Orders LIMIT 10 OFFSET 15";

// or

$sql = "SELECT * FROM Orders LIMIT 15, 10";


?>