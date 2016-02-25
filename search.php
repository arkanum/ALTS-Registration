<?php
    require_once('header.php');
?>
<div class="main">
    <div id="searching">
    	<div>
    		<h2>Search</h2>
    		<form action="" method="post">
    			<table>
                    <tr><td>First Name:</td><td><input type="text" name="firstName" autocomplete="off"/></td></tr>
                    <tr><td>Last Name:</td><td><input type="text" name="lastName" autocomplete="off"/></td></tr>
                    <tr><td>Bod Card Number:</td><td><input type="text" name="cardno" autocomplete="off"/></td></tr>
                    <tr><td><input type="submit" value="Search"></tr></td>
                </table>
    		</form>
    	</div>

    </div>
    <br><br>
    <div id="results">
	    <?php

	    if ($_SERVER['REQUEST_METHOD']=='POST'){

	    }else{
	    	echo '<h2>Members</h2>';
	    	$userResult = $handler->search(array("id", "firstName", "lastName", "cardno", "dateArray"), array(),"ORDER BY lastName ASC");
	    		echo '<table><tr class="memberlist"><th>First Name</th><th>Last Name</th><th>Bod Card No.</th><th>Last Session Attended<th><th></th></tr>';
	    		while ($row = $userResult->fetcharray(SQLITE3_ASSOC)){
	    			$tempDate = new dateList($row['dateArray']);
	    			$lastSession = end($tempDate->list);
	    			echo '<tr class="memberlist">';
	    			echo '<td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['cardno'].'</td><td>'.$lastSession.'</td>';
	    			echo '<td><form method="get" action="member.php"><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="memberbutton" value="Select"/></form></td>';
	    			echo '</tr>';
	    		}
	    		echo '</table>';
	    }


    ?>
	</div>
</div>

<?php
    require_once('footer.php');
?>
