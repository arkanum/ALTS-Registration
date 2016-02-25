<?php
    require_once('header.php');
?>
<div class="main">
	<div class="summary">
		<h2> Today </h2>
		<h4> Number of people attending on <?php echo $date;?> :</h4>
		<p style="font-size:48px;margin:0;"><?php
			$numOfPeopleToday = 0;
			$todayQuery = $handler->search(array("dateArray"), array());
			while ($row = $todayQuery->fetchArray(SQLITE3_ASSOC)){
				$tmp = new dateList($row['dateArray']);
				((end($tmp->list)==$date) ? $numOfPeopleToday++ : 0 );
			}
			echo $numOfPeopleToday;

		?></p>

		<h2>Numbers</h2>
		A .csv file of this data has been generated and is in the root folder.
		<table><tr class="tableheader"><th>Date</th><th>Number of People</th></tr>
		<?php
			//Generate an array of all of the dates
			$allDates = array();
			$query = $handler->search(array("dateArray"), array());
			while ($row = $query->fetchArray(SQLITE3_ASSOC)){
				$tmp = new dateList($row['dateArray']);
				$allDates = array_merge($allDates, $tmp->list);
			}

			//Generate histogram array
			$dateHistogram = array_count_values($allDates);
			foreach ($dateHistogram as $key => $value){
				echo '<tr class="table"><td>'.$key.'</td><td style="text-align: right;">'.$value.'</td></tr>';
			}

			//Generate a CSV for admin purposes.
			$fp = fopen('AttendanceHistogram.csv', 'w');
			foreach ($dateHistogram as $key => $value) {
			    fputcsv($fp, array($key,$value));
			}
			fclose($fp);
		?>
		</table>


	</div>
</div>
<?php
    require_once('footer.php');
?>
