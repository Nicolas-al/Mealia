<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use App\Entity\ProductsOrdered;
use App\Repository\OrderRepository;
use App\Repository\ProductsOrderedRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use function Symfony\Component\DependencyInjection\Loader\Configurator\ref;

class PdfController extends AbstractController
{
    
    public $knpSnappyPdf;

    public function __construct(Pdf $pdf) {
        $this->pdf = $pdf;
    }

    /**
     * @Route("/compte/commande/facture/{order_number}/pdf", name="invoice_pdf")
     *
     */
    public function index(Request $request, $order_number, OrderRepository $repoOrder, ProductsOrderedRepository $repoProductOrdered)
    {
        $order = $repoOrder->findBy(array('orderNumber' => $order_number));
        $productsOrdered = $repoProductOrdered->findBy(array('orderNumber' => $order[0]->getId()));
        // $invoiceNumber = $order;
       
        
        
        // dd($order[0]);
        $html = $this->renderView('pdf/invoice.html.twig', [
            'order' => $order[0],
            'productsOrdered' => $productsOrdered
         ]);
       $filename = "invoice_pdf_with_snappy";
        $this->pdf->setOption('encoding', 'UTF8');
       return new Response(
           $this->pdf->getOutputFromHtml($html),
           200,
           array(
               'Content-Type' => 'application/pdf',
               'Content-Disposition' => 'inline; filename="'.$filename.'.pdf"'
         )
       );
 
       
    }
}
