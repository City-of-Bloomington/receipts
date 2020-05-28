<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Accounts\Controllers;

use Domain\Accounts\UseCases\Add\Request as AddRequest;
use Web\Accounts\Views\AddView;
use Web\Controller;
use Web\View;

class AddController extends Controller
{
    public function __invoke(array $params): View
    {
        if (isset($_POST['name'])) {
            $update = $this->di->get('Domain\Accounts\UseCases\Add\Command');
            $req    = new AddRequest($_POST);
            $res    = $update($req);

            if (!$res->errors) {
                header('Location: '.View::generateUrl('accounts.index'));
                exit();
            }
        }
        return new AddView(isset($req) ? $req : new AddRequest(),
                           isset($res) ? $res : null);
    }
}
