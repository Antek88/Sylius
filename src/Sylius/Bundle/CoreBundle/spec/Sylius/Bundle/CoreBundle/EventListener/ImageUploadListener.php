<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\CoreBundle\EventListener;

use PHPSpec2\ObjectBehavior;

class ImageUploadListener extends ObjectBehavior
{
    /**
     * @param Sylius\Bundle\CoreBundle\Uploader\ImageUploaderInterface $uploader
     */
    function let($uploader)
    {
        $this->beConstructedWith($uploader);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\CoreBundle\EventListener\ImageUploadListener');
    }

    /**
     * @param Symfony\Component\EventDispatcher\GenericEvent                    $event
     * @param Sylius\Bundle\CoreBundle\Entity\Variant                           $variant
     * @param Sylius\Bundle\AssortmentBundle\Model\CustomizableProductInterface $product
     * @param Sylius\Bundle\CoreBundle\Model\ImageInterface                     $image
     */
    function it_uses_image_uploader_to_upload_images($event, $variant, $product, $image, $uploader)
    {
        $event->getSubject()->willReturn($product);
        $product->getMasterVariant()->willReturn($variant);
        $variant->getImages()->willReturn(array($image));
        $image->getId()->willReturn(null);
        $uploader->upload($image)->shouldBeCalled();

        $this->upload($event);
    }

    /**
     * @param Symfony\Component\EventDispatcher\GenericEvent $event
     */
    function it_throws_exception_if_event_subject_is_not_customizable_product($event, $uploader)
    {
        $event->getSubject()->willReturn($uploader);

        $this
            ->shouldThrow('InvalidArgumentException')
            ->duringUpload($event)
        ;
    }
}
