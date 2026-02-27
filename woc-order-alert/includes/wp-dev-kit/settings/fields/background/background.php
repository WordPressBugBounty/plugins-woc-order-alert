<?php if ( ! defined( 'ABSPATH' ) ) exit; // Cannot access directly.
/**
 *
 * Field: background
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'WPDK_Settings_Field_background' ) ) {
  class WPDK_Settings_Field_background extends WPDK_Settings_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
      parent::__construct( $field, $value, $unique, $where, $parent );
    }

    public function render() {

      $args                             = wp_parse_args( $this->field, array(
        'background_color'              => true,
        'background_image'              => true,
        'background_position'           => true,
        'background_repeat'             => true,
        'background_attachment'         => true,
        'background_size'               => true,
        'background_origin'             => false,
        'background_clip'               => false,
        'background_blend_mode'         => false,
        'background_gradient'           => false,
        'background_gradient_color'     => true,
        'background_gradient_direction' => true,
        'background_image_preview'      => true,
        'background_auto_attributes'    => false,
        'compact'                       => false,
        'background_image_library'      => 'image',
        'background_image_placeholder'  => esc_html__( 'Not selected', 'woc-order-alert' ),
      ) );

      if ( $args['compact'] ) {
        $args['background_color']           = false;
        $args['background_auto_attributes'] = true;
      }

      $default_value                    = array(
        'background-color'              => '',
        'background-image'              => '',
        'background-position'           => '',
        'background-repeat'             => '',
        'background-attachment'         => '',
        'background-size'               => '',
        'background-origin'             => '',
        'background-clip'               => '',
        'background-blend-mode'         => '',
        'background-gradient-color'     => '',
        'background-gradient-direction' => '',
      );

      $default_value = ( ! empty( $this->field['default'] ) ) ? wp_parse_args( $this->field['default'], $default_value ) : $default_value;

      $this->value = wp_parse_args( $this->value, $default_value );

      echo $this->field_before(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

      echo '<div class="wpdk_settings--background-colors">';

      //
      // Background Color
      if ( ! empty( $args['background_color'] ) ) {

        echo '<div class="wpdk_settings--color">';

        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="wpdk_settings--title">'. esc_html__( 'From', 'woc-order-alert' ) .'</div>' : '';

        WPDK_Settings::field( array(
          'id'      => 'background-color',
          'type'    => 'color',
          'default' => $default_value['background-color'],
        ), $this->value['background-color'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Gradient Color
      if ( ! empty( $args['background_gradient_color'] ) && ! empty( $args['background_gradient'] ) ) {

        echo '<div class="wpdk_settings--color">';

        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="wpdk_settings--title">'. esc_html__( 'To', 'woc-order-alert' ) .'</div>' : '';

        WPDK_Settings::field( array(
          'id'      => 'background-gradient-color',
          'type'    => 'color',
          'default' => $default_value['background-gradient-color'],
        ), $this->value['background-gradient-color'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      //
      // Background Gradient Direction
      if ( ! empty( $args['background_gradient_direction'] ) && ! empty( $args['background_gradient'] ) ) {

        echo '<div class="wpdk_settings--color">';

        echo ( ! empty( $args['background_gradient'] ) ) ? '<div class="wpdk_settings---title">'. esc_html__( 'Direction', 'woc-order-alert' ) .'</div>' : '';

        WPDK_Settings::field( array(
          'id'          => 'background-gradient-direction',
          'type'        => 'select',
          'options'     => array(
            ''          => esc_html__( 'Gradient Direction', 'woc-order-alert' ),
            'to bottom' => esc_html__( '&#8659; top to bottom', 'woc-order-alert' ),
            'to right'  => esc_html__( '&#8658; left to right', 'woc-order-alert' ),
            '135deg'    => esc_html__( '&#8664; corner top to right', 'woc-order-alert' ),
            '-135deg'   => esc_html__( '&#8665; corner top to left', 'woc-order-alert' ),
          ),
        ), $this->value['background-gradient-direction'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      echo '</div>';

      //
      // Background Image
      if ( ! empty( $args['background_image'] ) ) {

        echo '<div class="wpdk_settings--background-image">';

        WPDK_Settings::field( array(
          'id'          => 'background-image',
          'type'        => 'media',
          'class'       => 'wpdk_settings-assign-field-background',
          'library'     => $args['background_image_library'],
          'preview'     => $args['background_image_preview'],
          'placeholder' => $args['background_image_placeholder'],
          'attributes'  => array( 'data-depend-id' => $this->field['id'] ),
        ), $this->value['background-image'], $this->field_name(), 'field/background' );

        echo '</div>';

      }

      $auto_class   = ( ! empty( $args['background_auto_attributes'] ) ) ? ' wpdk_settings--auto-attributes' : '';
      $hidden_class = ( ! empty( $args['background_auto_attributes'] ) && empty( $this->value['background-image']['url'] ) ) ? ' wpdk_settings--attributes-hidden' : '';

      echo '<div class="wpdk_settings--background-attributes'. esc_attr( $auto_class . $hidden_class ) .'">';

      //
      // Background Position
      if ( ! empty( $args['background_position'] ) ) {

        WPDK_Settings::field( array(
          'id'              => 'background-position',
          'type'            => 'select',
          'options'         => array(
            ''              => esc_html__( 'Background Position', 'woc-order-alert' ),
            'left top'      => esc_html__( 'Left Top', 'woc-order-alert' ),
            'left center'   => esc_html__( 'Left Center', 'woc-order-alert' ),
            'left bottom'   => esc_html__( 'Left Bottom', 'woc-order-alert' ),
            'center top'    => esc_html__( 'Center Top', 'woc-order-alert' ),
            'center center' => esc_html__( 'Center Center', 'woc-order-alert' ),
            'center bottom' => esc_html__( 'Center Bottom', 'woc-order-alert' ),
            'right top'     => esc_html__( 'Right Top', 'woc-order-alert' ),
            'right center'  => esc_html__( 'Right Center', 'woc-order-alert' ),
            'right bottom'  => esc_html__( 'Right Bottom', 'woc-order-alert' ),
          ),
        ), $this->value['background-position'], $this->field_name(), 'field/background' );

      }

      //
      // Background Repeat
      if ( ! empty( $args['background_repeat'] ) ) {

        WPDK_Settings::field( array(
          'id'          => 'background-repeat',
          'type'        => 'select',
          'options'     => array(
            ''          => esc_html__( 'Background Repeat', 'woc-order-alert' ),
            'repeat'    => esc_html__( 'Repeat', 'woc-order-alert' ),
            'no-repeat' => esc_html__( 'No Repeat', 'woc-order-alert' ),
            'repeat-x'  => esc_html__( 'Repeat Horizontally', 'woc-order-alert' ),
            'repeat-y'  => esc_html__( 'Repeat Vertically', 'woc-order-alert' ),
          ),
        ), $this->value['background-repeat'], $this->field_name(), 'field/background' );

      }

      //
      // Background Attachment
      if ( ! empty( $args['background_attachment'] ) ) {

        WPDK_Settings::field( array(
          'id'       => 'background-attachment',
          'type'     => 'select',
          'options'  => array(
            ''       => esc_html__( 'Background Attachment', 'woc-order-alert' ),
            'scroll' => esc_html__( 'Scroll', 'woc-order-alert' ),
            'fixed'  => esc_html__( 'Fixed', 'woc-order-alert' ),
          ),
        ), $this->value['background-attachment'], $this->field_name(), 'field/background' );

      }

      //
      // Background Size
      if ( ! empty( $args['background_size'] ) ) {

        WPDK_Settings::field( array(
          'id'        => 'background-size',
          'type'      => 'select',
          'options'   => array(
            ''        => esc_html__( 'Background Size', 'woc-order-alert' ),
            'cover'   => esc_html__( 'Cover', 'woc-order-alert' ),
            'contain' => esc_html__( 'Contain', 'woc-order-alert' ),
            'auto'    => esc_html__( 'Auto', 'woc-order-alert' ),
          ),
        ), $this->value['background-size'], $this->field_name(), 'field/background' );

      }

      //
      // Background Origin
      if ( ! empty( $args['background_origin'] ) ) {

        WPDK_Settings::field( array(
          'id'            => 'background-origin',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Origin', 'woc-order-alert' ),
            'padding-box' => esc_html__( 'Padding Box', 'woc-order-alert' ),
            'border-box'  => esc_html__( 'Border Box', 'woc-order-alert' ),
            'content-box' => esc_html__( 'Content Box', 'woc-order-alert' ),
          ),
        ), $this->value['background-origin'], $this->field_name(), 'field/background' );

      }

      //
      // Background Clip
      if ( ! empty( $args['background_clip'] ) ) {

        WPDK_Settings::field( array(
          'id'            => 'background-clip',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Clip', 'woc-order-alert' ),
            'border-box'  => esc_html__( 'Border Box', 'woc-order-alert' ),
            'padding-box' => esc_html__( 'Padding Box', 'woc-order-alert' ),
            'content-box' => esc_html__( 'Content Box', 'woc-order-alert' ),
          ),
        ), $this->value['background-clip'], $this->field_name(), 'field/background' );

      }

      //
      // Background Blend Mode
      if ( ! empty( $args['background_blend_mode'] ) ) {

        WPDK_Settings::field( array(
          'id'            => 'background-blend-mode',
          'type'          => 'select',
          'options'       => array(
            ''            => esc_html__( 'Background Blend Mode', 'woc-order-alert' ),
            'normal'      => esc_html__( 'Normal', 'woc-order-alert' ),
            'multiply'    => esc_html__( 'Multiply', 'woc-order-alert' ),
            'screen'      => esc_html__( 'Screen', 'woc-order-alert' ),
            'overlay'     => esc_html__( 'Overlay', 'woc-order-alert' ),
            'darken'      => esc_html__( 'Darken', 'woc-order-alert' ),
            'lighten'     => esc_html__( 'Lighten', 'woc-order-alert' ),
            'color-dodge' => esc_html__( 'Color Dodge', 'woc-order-alert' ),
            'saturation'  => esc_html__( 'Saturation', 'woc-order-alert' ),
            'color'       => esc_html__( 'Color', 'woc-order-alert' ),
            'luminosity'  => esc_html__( 'Luminosity', 'woc-order-alert' ),
          ),
        ), $this->value['background-blend-mode'], $this->field_name(), 'field/background' );

      }

      echo '</div>';

      echo $this->field_after(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

    public function output() {

      $output    = '';
      $bg_image  = array();
      $important = ( ! empty( $this->field['output_important'] ) ) ? '!important' : '';
      $element   = ( is_array( $this->field['output'] ) ) ? join( ',', $this->field['output'] ) : $this->field['output'];

      // Background image and gradient
      $background_color        = ( ! empty( $this->value['background-color']              ) ) ? $this->value['background-color']              : '';
      $background_gd_color     = ( ! empty( $this->value['background-gradient-color']     ) ) ? $this->value['background-gradient-color']     : '';
      $background_gd_direction = ( ! empty( $this->value['background-gradient-direction'] ) ) ? $this->value['background-gradient-direction'] : '';
      $background_image        = ( ! empty( $this->value['background-image']['url']       ) ) ? $this->value['background-image']['url']       : '';


      if ( $background_color && $background_gd_color ) {
        $gd_direction   = ( $background_gd_direction ) ? $background_gd_direction .',' : '';
        $bg_image[] = 'linear-gradient('. $gd_direction . $background_color .','. $background_gd_color .')';
        unset( $this->value['background-color'] );
      }

      if ( $background_image ) {
        $bg_image[] = 'url('. $background_image .')';
      }

      if ( ! empty( $bg_image ) ) {
        $output .= 'background-image:'. implode( ',', $bg_image ) . $important .';';
      }

      // Common background properties
      $properties = array( 'color', 'position', 'repeat', 'attachment', 'size', 'origin', 'clip', 'blend-mode' );

      foreach ( $properties as $property ) {
        $property = 'background-'. $property;
        if ( ! empty( $this->value[$property] ) ) {
          $output .= $property .':'. $this->value[$property] . $important .';';
        }
      }

      if ( $output ) {
        $output = $element .'{'. $output .'}';
      }

      $this->parent->output_css .= $output;

      return $output;

    }

  }
}
