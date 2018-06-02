<?php
require("connectDB.php");
$db = get_db();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Topic Entry</title>
</head>

<body>
<div>

<h1>Enter New Scriptures and Topics</h1>

<form id="mainForm" action="teach06.php" method="POST">

  
    <input type="text" id="book" name="book" placeholder="book" />
	<br /><br />

	<input type="text" id="chapter" name="chapter" placeholder="chapter"/>
	<br /><br />

	<input type="text" id="verse" name="verse" placeholder="chapter"/>
	<br /><br />

	<textarea id="content" name="content" rows="5" cols="80"></textarea>
	<br /><br />

	<label>Topics:</label><br />

<?php
try
{
	
	$statement = $db->prepare('SELECT id, name FROM topic');
	$statement->execute();
	// Go through each result
	while ($row = $statement->fetch(PDO::FETCH_ASSOC))
	{
		$id = $row['id'];
		$name = $row['name'];
		// Notice that we want the value of the checkbox to be the id of the label
		echo "<input type='checkbox' name='topic[]' id='topic$id' value='$id'>";
		// Also, so they can click on the label, and have it select the checkbox,
		// we need to use a label tag, and have it point to the id of the input element.
		// The trick here is that we need a unique id for each one. In this case,
		// we use "chkTopics" followed by the id, so that it becomes something like
		// "chkTopics1" and "chkTopics2", etc.
		echo "<label for='topic$id'>$name</label><br />";
		// put a newline out there just to make our "view source" experience better
		echo "\n";
	}
}
catch (PDOException $ex)
{
	// Please be aware that you don't want to output the Exception message in
	// a production environment
	echo "Error connecting to DB. Details: $ex";
	die();
}
 
    $topicId = $db->lastInsertId("topic_id_seq");
    echo "<input type='checkbox' name='newTopic' id='topic$topicId' value='$topicId'>";
    
    if (isset($_POST['newTopic'])) {
    echo "<input type="text" id="name" name="name" placeholder="name"/>";
    }
?>
	<br />

	<input type="submit" value="Add to Database" />

</form>


</div>

</body>
</html>