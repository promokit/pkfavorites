<?php
/**
* Promokit Favorites Module
*
* @package   alysum
* @version   2.3.0
* @author    https://promokit.eu
* @copyright Copyright since 2011 promokit.eu <@email:support@promokit.eu>
* @license   You only can use module, nothing more!
*/

namespace Promokit\Module\Pkfavorites\Admin;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Form extends \Module
{
    private $translator;
    
    public function __construct()
    {
        $this->translator = \Context::getContext()->getTranslator();
    }

    public function getForm($values)
    {
        $toggler = [
            [
                'id' => 'active_off',
                'value' => 0,
                'label' => $this->translator->trans('No', [], 'Admin.Global')
            ],[
                'id' => 'active_on',
                'value' => 1,
                'label' => $this->translator->trans('Yes', [], 'Admin.Global')
            ],
        ];

        $input = [];

        $input[] = [
            'type' => 'select',
            'label' => $this->translator->trans('Product Miniature Button Hook', [], 'Modules.Pkfavorites.Admin'),
            'name' => 'button_hook',
            'class' => 'custom-select',
            'options' => [
                'query' => [
                    [
                        'id' => 'displayProductButton',
                        'name' => 'displayProductButton',
                    ],[
                        'id' => 'displayProductButtonFixed',
                        'name' => 'displayProductButtonFixed'
                    ],
                ],
                'id' => 'id',
                'name' => 'name'
            ]
        ];

        if ($values['button_hook'] == 'displayProductButtonFixed') {
            $input[] = [
                'type' => 'select',
                'label' => $this->translator->trans('Product Miniature Button Position', [], 'Modules.Pkfavorites.Admin'),
                'name' => 'button_position',
                'class' => 'custom-select',
                'options' => [
                    'query' => [
                        [
                            'id' => 'pktopleft',
                            'name' => 'Top Left',
                        ],[
                            'id' => 'pktopright',
                            'name' => 'Top Right'
                        ],
                    ],
                    'id' => 'id',
                    'name' => 'name'
                ]
            ];
        }

        $input[] = [
            'type' => 'switch',
            'label' => $this->translator->trans('Display overall favorites number', [], 'Modules.Pkfavorites.Admin'),
            'name' => 'overall_number',
            'class' => 'ps-switch',
            'is_bool' => true,
            'values' => $toggler,
        ];

        $formConfig = [
            'form' => [
                'input' => $input,
                'submit' => [
                    'title' => $this->translator->trans('Save', [], 'Admin.Actions')
                ],
            ],
        ];

        return $formConfig;
    }
}