<?php

defined('MOODLE_INTERNAL') || die();
require_once('../../lib/formslib.php');
use mod_extintmaxx\providers\acci;
use mod_extintmaxx\providers\provider_api_method_chains;

class mod_extintmaxx_reporting_form extends moodleform {
    public function definition() {
        global $DB, $USER;
        $acci = new acci();
        $methodchains = new provider_api_method_chains();
        $adminrecord = $methodchains->admin_record_exists('acci');
        $accirecords = $methodchains->provider_record_exists('acci');
        $mform = $this->_form;

        // Add a header.
        $mform->addElement('header', 'reportingheader', get_string('reporting', 'extintmaxx'));

        // Add a select element for the provider course.
        $mform->addElement('select', 'courseid', get_string('providercourse'), $this->get_all_provider_courses('acci'));

        // Add a select element for the student.
        $mform->addElement('select', 'studentid', get_string('student'), get_allowed_students($this->_customdata['caplevel'], $this->_customdata['instances']));
        $mform->setDefault('studentid', 0);

        // Add a submit button.
        $this->add_action_buttons(true, get_string('generate', 'extintmaxx'));
    }
    function get_all_provider_courses($provider) {
        global $DB;
        $methodchains = new provider_api_method_chains();

        $providercourses = $methodchains->provider_record_exists($provider);
        if ($providercourses) {
            $courses = array();
            foreach ($providercourses as $course) {
                $providercourseid = $course->providercourseid;
                $providercoursename = $course->providercoursename;
                $courses[$providercourseid] = $providercoursename;  
            }
            return $courses;
        } else {
            return false;
        }
    }
}