<?php

class Epaysimple_Payment_Model_Config_Source_Cctype
{
    public function toOptionArray()
    {
        $type =  array(
	        'krungsri'=>'ธนาคารกรุงศรี',
	        'scb'=>'ธนาคารไทยพาณิชย์',  
        );
        $options = array();
        foreach ($type as $code => $name) {
            $options[$code] = array(
               'value' => $code,
               'label' => $name
            );
        }
        return $options;
    }
}