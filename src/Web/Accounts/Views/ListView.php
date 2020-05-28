<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Web\Accounts\Views;

use Domain\Accounts\UseCases\Search\Response;

use Web\Block;
use Web\Template;

class ListView extends Template
{
    public function __construct(Response $response)
    {
        $format = !empty($_REQUEST['format']) ? $_REQUEST['format'] : 'html';
        parent::__construct('default', $format);

        if ($response->errors) {
            $_SESSION['errorMessages'] = $response->errors;
        }

        $this->blocks = [
            new Block('accounts/list.inc', ['accounts' => $response->accounts])
        ];
    }
}
