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

namespace Promokit\Module\Pkfavorites\Controller;

use Symfony\Component\Form\Form;
use Promokit\Module\Pkfavorites\Db\Db;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Promokit\Module\Pkfavorites\Type\ConfigurationType;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;

class AdminController extends FrameworkBundleAdminController
{
    public function __construct()
    {
        $this->db = new Db;
        $this->data = $this->db->getConfig();

        // a hack to detect this controller in BO
        $_POST['controller'] = 'AdminPkfavorites';
    }

    public function displayForm(Request $request): Response
    {
        $form = $this->makeForm($request);

        return $this->renderForm($form->createView());
    }

    public function processForm(Request $request): Response
    {
        $form = $this->makeForm($request);

        if (!$form->isValid()) {
            $this->addFlash('error', $this->trans('Invalid data. Unable to save', 'Modules.Pkfavorites.Admin'));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->db->setConfig($form->getData());

            $this->addFlash(
                $response['type'],
                $response['message']
            );
        }

        return $this->renderForm($form->createView());
    }

    private function makeForm(Request $request): Form
    {
        return $this->createForm(ConfigurationType::class, $this->data)->handleRequest($request);
    }

    private function renderForm($form): Response
    {
        return $this->render('@Modules/pkfavorites/views/templates/admin/form/form.html.twig', [
            'form' => $form,
            'data' => $this->data,
        ]);
    }
}
