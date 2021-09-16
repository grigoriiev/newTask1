<?php
require_once "./vendor/autoload.php";
require_once "config.php";
require_once "path.php";

function glob_tree_files($path, $config,$_base_path = null)
{


    if (is_null($_base_path)) {

        $_base_path = '';

    } else {

        $_base_path .= basename($path) . '/';

    }

    $out = array();

    foreach(glob($path . '/*') as $file) {

        if (is_dir($file)) {
            $array = explode('.', $file);

            if (in_array(strtolower(array_pop($array)), $config)){

                $out = array_merge($out, glob_tree_files($file, $config,$_base_path));
            }


        } else {

            $array = explode('.', $file);

            if (in_array(strtolower(array_pop($array)), $config)){

                $out[] = $path."/".$_base_path . basename($file);

            }
        }

    }

    return $out;
}

$files=glob_tree_files($path, $config,$_base_path = null);

foreach ($files as $key =>$file) {

    $fileLines=file($file);

    $pdf = new FPDF();

    $pdf->AddPage();

    $pdf->SetFont('Arial','B',16);

    foreach ($fileLines as  $key1 =>$value) {

        $pdf->Cell(10, 20, $value);
    }
    if(!file_exists($file . ".pdf")) {

        $pdf->Output("F", $file.".pdf");
    }


}
