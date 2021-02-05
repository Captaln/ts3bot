<?php
    require_once('_preload.php');
    $nav_expanded = TRUE;
    $saved = FALSE;

    if (array_key_exists("ChannelAutoCreate", $functions)) {
        $cacKey = $functions["ChannelAutoCreate"];

        if(filesize($config[$cacKey . "_channel_check_password_file_path"])) {
            $channelPasswords = fileReadContentWithSeparator($config[$cacKey . "_channel_check_password_file_path"], " = ");
        }else{
            $channelPasswords = [];
        }

    }

    foreach($_POST as $key => $value){
        if( str_starts_with($key, "rmItem")){
            $number = str_replace("rmItem", "", $key);
            unset($channelPasswords[$_POST['itemKey' . $number]]);
            writeConfigFileWithSeparator($channelPasswords, $config[$cacKey . "_channel_check_password_file_path"], " = ");
            $saved = TRUE;
        }
    }

    if (isset($_POST['save'])){
        $channelPasswords = [];
        foreach($_POST as $key => $value){
            if( str_starts_with($key, "newItemKey") && ! empty($value) ){
                $number = str_replace("newItemKey", "", $key);
                $channelPasswords[$value] = $_POST["newItem" . $number];
            }
            if( str_starts_with($key, "itemKey") && ! empty($value) ){
                $number = str_replace("itemKey", "", $key);
                $channelPasswords[$value] = $_POST["item" . $number];
            }
        }
        $config[$cacKey . "_channel_check_subchannel"] = $_POST['channel_check_subchannel'];

        writeConfigFileWithSeparator($channelPasswords, $config[$cacKey . "_channel_check_password_file_path"], " = ");

        saveConfig($config, $configPath);
        $saved = TRUE;
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Funktion - Automatisches Channel erstellen</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <?php
            require_once('_nav-header.php');
        ?>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <?php
                    require_once('_nav.php');
                ?>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Channel auto create</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item">Settings</a></li>
                            <li class="breadcrumb-item">Funktionen</li>
                            <li class="breadcrumb-item active">ChannelAutoCreate</li>
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
                            <form class="form-horizontal" data-toggle="validator" name="save" method="POST">
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label" for="inputParentIDs">Eine mit Komma getrennte Liste (ohne Leerzeichen) mit Parent Channel IDs.</label>
                                    <div class="col-sm-4">
                                        <input class="form-control" id="inputParentIDs" type="text" name="channel_check_subchannel" placeholder="enter channel ids" value=<?php echo '"' . $config[$cacKey . "_channel_check_subchannel"] . '"' ?> required/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label" for="inputChannelPasswords">Einträge mit Channel Passwörtern</label>
                                    <div class="col-xs-4">
                                        <label class="col-sm-12 control-label" for="inputChannelPasswords">Hauptchannel ID</label>
                                    </div>
                                    <div class="col-xs-4">
                                        <label class="col-sm-12 control-label" for="inputChannelPasswords">Passwort</label>
                                    </div>
                                </div>

<?php
$counter = 1;
if( ! empty($channelPasswords) ){
foreach($channelPasswords as $key=>$value){ ?>
                                    <div class="form-group row">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-1">
                                            <input class="form-control" id="itemKey<?php echo "$counter";?>" type="text" name="itemKey<?php echo "$counter";?>"  value='<?php echo "$key"; ?>' />
                                        </div>
                                        =
                                        <div class="col-sm-3">
                                            <input class="form-control" id="item<?php echo "$counter";?>" type="text" name="item<?php echo "$counter";?>"  value='<?php echo "$value"; ?>' />
                                        </div>
                                        <div class="text-center">
                                            <button name="rmItem<?php echo "$counter";?>" type="submit" class="btn btn-danger" ><i class="fas fa-trash-alt"></i></button>
                                        </div>
                                    </div>
<?php $counter++; }
}
for ($x = 1; $x <= 3; $x++) { ?>

                                    <div class="form-group row">
                                        <div class="col-sm-4"></div>
                                        <div class="col-sm-1">
                                            <input class="form-control" id="newItemKey<?php echo "$x"?>" type="text" name="newItemKey<?php echo "$x";?>"/>
                                        </div>
                                        =
                                        <div class="col-sm-3">
                                            <input class="form-control" id="newItem<?php echo "$x"?>" type="text" name="newItem<?php echo "$x";?>"/>
                                        </div>
                                    </div>
<?php } ?>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success" name="save"><i class="fas fa-plus"></i></button>
                                </div>
                                <br>
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
                        require_once('_footer.php');
                    ?>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/datatables-demo.js"></script>
    </body>
</html>