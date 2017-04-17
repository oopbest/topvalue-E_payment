<?php 
class Epaysimple_Payment_KrungsriprocessController extends Mage_Core_Controller_Front_Action {
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
    public function getPayway(){
        return Mage::getSingleton('payway/payway');
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
	/**
     * seting response after returning from Payway
     *
     * @param array $response
     * @return object $this
	 */
    protected function setPaywayResponse($response){
    	if(count($response)){
            $this->_paywayResponse = $response;
        }
        return $this;
    }
    /**
     * When a customer chooses Payway Payment on Checkout/Payment page
     *
     */
	public function redirectAction(){
		$session = $this->getCheckout();
		$order = $this->getOrder();
		if(!$order->getId()){
			$this->norouteAction();
			return;
		}
		$this->getResponse()->setBody($this->getLayout()->createBlock('payway/redirect')->setOrder($order)->toHtml());
    }
	
    private function _setPaywayResponse(){
    	if($this->getRequest()->isPost()){
			$this->setPaywayResponse($this->getRequest()->getPost());
		}else if($this->getRequest()->isGet()){
			$this->setPaywayResponse($this->getRequest()->getParams());
		}
    }

    public function _validateResponse(){
        if(isset($_POST['successcode'])){
			if($_POST['successcode']=='00'){
                return true;
			}else if($_POST['successcode']=='01'){
                return false;
			}
		}
    }

    

    public function ipnAction(){
        $data = $_REQUEST;
        if(is_array($data) || is_object($data)){
            $data = print_r($data, true);
        }
        Mage::log($data, null, 'payway_ipn.log', true);       
    }

    public function responseAction(){}
    public function successAction(){}
    public function cancelAction(){}
    public function failureAction(){
        $session = Mage::getSingleton('checkout/session');
		$session->addError($this->__('An unexpected error occurred. There is problem with your payment. Please try again.'));
		$this->_redirect('checkout/cart');
    }
}