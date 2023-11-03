<?php
function send_to($url)
{    header("Location: $url");
    die();
}
?>