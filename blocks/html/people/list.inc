<?php
/**
 * @copyright 2009-2020 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array  $this->people
 */
?>
<table>
    <thead>
        <tr><th><?= $this->_('name' ); ?></th>
            <th><?= $this->_('email'); ?></th>
            <th><?= $this->_('pin'  ); ?></th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach ($this->people as $person) {
            $name = parent::escape("{$person->firstname} {$person->lastname}");
            $url  = parent::generateUri('people.view', ['id'=>$person->id]);
            $pin  = str_pad($person->pin, 4, '0', STR_PAD_LEFT);

            echo "
            <tr><td><a href=\"$url\">$name</a></td>
                <td><a href=\"$url\">{$person->email}</a></td>
                <td>$pin</td>
            </tr>
            ";
        }
    ?>
    </tbody>
</table>
