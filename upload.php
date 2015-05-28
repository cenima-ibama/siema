<?php
    defined("TMP_PATH")
    || define("TMP_PATH", str_replace("\\", "/", realpath(__DIR__ . "/") . '/uploads/'));

    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header("Access-Control-Allow-Origin: *");

    $dir= $_POST["data"];
    $path = TMP_PATH;
    $finalPath = $path . $dir;

    $finalName = $_FILES['file']['name'];

    //verify if path already exists, remove and create folder.
    if (is_dir($finalPath)) {
            $diretorio = dir($finalPath);
            while ($arquivo = $diretorio->read()) {
                    if (($arquivo != '.') && ($arquivo != '..'))
                            unlink($finalPath . '/' . $arquivo);
            }

            rmdir($finalPath);

            if (!file_exists($finalPath)) {
                    mkdir($finalPath);
            }
    } else {
            mkdir($finalPath);
    }


    if (move_uploaded_file($_FILES['file']['tmp_name'], $finalPath . '/' . $finalName)) {
        return true;
    } else {
        return false;
    }
?>
