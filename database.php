<?php
$database = new mysqli("localhost", "root", "", "zoo_assad");

function extract_rows($table){
    $rowsArray = [];
    while($row = $table->fetch_assoc()){
        array_push($rowsArray, $row);
    }
    return $rowsArray;
}

function request($command, $param, $values){
    global $database;
    $pre = $database->prepare($command);
    if($param && $values) $pre->bind_param($param, ...$values);
    $pre->execute();
    $result = $pre->get_result();
    $pre->close();

    return $result;
}

//$database->close();
?>