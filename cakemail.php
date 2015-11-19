<?php

	// Create connection
	$DBServer = 'localhost';
	$DBUser = 'root';
	$DBPass = 'cakemail';
	$DBName = 'cakemail';

	$conn = new mysqli($DBServer, $DBUser, $DBPass, $DBName);

	// Check connection
	if ($conn->connect_error) {
	  die("Failed to connect: " . $conn->connect_error);
	}


	// Create table todo_list
	$sql = "CREATE TABLE IF NOT EXISTS todo_list (
		item_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		description VARCHAR(4000) NOT NULL,
		date_added DATE,
		date_due DATE,
		status VARCHAR(1) -- 'N' = new, 'P' = in progress, 'C' = completed
		)";


	if ($conn->query($sql) === TRUE) {
		echo "Table todo_list created";
	} else {
		echo "Error creating table: " . $conn->error;
	}

	// retrieves todo list
	$conn->get('/cakemail/todo_list', function () use ($conn){

		$sql = "SELECT * FROM todo_list ORDER BY item_id";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				echo "item_id: " . $row["item_id"] . "<br>" .
					"description: " . $row["description"] . "<br>" . 
					"date_added: " . $row["date+added"] . "<br>" . 
					"date_due: " . $row["date_due"] . "<br>" . 
					"status: " . $row["status"] . "<br><br>";
			}
		} else {
			echo "0 results";
		}


		// $data = array();
		// foreach ($items as $item) {
		// 	$data[] = array(
		// 		'item_id' => $item->item_id,
		// 		'description' => $item->description,
		// 		'date_added' => $item->date_added,
		// 		'date_due' => $item->date_due,
		// 		'status' => $item->status
		// 		);
		// }

		// echo json_encode($data);

	});

	// search by item id
	$conn->get('/cakemail/todo_list/search/{item_id}', function ($item_id) use ($conn) {

		$sql = "SELECT * FROM todo_list WHERE item_id = $item_id";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			while ($row = $result->fetch_assoc()) {
				echo "item_id: " . $row["item_id"] . "<br>" .
					"description: " . $row["description"] . "<br>" . 
					"date_added: " . $row["date+added"] . "<br>" . 
					"date_due: " . $row["date_due"] . "<br>" . 
					"status: " . $row["status"] . "<br><br>";
			}
		} else {
			echo "0 results";
		
	        }
        });

	// add new item
	$conn->post('/cakemail/todo_list/new_item', function () use ($conn) {

		$sql = "INSERT INTO todo_list (item_id, description, date_added, date_due, status) 
		VALUES (DEFAULT, $description, $date_added, $date_due, $status)";
		//$result = $conn->query($sql);


		if ($result->success() === TRUE) {
			echo "Item added successfully";
		} else {
			echo "Error adding item: " . $conn->error;
		}
	});

	// updated list based on item id
	$conn->put('/cakemail/todo_list/update/{item_id}', function ($item_id) use ($conn) {
		
		
	});

	// delete item
	$conn->delete('/cakemail/todo_list/delete/{item_id:[0-9]+}', function ($item_id) use ($conn) {

		$sql = "DELETE FROM todo_list WHERE item_id = $item_id";
		$result = $conn->query($sql);

		if ($result->success() === TRUE) {
			echo "Row deleted successfully";
		} else {
			echo "Error deleting row: " . $conn->error;
		}
		
	});

	// delete list
	$conn->delete('/cakemail/todo_list/delete', function () use ($conn){

		$sql = "DROP TABLE todo_list";
		$result = $conn->query($sql);

		if ($result->success() === TRUE) {
			echo "List deleted successfully";
		} else {
			echo "Error deleting list: " . $conn->error;
		}
		
	});

$conn->close();
?> 
