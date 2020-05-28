<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @param string $this->name
 * @param string $this->number
 * @param string $this->number_regex
 */
declare (strict_types=1);
?>
<section>
    <header><h1>New Account</h1></header>
    <form method="post">
        <fieldset>
            <?php
                $this->_include('accounts/partials/accountFields.inc');

                $h = $this->template->getHelper('saveAndCancelButtons');
                echo $h->saveAndCancelButtons(parent::generateUri('accounts.index'));
            ?>
        </fieldset>
    </form>
</section>
