<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Accounts\Controllers;

use Domain\Accounts\UseCases\Update\Request as UpdateRequest;
use Web\Accounts\Views\UpdateView;
use Web\Controller;
use Web\View;

class UpdateController extends Controller
{
    public function __invoke(array $params): View
    {
        $account_id = (int)$params['id'];

        if ($account_id) {
            if (isset($_POST['name'])) {
                $update = $this->di->get('Domain\Accounts\UseCases\Update\Command');
                $req    = new UpdateRequest($_POST);
                $res    = $update($req);

                if (!$res->errors) {
                    header('Location: '.View::generateUrl('accounts.index'));
                    exit();
                }
            }

            if (!isset($req)) {
                $load = $this->di->get('Domain\Accounts\UseCases\Info\Command');
                $ir   = $load($account_id);
                if ($ir->errors) {
                    return new \Web\Views\NotFoundView();
                }
                $req  = new UpdateRequest((array)$ir->account);
            }

            return new UpdateView($req, isset($res) ? $res : null);
        }
        return new \Web\Views\NotFoundView();
    }
}
