<?php

header("Content-Type:applicaton/json");

//Database variables
define("HOST", "localhost");
define("USER", "lampuser");
define("PASSWORD", "password");
define("DATABASE", "lamp2proj1");

//Ensure there is a GET string in the URL
if(isset($_GET['action'])){

    //Connection data
    $conn = new mysqli(HOST, USER, PASSWORD, DATABASE);

    if($conn->connect_error){
        die("Could not connect: " . $conn->connect_error);
    }

    //Load data base items.
    if($_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['action'] == 'list'){
        toDoList($conn);
    }
    else if($_SERVER['REQUEST_METHOD'] == 'POST' && $_GET['action'] == 'newTask'){
        // $a = $_POST;
        // echo json_encode($_POST);
         // newEntry($conn);

        // This won't run if it is it's own function.
        $description = $_POST['desc'];
        $priority = (int)$_POST['priority'];
        date_default_timezone_get("America/Toronto");
        $date = date("Y-m-d H:i:s");
        $completed = 0;

        $q1 = sprintf("INSERT INTO task 
                        (description, priority, dateCreated, completed, dateCompleted) 
                        VALUES ('%s', '%d', '%s', '%d', '%s')",
                        $description, $priority, $date, $completed, $date);

        $qRs = $conn->query($q1);

        $id = $conn->insert_id;

        $jsonArray = array(
            'id' => $id,
            'description' => $description,
            'priority' => $priority,
            'dateCreated' => $date
            );

        echo json_encode($jsonArray);

    }

    $conn->close();
}//End isset if


    function toDoList($connection){
         $listArray = array();

         //Select String
         $q = "SELECT description, priority, dateCreated, completed, dateCompleted FROM task ORDER BY priority desc";

         //Get results
         $results = $connection->query($q);

         while($info = $results->fetch_assoc()){
             //Get each task from the DB
             array_push($listArray, $info);
         }//End while

         $results->close();
        echo json_encode($listArray);
    }//End toDoList

    function newEntry($connection){

        $task = $_POST['desc'];
        $priority = (int)$_POST['priority'];
        $date = date("Y-m-d H:i:s");
        $completed = 0;

        $q1 = sprintf("INSERT INTO task 
                        (description, priority, dateCreated, completed, dateCompleted) 
                        VALUES ('%s', '%d', '%s', '%d', '%s')",
                        $task, $priority, $date, $completed, $date);

        $qRs = $conn->query($q1);

        $id = $connection->insert_id;

        $jsonArray = array(
            'id' => $id,
            'task' => $task,
            'priority' => $priority
            );

        echo json_encode($jsonArray);

    }//End newTask
?>