<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Accounts\UseCases\Search;

use Domain\Accounts\DataStorage\AccountsRepository;

class Command
{
    private $repo;

    public function __construct(AccountsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(): Response
    {
        try {
            $result = $this->repo->find();
            return new Response($result['rows'], $result['total']);
        }
        catch (\Exception $e) {
            return new Response([], null, [$e->getMessage()]);
        }
    }
}