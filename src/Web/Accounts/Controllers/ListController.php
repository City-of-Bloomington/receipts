<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Accounts\Controllers;

use Web\Accounts\Views\ListView;
use Web\Controller;
use Web\View;

class ListController extends Controller
{
    public function __invoke(array $params): View
    {
        $search   = $this->di->get('Domain\Accounts\UseCases\Search\Command');
        $response = $search();

        return new ListView($response);
    }
}
