<?php
/**
 * Plugin Name: Blondcoin Donation Plugin
 * Plugin URI: https://www.blondcoin.com
 * Description: Collect more Blondcoin donations online with this WordPress plugin.
 * Author: Blondcoin & alexss23
 * Version: 0.0.1
 * License: GPLv2 or later
 */

function blondcoin_donation_activation() {
    $options_array = array(
        'blondcoin_account' => '',
        'target' => '_blank'
    );
    if (get_option('blondcoin_donation_options') !== false) {
        update_option('blondcoin_donation_options', $options_array);
    } else {
        add_option('blondcoin_donation_options', $options_array);
    }
}

register_activation_hook(__FILE__, 'blondcoin_donation_activation');

function blondcoin_donation_link_shortcode() {
    $options = get_option('blondcoin_donation_options');

    return '<a href="https://blondcoin.com/blondcoin-donation.html?to=' . $options['blondcoin_account']
              . '" title="Donate blondcoin" target="' . $options['target'] . '">'
              . $options['blondcoin_account'] . '</a>';
}

add_shortcode('blondcoin_donation_link', 'blondcoin_donation_link_shortcode');

add_filter('widget_text', 'do_shortcode');

function blondcoin_donation_callback() {
}

function blondcoin_donation_account_callback() {
    $options = get_option('blondcoin_donation_options');
    echo "<input class='regular-text ltr' name='blondcoin_donation_options[blondcoin_account]' id='blondcoin_account' type='text' value='{$options['blondcoin_account']}'/>";
}

function blondcoin_donation_target_callback() {
    $options = get_option('blondcoin_donation_options');
    $target = array(
        '_blank' => 'Blank',
        '_self' => 'Self'
    );
    ?>
    <select id='target' name='blondcoin_donation_options[target]'>
        <?php
        foreach ($target as $key => $label) :
            if ($key == $options['target']) {
                $selected = "selected='selected'";
            } else {
                $selected = '';
            }
            echo "<option {$selected} value='{$key}'>{$label}</option>";
        endforeach;
        ?>
    </select>
    <p class="description"><?php _e('Select "Blank" to open Blondcoin.com in a new tab or select "Self" to open Blondcoin.com in the same tab.', 'blondcoin-donation') ?></p>
    <?php
}

function blondcoin_donation_settings_and_fields() {

    register_setting(
            'blondcoin_donation_options', 'blondcoin_donation_options'
    );

    add_settings_section(
            'donate_plugin_main_section', __('Main Settings', 'blondcoin-donation'), 'blondcoin_donation_callback', __FILE__
    );

    add_settings_field(
            'blondcoin_account', __('Ethreum account:', 'blondcoin-donation'), 'blondcoin_donation_account_callback', __FILE__, 'donate_plugin_main_section', array('label_for' => 'blondcoin_account')
    );

    add_settings_field(
            'target', __('Open Blondcoin.com:', 'blondcoin-donation'), 'blondcoin_donation_target_callback', __FILE__, 'donate_plugin_main_section', array('label_for' => 'target')
    );
}

add_action('admin_init', 'blondcoin_donation_settings_and_fields');

function blondcoin_donation_options_init() {
    add_options_page(
            __('Blondcoin Donation', 'blondcoin-donation'), __('Blondcoin Donation', 'blondcoin-donation'), 'administrator', __FILE__, 'blondcoin_donation_options_page'
    );
}

add_action('admin_menu', 'blondcoin_donation_options_init');

function blondcoin_donation_options_page() {
    ?>
    <div class="wrap">
        <h2><?php _e('blondcoin Donation Settings', 'blondcoin-donation') ?></h2>
        <form method="post" action="options.php" enctype="multipart/form-data">
            <?php
            settings_fields('blondcoin_donation_options');
            do_settings_sections(__FILE__);
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
