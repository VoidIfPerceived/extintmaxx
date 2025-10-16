<?php

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once $CFG->libdir.'/filelib.php';
require_once('../../lib/formslib.php');

class mod_extintmaxx_admin_actions_form extends moodleform {
    function definition() {
        $mform = $this->_form;

        $mform->addElement('button', 'reporting', get_string('viewreporting', 'extintmaxx'));
        $mform->registerNoSubmitButton('activity');
        $mform->addElement('button', 'activity', get_string('viewactivity', 'extintmaxx'));
    }
}