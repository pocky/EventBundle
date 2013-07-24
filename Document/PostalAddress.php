<?php

/*
 * This file is part of the Black package.
 *
 * (c) Alexandre Balmes <albalmes@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Black\Bundle\EventBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Black\Bundle\CommonBundle\Document\PostalAddress as AbstractPostalAddress;

/**
 * @ODM\EmbeddedDocument
 */
class PostalAddress extends AbstractPostalAddress
{
}
