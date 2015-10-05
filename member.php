<?php
    require_once('header.php');
    require_once('connect.php');
?>
<div class="main">
	<h2>Member Infomation</h2>
	<?php

		if (isset($_GET['id']) && isset($_POST['id'])){
			if (isset($_POST['comitteecheckbox'])){
				$db->exec("UPDATE ".$members." SET comittee=1 WHERE id=".$_POST['id'].";");
				echo '<META http-equiv="refresh" content="1">';
			}elseif (!(isset($_POST['comitteecheckbox']))){
				$db->exec("UPDATE ".$members." SET comittee=0 WHERE id=".$_POST['id'].";");
				echo '<META http-equiv="refresh" content="1">';
			}
		}elseif (isset($_GET['id'])){
			echo '<div id="member">';
			$memberQuery = $db->query("SELECT * FROM ".$members." WHERE id=".$_GET['id'].";");
			if ($memberQuery instanceof SQLite3Result){
				$memberResult = $memberQuery->fetchArray(SQLITE3_ASSOC);
				$datesAttended = new dateList($memberResult['dateArray']);
				//var_dump($datesAttended->list);
				echo '<table>';
				echo '<tr class="memberentry"><th>First Name</th><td>'.$memberResult['firstName'].'</td></tr>';
				echo '<tr class="memberentry"><th>Last Name</th><td>'.$memberResult['lastName'].'</td></tr>';
				echo '<tr class="memberentry"><th>Bod Card Number</th><td>'.$memberResult['cardno'].'</td></tr>';

				echo '<tr class="memberentry"><th>Sessions Attended<br>Total : '.$memberResult['sessionsAttended'].'</th><td>';
				foreach ($datesAttended->list as $item) {
					echo "$item <br>";
				}
				echo '</td></tr>';

				if ($memberResult['comittee']){
					$comitteeboxchecked = 'checked="checked"';
				}else{
					$comitteeboxchecked = '';
				}
				echo '<tr class="memberentry"><th>Committee</th><td><form method="post" action=""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$memberResult['id'].'"/><input type="checkbox" value="1" name="comitteecheckbox" id="comitteecheckbox"'.$comitteeboxchecked.'/><input type="submit" value="Update"></form></td></tr>';
				
				echo '</table>';
			}

			echo '</div>';
		}else{
			echo '<h3>Something went wrong, you got to the page in a wrong way. Go back to the search page.</h3>';
			echo '<meta http-equiv="refresh" content="5;url=/search.php"/>';
		}


	?>

</div>

<?php
    require_once('footer.php');
?>