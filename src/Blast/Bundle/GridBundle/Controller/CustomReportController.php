<?php

/*
 * Copyright (C) 2015-2018 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Blast\Bundle\GridBundle\Controller;

use Blast\Bundle\GridBundle\Form\CustomReportType;
use Blast\Component\Grid\Model\CustomReportInterface;
use Blast\Component\Grid\Repository\CustomReportRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CustomReportController extends Controller
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var string
     */
    protected $customReportClass;

    /**
     * @var CustomReportRepositoryInterface
     */
    protected $customReportRepository;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function customReportsListAction(string $gridName): Response
    {
        $currentUser = $this->getUser();
        $currentRequest = $this->requestStack->getCurrentRequest();

        $customReports = $this->customReportRepository->getCustomReportsForGridNameAndOwner($gridName, $currentUser);

        return $this->render('@BlastUI/Grid/CustomReport/list.html.twig', [
            'customReports' => $customReports,
            'resetPath'     => $currentRequest->get('resetPath'),
        ]);
    }

    public function buildCustomReportFormAction(string $gridName): Response
    {
        $currentRequest = $this->requestStack->getCurrentRequest();
        $uri = $currentRequest->get('uri');

        $customReportForm = $this->getForm($gridName, $uri);

        return $this->render('@BlastUI/Grid/CustomReport/form.html.twig', [
            'form' => $customReportForm->createView(),
        ]);
    }

    public function handleCustomReportFormAction(string $gridName): Response
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        $customReportForm = $this->getForm($gridName);

        $customReportForm->handleRequest($currentRequest);

        if ($customReportForm->isValid()) {
            $customReportFormData = $customReportForm->getData();

            $customReport = new $this->customReportClass(
                $customReportFormData['gridName'],
                $customReportFormData['uri'],
                $customReportFormData['name'],
                $customReportFormData['public']
            );

            $currentUser = $this->getUser();

            if ($currentUser !== null) {
                $customReport->setOwner($currentUser);
            }

            $this->customReportRepository->add($customReport);

            return $this->redirect($currentRequest->headers->get('referer'));
        }

        return $this->render('@BlastUI/Grid/CustomReport/form.html.twig', [
            'form' => $customReportForm->createView(),
        ]);
    }

    public function removeCustomReportAction(string $customReportId): Response
    {
        $customReport = $this->findCustomReportOr404($customReportId);

        $currentUser = $this->getUser();

        if ($currentUser !== $customReport->getOwner()) {
            $this->addFlash('error', $this->get('translator')->trans('blast.ui.grid.custom_reports.form.this_custom_report_does_not_belongs_to_you'));
        } else {
            $this->customReportRepository->remove($customReport);

            $currentRequest = $this->requestStack->getCurrentRequest();

            $this->addFlash('success', $this->get('translator')->trans('blast.ui.grid.custom_reports.form.report_removed'));
        }

        return $this->redirect($currentRequest->headers->get('referer'));
    }

    private function getForm(string $gridName, ?string $uri = null): FormInterface
    {
        return $this->formFactory->create(CustomReportType::class, [], [
            'gridName' => $gridName,
            'uri'      => $uri,
            'action'   => $this->router->generate('blast_grid_custom_report_handle_form', ['gridName' => $gridName]),
        ]);
    }

    private function findCustomReportOr404(string $customReportId): CustomReportInterface
    {
        try {
            return $this->customReportRepository->get($customReportId);
        } catch (\InvalidArgumentException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param RouterInterface
     */
    public function setRouter(RouterInterface $router): void
    {
        $this->router = $router;
    }

    /**
     * @param CustomReportRepositoryInterface
     */
    public function setCustomReportRepository(CustomReportRepositoryInterface $customReportRepository): void
    {
        $this->customReportRepository = $customReportRepository;
    }

    /**
     * @param string
     */
    public function setCustomReportClass(string $customReportClass): void
    {
        $this->customReportClass = $customReportClass;
    }

    /**
     * @param TranslatorInterface
     */
    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }
}
