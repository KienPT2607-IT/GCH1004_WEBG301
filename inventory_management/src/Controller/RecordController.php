<?php

namespace App\Controller;

use App\Entity\Record;
use App\Repository\RecordRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

#[Route('/record')]
class RecordController extends AbstractController
{
    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/admin/view/record', name: 'ad_view_records')]
    public function adViewRecords(RecordRepository $recordRepository)
    {
        $records = $recordRepository->findAll();
        if ($records == null) {
            $this->addFlash('error', 'No records found');
            return $this->redirectToRoute('ad_view_all_products');
        }
        return $this->render(
            'record/index.html.twig',
            [
                'records' => $records
            ]
        );
    }

    #[IsGranted("ROLE_PRD_ADMIN")]
    #[Route('/admin/fill/record', name: 'ad_fill_records_by_date')]
    public function adFillRecordByDate(Request $request, RecordRepository $recordRepository)
    {
        $date = $request->get('date');
        $records = $recordRepository->fillRecordByDate($date);
        if ($records == null) {
            $this->addFlash('error', "No records found");
        }
        return $this->render(
            'record/index.html.twig',
            [
                'records' => $records
            ]
        );
    }
}