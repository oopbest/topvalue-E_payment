<?php
class Epaysimple_Payment_Model_EpsMethod extends Mage_Payment_Model_Method_Abstract
{
    protected $_code = 'eps_cc';
    protected $_canSaveCc   = true;
    protected $_formBlockType = 'eps_payment/form_eps';
    protected $_infoBlockType = 'eps_payment/info_eps';

    public function assignData($data){
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
        $info->setEpayBank($data->getEpayBank());
        return parent::assignData($data);
    }
    
    public function canCapture(){
        return true;
    }
	
	public function capture(Varien_Object $payment, $amount){
        $payment->setStatus(self::STATUS_APPROVED)->setLastTransId($this->getTransactionId());
        return $this;
    }
    
    public function getOrderPlaceRedirectUrl(){
        return Mage::getUrl('eps/process/redirect');
    }
    
    public function cleanString($string){
        $string_step1 = strip_tags($string);
        $string_step2 = nl2br($string_step1);
        $string_step3 = str_replace("<br />","<br>",$string_step2);
        $cleaned_string = str_replace("\""," inch",$string_step3);
        return $cleaned_string;
    }
    
    public function getCustomer(){
        if(empty($this->_customer)){
            $this->_customer = Mage::getSingleton('customer/session')->getCustomer();
        }
        return $this->_customer;
    }
    public function getCheckout(){
        if(empty($this->_checkout)){
            $this->_checkout = Mage::getSingleton('checkout/session');
        }
        return $this->_checkout;
    }
    public function getQuote(){
        if(empty($this->_quote)){
            $this->_quote = $this->getCheckout()->getQuote();
        }
        return $this->_quote;
    }
    public function getOrder(){
        if(empty($this->_order)){
            $order = Mage::getModel('sales/order');
            $order->loadByIncrementId($this->getCheckout()->getLastRealOrderId());
            $this->_order = $order;
        }
        return $this->_order;
    }
    
    public function getPaymentUrl(){
	    // get payment mode
		$info = $this->getInfoInstance();
		$payment = $info->getData('epay_bank');
		$form_fields = array();

        if(!empty($payment)){
            require_once(Mage::getBaseDir().'/app/code/local/Epaysimple/Payment/Model/Method/'.$payment.'.php');
            $class = 'Epaysimple_Payment_Model_Method_'.$payment;
            $paymentMethod = new $class();
            return $paymentMethod->getPaymentUrl();
        }
        return '';

    }
    
    
    public function getFormFields(){
		// get payment mode
		$info = $this->getInfoInstance();
		$payment = $info->getData('epay_bank');
		$form_fields = array();

        if(!empty($payment)){
            require_once(Mage::getBaseDir().'/app/code/local/Epaysimple/Payment/Model/Method/'.$payment.'.php');
            $class = 'Epaysimple_Payment_Model_Method_'.$payment;
            $paymentMethod =  new $class();
            return $paymentMethod->createForm($info);
        }
        return $form_fields;
	}
	

}