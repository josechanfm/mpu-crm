<?php
if (extension_loaded('imagick')) {
    $imagick = new Imagick();
    echo "<h2>Imagick Version:</h2> " . $imagick->getVersion()['versionString'] . "<br>";

    // Display policies
    echo "<h2>Imagick Policies:</h2>";
    $policies = Imagick::getVersion();
    echo "<pre>" . print_r($policies, true) . "</pre>";
} else {
    echo "Imagick is not installed.";
}
?>