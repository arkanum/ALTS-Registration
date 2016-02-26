<?php
    require_once('header.php');
?>
<div class="main">
    <div id="registering">
    <?php
    //$debug = 1;

        //Check to confirm POST was used as request method
        if ($_SERVER['REQUEST_METHOD']=='POST'){
            echo '<h2>Attendance</h2>';
            //Delineate member creation from attendace
            if (!isset($_POST['newmember'])){
                //Committee button attendace
                if (isset($_POST['id'])){
                    //Check for conflicsts in id number. This whole thing should never go wrong as id should be unique.
                    $countResult = $handler->searchAND(array("count(*) as count"), array("id" => $_POST['id']))->fetchArray(SQLITE3_ASSOC);
                    if ($countResult['count']==1){
                        $memberResult = $handler->searchAND(array("firstName", "lastName", "dateArray", "sessionsAttended"),array("id" => $_POST['id']))->fetchArray(SQLITE3_ASSOC);
                        $tempDateArray = new dateList($memberResult['dateArray']);
                        if ($tempDateArray->updateList()){
                            if ($handler->update(array("sessionsAttended" => $memberResult['sessionsAttended'] + 1, "dateArray" => $tempDateArray->outputForStorage()), array("id" => $_POST['id']))){
                                echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                            }else{
                                echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                            }
                        }else{
                            echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' has already been marked as attending today.</h4>';
                        }
                    }else{
                        echo '<h1>This ID is not unique! Something has gone very wrong in the database! Please make sure that all IDs in the database are unique!!!</h1>';
                    }
                }elseif ($_POST['cardno']!=''){
                    $countResult = $handler->searchAND(array("count(*) as count"), array("cardno" => $_POST['cardno']))->fetchArray(SQLITE3_ASSOC);
                    if ($countResult['count']==1){  //Cases for only one result. Much the same as for id. This should not fail.
                        $memberResult = $handler->searchAND(array("id", "firstName", "lastName", "dateArray", "sessionsAttended"),array("cardno" => $_POST['cardno']))->fetchArray(SQLITE3_ASSOC);
                        $tempDateArray = new dateList($memberResult['dateArray']);
                        if ($tempDateArray->updateList()){
                            if ($handler->update(array("sessionsAttended" => $memberResult['sessionsAttended'] + 1, "dateArray" => $tempDateArray->outputForStorage()), array("id" => $memberResult['id']))){
                                echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                            }else{
                                echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                            }
                        }else{
                            echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' has already been marked as attending today.</h4>';
                        }
                    }elseif ($countResult['count']==0){ //Case for no results
                        echo '<h4>No matching results found.</h4>';
                    }else{      //Case for multiple results.
                        $memberQuery = $handler->searchAND(array("id", "firstName", "lastName", "cardno"),array("cardno" => $_POST['cardno']));
                        echo '<table><tr class="memberlist"><th>First Name</th><th>Last Name</th><th>Bod Card Number</th></tr>';
                        while ($row = $memberQuery->fetchArray(SQLITE3_ASSOC)){
                                echo '<tr class="memberlist"><td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['cardno'].'</td>';
                                echo '<td><form method="post" action=""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="memberbutton" value="Mark Attending"/></form></td>';
                        }
                        echo '</table>';
                        echo "\n";
                    }
                }elseif (($_POST['firstName']!='') && ($_POST['lastName']=='')){ //firstName only searches
                    //firstName clash check
                    $countResult = $handler->searchAND(array("count(*) as count"), array("firstName" => $_POST['firstName']), true)->fetchArray(SQLITE3_ASSOC);
                    if ($countResult['count']==1){  //Case for no clashes
                        $memberResult = $handler->searchAND(array("id", "firstName", "lastName", "dateArray", "sessionsAttended"),array("firstName" => $_POST['firstName']), true)->fetchArray(SQLITE3_ASSOC);
                        $tempDateArray = new dateList($memberResult['dateArray']);
                        if ($tempDateArray->updateList()){
                            if ($handler->update(array("sessionsAttended" => $memberResult['sessionsAttended'] + 1, "dateArray" => $tempDateArray->outputForStorage()), array("id" => $memberResult['id']))){
                                echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                            }else{
                                echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                            }
                        }else{
                            echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' has already been marked as attending today.</h4>';
                        }
                    }elseif ($countResult['count']==0){ //Case for no results
                        echo '<h4>No matching results found.</h4>';
                    }else{  //Case for multiple results
                        $memberQuery = $handler->searchAND(array("id", "firstName", "lastName", "cardno"),array("firstName" => $_POST['firstName']), true);
                        echo '<table><tr class="memberlist"><th>First Name</th><th>Last Name</th><th>Bod Card Number</th></tr>';
                        while ($row = $memberQuery->fetchArray(SQLITE3_ASSOC)){
                                echo '<tr class="memberlist"><td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['cardno'].'</td>';
                                echo '<td><form method="post" action=""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="memberbutton" value="Mark Attending"/></form></td></tr>';
                        }
                        echo '</table>';
                        echo "\n";
                    }
                }elseif (($_POST['firstName']=='') && ($_POST['lastName']!='')){ //lastName only searches
                    //lastName clash check
                    $countResult = $handler->searchAND(array("count(*) as count"),array("lastName" => $_POST['lastName']),true)->fetchArray(SQLITE3_ASSOC);
                    if ($countResult['count']==1){  //Case for no clashes
                        $memberResult = $handler->searchAND(array("id", "firstName", "lastName", "dateArray", "sessionsAttended"),array("lastName" => $_POST['lastName']), true)->fetchArray(SQLITE3_ASSOC);
                        $tempDateArray = new dateList($memberResult['dateArray']);
                        if ($tempDateArray->updateList()){
                            if ($handler->update(array("sessionsAttended" => $memberResult['sessionsAttended'] + 1, "dateArray" => $tempDateArray->outputForStorage()), array("id" => $memberResult['id']))){
                                echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                            }else{
                                echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                            }
                        }else{
                            echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' has already been marked as attending today.</h4>';
                        }
                    }elseif ($countResult['count']==0){ //Case for no results
                        echo '<h4>No matching results found.</h4>';
                    }else{  //Case for multiple results
                        $memberQuery = $handler->searchAND(array("id", "firstName", "lastName", "cardno"),array("lastName" => $_POST['lastName']), true);
                        echo '<table><tr class="memberlist"><th>First Name</th><th>Last Name</th><th>Bod Card Number</th></tr>';
                        while ($row = $memberQuery->fetchArray(SQLITE3_ASSOC)){
                                echo '<tr class="memberlist"><td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['cardno'].'</td>';
                                echo '<td><form method="post" action=""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="memberbutton" value="Mark Attending"/></form></td>';
                        }
                        echo '</table>';
                        echo "\n";
                    }
                }elseif (($_POST['firstName'] != '') && ($_POST['lastName'] != '')){ //firstName and lastName searches
                        $andCountResult = $handler->searchAND(array("count(*) as count"), array("firstName" => $_POST['firstName'], "lastName" => $_POST['lastName']),true)->fetchArray(SQLITE3_ASSOC);
                        $orCountResult = $handler->searchOR(array("count(*) as count"), array("firstName" => $_POST['firstName'], "lastName" => $_POST['lastName']),true)->fetchArray(SQLITE3_ASSOC);
                        if ($andCountResult['count']==1){//Case where andcount is 1
                            $memberResult = $handler->searchAND(array("id", "firstName", "lastName", "dateArray", "sessionsAttended"), array("firstName" => $_POST['firstName'], "lastName" => $_POST['lastName']),true)->fetchArray(SQLITE3_ASSOC);
                            $tempDateArray = new dateList($memberResult['dateArray']);
                            if ($tempDateArray->updateList()){ //Check if they have attended today
                                if ($handler->update(array("sessionsAttended" => $memberResult['sessionsAttended'] + 1, "dateArray" => $tempDateArray->outputForStorage()), array("id" => $memberResult['id']))){
                                    echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' marked as attending.</h4>';
                                }else{
                                    echo '<h1>Something went really wrong! Please record what happened and what you did before it happened and tell the IT Rep.';
                                }
                            }else{
                                echo '<h4>'.$memberResult['firstName'].' '.$memberResult['lastName'].' has already been marked as attending today.</h4>';
                            }
                        }elseif ($andCountResult['count']>1){//Case where andcount >1
                            $memberResult = $handler->searchAND(array("id", "firstName", "lastName", "cardno"), array("firstName" => $_POST['firstName'], "lastName" => $_POST['lastName']),true);
                            echo '<table><tr class="memberlist"><th>First Name</th><th>Last Name</th><th>Bod Card Number</th></tr>';
                            while ($row = $memberResult->fetchArray(SQLITE3_ASSOC)){
                                    echo '<tr class="memberlist"><td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['cardno'].'</td>';
                                    echo '<td><form method="post" action=""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="memberbutton" value="Mark Attending"/></form></td>';
                            }
                            echo '</table>';
                            echo "\n";
                        }elseif ($orCountResult['count']>1){//Case orcount >1
                            $memberResult = $handler->searchOR(array("id", "firstName", "lastName", "cardno"), array("firstName" => $_POST['firstName'], "lastName" => $_POST['lastName']),true);
                            echo '<table><tr class="memberlist"><th>First Name</th><th>Last Name</th><th>Bod Card Number</th></tr>';
                            while ($row = $memberResult->fetchArray(SQLITE3_ASSOC)){
                                    echo '<tr class="memberlist"><td>'.$row['firstName'].'</td><td>'.$row['lastName'].'</td><td>'.$row['cardno'].'</td>';
                                    echo '<td><form method="post" action=""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="memberbutton" value="Mark Attending"/></form></td>';
                            }
                            echo '</table>';
                            echo "\n";
                        }elseif ($orCountResult['count']==0){//Case both 0
                            echo '<h4>No matching results found.</h4>';
                        }
                }
            }else{
                $newDateArray = new DateList();
                $newDateArray->updateList();
                $stmt = $handler->db->prepare("INSERT INTO ".$handler->table." (firstName,lastName,cardno,sessionsAttended,committee,dateArray) VALUES ('".$_POST['firstName']."','".$_POST['lastName']."',".$_POST['cardno'].",1,0,:array);");
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
                    <tr><td><input type="submit" value="Search"></tr></td>
                </table>
            </form>
        </div>
    </div>
    <div id="committee">
        <h2>Commitee Members</h2>
        <table>
            <?php
                $committeeResult = $handler->searchAND(array("id", "firstName", "lastName"), array("committee" => 1));
                while ($row = $committeeResult->fetchArray(SQLITE3_ASSOC)){
                       echo '<tr><td><form method="post" action=""><input style="visibility:hidden;display:none;" type="text" name="id" value="'.$row['id'].'"/><input type="submit" class="committeebutton" value="'.$row['firstName'].' '.$row['lastName'].'"/></form></td></tr>';
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
                    <tr><td><input type="submit" value="Add Member"></tr></td>
                </table>
            </form>
        </div>
    </div>
</div>

<?php
    require_once('footer.php');
?>
