<?php
/*
 * This file is part of the Blackengine package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Black\Bundle\EventBundle\Form\ChoiceList;

use Symfony\Component\Form\Extension\Core\ChoiceList\LazyChoiceList;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;

/**
 * Class ExpectedList
 *
 * @package Black\Bundle\PageBundle\Form\ChoiceList
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class ExpectedList extends LazyChoiceList
{
    /**
     * @return \Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface|SimpleChoiceList
     */
    protected function loadChoiceList()
    {
        $array = array(
            'yes'   => 'event.admin.invitation.expected.choice.yes',
            'no'    => 'event.admin.invitation.expected.choice.no',
            'maybe' => 'event.admin.invitation.expected.choice.maybe'
        );

        $choices = new SimpleChoiceList($array);

        return $choices;
    }
}
