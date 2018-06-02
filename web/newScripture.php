<?php

// get the data from the POST
$newBook = $_POST['newBook'];
$newChapter = $_POST['newChapter'];
$newVerse = $_POST['newVerse'];
$newContent = $_POST['newContent'];
$newTopic = $_POST['newTopic'];


require("connectDB.php");
$db = get_db();
    
    $query = 'INSERT INTO scripture(book, chapter, verse, content) VALUES(:book, :chapter, :verse, :content)';
	$statement = $db->prepare($query);
	// Now we bind the values to the placeholders. This does some nice things
	// including sanitizing the input with regard to sql commands.
	$statement->bindValue(':book', $newBook);
	$statement->bindValue(':chapter', $newChapter);
	$statement->bindValue(':verse', $newVerse);
	$statement->bindValue(':content', $newContent);
	$statement->execute();
	// get the new id
	$scriptureId = $db->lastInsertId("scripture_id_seq");


//   
//    $statement = $db->prepare('INSERT INTO topic(name) VALUES(:name)');
//
//    $statement->bindValue(':name', $newTopic);
//    $statement->execute();
// 
// $topicId = $db->lastInsertId("topic_id_seq");
//		$statement = $db->prepare('INSERT INTO scripture_topic(scriptureid, topicid) VALUES(:scriptureId, :topicId)');
//		// Then, bind the values
//		$statement->bindValue(':scriptureId', $scriptureId);
//		$statement->bindValue(':topicId', $topicId);
//		$statement->execute();
//
header("Location: scriptureDetails.php");
die();
?>