<?php
	/**
	 * evCoupon module
	 * 	Adds some needed functionality to coupons
	 *
	 *	@author Joe Richardson, Gina Raker
	 *  @package trends
	 *         
	 */
	class evCoupon_Module extends Core_ModuleBase
	{
		protected function create_module_info()
		{
			return new Core_ModuleInfo(
				"EHLOVader's coupon module",
				"Adds some needed functionality to coupons",
				"EHLOVader" );
		}

		public function subscribeEvents()
		{
		    Backend::$events->addEvent('shop:onBeforeSetCouponCode', $this, 'check_coupon');
			
		}



		public function check_coupon($coupon)
		{

			if(strlen($coupon)){

				$cart_name = 'main';

				$shipping_info = Shop_CheckoutData::get_shipping_info();

				$subtotal = Shop_Cart::total_price_no_tax($cart_name, false);

				/**
				 * Apply discounts
				 */

				$shipping_method = Shop_CheckoutData::get_shipping_method();
				$payment_method = Shop_CheckoutData::get_payment_method();

				$payment_method_obj = $payment_method->id ? Shop_PaymentMethod::create()->find($payment_method->id) : null;
				$shipping_method_obj = $shipping_method->id ? Shop_ShippingOption::create()->find($shipping_method->id) : null;
				
				$cart_items = Shop_Cart::list_active_items($cart_name);

				$discount_info = Shop_CartPriceRule::evaluate_discount(
					$payment_method_obj, 
					$shipping_method_obj, 
					$cart_items,
					$shipping_info,
					$coupon, 
					Cms_Controller::get_customer(),
					$subtotal);
			

				if($discount_info->cart_discount == 0)
						return false;

			}

				return null;
			
		}

		/**
		 * Awaiting deprecation
		 */

		protected function createModuleInfo() {
			return $this->create_module_info();
		}

		
	}

/* End of file evcoupon_module.php */
/* Location: /classes/evcoupon_module.php */