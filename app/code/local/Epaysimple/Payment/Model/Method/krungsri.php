<?php
class Epaysimple_Payment_Model_Method_krungsri extends Epaysimple_Payment_Model_EpsMethod {
    function getPaymentUrl(){
        $snadbox = $this->getConfigData('krungsri_sand_box');
        echo $snadbox;
        if($snadbox == 'yes' || $snadbox == true){
            return 'https://servicekrungsrigroupcom-uatext.aycap.bayad.co.th/epp/payment';
        }else{
            return 'https://servicekrungsrigroup.com/epp/payment';
        }
    }

	public function createForm($info){
		$payment = $this->getQuote()->getPayment();
		$form_fields=array();

        $customer       = Mage::getSingleton('customer/session')->getCustomer();
        $merchantID     = $this->getConfigData('krungsri_mid');
        $orderId        = $this->getOrder()->getIncrementId();
        if(strlen($orderId) > 9){
            $maxlength = substr($orderId,1);
        }else{
            $maxlength = $orderId;
        }
        $amt            = intval($this->getOrder()->getBaseGrandTotal()).'00';
        $currencyCode   = "764";
        $result_url_1   = Mage::getUrl('krungsri/krungsriprocess/success', array('_secure' => true));
        $result_url_2   = Mage::getUrl('krungsri/krungsriprocess/response', array('_secure' => true));


		$form_fields['MERCHANTNUMBER']          = $merchantID;
        $form_fields['payment_description']     = $payment_description;
        $form_fields['ORDERNUMBER']             = $maxlength;
        $form_fields['PAYMENTTYPE']             = "DirectDebit";
        $form_fields['AMOUNT']                  = $amt;
        $form_fields['CURRENCY']                = $currencyCode;
        $form_fields['LANGUAGE']                = $dfLang;
        $form_fields['REF1']                    = $orderId;
        $form_fields['REF2']                    = "";
        $form_fields['REF3']                    = "";
        $form_fields['REF4']                    = "";
        $form_fields['REF5']                    = "";
        // print_r($form_fields);
        // exit();
        return $form_fields;
        
	}
}