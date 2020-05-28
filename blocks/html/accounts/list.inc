<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param array $this->accounts
 */
declare (strict_types=1);
use Web\Block;

$userCanAdd  = parent::isAllowed('accounts', 'add'   );
$userCanEdit = parent::isAllowed('accounts', 'update');
$h           = $this->template->getHelper('buttonLink');
?>
<section>
    <header>
        <h1>Accounts</h1>
        <?php
            if ($userCanAdd) {
                $block = new Block('toolbox.inc', ['actions' => [
                    'New Account' => [
                        'uri'   => parent::generateUri('accounts.add'),
                        'class' => 'add'
                    ]
                ]]);
                echo $block->render('html', $this->template);
            }
        ?>
    </header>
    <table>
    <?php
        foreach ($this->accounts as $a) {
            $name   = parent::escape($a->name);
            $number = parent::escape($a->number);
            $button = '';
            if ($userCanEdit) {
                $button = $h->buttonLink(
                    parent::generateUri('accounts.update', ['id'=>$a->id]),
                    'Edit',
                    'edit'
                );
            }

            echo "
            <tr><td>$button</td>
                <td>$name</td>
                <td>$number</td>
            </tr>
            ";
        }
    ?>
    </table>
</section>
