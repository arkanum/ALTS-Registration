<?php
    require_once('header.php');
?>
<div class="main">
	<h2>Member Infomation</h2>
	<?php

		if (isset($_GET['id']) && isset($_POST['id'])){
			if (isset($_POST['committeecheckbox'])){
				if ($handler->update(array("committee"=>"1"), array("id" => $_POST['id']))){
					echo "<h4>Member's committee status has been updated</h4>";
				}else{
					echo "Updating the database failed."; //This line in theory should not run.
				}
			}elseif (!(isset($_POST['committeecheckbox']))){
				if ($handler->update(array("committee"=>"0"), array("id" => $_POST['id']))){
					echo "<h4>Member's committee status has been updated</h4>";
				}else{
					echo "Updating the database failed."; //This line in theory should not run.
				}
			}
		}
		if (isset($_GET['id'])){
			echo '<div id="member">';
			$memberResult = $handler->searchAND(array("*"),array("id" => $_GET['id']))->fetchArray(SQLITE3_ASSOC);
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

			if ($memberResult['committee']){
				$committeeboxchecked = 'checked="checked"';
			}else{
				$committeeboxchecked = '';
			}
			echo '<tr class="memberentry"><th>Committee</th><td><form method="post" action=""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$memberResult['id'].'"/><input type="checkbox" value="1" name="committeecheckbox" id="committeecheckbox"'.$committeeboxchecked.'/><input type="submit" value="Update"></form></td></tr>';

			echo '</table>';

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
