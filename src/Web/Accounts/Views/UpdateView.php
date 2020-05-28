<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Accounts\Views;

use Domain\Accounts\Metadata as Account;
use Domain\Accounts\UseCases\Update\Request;
use Domain\Accounts\UseCases\Update\Response;

use Web\Block;
use Web\Template;

class UpdateView extends Template
{
    public function __construct(Request $req, ?Response $res=null)
    {
        parent::__construct('default', 'html');

        if ($res && $res->errors) { $_SESSION['errorMessages'] = $res->errors; }

        $vars = ['number_regex' => Account::NUMBER_REGEX];
        foreach ($req as $k=>$v) { $vars[$k] = parent::escape($v); }

        $this->blocks = [
            new Block('accounts/updateForm.inc', $vars)
        ];
    }
}
