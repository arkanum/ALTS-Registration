<?php
    require_once('header.php');
    require_once('connect.php');
?>
<div class="main">
    <div id="regisering">
    <?php
    //$debug = 1;

    class dateList {
        
        var $list;
        var $currentDate;

        function dateList($serial = null){ 
            if($serial!=null){//Unserializing serialized null yeilds flase not null.
                $this->list = unserialize($serial); 
            }else{
                $this->list = array();
            }
            $this->currentDate = $GLOBALS['date'];
        }
        function updateList(){
            if(in_array($this->currentDate, $this->list)){
                return 0;
            }else{
                $this->list[] = $this->currentDate;
                return 1;
            }
        }

        function outputForStorage(){
            return serialize($this->list);
        }

    }


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
                            $memberQuery = $db->query('SELECT firstName, lastName, dateArray FROM '.$members.' WHERE id='.$_POST['id'].';');
                            if ($memberQuery instanceof SQLite3Result){
                                $memberResult = $memberQuery->fetchArray(SQLITE3_ASSOC);
                                $tempDateArray = new dateList($memberResult['dateArray']);
                                if ($tempDateArray->updateList()){
                                    $stmt = $db->prepare('UPDATE '.$members.' SET sessionsAttended = sessionsAttended + 1, dateArray =:array WHERE id='.$_POST['id'].';');
                                    $stmt->bindParam(':array',$tempDateArray->outputForStorage());
                                    if ($stmt->execute()){
                                        echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                                    }else{
                                        echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                                    }
                                }else{
                                    echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' has already been marked as attending today.</h4>';
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
                            $memberQuery = $db->query('SELECT id, firstName, lastName, dateArray FROM '.$members.' WHERE cardno='.$_POST['cardno'].';');
                            if ($memberQuery instanceof SQLite3Result){
                                $memberResult = $memberQuery->fetchArray(SQLITE3_ASSOC);
                                $tempDateArray = new dateList($memberResult['dateArray']);
                                if ($tempDateArray->updateList()){
                                    $stmt = $db->prepare('UPDATE '.$members.' SET sessionsAttended = sessionsAttended + 1, dateArray =:array WHERE id='.$memberResult['id'].';');
                                    $stmt->bindParam(':array',$tempDateArray->outputForStorage());
                                    if ($stmt->execute()){
                                        echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                                    }else{
                                        echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                                    }
                                }else{
                                    echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' has already been marked as attending today.</h4>';
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
                    $countQuery = $db->query('SELECT count(*) as count FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" COLLATE NOCASE;');
                    if ($countQuery instanceof SQLite3Result){
                        $countResult = $countQuery->fetchArray(SQLITE3_ASSOC);
                        if ($countResult['count']==1){  //Case for no clashes
                            $memberQuery = $db->query('SELECT id, firstName, lastName, dateArray FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" COLLATE NOCASE;');
                            if ($memberQuery instanceof SQLite3Result){
                                $memberResult = $memberQuery->fetchArray(SQLITE3_ASSOC);
                                $tempDateArray = new dateList($memberResult['dateArray']);
                                if ($tempDateArray->updateList()){
                                    $stmt = $db->prepare('UPDATE '.$members.' SET sessionsAttended = sessionsAttended + 1, dateArray =:array WHERE id='.$memberResult['id'].';');
                                    $stmt->bindParam(':array',$tempDateArray->outputForStorage());
                                    if ($stmt->execute()){
                                        echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                                    }else{
                                        echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                                    }
                                }else{
                                    echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' has already been marked as attending today.</h4>';
                                }
                            }
                        }elseif ($countResult['count']==0){ //Case for no results
                            echo '<h4>No matching results found.</h4>';
                        }else{  //Case for multiple results
                            $memberQuery = $db->query('SELECT id, firstName, lastName, cardno FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" COLLATE NOCASE;');
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
                    $countQuery = $db->query('SELECT count(*) as count FROM '.$members.' WHERE lastName="'.$_POST['lastName'].'"COLLATE NOCASE;');
                    if ($countQuery instanceof SQLite3Result){
                        $countResult = $countQuery->fetchArray(SQLITE3_ASSOC);
                        if ($countResult['count']==1){  //Case for no clashes
                            $memberQuery = $db->query('SELECT id, firstName, lastName, dateArray FROM '.$members.' WHERE lastName="'.$_POST['lastName'].'" COLLATE NOCASE;');
                            if ($memberQuery instanceof SQLite3Result){
                                $memberResult = $memberQuery->fetchArray(SQLITE3_ASSOC);
                                $tempDateArray = new dateList($memberResult['dateArray']);
                                if ($tempDateArray->updateList()){
                                    $stmt = $db->prepare('UPDATE '.$members.' SET sessionsAttended = sessionsAttended + 1, dateArray =:array WHERE id='.$memberResult['id'].';');
                                    $stmt->bindParam(':array',$tempDateArray->outputForStorage());
                                    if ($stmt->execute()){
                                        echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                                    }else{
                                        echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                                    }
                                }else{
                                    echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' has already been marked as attending today.</h4>';
                                }
                            }
                        }elseif ($countResult['count']==0){ //Case for no results
                            echo '<h4>No matching results found.</h4>';
                        }else{  //Case for multiple results
                            $memberQuery = $db->query('SELECT id, firstName, lastName, cardno FROM '.$members.' WHERE lastName="'.$_POST['lastName'].'" COLLATE NOCASE;');
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
                    $andCountQuery = $db->query('SELECT count(*) as count FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" AND lastName="'.$_POST['lastName'].'" COLLATE NOCASE;');
                    $orCountQuery = $db->query('SELECT count(*) as count FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" OR lastName="'.$_POST['lastName'].'" COLLATE NOCASE;');
                    if (($andCountQuery instanceof SQLite3Result) && ($orCountQuery instanceof SQLite3Result)){
                        $andCountResult = $andCountQuery->fetchArray(SQLITE3_ASSOC);
                        $orCountResult = $orCountQuery->fetchArray(SQLITE3_ASSOC);
                        if ($andCountResult['count']==1){//Case where andcount is 1
                            $memberQuery = $db->query('SELECT id, firstName, lastName, dateArray FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" AND lastName="'.$_POST['lastName'].'" COLLATE NOCASE;');
                            if ($memberQuery instanceof SQLite3Result){
                                $memberResult = $memberQuery->fetchArray(SQLITE3_ASSOC);
                                $tempDateArray = new dateList($memberResult['dateArray']);
                                if ($tempDateArray->updateList()){
                                    $stmt = $db->prepare('UPDATE '.$members.' SET sessionsAttended = sessionsAttended + 1, dateArray =:array WHERE id='.$memberResult['id'].';');
                                    $stmt->bindParam(':array',$tempDateArray->outputForStorage());
                                    if ($stmt->execute()){
                                        echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                                    }else{
                                        echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                                    }
                                }else{
                                    echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' has already been marked as attending today.</h4>';
                                }
                            }
                        }elseif ($andCountResult['count']>1){//Case where andcount >1
                            $memberQuery = $db->query('SELECT id, firstName, lastName, cardno FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" AND lastName="'.$_POST['lastName'].'" COLLATE NOCASE;');
                            echo '<table><tr><th>First Name</th><th>Last Name</th><th>Bod Card Number</th></tr>';
                            while ($row = $memberQuery->fetchArray(SQLITE3_ASSOC)){
                                    echo '<tr><td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['cardno'].'</td>';
                                    echo '<td><form method="post" action""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="memberbutton" value="Mark Attending"/></form></td>';
                            }
                            echo '</table>';
                            echo "\n";
                        }elseif ($orCountResult['count']>1){//Case orcount >1
                            $memberQuery = $db->query('SELECT id, firstName, lastName, cardno FROM '.$members.' WHERE firstName="'.$_POST['firstName'].'" OR lastName="'.$_POST['lastName'].'" COLLATE NOCASE;');
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
                $newDateArray = new DateList();
                $newDateArray->updateList();
                $stmt = $db->prepare("INSERT INTO ".$members." (firstName,lastName,cardno,sessionsAttended,comittee,dateArray) VALUES ('".$_POST['firstName']."','".$_POST['lastName']."',".$_POST['cardno'].",1,0,:array);");
                $stmt->bindParam(':array',$newDateArray->outputForStorage());
                if ($stmt->execute()){
                    echo "<h4>New Member ".$_POST['firstName']." ".$_POST['lastName']." added</h4>";
                }else{
                    echo "<h4>Failed to add new member. Try again maybe?</h4>";
                }
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
                <input type="text" name="newmember" value="1" style="visibility:hidden;display:none;"/>
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