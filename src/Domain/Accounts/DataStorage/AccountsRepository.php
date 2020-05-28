<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Accounts\DataStorage;

use Domain\Accounts\Entities\Account;

interface AccountsRepository
{
    public function load(int $id): Account;
    public function find(): array;
    public function save(Account $account): int;
}
