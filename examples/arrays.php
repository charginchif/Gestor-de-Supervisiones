<?php

$array = [3, 8, "Arbol"];

echo print_r($array, true)."<br>";

$asociativo = [
    "nombre" => "Juan",
    "edad" => 30,
    "ciudad" => "Madrid",
    "casado" => false
];

    echo print_r($asociativo, true);


echo "<br>";

echo json_encode($array);
echo "<br>";

echo json_encode($asociativo);


$asociativo["Direccion"] = "Calle Falsa 123";

echo json_encode($asociativo);
echo "<br>";


?>