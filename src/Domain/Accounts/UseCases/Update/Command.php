<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Domain\Accounts\UseCases\Update;

use Domain\Accounts\Metadata;
use Domain\Accounts\Entities\Account;
use Domain\Accounts\DataStorage\AccountsRepository;

class Command
{
    private $repo;

    public function __construct(AccountsRepository $repository)
    {
        $this->repo = $repository;
    }

    public function __invoke(Request $req): Response
    {
        $errors = $this->validate($req);
        if ($errors) { return new Response(null, $errors); }

        try {
            $id  = $this->repo->save(new Account((array)$req));
            $res = new Response($id);
        }
        catch (\Exception $e) {
            $res = new Response(null, [$e->getMessage()]);
        }
        return $res;
    }

    private function validate(Request $req): array
    {
        $errors = [];
        if (!$req->id    ) { $errors[] = 'missingId';     }
        if (!$req->name  ) { $errors[] = 'missingName';   }
        if (!$req->number) { $errors[] = 'missingNumber'; }


        if (!preg_match('/'.Metadata::NUMBER_REGEX.'/', $req->number)) { $errors[] = 'invalidNumber'; }
        return $errors;
    }
}
