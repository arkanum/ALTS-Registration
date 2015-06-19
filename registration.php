<?php
    require_once('header.php');
    require_once('connect.php');
?>
<div class="main">
    <div id="regisering">
    <?php
        //Check to confirm POST was used as request method
        if ($_SERVER['REQUEST_METHOD']=='POST'){
            echo '<h2>Attendance</h2>';
            //Delineate member creation from attendace
            if (!isset($_POST['newmember'])){
                //Comitee button attendace
                if (isset($_POST['id'])){
                    //Check for conflicsts in id number. This whole thing should never go wrong as id should be unique.
                    $countQuery = $db->query('SELECT count(*) as count FROM '.$members.' WHERE id='.$_POST['id'].';');
                    if ($countQuery instanceof SQLite3Result){
                        $countResult = $countQuery->fetchArray(SQLITE3_ASSOC);
                        if ($countResult['count']==1){
                            $memberQuery = $db->query('SELECT firstName, lastName FROM '.$members.' WHERE id='.$_POST['id'].';');
                            if ($memberQuery instanceof SQLite3Result){
                                $memberResult = $memberQuery->fetchArray(SQLITE3_ASSOC);
                                if ($db->exec('UPDATE '.$members.' SET sessionsAttended = sessionsAttended + 1 WHERE id='.$_POST['id'].';')){
                                    echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                                }else{
                                    echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                                }
                            }
                        }
                    }
                }elseif ($_POST['cardno']!=''){
                    //Process for cardno clashes. This probably shouldn't happen, but i haven't made cardno unique so it may happen.
                    $countQuery = $db->query('SELECT count(*) as count FROM '.$members.' WHERE cardno='.$_POST['cardno'].';');      //Find number of entries with that cardno
                    if ($countQuery instanceof SQLite3Result){      
                        $countResult = $countQuery->fetchArray(SQLITE3_ASSOC);
                        if ($countResult['count']==1){  //Cases for only one result. Much the same as for id. This should not fail.
                            $memberQuery = $db->query('SELECT id, firstName, lastName FROM '.$members.' WHERE cardno='.$_POST['cardno'].';');
                            if ($memberQuery instanceof SQLite3Result){
                                $memberResult = $memberQuery->fetchArray(SQLITE3_ASSOC);
                                if ($db->exec('UPDATE '.$members.' SET sessionsAttended = sessionsAttended + 1 WHERE cardno='.$memberResult['id'].';')){
                                    echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                                }else{
                                    echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                                }
                            }
                        }elseif ($countResult['count']==0){ //Case for no results
                            echo '<h4>No matching results found.</h4>';
                        }else{      //Case for multiple results.
                            $memberQuery = $db->query('SELECT id, firstName, lastName, cardno FROM '.$members.' WHERE cardno='.$_POST['cardno'].';');
                            echo '<table><tr><th>First Name</th><th>Last Name</th><th>Bod Card Number</th></tr>';
                            while ($row = $memberQuery->fetchArray(SQLITE3_ASSOC)){
                                    echo '<tr><td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['cardno'].'</td>';
                                    echo '<td><form method="post" action""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="memberbutton" value="Mark Attending"/></form></td>';
                            }
                            echo '</table>';
                            echo "\n";
                        }
                    }
                }elseif (($_POST['firstName']!='') && ($_POST['lastName']=='')){ //firstName only searches
                    //firstName clash check
                    $countQuery = $db->query('SELECT count(*) as count FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'";');
                    if ($countQuery instanceof SQLite3Result){
                        $countResult = $countQuery->fetchArray(SQLITE3_ASSOC);
                        if ($countResult['count']==1){  //Case for no clashes
                            $memberQuery = $db->query('SELECT id, firstName, lastName FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'";');
                            if ($memberQuery instanceof SQLite3Result){
                                $memberResult = $memberQuery->fetchArray(SQLITE3_ASSOC);
                                if ($db->exec('UPDATE '.$members.' SET sessionsAttended = sessionsAttended + 1 WHERE cardno='.$memberResult['id'].';')){
                                    echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                                }else{
                                    echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                                }
                            }
                        }elseif ($countResult['count']==0){ //Case for no results
                            echo '<h4>No matching results found.</h4>';
                        }else{  //Case for multiple results
                            $memberQuery = $db->query('SELECT id, firstName, lastName, cardno FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'";');
                            echo '<table><tr><th>First Name</th><th>Last Name</th><th>Bod Card Number</th></tr>';
                            while ($row = $memberQuery->fetchArray(SQLITE3_ASSOC)){
                                    echo '<tr><td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['cardno'].'</td>';
                                    echo '<td><form method="post" action""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="memberbutton" value="Mark Attending"/></form></td>';
                            }
                            echo '</table>';
                            echo "\n";
                        }
                    }
                }elseif (($_POST['firstName']=='') && ($_POST['lastName']!='')){ //lastName only searches
                    //lastName clash check
                    $countQuery = $db->query('SELECT count(*) as count FROM '.$members.' WHERE lastName="'.$_POST['lastName'].'";');
                    if ($countQuery instanceof SQLite3Result){
                        $countResult = $countQuery->fetchArray(SQLITE3_ASSOC);
                        if ($countResult['count']==1){  //Case for no clashes
                            $memberQuery = $db->query('SELECT id, firstName, lastName FROM '.$members.' WHERE lastName="'.$_POST['lastName'].'";');
                            if ($memberQuery instanceof SQLite3Result){
                                $memberResult = $memberQuery->fetchArray(SQLITE3_ASSOC);
                                if ($db->exec('UPDATE '.$members.' SET sessionsAttended = sessionsAttended + 1 WHERE cardno='.$memberResult['id'].';')){
                                    echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                                }else{
                                    echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                                }
                            }
                        }elseif ($countResult['count']==0){ //Case for no results
                            echo '<h4>No matching results found.</h4>';
                        }else{  //Case for multiple results
                            $memberQuery = $db->query('SELECT id, firstName, lastName, cardno FROM '.$members.' WHERE lastName="'.$_POST['lastName'].'";');
                            echo '<table><tr><th>First Name</th><th>Last Name</th><th>Bod Card Number</th></tr>';
                            while ($row = $memberQuery->fetchArray(SQLITE3_ASSOC)){
                                    echo '<tr><td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['cardno'].'</td>';
                                    echo '<td><form method="post" action""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="memberbutton" value="Mark Attending"/></form></td>';
                            }
                            echo '</table>';
                            echo "\n";
                        }
                    }
                    
                }elseif (($_POST['firstName'] != '') && ($_POST['lastName'] != '')){ //firstName and lastName searches
                    $andCountQuery = $db->query('SELECT count(*) as count FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" AND lastName="'.$_POST['lastName'].'";');
                    $orCountQuery = $db->query('SELECT count(*) as count FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" OR lastName="'.$_POST['lastName'].'";');
                    if (($andCountQuery instanceof SQLite3Result) && ($orCountQuery instanceof SQLite3Result)){
                        $andCountResult = $andCountQuery->fetchArray(SQLITE3_ASSOC);
                        $orCountResult = $orCountQuery->fetchArray(SQLITE3_ASSOC);
                        if ($andCountResult['count']==1){//Case where andcount is 1
                            $memberQuery = $db->query('SELECT id, firstName, lastName FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" AND lastName="'.$_POST['lastName'].'";');
                            if ($memberQuery instanceof SQLite3Result){
                                $memberResult = $memberQuery->fetchArray(SQLITE3_ASSOC);
                                if ($db->exec('UPDATE '.$members.' SET sessionsAttended = sessionsAttended + 1 WHERE cardno='.$memberResult['id'].';')){
                                    echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                                }else{
                                    echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                                }
                            }
                        }elseif ($andCountResult['count']>1){//Case where andcount >1
                            $memberQuery = $db->query('SELECT id, firstName, lastName, cardno FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" AND lastName="'.$_POST['lastName'].'";');
                            echo '<table><tr><th>First Name</th><th>Last Name</th><th>Bod Card Number</th></tr>';
                            while ($row = $memberQuery->fetchArray(SQLITE3_ASSOC)){
                                    echo '<tr><td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['cardno'].'</td>';
                                    echo '<td><form method="post" action""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="memberbutton" value="Mark Attending"/></form></td>';
                            }
                            echo '</table>';
                            echo "\n";
                        }elseif ($orCountResult['count']>1){//Case orcount >1
                            $memberQuery = $db->query('SELECT id, firstName, lastName, cardno FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" OR lastName="'.$_POST['lastName'].'";');
                            echo '<table><tr><th>First Name</th><th>Last Name</th><th>Bod Card Number</th></tr>';
                            while ($row = $memberQuery->fetchArray(SQLITE3_ASSOC)){
                                    echo '<tr><td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['cardno'].'</td>';
                                    echo '<td><form method="post" action""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="memberbutton" value="Mark Attending"/></form></td>';
                            }
                            echo '</table>';
                            echo "\n";
                        }elseif ($orCountResult['count']==0){//Case both 0
                            echo '<h4>No matching results found.</h4>';
                        }
                    }
                }
            }else{
                echo "<h5>New Member</h5>";
            }
        }
    ?>
    </div>
    <div id="existingmember">
        <h2>Existing Memebers</h2>
        <div>
            <form method="post" action="">
                <table>
                    <tr><td>First Name:</td><td><input type="text" name="firstName" autocomplete="off"/></td></tr>
                    <tr><td>Last Name:</td><td><input type="text" name="lastName" autocomplete="off"/></td></tr>
                    <tr><td>Bod Card Number:</td><td><input type="text" name="cardno" autocomplete="off"/></td></tr>
                    <tr><td><input type="submit"></tr></td>
                </table>
            </form>
        </div>
    </div>
    <div id="comittee">
        <h2>Comitee Members</h2>
        <table>
            <?php
                $comitteeQuery = $db->query('SELECT id, firstName, lastName, cardno FROM '.$members.' WHERE comittee==1;');
                if ($comitteeQuery instanceof SQLite3Result){
                    while ($row = $comitteeQuery->fetchArray(SQLITE3_ASSOC)){
                           echo '<tr><td><form method="post" action""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="comitteebutton" value="'.$row['firstName'].' '.$row['lastName'].'"/></form></td></tr>';
                   }
                }
               echo "\n";
            ?>
        </table>
    </div>
    <div id="newmember">
        <h2>New Members</h2>
        <div>
            <form method="post" action="">
                <input type="text" name="newmember" value="true" style="visibility:hidden;display:none;"/>
                <table>
                    <tr><td>First Name:</td><td><input type="text" name="firstName" autocomplete="off"/></td></tr>
                    <tr><td>Last Name:</td><td><input type="text" name="lastName" autocomplete="off"/></td></tr>
                    <tr><td>Bod Card Number:</td><td><input type="text" name="cardno" autocomplete="off"/></td></tr>
                    <tr><td><input type="submit"></tr></td>
                </table>
            </form>
        </div>
    </div>
</div>

<?php
    require_once('footer.php');
?>