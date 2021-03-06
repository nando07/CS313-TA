<?php

// get the data from the POST
$book = $_POST['book'];
$chapter = $_POST['chapter'];
$verse = $_POST['verse'];
$content = $_POST['content'];
$topicIds = $_POST['topic'];
$newTopic = $_POST['newName'];
$newCheck = $_POST['newTopic'];


require("connectDB.php");
$db = get_db();
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
    
    $statement = $db->prepare('INSERT INTO topic(name) VALUES(:name)');
    $statement->bindValue(':name', $newTopic);
    $statement->execute();
    $scriptureId = $db->lastInsertId("scripture_id_seq");
    $statement = $db->prepare('INSERT INTO scripture_topic(scriptureid, topicid) VALUES(:scriptureId, :topicId)');
		// Then, bind the values
		$statement->bindValue(':scriptureId', $scriptureId);
		$statement->bindValue(':topicId', $newCheck);
		$statement->execute();
    
	// get the new id

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
    echo $newTopic;
	die();
}
// finally, redirect them to a new page to actually show the topics
//header("Location: teach06form.php");
//die(); // we always include a die after redirects. In this case, there would be no
//       // harm if the user got the rest of the page, because there is nothing else
//       // but in general, there could be things after here that we don't want them
//       // to see.
?>