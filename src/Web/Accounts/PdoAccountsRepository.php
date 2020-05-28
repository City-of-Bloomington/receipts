<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Accounts;

use Aura\SqlQuery\Common\SelectInterface;
use Web\PdoRepository;

use Domain\Accounts\Entities\Account;
use Domain\Accounts\DataStorage\AccountsRepository;

class PdoAccountsRepository extends PdoRepository implements AccountsRepository
{
    const TABLE = 'accounts';
    public static $DEFAULT_SORT = ['name'];

    public function columns()
    {
        static $columns;
        if (!$columns) { $columns = array_keys(get_class_vars('Domain\Accounts\Entities\Account')); }
        return $columns;
    }

    public function load(int $id): Account
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from(self::TABLE);
        $select->where('id=?', $id);
        $result = $this->performSelect($select);
        if (count($result['rows'])) {
            return new Account($result['rows'][0]);
        }
        throw new \Exception('accounts/unknown');
    }

    public static function hydrate(array $row): Account { return new Account($row); }

    /**
     * Look for accounts using wildcard matching of fields
     */
    public function find(): array
    {
        $select = $this->queryFactory->newSelect();
        $select->cols($this->columns())->from(self::TABLE);

        return parent::performHydratedSelect($select,
                                             __CLASS__.'::hydrate',
                                             self::$DEFAULT_SORT);
    }

    public function save(Account $account): int
    {
        return parent::saveToTable((array)$account, self::TABLE);
    }
}
