<?php

add_action( 'admin_menu', 'myfaves_settings_page' );

function myfaves_settings_page() {
    add_options_page( 'My Faves', 'My Faves', 'manage_options', 'myfaves-options', 'myfaves_options_page' );
}

add_action( 'admin_init', 'myfaves_settings_init' );

function myfaves_settings_init(  ) {
    register_setting( 'myfaves', 'myfaves_custom_text' );
    register_setting( 'myfaves', 'myfaves_hide_tags' );
    register_setting( 'myfaves', 'myfaves_button_settings' );
    add_settings_section(
        'myfaves_settings_section',
        __( 'Settings', 'myfaves' ),
        'myfaves_settings_section_callback',
        'myfaves'
    );

    add_settings_field(
        'myfaves_custom_text_txt',
        __( 'Button Custom Text - Fave', 'myfaves' ),
        'myfaves_custom_text_render',
        'myfaves',
        'myfaves_settings_section'
    );
    add_settings_field(
        'myfaves_unfave_custom_text_txt',
        __( 'Button Custom Text - Unfave', 'myfaves' ),
        'myfaves_unfave_custom_text_render',
        'myfaves',
        'myfaves_settings_section'
    );

    add_settings_field(
        'myfaves_hide_tags_slct',
        __( 'Hide Tags', 'myfaves' ),
        'myfaves_hide_tags_render',
        'myfaves',
        'myfaves_settings_section'
    );
    add_settings_field(
        'myfaves_button_text_color_txt',
        __( 'Button Text Color', 'myfaves' ),
        'myfaves_button_text_color_render',
        'myfaves',
        'myfaves_settings_section'
    );
    add_settings_field(
        'myfaves_button_color_txt',
        __( 'Button Color', 'myfaves' ),
        'myfaves_button_color_render',
        'myfaves',
        'myfaves_settings_section'
    );
    add_settings_field(
        'myfaves_button_color_hover_txt',
        __( 'Button Hover Color', 'myfaves' ),
        'myfaves_button_color_hover_render',
        'myfaves',
        'myfaves_settings_section'
    );
    add_settings_field(
        'myfaves_button_border_radius_txt',
        __( 'Button Border Radius', 'myfaves' ),
        'myfaves_button_border_radius_render',
        'myfaves',
        'myfaves_settings_section'
    );
}

function myfaves_custom_text_render() {
    $options = get_option( 'myfaves_custom_text' );
    ?>
    <input type='text' name='myfaves_custom_text[myfaves_custom_text_txt]' value='<?php echo $options['myfaves_custom_text_txt']??'Save' ; ?>' maxlength=100  style="width:300px">
    <?php
}

function myfaves_unfave_custom_text_render() {
    $options = get_option( 'myfaves_custom_text' );
    ?>
    <input type='text' name='myfaves_custom_text[myfaves_unfave_custom_text_txt]' value='<?php echo $options['myfaves_unfave_custom_text_txt']??'Unsave'; ?>' maxlength=100  style="width:300px">
    <?php
}

function myfaves_hide_tags_render() {
    $options = get_option( 'myfaves_hide_tags' );
    ?>
    <select name='myfaves_hide_tags[myfaves_hide_tags_slct]' >
        <option value='no' <?php selected( $options['myfaves_hide_tags_slct']??'', 'no' ); ?> >No</option>
        <option value='yes' <?php selected( $options['myfaves_hide_tags_slct']??'', 'yes' ); ?> >Yes</option>
    </select>
    <?php
}
function myfaves_button_text_color_render() {
    $options = get_option( 'myfaves_button_settings' );
    ?>
    <input type='color' name='myfaves_button_settings[myfaves_button_text_color_txt]' value='<?php echo $options['myfaves_button_text_color_txt']??'#ffffff'; ?>' maxlength=25  style="width:100px">
    <?php
}
function myfaves_button_color_render() {
    $options = get_option( 'myfaves_button_settings' );
    ?>
    <input type='color' name='myfaves_button_settings[myfaves_button_color_txt]' value='<?php echo $options['myfaves_button_color_txt']??'#2ACCE5'; ?>' maxlength=25  style="width:100px">
    <?php
}
function myfaves_button_color_hover_render() {
    $options = get_option( 'myfaves_button_settings' );
    ?>
    <input type='color' name='myfaves_button_settings[myfaves_button_color_hover_txt]' value='<?php echo $options['myfaves_button_color_hover_txt']??'#333333'; ?>' maxlength=25  style="width:100px">
    <?php
}
function myfaves_button_border_radius_render() {
    $options = get_option( 'myfaves_button_settings' );
    ?>
    <input type='number' name='myfaves_button_settings[myfaves_button_border_radius_txt]' value='<?php echo $options['myfaves_button_border_radius_txt']??'5'; ?>' min=0 max=200  style="width:100px"> PX
    <?php
}
function myfaves_settings_section_callback() {
    echo __( 'Set My Faves button settings', 'myfaves' );
}

function myfaves_options_page() {
?>
    <form action='options.php' method='post'>

        <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

        <?php
        settings_fields( 'myfaves' );
        do_settings_sections( 'myfaves' );
        submit_button();
        ?>

    </form>
    <?php
}