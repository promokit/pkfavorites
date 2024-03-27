<?php

/**
 * Promokit Favorites
 *
 * @package   Promokit
 * @version   3.0.0
 * @author    https://promokit.eu
 * @copyright Copyright Ⓒ Since 2011 promokit.eu <@email:support@promokit.eu>
 * @license   You only can use the module, nothing more!
 */
declare(strict_types=1);

namespace Promokit\Module\Pkfavorites\Type;

use Context;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use PrestaShopBundle\Form\Admin\Type\SwitchType;

class ConfigurationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translator = Context::getContext()->getTranslator();

        $builder
            ->add('button_hook', ChoiceType::class, [
                'label' => $translator->trans('Product Miniature Button Hook', [], 'Modules.Pkfavorites.Admin'),
                'required' => true,
                'choices' => [
                    'displayProductButton' => 'displayProductButton',
                    'displayProductButtonFixed' => 'displayProductButtonFixed',
                ],
                'attr' => [
                    'data-toggle' => 'select2',
                ],
            ])
            ->add('button_position', ChoiceType::class, [
                'label' => $translator->trans('Product Miniature Button Position', [], 'Modules.Pkfavorites.Admin'),
                'required' => true,
                'choices' => [
                    'Top Left' => 'pktopleft',
                    'Top Right' => 'pktopright',
                ],
                'attr' => [
                    'data-toggle' => 'select2',
                ],
            ])
            ->add('overall_number', SwitchType::class, [
                'label' => $translator->trans('Display overall favorites number', [], 'Modules.Pkfavorites.Admin'),
                'required' => false,
            ]);

            $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();
    
                if ($data['button_hook'] === 'displayProductButton') {
                    $form->remove('button_position');
                    $form->remove('overall_number');
                }
            });
    }
}
