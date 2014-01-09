<?php

    
class Tx_HuubZeitschriftendienst_Task_ZeitschriftenTaskAddFields implements tx_scheduler_AdditionalFieldProvider {
    
    public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $parentObject) {
        
        if (empty($taskInfo['suchStringEzb'])) {
            if($parentObject->CMD == 'edit') {
                $taskInfo['suchStringEzb'] = $task->suchStringEzb;
            } else {
                $taskInfo['suchStringEzb'] = '';
            }
        }

        if (empty($taskInfo['suchStringZdb'])) {
            if($parentObject->CMD == 'edit') {
                $taskInfo['suchStringZdb'] = $task->suchStringZdb;
            } else {
                $taskInfo['suchStringZdb'] = '';
            }
        }

        // Write the code for the field
        $fieldID = 'suchStringEzb';
        $fieldCode = '<input type="text" name="tx_scheduler[suchStringEzb]" id="' . $fieldID . '" value="' . $taskInfo['suchStringEzb'] . '" size="15" />';
        $additionalFields = array();
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => 'Suchstring für die EZB Datenbank: notation=',
            'cshKey'   => '_MOD_tools_txschedulerM1',
            'cshLabel' => $fieldID,
        );

        // Write the code for the field
        $fieldID = 'suchStringZdb';
        $fieldCode = '<input type="text" name="tx_scheduler[suchStringZdb]" id="' . $fieldID . '" value="' . $taskInfo['suchStringZdb'] . '" size="15" />';
        $additionalFields[$fieldID] = array(
            'code'     => $fieldCode,
            'label'    => 'Suchstring für die ZDB Datenbank: ssg=',
            'cshKey'   => '_MOD_tools_txschedulerM1',
            'cshLabel' => $fieldID,
        );

        return $additionalFields;
    }

    public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $parentObject) {
        //$submittedData['ip'] = trim($submittedData['ip']);
        //$submittedData['port'] = trim($submittedData['port']);
        return true;
    }

    public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
        $task->suchStringEzb = $submittedData['suchStringEzb'];
        $task->suchStringZdb = $submittedData['suchStringZdb'];
    }
    
}
    
?>