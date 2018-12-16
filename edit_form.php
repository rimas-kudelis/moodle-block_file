<?php

class block_file_edit_form extends block_edit_form
{
    protected function specific_definition($mform)
    {
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_file'));
        $mform->setDefault('config_title', get_string('file', 'block_file'));
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('filemanager', 'config_select_file', get_string('configfile', 'block_file'), null, array('subdirs' => false, 'maxfiles' => 1));
        $mform->setType('config_select_file', PARAM_RAW);
        $mform->addElement('text', 'config_height', get_string('configheight', 'block_file'));
        $mform->setType('config_height', PARAM_TEXT);
    }
}
