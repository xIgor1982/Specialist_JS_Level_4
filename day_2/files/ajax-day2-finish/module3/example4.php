<?php

header('Content-Type: application/json');
$object = (object) array(
  "name" => 'Molecule Man',
  "age" => 29
);

$json = json_encode($object);

print_r($json);

