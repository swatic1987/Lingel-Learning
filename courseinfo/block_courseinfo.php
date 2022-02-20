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
class block_courseinfo extends block_base {
    public function init() {
        $this->title = get_string('courseinfo', 'block_courseinfo');
    }
    public function applicable_formats() {
        return array('all' => false, 'mod' => false, 'tag' => false, 'my' => false, 'course' => true);
    }
    public function get_content() {
        global $CFG, $DB, $COURSE, $USER;
        if ($this->content !== null) {
            return $this->content;
        }
        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';
        $course = $this->page->course;
        $modinfo = get_fast_modinfo($course);
        $text = "";
        $sql = "SELECT coursemoduleid FROM {course_modules_completion} where userid=".$USER->id . " AND completionstate =1";
        $modulelist = $DB->get_records_sql($sql);
        $status = '';
        foreach ($modinfo->cms as $cm) {
            if (array_key_exists($cm->id, $modulelist)) {
                $status = "Completed";
            } else {
                $status = "Due";
            }
                $timestamp = $cm->added;
                $newdate = date("d-M-Y", $timestamp);
                $type = $cm->modname;
                $actname = $cm->name;
                $url = $cm->id." - ".'<a href="'.$CFG->wwwroot.'/mod/'.$type.'/view.php?id='.$cm->id.'">'
                       .$actname.'</a>'." - ".$newdate. " - ".$status;
                $text .= $url."</br>";
        }
        $this->content->text  = $text;
        return $this->content;
    }
}
