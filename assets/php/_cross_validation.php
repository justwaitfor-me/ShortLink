<?php
$unique = uniqid();
echo '<input type="hidden" name="'. $unique .'_cross_validation_check" value="true">';

if(!isset($cross_validation_check)) {
    http_response_code(400);
    echo 'Error: Cross validation failed.';
    exit;
}