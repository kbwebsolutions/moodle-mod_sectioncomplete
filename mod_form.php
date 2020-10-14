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

defined('MOODLE_INTERNAL') || die();

require_once ($CFG->dirroot.'/course/moodleform_mod.php');

class mod_sectioncomplete_mod_form extends moodleform_mod {

    function definition() {
        global $PAGE;
        $mform = $this->_form;

        $mform->addElement('header', 'generalhdr', get_string('general'));

        $mform->addElement('text', 'name', get_string('sectiontitle', 'sectioncomplete'), ['maxlength' => 255]);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');


        $this->standard_intro_elements(get_string('message', 'sectioncomplete'));
        $mform->addRule('introeditor', null, 'required', null, 'client');

        // SectionComplete does not add "Show description" checkbox meaning that 'intro' is always shown on the course page.
        $mform->addElement('hidden', 'showdescription', 1);
        $mform->setType('showdescription', PARAM_INT);

        $this->standard_coursemodule_elements();

        //-------------------------------------------------------------------------------
        // buttons
        $this->add_action_buttons(true, false, null);

    }

    public function add_completion_rules() {
        $mform =& $this->_form;

        $mform->addElement('checkbox', 'completebtnticked', '', get_string('completebtnticked','sectioncomplete'));
        $mform->setDefault('completebtnticked', 1);

        return array('completebtnticked');
    }

    function completion_rule_enabled($data) {
        return !empty($data['completebtnticked']);
    }
}