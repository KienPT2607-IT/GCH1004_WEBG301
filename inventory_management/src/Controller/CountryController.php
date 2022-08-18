<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/country/admin')]
class CountryController extends AbstractController
{
    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/index', name: 'index_country')]
    public function index()
    {
        $country = $this->getDoctrine()->getRepository(Country::class)->findAll();
        return $this->render(
            'country/index.html.twig',
            [
                'country' => $country
            ]
        );
    }
    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/detail/{id}', name: 'detail_country')]
    public function detail($id, CountryRepository $countryRepository)
    {
        $country = $countryRepository->find($id);
        if ($country == null) {
            $this->addFlash('success', 'Invalid country');
            return $this->redirectToRoute('index_country');
        }
        return $this->render(
            'country/detail.html.twig',
            [
                'country' => $country
            ]
        );
    }
    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/delete/{id}', name: 'delete_country')]
    public function delete($id, ManagerRegistry $managerRegistry)
    {
        $country = $managerRegistry->getRepository(Country::class)->find($id);
        if ($country == null) {
            $this->addFlash('error', 'country not found');
        } else if (count($country->getProducts()) >= 1) {
            $this->addFlash('error', 'CAN NOT DELETE THIS country');
        } else {
            $manager = $managerRegistry->getManager();
            $manager->remove($country);
            $manager->flush();
            $this->addFlash('success', 'country deleted successfully');
        }
        return $this->redirectToRoute('index_country');
    }
    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/add', name: 'add_country')]
    public function add(Request $request)
    {
        $country = new Country;
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($country);
            $manager->flush();
            $this->addFlash('success', 'country added successfully');
            return $this->redirectToRoute('index_country');
        }
        return $this->renderForm(
            'country/add.html.twig',
            [
                'countryForm' => $form
            ]
        );
    }
    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/edit/{id}', name: 'edit_country')]
    public function edit(Request $request, $id)
    {
        $country = $this->getDoctrine()->getRepository(Country::class)->find($id);
        if ($country == null) {
            $this->addFlash('error', 'country not found');
        } else {
            $form = $this->createForm(CountryType::class, $country);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($country);
                $manager->flush();
                $this->addFlash('success', 'country updated successfully');
                return $this->redirectToRoute('index_country');
            }
            return $this->renderForm(
                'country/edit.html.twig',
                [
                    'countryForm' => $form
                ]
            );
        }
    }
    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/search', name: 'search_country')]
    public function search(Request $request, CountryRepository $countryRepository)
    {
        $key = $request->get('name');
        $country = $countryRepository->searchContriesByTitle($key);
        return $this->render(
            'country/index.html.twig',
            [
                'country' => $country
            ]
        );
    }
}
