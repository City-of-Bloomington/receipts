<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->name
 * @param string $this->number
 * @param string $this->number_regex
 */
declare (strict_types=1);

$h = $this->template->getHelper('field');

echo $h->field([
    'name'     => 'name',
    'id'       => 'name',
    'label'    => 'Name',
    'value'    => $this->name,
    'required' => true
]);

$pattern = '###-##-######-#####';
echo $h->field([
    'name'     => 'number',
    'id'       => 'number',
    'label'    => 'Number',
    'value'    => $this->number,
    'required' => true,
    'help'     => "Numbers must be formatted as $pattern",
    'attr'     => ['pattern' => $this->number_regex, 'placeholder' => $pattern]
]);
