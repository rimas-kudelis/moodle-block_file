<?php
defined('MOODLE_INTERNAL') || die();

class block_file extends block_base
{

    public function init()
    {
        $this->title = get_string('file', 'block_file');
    }

    public function get_content()
    {
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content         =  new stdClass;
        $this->content->text   = 'The content of the File block!';
        $this->content->footer = 'Footer here...';

        return $this->content;
    }
}
