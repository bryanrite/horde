<?php
/**
 * Wrapper for Horde_Cli that adds functionality specific to Horde
 * applications.
 *
 * Copyright 2011 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @todo Extend Horde_Cli when we can use LSB of PHP 5.3 in Horde_Cli::init().
 *
 * @author  Jan Schneider <jan@horde.org>
 * @package Core
 */
class Horde_Core_Cli
{
    /**
     * The Horde_Cli object we are wrapping.
     *
     * @var Horde_Cli
     */
    protected $_cli;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->_cli = Horde_Cli::init();
    }

    /**
     * Proxy method.
     */
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->_cli, $method), $args);
    }

    /**
     * Shows a prompt for a single configuration setting.
     *
     * @param Horde_Variables $vars  This is going to be populated with the
     *                               answers.
     * @param string $prefix         The current prefix for $name.
     * @param string $name           The name of the configuration setting.
     * @param array $field           A part of the parsed configuration tree as
     *                               returned from Horde_Config.
     */
    public function question($vars, $prefix, $name, $field)
    {
        if (!isset($field['desc'])) {
            // This is a <configsection>.
            foreach ($field as $sub => $sub_field) {
                $this->question($vars, $prefix . '__' . $name, $sub, $sub_field);
            }
            return;
        }

        $question = $field['desc'];
        $default = $field['default'];
        $values = null;
        if (isset($field['switch'])) {
            $values = array();
            foreach ($field['switch'] as $case => $case_field) {
                $values[$case] = $case_field['desc'];
            }
        } else {
            switch ($field['_type']) {
            case 'boolean':
                $values = array(true => 'Yes', false => 'No');
                $default = (int)$default;
                break;
            case 'enum':
                $values = $field['values'];
                break;
            }
            if (!empty($field['required'])) {
                $question .= $this->red('*');
            }
        }

        while (true) {
            if ($name == 'password') {
                $value = $this->passwordPrompt($question);
            } else {
                $value = $this->prompt($question, $values, $default);
            }
            if (empty($field['required']) || $value !== '') {
                break;
            } else {
                $this->writeln($this->red('This field is required.'));
            }
        }

        if (isset($field['switch']) &&
            !empty($field['switch'][$value]['fields'])) {
            foreach ($field['switch'][$value]['fields'] as $sub => $sub_field) {
                $this->question($vars, $prefix, $sub, $sub_field);
            }
        }

        $vars->set($prefix . '__' . $name, $value);
    }
}