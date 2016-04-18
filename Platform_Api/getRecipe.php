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
	$ingredient = $_POST["ingredient"];

	printf($ingredient);

	$stmt = $link->prepare("SELECT r.id, r.name
                                FROM recipe as r, ingredient as i, recipe_ingred as ri 
                                WHERE r.id = ri.rid and i.id = ri.iid and i.name = ?");
    $stmt->bind_param('s', $ingredient);
    $stmt->execute();
    $stmt->bind_result($rid, $name);
    $json = array();

    while ($stmt->fetch()) {
        $rname = $name;
	    $link2 = new mysqli("127.0.0.1","root","root");
	    if ($link2->connect_error)
	    {
		    die("Connection failed ".$conn->connect_error);
	    }
	    mysqli_select_db($link2,"MakeAMeal");

        $stmt2 = $link2->prepare("SELECT i.name
                                 FROM recipe as r, ingredient as i, recipe_ingred as ri 
                                 WHERE r.id = ri.rid and i.id = ri.iid and r.id = ?");
        $stmt2->bind_param('i', $rid);
        $stmt2->execute();
        $stmt2->bind_result($iname);

        $json2 = array();

        while($stmt2->fetch())
	    {
		    $json2[]= array($iname);
	    }

        $json[] = array(
		    'rname' => $rname,
            'ingredlist' => $json2);

        $stmt2->close();
        $link2->close();
    }


    $jsonstring = json_encode($json);
	echo $jsonstring;

    mysqli_close($link);
?>