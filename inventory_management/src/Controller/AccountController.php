<?php

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account')]
class AccountController extends AbstractController

{
    #[IsGranted("ROLE_ACC_ADMIN")]
    #[Route('/admin/index', name: 'ad_view_all_staffs')]
    public function viewAllAccount(AccountRepository $accountRepository)
    {
        $accounts = $accountRepository->findAll();
        if ($accounts == null) {
            $this->addFlash('error', 'Loading accounts failed!');
            return $this->redirectToRoute('ad_view_all_staffs');
        }
        return $this->render(
            'account/index.html.twig',
            [
                'accounts' => $accounts
            ]
        );
    }
}
