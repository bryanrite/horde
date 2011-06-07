<?php
/**
 * This Ulaform script allows for the fields in a form to be sorted in
 * a specific order, using the standard Horde_Form sorter field.
 *
 * Copyright 2003-2011 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 *
 * @author Marko Djukic <marko@oblo.com>
 */

require_once dirname(__FILE__) . '/lib/Application.php';
Horde_Registry::appInit('ulaform', array('admin' => true));

/* Get some variables. */
$vars = Horde_Variables::getDefaultVariables();
$form_id = $vars->get('form_id');
$formname = $vars->get('formname');
$fields = $ulaform_driver->getFieldsArray($form_id);

/* Set up the form object. */
$sortform = new Horde_Form($vars, _("Sort Fields"));

/* Set up the form. */
$sortform->setButtons(_("Save"));
$sortform->addVariable(_("Select the sort order of the fields"), 'field_order', 'sorter', false, false, null, array($fields, 12));
$sortform->addHidden('', 'form_id', 'int', true);

if ($formname) {
    $sortform->validate($vars);

    if ($sortform->isValid()) {
        $sortform->getInfo($vars, $info);
        $sort = $injector->getInstance('Ulaform_Factory_Driver')->create()->sortFields($info);
        if (is_a($sort, 'PEAR_Error')) {
            Horde::logMessage($sort, 'ERR');
            $notification->push(sprintf(_("Error saving fields. %s."), $sort->getMessage()), 'horde.error');
        } else {
            $notification->push(_("Field sort order saved."), 'horde.success');
            $url = Horde::url('fields.php', true);
            header('Location: ' . Horde_Util::addParameter($url, array('form_id' => $form_id), null, false));
            exit;
        }
    }
}

/* Render the form. */
$template = $injector->getInstance('Horde_Template');
Horde::startBuffer();
$sortform->renderActive(new Horde_Form_Renderer(), $vars, 'sortfields.php', 'post');
$template->set('main', Horde::endBuffer());

require $registry->get('templates', 'horde') . '/common-header.inc';
echo Horde::menu();
$notification->notify(array('listeners' => 'status'));
echo $template->fetch(ULAFORM_TEMPLATES . '/main/main.html');
require $registry->get('templates', 'horde') . '/common-footer.inc';