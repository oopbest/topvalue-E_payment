<?php 
class Epaysimple_Payment_Block_Redirect extends Mage_Core_Block_Abstract {
	protected function _toHtml(){		
		$standard = $this->getOrder()->getPayment()->getMethodInstance();
		$url = $standard->getPaymentUrl();
        $form = new Varien_Data_Form();
        $form->setAction($url)->setName('sendform')->setMethod('get')->setUseContainer(true);
		$md = '';
		
		foreach($standard->getFormFields() as $field => $value){
            $form->addField($field, 'hidden', array('name'=>$field, 'value'=>$value));
        }
        $html = '<html><body>';
        $html.= $this->__('You will be redirected to Payment Gateway in a few seconds.');
		$html.= $form->toHtml();
        $html.= '<script type="text/javascript">document.sendform.submit();</script>';
        $html.= '</body></html>';        
		return $html;
    }
}
?>