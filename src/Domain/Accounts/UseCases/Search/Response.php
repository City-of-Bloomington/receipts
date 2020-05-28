<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Accounts\UseCases\Search;

class Response
{
    public $accounts = [];
    public $errors   = [];
    public $total    = 0;

    public function __construct(array $accounts, int $total=null, array $errors=null)
    {
        $this->accounts = $accounts;
        $this->total    = $total;
        $this->errors   = $errors;
    }
}
