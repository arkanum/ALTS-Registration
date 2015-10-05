<?php
    require_once('header.php');
    require_once('connect.php');
?>
<div class="main">
	<div class="summary">
		<h2> Today </h2>
		<h4> Number of people attending on <?php echo $date;?> :</h4>
		<p style="font-size:48px;margin:0;"><?php
			$numOfPeopleToday = 0;
			$todayQuery = $db->query("SELECT dateArray FROM ".$members.";");
			while ($row = $todayQuery->fetchArray(SQLITE3_ASSOC)){
				$tmp = new dateList($row['dateArray']);
				((end($tmp->list)==$date) ? $numOfPeopleToday++ : 0 );
			}
			echo $numOfPeopleToday;

		?></p>


	</div>
</div>
<?php
    require_once('footer.php');
?>