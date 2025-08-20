<?php

require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/formslib.php');

defined('MOODLE_INTERNAL') || die();

/**
 * Settings for Maxx External Integration Plugin
 * Admin Settings:
 * Select Provider (Determines Form Requirements) : Ability to select an integrated provider from a menu
 * API Key (Read Requirements from Select Provider) : Text field for the API key of the selected provider
 * Provider Username (Read Requirements from Select Provider) : Text field for the username of the selected provider
 * Provider Password (Read Requirements from Select Provider) : Text field for the password of the selected provider
 */

class mod_extintmaxx_manage_form extends moodleform {
    public function definition() {
        global $CFG, $DB;
        $mform = $this->_form;

        $provideroptions = array(
            'acci' => get_string('acci', 'extintmaxx'),
            // 'nali' => get_string('nali', 'extintmaxx')
        );

        $profilelist = $DB->get_records('extintmaxx_admin', null, 'name ASC', 'id, name');

        $mform->addElement('select', 'provider', get_string('providersselection', 'extintmaxx'), $provideroptions);
        $mform->addHelpButton('provider', 'providersselection', 'extintmaxx');

        $mform->addElement('text', 'name', get_string('name', 'extintmaxx'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addHelpButton('name', 'name', 'extintmaxx');

        $mform->addElement('text', 'providerusername', get_string('providerusername', 'extintmaxx'));
        $mform->setType('providerusername', PARAM_TEXT);
        $mform->addHelpButton('providerusername', 'providerusername', 'extintmaxx');

        $mform->addElement('text', 'providerpassword', get_string('providerpassword', 'extintmaxx'));
        $mform->setType('providerpassword', PARAM_TEXT);
        $mform->addHelpButton('providerpassword', 'providerpassword', 'extintmaxx');

        $mform->addElement('text', 'url', get_string('environmenturl', 'extintmaxx'));
        $mform->setType('url', PARAM_TEXT);
        $mform->addHelpButton('url', 'environmenturl', 'extintmaxx');

        $this->add_action_buttons(
            false,
            get_string('insertprovidercredentials', 'extintmaxx')
        );
        
        // $mform->addElement('submit', 'mod_extintmaxx_manage_form', get_string('insertprovidercredentials', 'extintmaxx'));
    }
}