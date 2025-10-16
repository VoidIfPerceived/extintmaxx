<?php

namespace mod_extintmaxx\task;
defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '../../../lib.php');

class acci_grade_check extends \core\task\adhoc_task {

    public function execute() {
        echo('Getting instances of "External Integration for Maxx Content..."');
        $instances = $this->get_provider_instances();
        foreach ($instances as $instance) {
            extintmaxx_update_grades($instance);
        }
    }

    function get_provider_instances() {
        global $DB;
        $providerinstances = $DB->get_records(
            'extintmaxx',
            ['provider' => 'acci']
        );
        return $providerinstances;
    }
}
