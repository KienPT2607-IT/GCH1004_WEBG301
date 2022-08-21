<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Record;
use App\Form\ProductType;
use App\Form\ProductEditType;
use App\Repository\AccountRepository;
use App\Repository\ProductRepository;
use App\Repository\RecordRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/product')]
class ProductController extends AbstractController
{
    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/admin/view/all', name: 'ad_view_all_products')]
    public function viewAllProduct(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();
        if ($products == null) {
            $this->addFlash('error', 'No product found!');
            return $this->redirectToRoute('ad_view_all_products');
        }
        return $this->render(
            'product/index.html.twig',
            [
                'products' => $products
            ]
        );
    }

    #[IsGranted("ROLE_STAFF")]
    #[Route('/staff/view/all', name: 'staff_view_all_products')]
    public function staffViewAllProduct(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();
        if ($products == null) {
            $this->addFlash('error', 'No product found!');
            return $this->redirectToRoute('ad_view_all_products');
        }
        return $this->render(
            'product/staff_index.html.twig',
            [
                'products' => $products
            ]
        );
    }

    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/admin/view/detail/{id}', name: 'ad_view_product_detail')]
    public function viewProductDetail($id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);
        if ($product == null) {
            $this->addFlash('error', 'Loading product failed!');
            return $this->redirectToRoute('ad_view_all_products');
        }
        return $this->render(
            'product/detail.html.twig',
            [
                'product' => $product
            ]
        );
    }

    #[IsGranted("ROLE_STAFF")]
    #[Route('/staff/view/detail/{id}', name: 'staff_view_product_detail')]
    public function staffViewVProductDetail($id, ProductRepository $productRepository)
    {
        $product = $productRepository->find($id);
        if ($product == null) {
            $this->addFlash('error', 'Loading product failed!');
            return $this->redirectToRoute('ad_view_all_products');
        }
        return $this->render(
            'product/staff_detail.html.twig',
            [
                'product' => $product
            ]
        );
    }

    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/admin/delete/{id}', name: 'ad_delete_product')]
    public function deleteProduct($id, ManagerRegistry $managerRegistry)
    {
        $product = $managerRegistry->getRepository(Product::class)->find($id);
        if ($product == null) {
            $this->addFlash('error', 'Loading product failed!');
            return $this->redirectToRoute('ad_view_all_products');
        }
        if (count($product->getRecords()) > 0) {
            $this->addFlash('error', 'Cannot delete this product because it is mapped in records!!');
            return $this->redirectToRoute('ad_view_all_products');
        }
        $manager = $managerRegistry->getManager();
        $manager->remove($product);
        $manager->flush();
        $message = $product->getName() . ' Deleted Successfully';
        $this->addFlash('success', $message);
        return $this->redirectToRoute('ad_view_all_products');
    }

    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/admin/add', name: 'ad_add_product')]
    public function addNewProduct(Request $request)
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();
            $this->addFlash('success', 'New Product Added Successfully!');
            return $this->redirectToRoute("ad_view_all_products");
        }
        return $this->renderForm(
            "product/add.html.twig",
            [
                'product_add_form' => $form
            ]
        );
    }

    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/admin/edit/{id}', name: 'ad_edit_product')]
    public function editProduct($id, Request $request)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
        if ($product == null) {
            $this->addFlash('error', 'Loading product failed!');
            return $this->redirectToRoute('ad_view_all_products');
        }
        $form = $this->createForm(ProductEditType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($product);
            $manager->flush();
            $this->addFlash('success', 'Product Edited Successfully !');
            return $this->redirectToRoute("ad_view_all_products");
        }
        return $this->renderForm(
            "product/edit.html.twig",
            [
                'product_edit_form' => $form
            ]
        );
    }

    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/admin/raise/product', name: 'ad_raise_product')]
    public function raiseNumbOfProducts(Request $request, ProductRepository $productRepository)
    {
        $product = $productRepository->find($request->get('id'));
        $numberRaised = $request->get('numb_raised');
        if ($numberRaised == null) {
            $this->addFlash('error', 'Raise number of ' . $product->getName() . ' failed!');
            return $this->redirectToRoute("ad_view_all_products");
        }
        $product->setRemain($product->getRemain() + $numberRaised);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($product);
        $manager->flush();
        $this->addFlash('success', 'Raised Successfully !');
        return $this->redirectToRoute("ad_view_all_products");
    }

    #[IsGranted("ROLE_STAFF")]
    #[Route('/staff/take/product', name: 'staff_take_product')]
    public function takeNumbOfProducts(Request $request, ProductRepository $productRepository, AccountRepository $accountRepository)
    {
        $product = $productRepository->find($request->get('id'));
        if ($product == null) {
            $this->addFlash('error', 'Product not found!');
            return $this->redirectToRoute('staff_view_all_products');
        }
        $numberTaken = $request->get('numb_taken');
        if ($numberTaken == null) {
            $this->addFlash('error', 'Take ' . $product->getName() . ' failed!');
            return $this->redirectToRoute("staff_view_all_products");
        }
        $account = $accountRepository->find($request->get('user_id'));
        $this->saveRecord($account, $product, $numberTaken);
        $product->setRemain($product->getRemain() - $numberTaken);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($product);
        $manager->flush();
        $this->addFlash('success', 'Taken Successfully !');
        return $this->redirectToRoute("staff_view_all_products");
    }

    private function saveRecord($user, $productId, $quantity)
    {
        $record = new Record;
        $record->setUsename($user)
            ->setProduct($productId)
            ->setQuantity($quantity)
            ->setDate(\DateTime::createFromFormat('Y-m-d', date('Y-m-d')));
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($record);
        $manager->flush();
    }

    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/admin/seach', name: 'ad_search_product')]
    public function adSearchForProduct(Request $request, ProductRepository $productRepository)
    {
        $key = $request->get('keyword');
        $products = $productRepository->searchProductByName($key);
        if ($products == null) {
            $this->addFlash('error', "No book found");
        }
        return $this->render(
            'product/index.html.twig',
            [
                'products' => $products
            ]
        );
    }

    #[IsGranted("ROLE_STAFF")]
    #[Route('/staff/seach', name: 'staff_search_product')]
    public function staffSearchForProduct(Request $request, ProductRepository $productRepository)
    {
        $key = $request->get('keyword');
        $products = $productRepository->searchProductByName($key);
        if ($products == null) {
            $this->addFlash('error', "No book found");
        }
        return $this->render(
            'product/staff_index.html.twig',
            [
                'products' => $products
            ]
        );
    }

    #[IsGranted('ROLE_PRD_ADMIN')]
    #[Route('/admin/product/asc', name: 'ad_sort_product_id_ascending')]
    public function adSortProductIdAsc(ProductRepository $productRepository)
    {
        $products = $productRepository->sortProductByIdAsc();
        return $this->render(
            'product/index.html.twig',
            [
                'products' => $products
            ]
        );
    }

    #[IsGranted('ROLE_PRD_ADMIN')]
    #[Route('/admin/product/desc', name: 'ad_sort_product_id_descending')]
    public function adSortProductIdDesc(ProductRepository $productRepository)
    {
        $products = $productRepository->sortProductByIdDesc();
        return $this->render(
            'product/index.html.twig',
            [
                'products' => $products
            ]
        );
    }

    #[IsGranted('ROLE_STAFF')]
    #[Route('/staff/product/asc', name: 'staff_sort_product_id_ascending')]
    public function staffSortProductIdAsc(ProductRepository $productRepository)
    {
        $products = $productRepository->sortProductByIdAsc();
        return $this->render(
            'product/staff_index.html.twig',
            [
                'products' => $products
            ]
        );
    }

    #[IsGranted('ROLE_STAFF')]
    #[Route('/staff/product/desc', name: 'staff_sort_product_id_descending')]
    public function staffSortProductIdDesc(ProductRepository $productRepository)
    {
        $products = $productRepository->sortProductByIdDesc();
        return $this->render(
            'product/staff_index.html.twig',
            [
                'products' => $products
            ]
        );
    }
}