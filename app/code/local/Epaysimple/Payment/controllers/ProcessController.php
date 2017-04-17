<?php 
class Epaysimple_Payment_ProcessController extends Mage_Core_Controller_Front_Action {
    protected $_order;
	protected $_paywayResponse = null; //holds the response params from payway
    /**
     * Get Checkout Singleton
     *
     * @return Mage_Checkout_Model_Session
     */
    public function getCheckout(){
        return Mage::getSingleton('checkout/session');
    }
   	protected function _expireAjax(){
        if(!$this->getCheckout()->getQuote()->hasItems()){
            $this->getResponse()->setHeader('HTTP/1.1','403 Session Expired');
            exit;
        }
    }
    /**
     *  Get order
     *
     *  @param    none
     *  @return	  Mage_Sales_Model_Order
     */
    public function getOrder(){
        if($this->_order == null){
            $session = Mage::getSingleton('checkout/session');
            $this->_order = Mage::getModel('sales/order');
            $this->_order->loadByIncrementId($session->getLastRealOrderId());
        }
        return $this->_order;
    }
	public function redirectAction(){
		$session = $this->getCheckout();
		$order = $this->getOrder();
		if(!$order->getId()){
			$this->norouteAction();
			return;
		}
		$this->getResponse()->setBody($this->getLayout()->createBlock('eps_payment/redirect')->setOrder($order)->toHtml());
    }

    public function responseAction(){}
    public function successAction(){}

}