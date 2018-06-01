<?php

require("connectDB.php");
$db = get_db();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Scripture and Topic List</title>
</head>

<body>
<div>

<h1>Scripture and Topic List</h1>

<?php
try
{
	
	// prepare the statement
	$statement = $db->prepare('SELECT id, book, chapter, verse, content FROM scripture');
	$statement->execute();
	// Go through each result
	while ($row = $statement->fetch(PDO::FETCH_ASSOC))
	{
		echo '<p>';
		echo '<strong>' . $row['book'] . ' ' . $row['chapter'] . ':';
		echo $row['verse'] . '</strong>' . ' - ' . $row['content'];
		echo '<br />';
		echo 'Topics: ';
		// get the topics now for this scripture
		$stmtTopics = $db->prepare('SELECT name FROM topic t'
			. ' INNER JOIN scripture_topic st ON st.topicid = t.id'
			. ' WHERE st.scriptureid = :scriptureid');
		$stmtTopics->bindValue(':scriptureid', $row['id']);
		$stmtTopics->execute();
		// Go through each topic in the result
		while ($topicRow = $stmtTopics->fetch(PDO::FETCH_ASSOC))
		{
			echo $topicRow['name'] . ' ';
		}
		echo '</p>';
	}
}
catch (PDOException $ex)
{
	echo "Error with DB. Details: $ex";
	die();
}
?>

</div>
<div>
  <form name="newScriptureForm" action="scriptureDetails.php">
   <label>New Topic:</label>
    <input type='checkbox' name='newTopicCheck' id='newTopicCheck'>
    <input type="text" id="" name="newTopic" placeholder="New Topic"/>
	<br /><br />
	<input type="text" id="newBook" name="newBook" placeholder="book" />
	<br /><br />

	<input type="text" id="newChapter" name="newChapter" placeholder="chapter"/>
	<br /><br />

	<input type="text" id="newVerse" name="newVerse" placeholder="chapter"/>
	<br /><br />

	<textarea id="newContent" name="newContent" rows="5" cols="80"></textarea>
	<br /><br />
	<input type="submit" name="submit" value="Add New Scripture">
</form>
</div>

   <?php

// get the data from the POST
$book = $_POST['newBook'];
$chapter = $_POST['newChapter'];
$verse = $_POST['newVerse'];
$content = $_POST['newContent'];
$newTopic = $_POST['newTopic'];

require("connectDB.php");
$db = get_db();
    
    $query = 'INSERT INTO scripture(book, chapter, verse, content) VALUES(:book, :chapter, :verse, :content)';
	$statement = $db->prepare($query);
	// Now we bind the values to the placeholders. This does some nice things
	// including sanitizing the input with regard to sql commands.
	$statement->bindValue(':book', $book);
	$statement->bindValue(':chapter', $chapter);
	$statement->bindValue(':verse', $verse);
	$statement->bindValue(':content', $content);
	$statement->execute();
	// get the new id
	$scriptureId = $db->lastInsertId("scripture_id_seq");
    
    
    $query = 'INSERT INTO topic(name) VALUES(:name)';
	$statement = $db->prepare($query);
    $statement->bindValue(':name', $newTopic);
    $statement->execute();
    
	// Now go through each topic id in the list from the user's checkboxes
	foreach ($topicIds as $topicId)
	{
		$statement = $db->prepare('INSERT INTO scripture_topic(scriptureid, topicid) VALUES(:scriptureId, :topicId)');
		// Then, bind the values
		$statement->bindValue(':scriptureId', $scriptureId);
		$statement->bindValue(':topicId', $topicId);
		$statement->execute();
	}
}
catch (Exception $ex)
{
	// Please be aware that you don't want to output the Exception message in
	// a production environment
	echo "Error with DB. Details: $ex";
	die();
}


</body>
</html>