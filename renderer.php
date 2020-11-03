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
 * @package
 * @copyright Copyright (c)  Chartered College of Teaching. (http://www.charterd.college)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/mod/sectioncomplete/lib.php');

defined('MOODLE_INTERNAL') || die();

class mod_sectioncomplete_renderer extends \plugin_renderer_base {

    public function display_content(\cm_info $cm) {
        global $CFG, $DB, $USER;

        $title = $cm->name;

        //TODO Kieran, need to actually get the course, probably from $cm
        if(sectioncomplete_get_completion_state(2,$cm, $USER->id)) {
            $button = $CFG->wwwroot ."/mod/sectioncomplete/pix/tocomplete.svg";
        } else {
            $button = $CFG->wwwroot ."/mod/sectioncomplete/pix/completed.svg";
        }

        $data = [
                'title' => $title,
                'content' => format_module_intro('sectioncomplete', $cm->customdata, $cm->id),
                'buttonimage' => $button
        ];

        return $this->render_from_template('mod_sectioncomplete/content', $data);
    }

}