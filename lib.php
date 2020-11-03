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
 *
 * @package mod_sectioncomplete
 * @copyright Copyright (c) 2020 Chartered College of Teaching. (http://www.charterd.college)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * List of features that this plugin uses
 *
 * @param $feature
 * @return bool|null
 */
function mod_sectioncomplete_supports($feature) {
    switch ($feature) {
        case FEATURE_COMPLETION_HAS_RULES:
        case FEATURE_NO_VIEW_LINK:
        case FEATURE_MOD_INTRO:
        case FEATURE_IDNUMBER:
            return true;

        case FEATURE_MOD_ARCHETYPE:
           // return MOD_ARCHETYPE_RESOURCE;

        default: return null;
    }
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will create a new instance and return the id number
 * of the new instance.
 *
 * @global object
 * @param object $completedsection
 * @return bool|int
 */
function sectioncomplete_add_instance($completedsection) {
    global $DB;

    $completedsection->timemodified = time();

    return $DB->insert_record("sectioncomplete", $completedsection);
}

/**
 * Given an object containing all the necessary data,
 * (defined by the form in mod_form.php) this function
 * will update an existing instance with new data.
 *
 * @global object
 * @param object $completedsection
 * @return bool
 */
function sectioncomplete_update_instance($completedsection) {
    global $DB;

    $completedsection->name = get_label_name($completedsection);
    $completedsection->timemodified = time();
    $completedsection->id = $completedsection->instance;

    $completiontimeexpected = !empty($completedsection->completionexpected) ? $completedsection->completionexpected : null;
    \core_completion\api::update_completion_date_event($completedsection->coursemodule, 'label', $completedsection->id, $completiontimeexpected);

    return $DB->update_record("sectioncomplete", $completedsection);
}

/**
 * Given an ID of an instance of this module,
 * this function will permanently delete the instance
 * and any data that depends on it.
 *
 * @global object
 * @param int $id
 * @return bool
 */
function sectioncomplete_delete_instance($id) {
    global $DB;

    $DB->delete_records('sectioncomplete', ['id' => $id]);

}

/**
 * Given a course_module object, this function returns any
 * "extra" information that may be needed when printing
 * this activity in a course listing.
 * See get_array_of_activities() in course/lib.php
 *
 * @global object
 * @param object $cm
 * @return cached_cm_info|null
 */
function sectioncomplete_get_coursemodule_info($cm) {
    global $DB;

    $params = ['id' => $cm->instance];
    if (!$record = $DB->get_record('sectioncomplete', $params, 'id, name, intro, introformat')) {
        return false;
    }

    $result = new cached_cm_info();
    $result->name = $record->name;
    $result->customdata = new stdClass();
    $result->customdata->intro = $record->intro;
    $result->customdata->introformat = $record->introformat;

    return $result;
}

/**
 * Obtains the automatic completion state for this choice based on any conditions
 * in forum settings.
 *
 * @param object $course Course
 * @param object $cm Course-module
 * @param int $userid User ID
 * @param bool $type Type of comparison (or/and; can be used as return value if no conditions)
 * @return bool True if completed, false if not, $type if conditions not set.
 */
function sectioncomplete_get_completion_state($course, $cm, $userid) {
    global $DB;

    // Get choice details
    $complete = $DB->get_record('sectioncomplete', array('id'=>$cm->instance), '*',
            MUST_EXIST);

    // If completion option is enabled, evaluate it and return true/false
    if($complete->completebtnticked) {
        return $DB->record_exists('sectioncomplete_users', array(
                'sectionid'=>$complete->id, 'userid'=>$userid));
    } else {
        // Completion option is not enabled so just return $type
        return $type;
    }
}

function mod_sectioncomplete_cm_info_view(cm_info $cm) {
    global $PAGE;

    if (!$cm->uservisible) {
        return;
    }
    $renderer = $PAGE->get_renderer('sectioncomplete');
    $cm->set_content($renderer->display_content($cm), true);
}