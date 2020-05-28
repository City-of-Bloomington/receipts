<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Accounts\UseCases\Add;

class Request
{
    public $number;
    public $name;

    public function __construct(?array $data=null)
    {
        if (!empty($data['number'])) { $this->number = $data['number']; }
        if (!empty($data['name'  ])) { $this->name   = $data['name'  ]; }
    }
}
