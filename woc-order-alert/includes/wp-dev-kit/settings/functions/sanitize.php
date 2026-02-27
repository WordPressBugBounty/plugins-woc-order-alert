<?php if ( ! defined( 'ABSPATH' ) ) exit; // Cannot access directly.
/**
 *
 * Sanitize
 * Replace letter a to letter b
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'pb_settings_sanitize_replace_a_to_b' ) ) {
  function pb_settings_sanitize_replace_a_to_b( $value ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
    return str_replace( 'a', 'b', $value );
  }
}

/**
 *
 * Sanitize title
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! function_exists( 'pb_settings_sanitize_title' ) ) {
  function pb_settings_sanitize_title( $value ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
    return sanitize_title( $value );
  }
}
