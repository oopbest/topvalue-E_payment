<?php
class Epaysimple_Payment_Model_Method_scb extends Epaysimple_Payment_Model_EpsMethod {
    function getPaymentUrl(){
        $snadbox = $this->getConfigData('scb_sand_box');
        if($snadbox == 'yes' || $snadbox == true){
            return 'https://nsips-test.scb.co.th:443/NSIPSWeb/NsipsMessageAction.do';
        }else{
            return 'https://nsips.scb.co.th/NSIPSWeb/NsipsMessageAction.do';
        }
    }

	public function createForm($info){
		$payment = $this->getQuote()->getPayment();
		$form_fields=array();

        $customer       = Mage::getSingleton('customer/session')->getCustomer();
        $merchantID     = $this->getConfigData('scb_mid');
        $terminalID     = $this->getConfigData('scb_tid');
        $orderId        = $this->getOrder()->getIncrementId();
        if(strlen($orderId) > 9){
            $maxlength = substr($orderId,1);
        }else{
            $maxlength = $orderId;
        }
        $amt            = intval($this->getOrder()->getBaseGrandTotal()).'00';
        $currencyCode   = "THB";
        $result_url_1   = Mage::getUrl('scb/scbprocess/success', array('_secure' => true));
        $result_url_2   = Mage::getUrl('scb/scbprocess/response', array('_secure' => true));


		$form_fields['profile_id']          = $merchantID;
        $form_fields['terminal']            = $terminalID;
        $form_fields['command']             = "WPDBAC";
        // $form_fields['payee_id']            = "";
        $form_fields['cust_id']             = $orderId;
        $form_fields['ref_no']              = $orderId;
        $form_fields['ref_date']            = date('YmdHis');
        $form_fields['amount']              = $amt;
        $form_fields['currency']            = $currencyCode;
        // $form_fields['description']         = $payment_description;
        // $form_fields['usrdat']              = "";
        $form_fields['dueDate']             = date('Ymd');;
        $form_fields['backURL']             = $result_url_2;
        // print_r($form_fields);
        // exit();
        return $form_fields;
        
	}
}