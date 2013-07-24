<?php

/*
 * This file is part of the Black package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Black\Bundle\EventBundle\Form\Type;

use Black\Bundle\CommonBundle\Form\Type\PostalAddressType as AbstractPostalAddressType;

/**
 * Class PostalAddressType
 *
 * @package Black\Bundle\EventBundle\Form\Type
 */
class PostalAddressType extends AbstractPostalAddressType
{
    public function getName()
    {
        return 'black_event_postaladdress';
    }
}
