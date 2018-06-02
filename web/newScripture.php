<?php

// get the data from the POST
$newBook = $_POST['newBook'];
$newChapter = $_POST['newChapter'];
$newVerse = $_POST['newVerse'];
$newContent = $_POST['newContent'];
$newTopic = $_POST['newTopic'];


require("connectDB.php");
$db = get_db();
//    
try
{
	// Add the Scripture
	// We do this by preparing the query with placeholder values
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
header("Location: scriptureDetails.php");
die();
?>