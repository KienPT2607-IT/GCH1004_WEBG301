<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/brand')]
class BrandController extends AbstractController
{
    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/admin/index', name: 'brand_index')]
   public function brandIndex () {
      $brand = $this->getDoctrine()->getRepository(Brand::class)->findAll();
      return $this->render('brand/index.html.twig',
        [
            'brand' => $brand
        ]);
   } 
   #[Route('/admin/list', name: 'brand_list')]
   public function brandList() {
      $brands = $this->getDoctrine()->getRepository(Brand::class)->findAll();
      return $this->render('brand/list.html.twig',
        [
            'brands' => $brands
        ]);
   } 
   #[IsGranted("ROLE_PRD_ADMIN")]
   #[Route('/admin/add', name: 'brand_add')]
   public function brandAdd(Request $request) {
    $brand = new Brand();
    $form = $this->createForm(BrandType::class, $brand);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
        $manager = $this->getDoctrine()->getManager();
        $manager -> persist($brand);
        $manager->flush();
        $this -> addFlash('Success','Brand added successfully');
        return $this->redirectToRoute('brand_index');
    }
    return $this ->renderForm('brand/add.html.twig',
    [
        'brandForm'=>$form
    ]);
   }

   #[IsGranted("ROLE_PRD_ADMIN")]
   #[Route('/admin/detail/{id}', name: 'brand_detail')]
   public function brandDetail ($id, BrandRepository $brandRepository) {
      $brand = $brandRepository->find($id);
      if ($brand == null) {
         $this->addFlash('Warning','Invalid genre id !');
         return $this->redirectToRoute('brand_index');
      }
      return $this->render('brand/detail.html.twig',
        [
            'brand' => $brand
        ]);   
   }
   #[IsGranted("ROLE_PRD_ADMIN")]
   #[Route('/admin/delete/{id}', name: 'brand_delete')]
   public function brandDelete ($id, ManagerRegistry $managerRegistry) {
     $brand = $managerRegistry->getRepository(Brand::class)-> find($id);
     if($brand == null){
        $this->addFlash('error','Brand not found');
     }else if (count($brand->getProducts()) >= 1){ //check xem genre này có ràng buộc với book hay không trước khi xóa
            //nếu có tối thiểu 1 book thì hiển thị lỗi và không cho xóa  
         $this->addFlash('error', 'CAN NOT DELETE THIS BRAND');
     }else{
        $manager = $managerRegistry->getManager();
        $manager->remove($brand);
        $manager->flush();
        $this -> addFlash('success','Brand deleted successfully');
     }
     return $this -> redirectToRoute('brand_index');
   }
   #[IsGranted("ROLE_PRD_ADMIN")]
   #[Route('/admin/edit/{id}', name: 'brand_edit')]
   public function brandEdit ($id, Request $request){
    $brand = $this->getDoctrine()->getRepository(Brand::class)->find($id);
    if($brand ==null){
        $this->addFlash('Warning','Brand not found');
   }else{
    $form = $this->createForm(BrandType::class, $brand);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($brand);
        $manager->flush();
        $this -> addFlash('Success','Brand updated successfully');
        return $this -> redirectToRoute('brand_index');
    }
    return $this -> renderForm('brand/edit.html.twig',
    [
        'brandForm' => $form
    ]);
   }
    }


   #[Route('/search', name: 'search_brand_name')]
   public function searchBrand(BrandRepository $brandRepository, Request $request)
   {
      $key = $request->get('name');
      $brand = $brandRepository->searchBrandByName($key);
        // if ($brands == null) {
        //    $this->addFlash('warning', "No brand found");
        // }
      return $this->render(
         'brand/index.html.twig',
         [
            'brand' => $brand
         ]
      );
   }
}