<?php if ( ! defined( 'ABSPATH' ) ) exit; // Cannot access directly.
/**
 *
 * Field: switcher
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'WPDK_Settings_Field_switcher' ) ) {
  class  WPDK_Settings_Field_switcher extends WPDK_Settings_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $active     = ( ! empty( $this->value ) ) ? ' wpdk_settings--active' : '';
      $text_on    = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : esc_html__( 'On', 'woc-order-alert' );
      $text_off   = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : esc_html__( 'Off', 'woc-order-alert' );
      $text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: '. esc_attr( $this->field['text_width'] ) .'px;"': '';

      echo $this->field_before(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

      echo '<div class="wpdk_settings--switcher'. esc_attr( $active ) .'"'. $text_width .'>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      echo '<span class="wpdk_settings--on">'. esc_attr( $text_on ) .'</span>';
      echo '<span class="wpdk_settings--off">'. esc_attr( $text_off ) .'</span>';
      echo '<span class="wpdk_settings--ball"></span>';
      echo '<input type="hidden" name="'. esc_attr( $this->field_name() ) .'" value="'. esc_attr( $this->value ) .'"'. $this->field_attributes() .' />'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
      echo '</div>';

      echo ( ! empty( $this->field['label'] ) ) ? '<span class="wpdk_settings--label">'. esc_attr( $this->field['label'] ) . '</span>' : '';

      echo $this->field_after(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

  }
}
