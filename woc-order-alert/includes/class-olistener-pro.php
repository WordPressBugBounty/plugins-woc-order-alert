<?php

use WPDK\Utils;

if ( ! class_exists( 'OlistenerPro' ) ) {
	class OlistenerPro {

		protected static $_instance = null;

		/**
		 * OlistenerPro constructor.
		 */
		public function __construct() {
			add_filter( 'olistener_filters_should_notify', array( $this, 'apply_pro_settings' ), 10, 2 );
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
		 *
		 * @return mixed
		 */
		public function apply_pro_settings( $should_notify, $order_id ) {

			global $olistener_sc_client;

			if ( is_null( $olistener_sc_client->settings()->activation_id ) ) {
				return $should_notify;
			}

			$summary            = array();
			$order              = wc_get_order( $order_id );
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
			$users            = (array) Utils::get_option( 'olistener_users', array() );
			$summary['users'] = in_array( $order->get_customer_id(), $users );

			/**
			 * Check - User Roles
			 */
			$user_roles       = (array) Utils::get_option( 'olistener_user_roles', array() );
			$order_customer   = get_user_by( 'id', $order->get_customer_id() );
			$summary['roles'] = ! empty( array_intersect( $user_roles, (array) $order_customer->roles ) );

			/**
			 * Check - Relation
			 */
			$rules_relation = Utils::get_option( 'olistener_rules_relation', array() );
			$rules_relation = empty( $rules_relation ) ? array_keys( array_filter( $summary ) ) : $rules_relation;
			$final_result   = false;

			if ( empty( $rules_relation ) ) {
				$rules_relation = array_keys( array_filter( $summary ) );
			}

			foreach ( $rules_relation as $index => $rule_for ) {
				if ( $index > 0 ) {
					$final_result = $final_result && isset( $summary[ $rule_for ] ) && $summary[ $rule_for ];
				} else {
					$final_result = isset( $summary[ $rule_for ] ) && $summary[ $rule_for ];
				}
			}

			return $final_result;
		}
	}
}
