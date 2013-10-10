<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order extends CI_Controller {

    public function __construct(){
        $this->order = array();
        parent::__construct();

        //Load libraries and models
        $this->load->library('stripe');
        $this->load->model('Customer_model', 'customers');
        $this->load->model('Order_model', 'orders');
    }

	public function index()
	{
		$this->_validateAddress();

		//@todo -- need to check if we have customer information on file!

		//Verify post exitst. Redirect if not
		if(empty($_POST)){
			$this->load->view('/garcinia/order');
		}
		else{
			$this->order = $this->input->post();

			//Create the Stripe Customer
			//@TODO - need to check if customer already exists. If so, use the existing stripe id
			$stripeCustomer = (array) $this->_createStripeCustomer($this->order['stripeToken'], $this->order['Email']);

			//Managing Stripe Errors
			if(!array_key_exists('error', $stripeCustomer)){
				$this->order['stripe_id'] = $stripeCustomer['id'];
			}else{
			//@todo -- error handling!
				echo "There was a stripe error";
				return;
			}

			//Validate address
			//@todo
			//$address = $this->_validateAddress(/*apprporiate shipping info here*/);

			//create the customer record
			$this->order['cid'] = $this->customers->createCustomer(array(
				'stripe_id' => $this->order['stripe_id'],
				'first' => $this->order['FirstName'],
				'last' => $this->order['LastName'],
				'email' => $this->order['Email'],
				'street_1' => $this->order['Street1'],
				'street_2' => $this->order['Street2'],
				'city' => $this->order['City'],
				'state' => $this->order['State'],
				'zip' => $this->order['Zip']
			));

			//Determine the order price
			$this->order['price'] = $this->_calculateCost();

			//Charge the customer
			$stripePayment = (array) $this->stripe->charge_customer($this->order['price'] * 100, $this->order['stripe_id'], "Product $->this->order['productId'] purchase");
			
			//Managing Stripe Errors
			if(!array_key_exists('error', $stripePayment)){
				$this->order['payment_id'] = $stripePayment['id'];
			}else{
			//@todo -- error handling!
				echo "There was an error charging this card;";
				print_r($stripePayment);
				return;
			}

						//Create Shipment
			$this->order['deliveryConfirmation'] = $this->_createShipment();

			//create the order record
			//@todo - add shipping method
			//@todo add price?
			$this->order['oid'] = $this->orders->createOrder(array(
				'customer_id' => $this->order['cid'],
				'product_id' => $this->order['productID'],
				'payment_id' => $this->order['payment_id'],
				'shared' => $this->order['socialUse'],
				'shipping_confirmation' => $this->order['deliveryConfirmation']
			));

			//@todo - add view data!
			$this->load->view($this->input->post('redirectUrl'));
		}
	}

	//Send a conirmation email 
	private function _sendConfirmation(){
		$this->load->library('email');

		$this->email->initialize(array(
		  'protocol' => 'smtp',
		  'smtp_host' => 'smtp.sendgrid.net',
		  'smtp_user' => 'sendgridusername',
		  'smtp_pass' => 'sendgridpassword',
		  'smtp_port' => 587,
		  'crlf' => "\r\n",
		  'newline' => "\r\n"
		));

		$this->email->from('your@example.com', 'Your Name');
		$this->email->to('someone@example.com');
		$this->email->cc('another@another-example.com');
		$this->email->bcc('them@their-example.com');
		$this->email->subject('Email Test');
		$this->email->message('Testing the email class.');
		$this->email->send();

		echo $this->email->print_debugger();

	}

	//Calculated total cost of order
	private function _calculateCost(){
		//Determine Shipping Cost
		//@todo - padd address in here
		$shippingCost = $this->_estimateShipping();
	
		//Determine the itemCost
		$itemCost = $this->orders->itemCost($this->order['productID'])['price'];

		$orderCost = $shippingCost + $itemCost;

		//Check if social savings are applied
		if($this->order['socialUse'] != "0"){
			$orderCost = $orderCost - 5;
		}
		return $orderCost;
	}


	//Create a customer in stripe account
	private function _createStripeCustomer($token, $email){
		return $this->stripe->customer_create($token, $email);
	}

	//Charge customer via stripe
	private function _charge(){
		return $this->stripe->customer_create($token, $email);
	}

	//Validate address with USPS
	private function _validateAddress(){
		$this->load->library('USPS');

		//CREATE AN ARRAY OF ADDRESSES (MAX 5)
		$addresses = array(
	        'firm_name' => 'XYZ Company',
	        'address1' => '1234 Fake St.',
	        'address2' => 'Apt #1234',
	        'city' => 'Testingville',
	        'state' => 'AZ',
	        'zip5' => '12345',
    	);

		//RUN ADDRESS STANDARDIZATION REQUEST
		$verified_address = $this->usps->address_standardization($addresses);

		//OUTPUT RESULTS
		print_r($verified_address);
		//return "Reformatted Address";
		//@todo - verify that address is legitimate
		//if not we need to let the customer know of the error
		//otherswise, we return the Proper address returned from USPS
	}

	//Estimate shipping from USPS
	private function _estimateShipping(){
		//this needs to estimate shipping and determine what we want to do
		return 5.99;
	}


	//create the shipment
	private function _createShipment(){
		//created a shipment with USPS, so we can print labels and such
		//returns tracking number for order record
		return "orderTrackingNumerWouldBeHere";
	}

	//track and order's shipment process
	public function track(){
		//this is dependent on getting tracking numbers back from USPS
	}
}