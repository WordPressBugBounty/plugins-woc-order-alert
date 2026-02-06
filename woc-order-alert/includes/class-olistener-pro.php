<?php

use WPDK\Utils;

if ( ! class_exists( 'OlistenerPro' ) ) {
	class OlistenerPro {

		protected static $_instance = null;

		/**
		 * OlistenerPro constructor.
		 */
		public function __construct() {
			add_filter( 'olistener_filters_should_notify', array( $this, 'apply_pro_settings' ), 10, 3 );
		}

		/**
		 * Instance of the listener class
		 *
		 * @return \OlistenerPro|null
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}


		/**
		 * Apply pro settings and decide to notify or not
		 *
		 * @param $should_notify
		 * @param $order_id
		 * @param $order
		 *
		 * @return mixed
		 */
		public function apply_pro_settings( $should_notify, $order_id, $order = null ) {

			// Debug: Log the function call
			error_log('OlistenerPro::apply_pro_settings called for order ID: ' . $order_id);

			// Remove the activation check that might be blocking the logic
			// The PRO functionality should work regardless of activation status for testing
			/*
			global $olistener_sc_client;

			if ( is_null( $olistener_sc_client->settings()->activation_id ) ) {
				return $should_notify;
			}
			*/

			$summary            = array();
			$order              = $order ?: wc_get_order( $order_id );
			
			if (!$order instanceof WC_Order) {
				error_log('OlistenerPro - Invalid order object for ID: ' . $order_id);
				return $should_notify;
			}
			
			$ordered_products   = array();
			$ordered_categories = array();
			$ordered_tags       = array();

			/**
			 * Check if custom searching rules are disabled
			 */
			$enable_rules = Utils::get_option( 'olistener_enable_rules' );

			if ( ! $enable_rules ) {
				return $should_notify;
			}

			foreach ( $order->get_items() as $order_item ) {

				if ( ! $order_item instanceof WC_Order_Item_Product ) {
					continue;
				}

				$ordered_product = $order_item->get_product();

				if ( $ordered_product->get_type() == 'variation' ) {
					$parent_product     = wc_get_product( $ordered_product->get_parent_id() );
					$ordered_products[] = $parent_product->get_id();
					$ordered_categories = array_merge( $ordered_categories, $parent_product->get_category_ids() );
					$ordered_tags       = array_merge( $ordered_tags, $parent_product->get_tag_ids() );
				} else {
					$ordered_products[] = $ordered_product->get_id();
					$ordered_categories = array_merge( $ordered_categories, $ordered_product->get_category_ids() );
					$ordered_tags       = array_merge( $ordered_tags, $ordered_product->get_tag_ids() );
				}
			}

			$ordered_products   = array_filter( array_unique( $ordered_products ) );
			$ordered_categories = array_filter( array_unique( $ordered_categories ) );
			$ordered_tags       = array_filter( array_unique( $ordered_tags ) );

			/**
			 * Check - Products Included
			 */
			$products_included   = Utils::get_option( 'olistener_products_included', array() );
			$products_matched    = array_intersect( $products_included, $ordered_products );
			$summary['products'] = count( $products_matched ) > 0;

			/**
			 * Check - Product Categories
			 */
			$categories_included   = Utils::get_option( 'olistener_categories_included', array() );
			$categories_matched    = array_intersect( $categories_included, $ordered_categories );
			$summary['categories'] = count( $categories_matched ) > 0;

			/**
			 * Check - Product Categories
			 */
			$tags_included   = Utils::get_option( 'olistener_tags_included', array() );
			$tags_matched    = array_intersect( $tags_included, $ordered_tags );
			$summary['tags'] = count( $tags_matched ) > 0;

			/**
			 * Check - Minimum Ordered Items
			 */
			$min_order_amount  = Utils::get_option( 'olistener_min_order_amount' );
			$summary['amount'] = $min_order_amount > 0 && $order->get_total() >= $min_order_amount;

			/**
			 * Check - Users
			 */
			$users = (array) Utils::get_option( 'olistener_users', array() );
			$customer_id = $order->get_customer_id();
			
			// Handle guest orders (customer_id = 0) and get correct user ID
			if ($customer_id === 0) {
				// Try to get user by email for guest orders or admin-placed orders
				$order_email = $order->get_billing_email();
				if ($order_email) {
					$user_by_email = get_user_by('email', $order_email);
					$customer_id = $user_by_email ? $user_by_email->ID : 0;
				}
			}
			
			$summary['users'] = !empty($users) ? in_array($customer_id, $users) : true;

			/**
			 * Check - User Roles
			 */
			$user_roles = (array) Utils::get_option( 'olistener_user_roles', array() );
			
			// If no roles are selected, don't filter by roles
			if (empty($user_roles)) {
				$summary['roles'] = true;
			} else {
				$order_customer = $customer_id > 0 ? get_user_by('id', $customer_id) : null;
				
				if ($order_customer && isset($order_customer->roles) && is_array($order_customer->roles)) {
					$user_roles = array_map('strtolower', $user_roles);
					$customer_roles = array_map('strtolower', $order_customer->roles);
					$summary['roles'] = !empty(array_intersect($user_roles, $customer_roles));
				} else {
					// Guest user or user not found
					$summary['roles'] = in_array('guest', array_map('strtolower', $user_roles));
				}
			}

			/**
			 * Check - Relation
			 */
			$rules_relation = (array) Utils::get_option('olistener_rules_relation', array());
			
			// Debug: Log the summary and rules
			error_log('OlistenerPro - Summary: ' . print_r($summary, true));
			error_log('OlistenerPro - Rules Relation: ' . print_r($rules_relation, true));
			
			// If no rules are selected, return true if any condition is met
			if (empty($rules_relation)) {
				$result = in_array(true, $summary);
				error_log('OlistenerPro - No rules selected, result: ' . ($result ? 'true' : 'false'));
				return $result;
			}

			// Check if all selected rules are satisfied
			foreach ($rules_relation as $rule) {
				if (!isset($summary[$rule]) || !$summary[$rule]) {
					error_log('OlistenerPro - Rule "' . $rule . '" failed or not set');
					return false;
				}
			}

			error_log('OlistenerPro - All rules passed, returning true');
			return true;
		}
	}
}
