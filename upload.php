<?php
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header("Access-Control-Allow-Origin: *");

    $dir= $_POST["data"];
    $path = getcwd() . "uploads/";
    $finalPath = $path . $dir . "/";

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


    if (move_uploaded_file($_FILES['file']['tmp_name'], $finalPath . $finalName)) {
        print_r(true);
    } else {
        print_r(false);
    }
?>