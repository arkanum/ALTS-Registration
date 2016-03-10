<?php
    require_once('header.php');
?>
<div class="main">
	<div class="summary">
		<h2> Today </h2>
		<h4> Number of people attending on <?php echo $date;?> :</h4>
		<p style="font-size:48px;margin:0;">
		<?php
			$count = 0;
			$committeecount = 0;
			$todayQuery = $handler->search(array("dateArray", "committee"), array());
			while ($row = $todayQuery->fetchArray(SQLITE3_ASSOC)){
				$tmp = new dateList($row['dateArray']);
				// ((end($tmp->list)==$date) ? $numOfPeopleToday++ : 0 );
				if (end($tmp->list)==$date && $row['committee']==1){
					$committeecount++;
					$count++;
				}elseif (end($tmp->list)==$date){
					$count++;
				}
			}
			echo '<p style="font-size:48px;margin:0;">'.$count."</p>";
			echo '<h5>Of which, <b style="font-size:24px">'.$committeecount."</b> are on the committee.</h5>";

		?>

		<h2>Numbers</h2>
		A .csv file of this data has been generated and is in the root folder or <h5 style="display:inline;color:#0083AF;"><a href="AttendanceHistogram.csv">here</a></h5>.
		<table><tr class="tableheader"><th>Date</th><th>Number of People</th><th>Number of Committee</th></tr>
		<?php
			//Generate an array of all of the dates
			$allDates = array();
			$committeeDates = array();
			$query = $handler->search(array("dateArray", "committee"), array());
			while ($row = $query->fetchArray(SQLITE3_ASSOC)){
				$tmp = new dateList($row['dateArray']);
				$allDates = array_merge($allDates, $tmp->list);
				if ($row['committee']==1){
					$committeeDates = array_merge($committeeDates, $tmp->list);
				}
			}

			//Generate histogram array
			$dateHistogram = array_count_values($allDates);
			uksort($dateHistogram, "datecompare");
			$committeeDateHistogram = array_count_values($committeeDates);
			foreach ($dateHistogram as $key => $value){
				echo '<tr class="table memberlist"><td>'.$key.'</td><td style="text-align: right;">'.$dateHistogram[$key].'</td><td style="text-align: right;"">'.$committeeDateHistogram[$key].'</td></tr>';
			}

			//Generate a CSV for admin purposes.
			$fp = fopen('AttendanceHistogram.csv', 'w');
			fputcsv($fp, array("Date","Number of People","Number of Committee"));
			foreach ($dateHistogram as $key => $value) {
			    fputcsv($fp, array($key,$dateHistogram[$key],$committeeDateHistogram[$key]));
			}
			fclose($fp);
		?>
		</table>


	</div>
</div>
<?php
    require_once('footer.php');
?>
