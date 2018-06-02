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
$q = 'INSERT INTO scripture(book, chapter, verse, content) VALUES(:newBook, :newChapter, :newVerse, :newContent)';
	$stmt = $db->prepare($q);
//	// Now we bind the values to the placeholders. This does some nice things
//	// including sanitizing the input with regard to sql commands.
	$stmt->bindValue(':newBook', $newBook);
	$stmt->bindValue(':newChapter', $newChapter);
	$stmt->bindValue(':newVerse', $newVerse);
	$stmt->bindValue(':newContent', $newContent);
	$stmt->execute();
//	// get the new id
//	$scriptureId = $db->lastInsertId("scripture_id_seq");


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