<?php

function miga_simple_events_shortcode($atts, $content, $tag)
{
    $height = "auto";
    $showDayName = true;
    $theme = "theme1";
    $usTime = false;
    $usDate = false;
    $maxAmount = -1;

    if (isset($atts["height"])) {
        $height = (int)$atts["height"]."px";
    }
    if (isset($atts["dayname"])) {
        $showDayName = (bool)$atts["dayname"];
    }
    if (isset($atts["showyear"])) {
        $showDayName = (bool)$atts["showyear"];
    }
    if (isset($atts["theme"])) {
        $theme = $atts["theme"];
    }
    if (isset($atts["usTime"])) {
        $usTime = $atts["usTime"];
    }
    if (isset($atts["maxAmount"])) {
        $maxAmount = (int)$atts["maxAmount"];
    }
    ob_start();

    if ($theme == "theme1") {
        require("theme1.php");
    } elseif ($theme == "theme2") {
        require("theme2.php");
    }
    return ob_get_clean();
}
