<?php
    session_start();

    require_once($_SERVER["DOCUMENT_ROOT"] . '/_preload.php');

    $_SESSION["nav_expanded"] = TRUE;
    $saved = FALSE;
    if (array_key_exists("Twitch", $_SESSION["functions"])) {
        $twitchKey = $_SESSION["functions"]["Twitch"];

        if(filesize($_SESSION["config"][$twitchKey . "_twitch_config_name"])) {
            $twitchUser = fileReadContentWithSeparator($_SESSION["config"][$twitchKey . "_twitch_config_name"], " #=# ");
        }else{
            $twitchUser = [];
        }

    }else{
        header("Refresh:0; url=/core.php");
        exit();
    }

    foreach($_POST as $key => $value){
        if( str_starts_with($key, "rmItem")){
            $number = str_replace("rmItem", "", $key);
            unset($twitchUser[$_POST['itemKey' . $number]]);
            writeConfigFileWithSeparator($twitchUser, $_SESSION["config"][$twitchKey . "_twitch_config_name"], " #=# ");
            $saved = TRUE;
        }
    }

    if (isset($_POST['save'])){
        $twitchUser = [];
        foreach($_POST as $key => $value){
            if( str_starts_with($key, "newItemKey") && ! empty($value) ){
                $number = str_replace("newItemKey", "", $key);
                $twitchUser[$value] = $_POST["newItem" . $number];
            }
            if( str_starts_with($key, "itemKey") && ! empty($value) ){
                $number = str_replace("itemKey", "", $key);
                $twitchUser[$value] = $_POST["item" . $number];
            }
        }
        $_SESSION["config"][$twitchKey . "_twitch_api_client_id"] = $_POST['twitch_api_client_id'];
        $_SESSION["config"][$twitchKey . "_twitch_api_client_oauth_token"] = $_POST['twitch_api_client_oauth_token'];
        $_SESSION["config"][$twitchKey . "_twitch_server_group"] = $_POST['twitch_server_group'];

        writeConfigFileWithSeparator($twitchUser, $_SESSION["config"][$twitchKey . "_twitch_config_name"], " #=# ");

        saveConfig($_SESSION["config"], $_SESSION["configPath"]);
        $saved = TRUE;
    }
    print_r($_POST);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Funktion - Twitch Verbindung</title>
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php
            require_once($_SERVER["DOCUMENT_ROOT"] . '/_nav-header.php');
        ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php
                    require_once($_SERVER["DOCUMENT_ROOT"] . '/_nav.php');
                ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Twitch Controller</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item">Settings</a></li>
                            <li class="breadcrumb-item">Funktionen</li>
                            <li class="breadcrumb-item active">Twitch Controller</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-area mr-1"></i>
                                Settings
                            </div>
                            <?php if($saved) { ?>
                            <br>
                            <div class="col-md-3"></div>
                            <div id="savedDiv" class="row saved-row">
                                <label class="saved-label">Config gespeichert. Bitte den Bot neustarten! Oder '!botconfigreload' dem bot schreiben!</label>
                            </div>
                            <br>
                            <?php }?>
                            <br>
                            <div style="align-self: center;">
                                <div class="col-sm-4"></div>
                                <div class="row" >
                                    <h4>Wie erstelle ich einen token und woher bekomme ich eine client id?</h4>
                                </div>
                                <div >
                                    <p>1. Gehe auf die Seite <a href="https://twitchtokengenerator.com/">https://twitchtokengenerator.com/</a> (dieser Dienst wird nicht von capdeveloping betrieben!!! Nutzen auf eigene Gefahr!)</p>
                                    <p>2. Wähle Custom Scope aus. Da der Bot nur die Channel Abfragt, ob sie online sind, wird kein Scope der dort gelistet ist bentötigt.</p>
                                    <p>3. Scrolle nach unten und klicke auf token generieren. Nun fragt Twitch, ob du dich von dort aus anmelden und die Funktionen erlauben möchtest.</p>
                                    <p>   Der Twitch Account muss die Channel nicht abonniert haben. Es kann auch dafür ein neuer Account erstellt werden.</p>
                                    <p>4. Nun kann die CLIENT ID und der ACCESS TOKEN von dort kopiert werden.</p>
                                </div>
                            </div>
                            <br>
                            <form class="form-horizontal" data-toggle="validator" name="save" method="POST">
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label" for="inputTwitchId">Twitch client id</label>
                                    <div class="col-sm-4">
                                        <input class="form-control" id="inputTwitchId" type="text" name="twitch_api_client_id" placeholder="twitch client id" value=<?php echo '"' . $_SESSION["config"][$twitchKey . "_twitch_api_client_id"] . '"' ?> required/>
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <label class="col-sm-4 control-label" for="inputTwitchOauth">Twitch OAuth Token</label>
                                    <div class="col-sm-4">
                                        <input class="form-control" id="inputTwitchOauth" type="password" name="twitch_api_client_oauth_token" placeholder="twitch client oauth token" value=<?php echo '"' . $_SESSION["config"][$twitchKey . "_twitch_api_client_oauth_token"] . '"' ?> required/>
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <label class="col-sm-4 control-label" for="inputTwitchGroup">Twitch Servergroup</label>
                                    <div class="col-sm-4">
                                        <select name="twitch_server_group" class="form-select" aria-label="select">
