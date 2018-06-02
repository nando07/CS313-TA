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

                <input type="text" id="chapter" name="chapter" placeholder="chapter" />
                <br /><br />

                <input type="text" id="verse" name="verse" placeholder="verse" />
                <br /><br />

                <textarea id="content" name="content" rows="5" cols="80"></textarea>
                <br /><br />

                <label>Topics:</label><br />

                <?php
    
    $counter = 0;
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
        $counter = $id + 1;
	}
}
catch (PDOException $ex)
{
	// Please be aware that you don't want to output the Exception message in
	// a production environment
	echo "Error connecting to DB. Details: $ex";
	die();
}

	
echo "<input type='checkbox' name='newTopic' id='topic$counter' value='$counter'>";
 echo "<input type='text' id='newName' name='newName' placeholder='name'/>";
    ?>
                    <input type="submit" onclick="myFunction()" value="Add to Database" />
                    <br /><br />


            </form>


       
       
        </div>

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
   
    </body>
    <script type="text/javascript">
   function myFunction() {     
        $.ajax({
type: "POST",
url: "teach06.php",
data: dataString,
cache: false,
success: function(html) {
alert(html);
}
});
   }
    </script>

    </html>