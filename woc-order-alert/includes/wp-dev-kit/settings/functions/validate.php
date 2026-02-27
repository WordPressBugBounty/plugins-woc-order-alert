<?php if ( ! defined( 'ABSPATH' ) ) exit; // Cannot access directly.
/**
 *
 * Email validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'pb_settings_validate_email' ) ) {
  function pb_settings_validate_email( $value ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound

    if ( ! filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
      return esc_html__( 'Please enter a valid email address.', 'woc-order-alert' );
    }

  }
}

/**
 *
 * Numeric validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'pb_settings_validate_numeric' ) ) {
  function pb_settings_validate_numeric( $value ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound

    if ( ! is_numeric( $value ) ) {
      return esc_html__( 'Please enter a valid number.', 'woc-order-alert' );
    }

  }
}

/**
 *
 * Required validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'pb_settings_validate_required' ) ) {
  function pb_settings_validate_required( $value ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound

    if ( empty( $value ) ) {
      return esc_html__( 'This field is required.', 'woc-order-alert' );
    }

  }
}

/**
 *
 * URL validate
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'pb_settings_validate_url' ) ) {
  function pb_settings_validate_url( $value ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound

    if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
      return esc_html__( 'Please enter a valid URL.', 'woc-order-alert' );
    }

  }
}

/**
 *
 * Email validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'pb_settings_customize_validate_email' ) ) {
  function pb_settings_customize_validate_email( $validity, $value, $wp_customize ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound

    if ( ! sanitize_email( $value ) ) {
      $validity->add( 'required', esc_html__( 'Please enter a valid email address.', 'woc-order-alert' ) );
    }

    return $validity;

  }
}

/**
 *
 * Numeric validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'pb_settings_customize_validate_numeric' ) ) {
  function pb_settings_customize_validate_numeric( $validity, $value, $wp_customize ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound

    if ( ! is_numeric( $value ) ) {
      $validity->add( 'required', esc_html__( 'Please enter a valid number.', 'woc-order-alert' ) );
    }

    return $validity;

  }
}

/**
 *
 * Required validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'pb_settings_customize_validate_required' ) ) {
  function pb_settings_customize_validate_required( $validity, $value, $wp_customize ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound

    if ( empty( $value ) ) {
      $validity->add( 'required', esc_html__( 'This field is required.', 'woc-order-alert' ) );
    }

    return $validity;

  }
}

/**
 *
 * URL validate for Customizer
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'pb_settings_customize_validate_url' ) ) {
  function pb_settings_customize_validate_url( $validity, $value, $wp_customize ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound

    if ( ! filter_var( $value, FILTER_VALIDATE_URL ) ) {
      $validity->add( 'required', esc_html__( 'Please enter a valid URL.', 'woc-order-alert' ) );
    }

    return $validity;

  }
}
