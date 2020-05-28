<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Accounts\UseCases\Info;

use Domain\Accounts\Entities\Account;

class Response
{
    public $account;
    public $errors;

    public function __construct(?Account $account=null, ?array $errors=null)
    {
        $this->account = $account;
        $this->errors  = $errors;
    }
}
