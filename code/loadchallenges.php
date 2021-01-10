<?php

include "db_connection.php";
include "reading_challenge_widget.php";
include "functions.php";
session_start();
$year = $_GET['year'];
echo "<h4 style='color:maroon'>My Reading Challenge for $year</h4>";
create_widget($db, $_SESSION['userid'], $year);

echo "<h4 style='color:maroon'>Friends' Reading Challenges for $year";

$ids = get_friends_ids($db);
@$ids = split("x", $ids);

for($i=0;$i<sizeof($ids);$i++){
	create_widget($db, $ids[$i], $year);
}


?>