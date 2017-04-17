<?php
class Epaysimple_Payment_Block_Form_Eps extends Mage_Payment_Block_Form{
    protected function _construct(){
        parent::_construct();
        $this->setTemplate('epaysimple/form/eps.phtml');
    }
    public function getBankconfig(){
		$method = $this->getMethod();
        $bank_enabled = $method->getConfigData('bank_enabled');
        $bank_enabled = explode(',', $bank_enabled);
        return $bank_enabled;
    }
}