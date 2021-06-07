<?php
/**
 * Sanitize $data to prevent script insertion in database
 * @param $data
 * @return string
 */
function sanitize($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = addslashes($data);

    return $data;
}

/**
 * Debug function
 * @param $data
 */
function pre($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}