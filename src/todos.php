<?php
	require_once('database.php');
	
	if($_SERVER['REQUEST_METHOD'] === GET){
		// Get all todos query
		$todosQuery = "SELECT * FROM todos"; 
		
		// Get all todos
		$todos = mysqli_query($con,$todosQuery) or die("error"); // http://us3.php.net/manual/en/mysqli.query.php
		while ($todo = mysqli_fetch_assoc($todos)) {
		    $rows[] = $todo;
		}
		
		// Return todos
		print_r(json_encode($rows));
		
	} else if($_SERVER['REQUEST_METHOD'] === POST){	
		// Retrieve raw POST data from request. 
		$post_data = file_get_contents("php://input");
		 
		// Decode POST data into an associative array. 
		$post_data = json_decode($post_data, TRUE);
		
		// Set vars
		$title = $post_data['title'];
		$date = $post_data['date'];
		$completed = $post_data['completed'];
		
		// Query to insert todo
		$addTodoQuery = "INSERT INTO todos (title,date_added) VALUES ('$title','$date')";
		if(mysqli_query($con,$addTodoQuery)){
			$ID = mysqli_insert_id($con);
			$getTodoQuery = "SELECT * FROM todos WHERE ID='$ID'";
			$todos = mysqli_query($con,$getTodoQuery) or die("error"); // http://us3.php.net/manual/en/mysqli.query.php
			while ($todo = mysqli_fetch_assoc($todos)) {
			    $rows[] = $todo;
			}
			echo json_encode($rows[0]);
		} else {
			echo "Server Says: You Failed";
		}
		
	} else if ($_SERVER['REQUEST_METHOD'] === PUT){
		// Retrieve raw POST data from request. 
		$post_data = file_get_contents("php://input");
		 
		// Decode POST data into an associative array. 
		$post_data = json_decode($post_data, TRUE);
		
		// Set vars
		$title = $post_data['title'];
		$checked = $post_data['checked'];
		$ID = $post_data['ID'];
		$date_finished = $post_data['date_finished'];
		
		$updateTodoQuery = "UPDATE todos  SET title='$title', checked='$checked'";
		if($date_finished == 'nodate'){
    		$updateTodoQuery .= ", date_finished=NULL";
		} else {
    		$updateTodoQuery .= ", date_finished='$date_finished'";
		}
		$updateTodoQuery .= " WHERE ID='$ID'";
		
		if(mysqli_query($con,$updateTodoQuery)){
			$getTodoQuery = "SELECT * FROM todos WHERE ID='$ID'";
			$todos = mysqli_query($con,$getTodoQuery) or die("error"); // http://us3.php.net/manual/en/mysqli.query.php
			while ($todo = mysqli_fetch_assoc($todos)) {
			    $rows[] = $todo;
			}
			echo json_encode($rows[0]);
		} else {
			echo "Server Says: You Failed";
		}
		
	} else if ($_SERVER['REQUEST_METHOD'] === DELETE){
		// Set vars
		$ID = substr($_SERVER[REQUEST_URI], strrpos($_SERVER[REQUEST_URI], '/') + 1);
		
		// Delete
		$deleteTodoQuery = "DELETE FROM todos WHERE ID='$ID'";
		if(mysqli_query($con,$deleteTodoQuery)){
			echo "Server Says: Successfully Deleted";
		} else {
			echo "Server Says: You Failed";
		}
	} else {
		echo $_SERVER['REQUEST_METHOD'];
	}
?>