<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category/admin')]
class CategoryController extends AbstractController
{
    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/index', name: 'index_category')]
    public function index()
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render(
            'category/index.html.twig',
            [
                'category' => $category
            ]
        );
    }

    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/detail/{id}', name: 'detail_category')]
    public function detail($id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);
        if ($category == null) {
            $this->addFlash('success', 'Invalid category.');
            return $this->redirectToRoute('index_category');
        }
        return $this->render(
            'category/detail.html.twig',
            [
                'category' => $category
            ]
        );
    }

    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/delete/{id}', name: 'delete_category')]
    public function delete($id, ManagerRegistry $managerRegistry)
    {
        $category = $managerRegistry->getRepository(Category::class)->find($id);
        if ($category == null) {
            $this->addFlash('error', 'Category not found');
        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($category);
            $manager->flush();
            $this->addFlash('success', 'Delete category successfully');
        }
        return $this->redirectToRoute('index_category');
    }

    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/add', name: 'add_category')]
    public function add(Request $request)
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($category);
            $manager->flush();
            $this->addFlash('success', 'Category added successfully');
            return $this->redirectToRoute('index_category');
        }
        return $this->renderForm(
            'category/add.html.twig',
            [
                'categoryForm' => $form
            ]
        );
    }

    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/edit{id}', name: 'edit_category')]
    public function edit(Request $request, $id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        if ($category == null) {
            $this->addFlash('error', 'Category not found');
        } else {
            $form = $this->createForm(CategoryType::class, $category);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($category);
                $manager->flush();
                $this->addFlash('success', 'Category updated successfully');
                return $this->redirectToRoute('index_category');
            }
            return $this->renderForm(
                'category/edit.html.twig',
                [
                    'categoryForm' => $form
                ]
            );
        }
    }
    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/search', name: 'search_category')]
    public function search(Request $request, CategoryRepository $categoryRepository)
    {
        $key = $request->get('name');
        $category = $categoryRepository->searchCategoryByTitle($key);
        return $this->render(
            'category/index.html.twig',
            [
                'category' => $category
            ]
        );
    }
}