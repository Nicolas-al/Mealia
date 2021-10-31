<?php

namespace App\Service\Stock;

use App\Entity\ZeroStock;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

Class StockService
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    

    public function removeZeroStock($product)
    {
        // on vérifie si un produit à son stock qui est à 0
        $repoZeroStock = $this->em->getRepository(ZeroStock::class);
        $zeroStock = $repoZeroStock->findOneBy(['productId' => $product->getId()]);

        if($product->getSize()->getStockSizeOne() > 0 || $product->getSize()->getStockSizeTwo() || $product->getSize()->getStockSizeThree()){
            if($zeroStock){
                dump('ok');
                $this->em->remove($zeroStock);
                $this->em->flush();
            }
        }
    }
}