<?php foreach ($_SESSION['db_groups'] as $id=>$name){
    if ( strval($id) === $_SESSION["config"][$twitchKey . "_twitch_server_group"]) {
?>
                                            <option selected value="<?php echo $id?>"><?php print_r("(" . $id . ") " . $name)?></option>
<?php } else {?>
                                            <option value="<?php echo $id?>"><?php print_r("(" . $id . ") " . $name)?></option>
<?php }
}?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 control-label" for="inputTwitchUserFile"></label>
                                    <div class="col-xs-5">
                                        <label class="col-sm-12 control-label" for="inputChannelPasswords">Twitchusername (kleingeschrieben)</label>
                                    </div>
                                    <div class="col-xs-4">
                                        <label class="col-sm-12 control-label" for="inputChannelPasswords">Teamspeak User</label>
                                    </div>
                                </div>

<?php
$counter = 1;
if( ! empty($twitchUser) ){
foreach($twitchUser as $key=>$value){ ?>
                                    <div class="form-group row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-2">
                                            <input class="form-control" id="itemKey<?php echo "$counter";?>" type="text" name="itemKey<?php echo "$counter";?>"  value='<?php echo "$key"; ?>' />
                                        </div>
                                        =
                                        <div class="col-sm-3">
                                            <select name="item<?php echo "$counter";?>" class="form-select" aria-label="select">
                                                <option value="" >-- User auswählen --</option>
<?php foreach ($_SESSION['db_users'] as $uid=>$name){
    if ( strval($uid) === $value) {
?>
                                                <option selected value="<?php echo $uid?>"><?php print_r($name)?></option>
<?php } else {?>
                                                <option value="<?php echo $uid?>"><?php print_r($name)?></option>
<?php }
}?>
                                            </select>
                                        </div>
                                        <div class="text-center">
                                            <button name="rmItem<?php echo "$counter";?>" type="submit" class="btn btn-danger" ><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </div>
<?php $counter++; }
}
for ($x = 1; $x <= 3; $x++) { ?>

                                    <div class="form-group row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-2">
                                            <input class="form-control" id="newItemKey<?php echo "$x"?>" type="text" name="newItemKey<?php echo "$x";?>"/>
                                        </div>
                                        =
                                        <div class="col-sm-3">
                                            <select name="newItem<?php echo "$x"?>" class="form-select" aria-label="select">
                                                <option value="" >-- User auswählen --</option>
<?php foreach ($_SESSION['db_users'] as $uid=>$name){
    if ( strval($uid) === "ServerQuery") {
        continue;
    ?>
<?php } else {?>
                                                <option value="<?php echo $uid?>"><?php print_r($name)?></option>
<?php }
}?>
                                            </select>
                                        </div>
                                    </div>
<?php } ?>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="display: block;">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" name="save"><i class="fas fa-save"></i>&nbsp;speichern</button>
                                    </div>
                                </div>
                                <div class="row">&nbsp;</div>
                            </form>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <?php
                        require_once($_SERVER["DOCUMENT_ROOT"] . '/_footer.php');
                    ?>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="../js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    </body>
</html>