<?php
    session_start();

    require 'checklogin.php';

    $error_msg = '';

    // Hier worden messages gepakt en gereset wanneer nodig.
    $notice_msg = empty($_SESSION['msg_notice']) ? '' : $_SESSION['msg_notice']; 
    $_SESSION['msg_notice'] = '';
    
    $succes_msg = empty($_SESSION['msg_succes']) ? '' : $_SESSION['msg_succes']; 
    $_SESSION['msg_succes'] = '';

    // Laad het database php script.
    $mysqli = '';
    require 'db.php';

    $sql = "SELECT * FROM bestellingen WHERE klant=" . $_SESSION['id'] . " ORDER BY datum DESC";
    $geschiedenis = $mysqli->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- De titel van de website -->
    <title>DRL Pizza | Profiel</title>

    <!-- Charset -->
    <meta charset="utf-8">

    <link rel="stylesheet" href="..\theme.css">
    <link rel="stylesheet" href="..\animate.css">
    <link rel="stylesheet" href="profielpagina.css">

    <!-- Import van de Roboto font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    
    <!-- Deze paar regels zorgen voor de fav icons -->
    <link rel="icon" type="image/png" sizes="32x32" href="../resources/fav-icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../resources/fav-icons/favicon-16x16.png">

    <!-- Latest compiled and minified CSS van boostrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme voor boostrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript voor boostrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
    <!-- De wrapper voor het menu aan de bovenkant -->
    <nav class="navbar">
        <!-- De container div die zorgt waarin de boostrap elementen komen. -->
        <div class="container">
            <!-- Word naar links getrokken -->
            <div class="pull-left">
                <ul>
                    <li id="logo"><a href="..\index.html">DRL PIZZAS</a></li>
                </ul>
            </div>

            <!-- Word naar rechts getrokken -->
            <div id="center" class="pull-right">
                <!-- Unondered list die de items bevat -->
                <ul>
                    <!-- De <a> word gebruikt om links mee te maken. -->
                    <li><a href="uitloggen.php">Uitloggen</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="venster">
        <div class="container">

            <div class="succes-box <?php if($succes_msg == '') echo 'hide'; ?>">
                <span><?php echo $succes_msg;?></span>
            </div>
            
            <div class="notice-box <?php if($notice_msg == '') echo 'hide'; ?>">
                <span><?php echo $notice_msg;?></span>
            </div>

            <div class="profiel">
                <div class="naam">
                    <h2>Welkom terug,</h2>
                    <span><?php echo $_SESSION['voornaam'] . ' ' . $_SESSION['achternaam']; ?></span>
                    <span class="id">#<?php echo $_SESSION['id']; ?></span>
                </div>

                <div class="menu clearfix">
                    <div class="stats">
                        <ul>
                            <li><img src="../resources/icons/kalender.png" style="height: 16px; width: 16px"> <span class="key">Geregistreerd op:</span> <span class="value"><?php echo $_SESSION['registratie'];?></span></li>
                            <li><img src="../resources/icons/aantal.png" style="height: 16px; width: 16px"> <span class="key">Aantal bestellingen:</span> <span class="value"><?php echo $_SESSION['bestellingen'];?></span></li>
                            <li><img src="../resources/icons/pizza.png" style="height: 16px; width: 16px"> <span class="key">Spaarpunten:</span> <span class="value"><?php echo $_SESSION['spaarpunten'];?></span></li>
                        </ul>
                    </div>
                    <div class="bestel">
                        <form action="bestellen.php" method="get">
                            <button type="submit" id="fbutton"><img style="height: 16px; width: 16px" src="http://wfarm2.dataknet.com/static/resources/icons/set112/54979173.png"> Nieuwe bestelling</button>
                        </form>
                    </div>
                </div>
                

                <div class="geschiedenis">
                    <h3>Geschiedenis</h3>
                    <?php if($geschiedenis->num_rows == 0) echo "<span style='color: green'>U heeft nog geen bestellingen geplaats.</span>"; ?>
                    
                    <div class="scroll <?php if($geschiedenis->num_rows == 0) echo "hide"; ?> ">
                        <table>
                            <tr>
                                <th>Bestelling</th>
                                <th>Afhalen</th>
                                <th>Adres</th>
                                <th>Prijs</th>
                                <th>Datum</th>
                                <th>Afgehandeld</th>
                            </tr>
                            <?php
                            
                                if($geschiedenis->num_rows == 0){
                                    // Origneel gebruik voor een bericht. Nu niet langer meer.
                                    // Misschien nog voor later gebruik.
                                } else {
                            
                                    while($rij = $geschiedenis->fetch_assoc()) {
                                        echo '<tr>';
                                
                                        echo "<td>" . $rij['bestelling'] . "</td>";
                                        if($rij['afhalen'] == '0'){
                                            echo "<td>" . "<span>Nee</span>" . "</td>";
                                        } else{
                                            echo "<td>" . "<span>Ja</span>" . "</td>";
                                        }
                                        
                                        echo "<td>" . $rij['adres'] . "</td>";
                                        echo "<td>€ " . $rij['totaalprijs'] . "</td>";
                                        echo "<td>" . $rij['datum'] . "</td>";

                                        if($rij['status'] == 'OPEN'){
                                            echo "<td>" . "<span class='nee'>Nee</span>" . "</td>";
                                        } else if($rij['status'] == 'GESLOTEN'){
                                            echo "<td>" . "<span class='ja'>Ja</span>" . "</td>";
                                        }
                                        else{
                                            echo "<td>" . "<span class='cancelled'>Cancelled</span>" . "</td>";
                                        }
                                        
                                        echo '</tr>';
                                    }
                                }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
