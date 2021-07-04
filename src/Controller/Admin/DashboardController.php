<?php

namespace App\Controller\Admin;

use App\Entity\ElectroVanne;
use App\Entity\Piquet;
use App\Entity\Armoire;
use App\Entity\Parcelle;
use App\Entity\Operateur;

use App\Repository\ElectroVanneRepository;
use App\Repository\PiquetRepository;
use App\Repository\ArmoireRepository;
use App\Repository\ParcelleRepository;
use App\Repository\OperateurRepository;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    
    /**
     * @var ElectroVanneRepository
     */
    protected ElectroVanneRepository $electrovanneRepository;
    
    /**
     * @var PiquetRepository
     */
    protected PiquetRepository $piquetRepository;
    
    /**
     * @var ArmoireRepository
     */
    protected ArmoireRepository $armoireRepository;
    
    /**
     * @var ParcelleRepository
     */
    protected ParcelleRepository $parcelleRepository;
    
    /**
     * @var OperateurRepository
     */
    protected OperateurRepository $operateurRepository;
    
    public function __construct(
        ElectroVanneRepository $electrovanneRepository,
        PiquetRepository $piquetRepository,
        ArmoireRepository $armoireRepository,
        ParcelleRepository $parcelleRepository,
        OperateurRepository $operateurRepository
        )
    {
        $this->electrovanneRepository = $electrovanneRepository;
        $this->piquetRepository = $piquetRepository;
        $this->armoireRepository = $armoireRepository;
        $this->parcelleRepository = $parcelleRepository;
        $this->operateurRepository = $operateurRepository;
    }
    
    /**
     * @Route("/admin_59fq5a", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            'countElectrovanne' => $this->electrovanneRepository->countAllElectrovanne(),
            'countPiquet' => $this->piquetRepository->countAllPiquet(),
            'countArmoire' => $this->armoireRepository->countAllArmoire(),
            'parcelles' => $this->parcelleRepository->findAll(),
            'countOperateur' => $this->operateurRepository->countAllOperateur(),
            'countOperateurInvalid' => $this->operateurRepository->countAllOperateurInvalid(),
            'operateursToValidate' => $this->operateurRepository->findBy(['verified_by_admin' => false]),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration Capou');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Operateur', 'fas fa-user', Operateur::class);
        yield MenuItem::linkToCrud('Parcelle', 'fas fa-faucet', Parcelle::class);
        yield MenuItem::linkToRoute('Retour sur le site', 'fa fa-arrow-circle-left', 'home');
    }
}
