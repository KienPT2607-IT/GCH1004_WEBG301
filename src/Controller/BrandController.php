<?php

namespace App\Controller;

use App\Entity\Brand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrandController extends AbstractController
{
    #[Route('/index', name: 'brand_index')]
   public function genreIndex () {
      $brand = $this->getDoctrine()->getRepository(Brand::class)->findAll();
      return $this->render('brand/index.html.twig',
        [
            'brand' => $brand
        ]);
   } 
}