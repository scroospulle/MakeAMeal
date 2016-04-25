<?php
	//This is for getting Teams a user is part of
	//Session Boot
	session_start();
	$link = new mysqli("127.0.0.1","root","root");

	if ($link->connect_error)
	{
		die("Connection failed ".$conn->connect_error);
	}
	mysqli_select_db($link,"MakeAMeal");

	//Get HTTP response ready

	//Getting values
	$rid = $_POST["rid"];

	$stmt = $link->prepare("SELECT r.name, r.type, r.description, r.photo
                                FROM recipe as r
                                WHERE r.id = ?");
    $stmt->bind_param('i', $rid);
    $stmt->execute();
    $stmt->bind_result($name, $type, $description, $photo);
    $json = array();

    while ($stmt->fetch()) {
        $json[] = array(
		    'name' => $name,
            'type' => $type,
            'description' => $description,
            'photo' => $photo
            );
    }


    $jsonstring = json_encode($json);
	echo $jsonstring;

    mysqli_close($link);
?>