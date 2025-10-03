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
        global $CFG, $DB, $PAGE;
        $mform = $this->_form;

    $profile = $this->_customdata['profile'] ?? null;
        $provider = $this->_customdata['provider'] ?? 'acci';

        $provideroptions = array(
            'acci' => get_string('acci', 'extintmaxx'),
            // 'nali' => get_string('nali', 'extintmaxx')
        );

        // Build an options array for the profile select: id => name.
        $profilelist = array();
        $profilerecords = $DB->get_records('extintmaxx_admin', null, 'id', 'id, name');
        foreach ($profilerecords as $p) {
            $profilelist[$p->id] = $p->name;
        }

        $providerelement = $mform->addElement('select', 'provider', get_string('providersselection', 'extintmaxx'), $provideroptions);
        $mform->addHelpButton('provider', 'providersselection', 'extintmaxx');
        $providerelement->setSelected($this->_customdata['provider']);

    $profileelement = $mform->addElement('select', 'profile', get_string('profile', 'extintmaxx'), $profilelist);
        $mform->addHelpButton('profile', 'profile', 'extintmaxx');
    $profileelement->setSelected($this->_customdata['profile']);

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

        // On change of provider selection, update the form fields:
    }
}