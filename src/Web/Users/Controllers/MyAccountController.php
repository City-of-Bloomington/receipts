<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Web\Users\Controllers;

use Web\Authentication\Auth;
use Web\Controller;
use Web\View;
use Web\Users\Views\InfoView;

class MyAccountController extends Controller
{
    public function __invoke(array $params): View
    {
        $user = Auth::getAuthenticatedUser($this->di->get('Web\Authentication\AuthenticationService'));
        if ($user) {
            return new InfoView($user);
        }

        return new \Web\Views\ForbiddenView();
    }
}
