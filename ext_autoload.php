<?php

//return array('tx_zeitschriften_task' => t3lib_extMgm::extPath('huub_zeitschriftendienst', 'tasks/zeitschriften_task.php'));

//Tx_HuubZeitschriftendienst_Task_ZeitschriftenTask

$extensionClassesPath = t3lib_extMgm::extPath('huub_zeitschriftendienst') . 'Classes/';
return array(
    'tx_huubzeitschriftendienst_task_zeitschriftentask' => $extensionClassesPath . 'Task/ZeitschriftenTask.php',
    'tx_huubzeitschriftendienst_task_zeitschriftentaskaddfields' => $extensionClassesPath . 'Task/ZeitschriftenTaskAddFields.php',
);
?>

