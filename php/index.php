<?php
// If the form is submit
if (isset($_POST['submit']) && $_POST['submit'] === "submit-analyse"){
    $img = $_POST['img'];
    if (file_exists($img)){
        $ext = explode('.', $img);
        $ext = strtolower(end($ext));
        // If the file is jpg jpeg tif or tiff
        if(in_array($ext, array('jpg', 'jpeg', 'tif', 'tiff'))){
            // If the file $img contain Exif infos
            if($exif = exif_read_data($img, 0, true, false)){
                // Read the first part of the array
                $exif_array = array("FileName" => "", "Model" => "", "ExposureTime" => "", "Make" => "", "DateTimeOriginal" => "", "ISOSpeedRatings" => "", "ExposureTime" => "", "FileDateTime" => "", "Software" => "", "FileSize" => "", "FileType" => "", "MimeType" => "", "SectionsFound" => "", "html" => "", "Height" => "", "Width" => "", "IsColor" => "", "ByteOrderMotorola" => "", "PixelUnit" => "", "PixelPerUnitX" => "", "PixelPerUnitY" => "", "Flash" => "", "LightSource" => "", "MeteringMode" => "", "ApertureFNumber" => "", "FNumber" => "", "ExifVersion" => "", "DateTimeDigitized" => "", "ConponentsConfiguration" => "", "ShutterSpeedValue" => "", "Orientation" => "", "ApertureValue" => "", "ExposureBiasValue" => "", "FlashPixVersion" => "", "ColorSpace" => "", "ExifImageWidth" => "", "ExifImageLength" => "", "ExposureMode" => "", "WhiteBalance" => "", "DigitalZoomRatio" => "", "SceneCaptureType" => "", "Compression" => "", "XResolution" => "", "YResolution" => "", "ResolutionUnit" => "", "JPEGInterchangeFormat" => "", "JPEGInterchageFormatLength" => "", "YCbCrPositioning" => "", "Exif_IFD_Pointer" => "", "GPS_IFD_Pointer" => "");
                foreach ($exif as $key => $section){
                    // Read second part of the array
                    foreach ($section as $name => $value){
                        $exif_array[$name] = $value;
                    }
                }
            }
            else {
                $message = "file not supported";
            }
        }
    }
    else{
        $message = "file not found";
    }
}
?>
<!DOCTYPE html>
<html lang = "fr">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <h1>EXIF in PHP to get picture data</h1>
    <form class = "standard-form" method = "post" action = "index.php#result">
        <h2 class = "title-form">Analyse de l'image</h2>
        <input class = "field" type = "text" name = "img" placeholder = "entrez le chemin de votre image">
        <p class = "info">Picture need to be a jpeg, jpg, tif or tiff</p>
        <p class = "info">Choose time definition</p>
        <span class = "info-time"><input type = "radio" name = "time" value = "1"> (dd/mm/YYYY)</span><br>
        <span class = "info-time"><input type = "radio" name = "time" value = "2"> (YYYY/mm/dd)</span><br>
        <button class = "btn btn-valid" name = "submit" value = "submit-analyse">Analyse Picture</button>
    </form>
    <?php
        // Display infos from picture
        if (isset($message) && !empty($message))
            echo '<div id = "result" class = "standard-form"><p class = "info">'.$message.'</p></div>';
        if (isset($exif_array)){
            echo '<div id = "result" class = "standard-form">';
            echo '<img alt = "'.$img.'" src = "'.$img.'" class = "img-form">';
            echo '<p class = "info">File Name : '.$exif_array['FileName'].'</p>';
            if (!empty($exif_array['Width']))
                echo '<p class = "info">Width : '.$exif_array['Width'].'</p>';
            if (!empty($exif_array['Height']))
                echo '<p class = "info">Height : '.$exif_array['Height'].'</p>';
            if (!empty($exif_array['MimeType']))
                echo '<p class = "info">Mime Type : '.$exif_array['MimeType'].'</p>';
            if (!empty($exif_array['FileSize']))
            {
                $kb = $exif_array['FileSize'] / 1000;
                $mb = $kb / 1000;
                $gb = $mb / 1000;
                echo '<p class = "info">File Size : '.$exif_array['FileSize'].' Bytes';
                if ($kb > 1)
                    echo ' / '.$kb.' kB';
                if ($mb > 1)
                    echo ' / '.$mb.' mB';
                if ($gb > 1)
                    echo ' / '.$gb.' gB';
                echo '</p>';
            }
            if (!empty($exif_array['FileType']))
                echo '<p class = "info">File Type : '.$exif_array['FileType'].'</p>';
            if (!empty($exif_array['IsColor']) && $exif_array['IsColor'] == '1')
                echo '<p class = "info">Color : Yes</p>';
            else
                echo '<p class = "info">Color : No</p>';
            if (!empty($exif_array['SectionsFound']))
                echo '<p class = "info">Sections Found : '.$exif_array['SectionsFound'].'</p>';
            if (!empty($exif_array['Make']))
                echo '<p class = "info">Brand : '.$exif_array['Make'].'</p>';
            if (!empty($exif_array['Model']))
                echo '<p class = "info">Model : '.$exif_array['Model'].'</p>';
            if (!empty($exif_array['Orientation']))
                echo '<p class = "info">Orientation : '.$exif_array['Orientation'].'</p>';
            if (!empty($exif_array['ExposureTime']))
                echo '<p class = "info">Exposure Time : '.$exif_array['ExposureTime'].'</p>';
            if (!empty($exif_array['ISOSpeedRatings']))
                echo '<p class = "info">ISO Speed Ratings : '.$exif_array['ISOSpeedRatings'].'</p>';
            if (!empty($exif_array['ApertureFNumber']))
                echo '<p class = "info">Aperture F Number : '.$exif_array['ApertureFNumber'].'</p>';
            if (!empty($exif_array['FNumber']))
                echo '<p class = "info">F Number : '.$exif_array['FNumber'].'</p>';
            if (!empty($exif_array['FileDateTime']))
                echo '<p class = "info">File Date Time : '.$exif_array['FileDateTime'].'</p>';
            if (!empty($exif_array['DateTimeOriginal'])){
                echo '<p class = "info">Date Time Original : '.$exif_array['DateTimeOriginal'].'</p>';
                $date = $exif_array['DateTimeOriginal'];
                $date2 = explode(":", current(explode(" ", $date)));
                $exp = explode(" ", $date);
                $time = explode(":", end($exp));
                $hour = $time[0];
                $min = $time[1];
                $sec = $time[2];
                $year = $date2[0];
                $month = $date2[1];
                $day = $date2[2];
                if (isset($_POST['time']) && $_POST['time'] == 1)
                    echo '<p class = "info">Date (d/m/Y) : '.$day.'/'.$month.'/'.$year.' at '.$hour.' h : '.$min.' min : '.$sec.' sec</p>';
                else
                    echo '<p class = "info">Date (Y/m/d) : '.$year.'/'.$month.'/'.$day.' at '.$hour.' h : '.$min.' min : '.$sec.' sec </p>';
            }
            if (!empty($exif_array['DateTimeDigitized']))
                echo '<p class = "info">Date Time Digitized : '.$exif_array['DateTimeDigitized'].'</p>';
            if (!empty($exif_array['Software']))
                echo '<p class = "info">Software : '.$exif_array['Software'].'</p>';
            if (!empty($exif_array['html']))
                echo '<p class = "info">hmtl : '.$exif_array['html'].'</p>';
            if (!empty($exif_array['ByteOrderMotorola']))
                echo '<p class = "info">Byte Order Motorola : '.$exif_array['ByteOrderMotorola'].'</p>';
            if (!empty($exif_array['PixelUnit']))
                echo '<p class = "info">Pixel Unit : '.$exif_array['PixelUnit'].'</p>';
            if (!empty($exif_array['PixelPerUnitX']))
                echo '<p class = "info">Pixel Per Unit X : '.$exif_array['PixelPerUnitX'].'</p>';
            if (!empty($exif_array['PixelPerUnitY']))
                echo '<p class = "info">Pixel Per Unit Y : '.$exif_array['PixelPerUnitY'].'</p>';
            if (!empty($exif_array['LightSource'])){
                if ($exif_array['LightSource'] == 1)
                    echo '<p class = "info">Light Source : Yes</p>';
                else
                    echo '<p class = "info">Light Source : No</p>';
            }
            if (!empty($exif_array['Flash']))
                echo '<p class = "info">Flash : '.$exif_array['Flash'].'</p>';
            if (!empty($exif_array['MeteringMode']))
                echo '<p class = "info">Metering Mode : '.$exif_array['MeteringMode'].'</p>';
            if (!empty($exif_array['ExifVersion']))
                echo '<p class = "info">Exif Version : '.$exif_array['ExifVersion'].'</p>';
            if (!empty($exif_array['ComponentsConfiguration']))
                echo '<p class = "info">Components Configuration : '.$exif_array['ComponentsConfiguration'].'</p>';
            if (!empty($exif_array['ShutterSpeedValue']))
                echo '<p class = "info">Shutter Speed Value : '.$exif_array['ShutterSpeedValue'].'</p>';
            if (!empty($exif_array['ApertureValue']))
                echo '<p class = "info">Aperture Value : '.$exif_array['ApertureValue'].'</p>';
            if (!empty($exif_array['ExposureBiasValue']))
                echo '<p class = "info">Exposure Bias Value : '.$exif_array['ExposureBiasValue'].'</p>';
            if (!empty($exif_array['FlashPixVersion']))
                echo '<p class = "info">Exposure Bias Value : '.$exif_array['FlashPixVersion'].'</p>';
            if (!empty($exif_array['ColorSpace']))
                echo '<p class = "info">Color Space : '.$exif_array['ColorSpace'].'</p>';
            if (!empty($exif_array['ExifImageWidth']))
                echo '<p class = "info">Exif Image Width : '.$exif_array['ExifImageWidth'].'</p>';
            if (!empty($exif_array['ExifImageLength']))
                echo '<p class = "info">Exif Image Length : '.$exif_array['ExifImageLength'].'</p>';
            if (!empty($exif_array['ExposureMode']))
                echo '<p class = "info">Exposure Mode : '.$exif_array['ExposureMode'].'</p>';
            if (!empty($exif_array['WhiteBalance']))
                echo '<p class = "info">White Balance : '.$exif_array['WhiteBalance'].'</p>';
            if (!empty($exif_array['DigitalZoomRatio']))
                echo '<p class = "info">Digital Zoom Ratio : '.$exif_array['DigitalZoomRatio'].'</p>';
            if (!empty($exif_array['SceneCaptureType']))
                echo '<p class = "info">Scene Capture Type : '.$exif_array['SceneCaptureType'].'</p>';
            if (!empty($exif_array['Compression']))
                echo '<p class = "info">Compression : '.$exif_array['Compression'].'</p>';
            if (!empty($exif_array['XResolution']))
                echo '<p class = "info">X Resolution : '.$exif_array['XResolution'].'</p>';
            if (!empty($exif_array['YResolution']))
                echo '<p class = "info">Y Resolution : '.$exif_array['YResolution'].'</p>';
            if (!empty($exif_array['ResolutionUnit']))
                echo '<p class = "info">Resolution Unit : '.$exif_array['ResolutionUnit'].'</p>';
            if (!empty($exif_array['JPEGInterchangeFormat']))
                echo '<p class = "info">JPEG Interchange Format : '.$exif_array['JPEGInterchangeFormat'].'</p>';
            if (!empty($exif_array['JPEGInterchangeFormatLength']))
                echo '<p class = "info">JPEG Interchange Format Length : '.$exif_array['JPEGInterchangeFormatLength'].'</p>';
            if (!empty($exif_array['YCbCrPositioning']))
                echo '<p class = "info">Y Cb Cr Positioning : '.$exif_array['YCbCrPositioning'].'</p>';
            if (!empty($exif_array['Exit_IFD_Pointer']))
                echo '<p class = "info">Exit IFD Pointer : '.$exif_array['Exit_IFD_Pointer'].'</p>';
            if (!empty($exif_array['GPS_IFD_Pointer']))
                echo '<p class = "info">GPS IFD Pointer : '.$exif_array['GPS_IFD_Pointer'].'</p>';
            echo '</div>';
        }
    ?>
</body>
</html>