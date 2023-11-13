<?php
    include "Device.php";
    $PC02 = new Device(1,'Windows 11','324AC','PC Patatero','21.30.40.50',53);
    echo "<br><br><br>";
    $PC02->insertDeviceIntoDatabase('admin');
    foreach ($PC02->getDeviceProperties() as $fila){
        echo $fila . "<br>";
    }
