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
 * Class VisibilityList
 *
 * @package Black\Bundle\PageBundle\Form\ChoiceList
 * @author  Alexandre Balmes <albalmes@gmail.com>
 * @license http://opensource.org/licenses/mit-license.php MIT
 */
class VisibilityList extends LazyChoiceList
{
    /**
     * @return \Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface|SimpleChoiceList
     */
    protected function loadChoiceList()
    {
        $array = array(
            'public'    => 'event.admin.event.visibility.choice.public',
            'private'   => 'event.admin.event.visibility.choice.private'
        );

        $choices = new SimpleChoiceList($array);

        return $choices;
    }
}
