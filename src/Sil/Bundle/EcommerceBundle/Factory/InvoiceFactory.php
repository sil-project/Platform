<?php

/*
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU GPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Sil\Bundle\EcommerceBundle\Factory;

use Blast\Bundle\CoreBundle\CodeGenerator\CodeGeneratorInterface;
use Knp\Snappy\GeneratorInterface;
use Sil\Bundle\EcommerceBundle\Entity\Invoice;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Exception\UnsupportedMethodException;
use Symfony\Component\Templating\EngineInterface;

/**
 * Factory for Invoice entities.
 *
 * @author Marcos Bezerra de Menezes <marcos.bezerra@libre-informatique.fr>
 */
class InvoiceFactory implements InvoiceFactoryInterface
{
    /**
     * @var CodeGeneratorInterface
     */
    private $codeGenerator;

    /**
     * @var GeneratorInterface
     */
    private $pdfGenerator;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var string
     */
    private $template;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @param CodeGeneratorInterface $codeGenerator
     * @param GeneratorInterface     $pdfGenerator
     * @param EngineInterface        $templating
     * @param string                 $template
     * @param string                 $rootDir
     */
    public function __construct($codeGenerator, $pdfGenerator, $templating, $template, $rootDir)
    {
        $this->codeGenerator = $codeGenerator;
        $this->pdfGenerator = $pdfGenerator;
        $this->templating = $templating;
        $this->template = $template;
        $this->rootDir = $rootDir;
    }

    /**
     * {@inheritdoc}
     *
     * @throws UnsupportedMethodException
     */
    public function createNew()
    {
        throw new UnsupportedMethodException('createNew');
    }

    /**
     * @param OrderInterface $order
     *
     * @return Invoice
     */
    public function createForOrder(OrderInterface $order, $type = Invoice::TYPE_DEBIT)
    {
        $invoice = new Invoice();
        $number = $this->codeGenerator::generate($invoice);

        $data = [
            'order'        => $order,
            'number'       => $number,
            'date'         => date('d/m/Y'),
            'base_dir'     => $this->rootDir . '/../web',
            'invoice_type' => $type,
        ];

        $html = $this->templating->render($this->template, $data);
        $pdf = $this->pdfGenerator->getOutputFromHtml($html);

        $total = $type === Invoice::TYPE_DEBIT ?
            $order->getTotal() :
            $order->getTotal() * -1
        ;

        $order->addInvoice($invoice);
        $invoice->setOrder($order);
        $invoice->setNumber($number);
        $invoice->setMimeType('application/pdf');
        $invoice->setFile($pdf);
        $invoice->setType($type);
        $invoice->setTotal($total);

        return $invoice;
    }
}
