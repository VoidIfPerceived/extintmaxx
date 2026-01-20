<?php

namespace mod_extintmaxx\task;

use mod_extintmaxx\task\acci_grade_check;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../../../config.php');
require_once(__DIR__ . '/../../lib.php');

class timed_grade_check extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('timed_grade_check', 'mod_extintmaxx');
    }

    public function execute() {
        global $DB, $CFG;
        
        mtrace("\n");
        mtrace('Starting new scheduled grade check task for "External Integration for Maxx Content"...');
        
        try {
            require_once($CFG->dirroot . '/mod/extintmaxx/lib.php');
            
            $accigradecheck = new acci_grade_check();
            $result = $accigradecheck->execute();
            
            if ($result === false) {
                mtrace('Grade check failed to complete');
                return false;
            }
            
            mtrace('Grade check completed successfully');
            return true;
            
        } catch (\Exception $e) {
            mtrace('Error in grade check task: ' . $e->getMessage());
            return false;
        }
    }
}