<?php
    require_once($_SERVER["DOCUMENT_ROOT"] . '/_preload.php');
    $_SESSION["nav_expanded"] = TRUE;
    if (array_key_exists("AutoRemove", $_SESSION["functions"])) {
        $autormKey = $_SESSION["functions"]["AutoRemove"];
    }else{
        header("Refresh:0; url=/core.php");
        exit();
    }
    $saved = FALSE;
    if (isset($_POST['update'])){

        $_SESSION["config"][$autormKey . "_auto_remove_group_ids"] = $_POST['auto_remove_group_ids'];

        saveConfig($_SESSION["config"], $_SESSION["configPath"]);
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
        <title>Funktion - Auto Remove Servergroup</title>
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
                        <h1 class="mt-4">AutoRemove Servergroup</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item">Settings</a></li>
                            <li class="breadcrumb-item">Funktionen</li>
                            <li class="breadcrumb-item active">AutoRemove</li>
                        </ol>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-chart-area mr-1"></i>
                                Settings
                            </div>
                            <br>
                            <form class="form-horizontal" data-toggle="validator" name="update" method="POST">
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label" for="inputServergroup">AutoRemove Servergroup</label>
                                    <div class="col-sm-4">
                                        <div name="auto_remove_group_ids" id="single-select"></div>
                                    </div>
                                </div>
                                <div class="col-md-3"></div>
                                <?php if($saved) { ?>
                                <div id="savedDiv" class="row saved-row">
                                    <label class="saved-label">Config gespeichert. Bitte den Bot neustarten!</label>
                                </div>
                                <?php }?>
                                <div class="row">&nbsp;</div>
                                <div class="row" style="display: block;">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" name="update"><i class="fas fa-save"></i>&nbsp;speichern</button>
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
        <link rel="stylesheet" href="../js/virtual-select.min.css" />
        <script src="../js/virtual-select.min.js"></script>
        <script src="../js/scripts.js"></script>
        <script src="../js/custom-scripts.php"></script>
        <script>
            VirtualSelect.init({
                ele: '#single-select',
                options: getGroups(),
                multiple: false,
                search: true,
                selectedValue: ["<?php echo $_SESSION["config"][$autormKey . "_auto_remove_group_ids"];?>"],
                placeholder: '-- Servergruppe auswählen --',
            });
        </script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    </body>
</html>