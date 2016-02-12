<?php
    ini_set('display_errors', 'On');
    $dateObject = new DateTime;
    $date = $dateObject->format("d-m-Y");
    require('classdefs.php');
    $handler = new DatabaseHandler("test.db",'trial')
?>
<!DOCTYPE html>
<html lang="en-gb">
    <head>
        <title>Alts Registration </title>
        <link rel="stylesheet" href="site.css" type="text/css" />
        <link rel="icon" type="image/png" href="/images/icons/favicon-32x32.png" sizes="32x32" />
        <link rel="icon" type="image/png" href="/images/icons/favicon-16x16.png" sizes="16x16" />
    </head>
    <body bgcolor="#AAAAAA">
        <div class="header">
            <table id='headertable'>
                <tr>
                    <th id="logo">
                        <a href="/"><image src="/images/logo.png" alt="Logo"></a>
                    </th>
                    <th>
                        <ul id="navlist">
                            <li class="navitem"><a href="/registration.php">Registration</a></li>
                            <li class="navitem"><a href="/search.php">Search</a></li>
                            <li class="navitem"><a href="/summary.php">Summary</a></li>
                            <li class="navitem"><a href="/phpadmin/phpliteadmin.php">Database&nbspTool</a></li>
                        </ul>
                    </th>
                </tr>
            </table>
            <span id="datecheck">
                <h3>Current Date: <?php echo $date;?> Is this correct? If not change your computers clock and restart the server.</h3>
            </span>
        </div>
