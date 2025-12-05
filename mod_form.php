<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Activity creation/editing form for the mod_extintmaxx plugin.
 *
 * @package   mod_extintmaxx
 * @copyright 2025, Sophi Dickens <sophidickens.e@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/extintmaxx/lib.php');

$profile = optional_param('profile', null, PARAM_INT);

use mod_extintmaxx\providers\provider_api_method_chains;

class mod_extintmaxx_mod_form extends \moodleform_mod {
    function definition() {
        $methodchains = new provider_api_method_chains();

        $coursesarray = array();

        $allcourses = $methodchains->get_all_provider_courses();
        foreach ($allcourses as $course) {
            $coursesarray[$course->id] = $course->providercoursename;
        }

        $profiles = array(
            'acci' => get_string('acci', 'extintmaxx'),
            // 'nali' => get_string('nali', 'extintmaxx')
        );
        global $CFG, $DB;
        $mform = $this->_form;

        $this->standard_coursemodule_elements();

        $mform->addElement('header', 'externalcourseselection', get_string('pluginspecificheader', 'extintmaxx'));

        $mform->addElement('select', 'providercourse', get_string('providercourse', 'extintmaxx'), $coursesarray);
        $mform->addHelpButton('providercourse', 'providercourse', 'extintmaxx');

        $mform->addElement('header', 'grading', get_string('grading', 'extintmaxx'));

        $mform->addElement('text', 'grade', get_string('grade', 'extintmaxx'));
        $mform->setType('grade', PARAM_INT);
        $mform->addHelpButton('grade', 'grade', 'extintmaxx');

        $this->add_action_buttons();
    }
}
