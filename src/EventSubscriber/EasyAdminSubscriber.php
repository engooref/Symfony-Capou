<?php

namespace App\EventSubscriber;

use App\Entity\Piquet;
use App\Entity\Armoire;
use App\Entity\Parcelle;
use App\Entity\Operateur;
use App\Entity\ElectroVanne;

use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

use \DateTime;
use Doctrine\ORM\EntityManagerInterface;


class EasyAdminSubscriber implements EventSubscriberInterface 
{
    private $passwordEncoder;
    private $mailer;
    private $doctrineManager;
    
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer, EntityManagerInterface $manager, SessionInterface $session)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailer;
        $this->doctrineManager = $manager;
        $this->session = $session;
    }
    
    
    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['createUserAndGroup'],
            BeforeEntityUpdatedEvent::class => ['updateParcelle'],
        ];
    }
    
    public function createUserAndGroup(BeforeEntityPersistedEvent $event) {
        $entity = $event->getEntityInstance();
        
        if ($entity instanceof Operateur) {
            /* G�n�ration de mot de passe */
            $password = $this->_generateRandomPassword();
            
            /* Cryptage de mot de passe */
            $entity->setPassword($this->passwordEncoder->encodePassword(
                $entity,
                $password
                ));
            
            $entity->setVerifiedbyadmin(true);
            $entity->setIsFirstConnexion(true);
            $entity->setCreatedAt(new DateTime());
            
            
            $titreMail = "Lyc�e Capou - Votre compte a bien �t� cr�� par un administrateur";
            $message = (new \Swift_Message($titreMail))
            ->setCharset('ISO-8859-1')
            ->setFrom('inscription.lyceecapou@gmail.com')
            ->setTo($entity->getEmail())
            ->setBody(
                '<h1>Bonjour ! Un administrateur vous a cr�� un compte sur le site Lyc�e Capou - Irrigation Connect�e</h1>
            <br>
            <p>Informations de connexion pour vous connecter � votre compte :</p>
            <p><u>Identifiant :</u> <b>'. $entity->getEmail() . '</b></p>
            <p><u>Mot de passe :</u> <b> '. $password . '</b></p>
            </p>
            <br>
            <p>Attention, vous avez re�u un mot de passe g�n�r� al�atoirement, toutefois, nous vous recommandons de le changer suite � votre premi�re connexion sur notre site.</p>
            <br>
            <p>Merci de votre compr�hension. Cordialement !</p>',
                'text/html'
                );
            
            try {
                $this->mailer->send($message);
            } catch (TransportExceptionInterface $e) {
                throw new CustomUserMessageAuthenticationException("L'envoi du mail a echou�.");
            }
        }
        
        if ($entity instanceof Parcelle) {
            // creation operateurs
            foreach($entity->getIdOperateurs() as $idOperateur){
                $idOperateur->setIdParcelle($entity);
                $entity->addIdOperateur($idOperateur);
            }
            // creation piquets
            foreach($entity->getIdPiquets() as $idPiquet){
                $idPiquet->setIdParcelle($entity);
                $entity->addIdPiquet($idPiquet);
            }
            // création electrovannes
            foreach($entity->getIdElectrovannes() as $idElectrovanne){
                $idElectrovanne->setIdParcelle($entity);
                $entity->addIdElectrovanne($idElectrovanne);
            }
            // création armoire
            foreach($entity->getIdArmoires() as $idArmoire){
                $idArmoire->setIdParcelle($entity);
                $entity->addIdArmoire($idArmoire);
            }
        }
    }

    public function updateParcelle(BeforeEntityUpdatedEvent $event)
    {
        $entity = $event->getEntityInstance(); // On r�cup�re l'instance de l'entit�
        if (!($entity instanceof Parcelle)) { // On test si l'entit� est bien une instance de Parcelle
            return;
        }
        
        //dump($entity);die();
        
        $defaultGroup = $this->doctrineManager->getRepository(Parcelle::class)->findOneById('1'); // La parcelle consid�r� "par d�faut" est la parcelle avec comme id=1
        
        //gestion des opérateurs
        $GroupAct = $this->doctrineManager->getRepository(Parcelle::class)->findOneById($entity->getId()); // La parcelle actuel est l'id de l'entit�
        
        $operatorTab = $this->doctrineManager->getRepository(Operateur::class)->findBy(['idParcelle'=>$GroupAct->getId()]);
        
        foreach($operatorTab as $Operator){ // Pour chaque op�rateurs contenus dans la parcelle actuel, on les transfere dans la parcelle par d�faut (1)
            $Operator->setIdParcelle($defaultGroup);
            $this->doctrineManager->flush();
        }
        
        foreach($entity->getIdOperateurs() as $idOperateur){ // Pour chaque op�rateurs contenus dans l'entit�, on assigne aux nouvelles parcelles les cl�s �trang�res
            $idOperateur->setIdParcelle($entity);
            $entity->addIdOperateur($idOperateur);
        }
        
        
        //gestion des piquets
        $piquetTab = $this->doctrineManager->getRepository(Piquet::class)->findBy(['idParcelle'=>$GroupAct->getId()]);
        
        foreach($piquetTab as $Piquet){ // Pour chaque op�rateurs contenus dans la parcelle actuel, on les transfere dans la parcelle par d�faut (1)
            $Piquet->setIdParcelle(null);
            $this->doctrineManager->flush();
        }
        
        foreach($entity->getIdPiquets() as $idPiquet){ // Pour chaque op�rateurs contenus dans l'entit�, on assigne aux nouvelles parcelles les cl�s �trang�res
            $idPiquet->setIdParcelle($entity);
            $entity->addIdPiquet($idPiquet);
        }
        
        //gestion des electrovannes
        $electrovanneTab = $this->doctrineManager->getRepository(ElectroVanne::class)->findBy(['idParcelle'=>$GroupAct->getId()]);
        
        foreach($electrovanneTab as $Electrovanne){ // Pour chaque op�rateurs contenus dans la parcelle actuel, on les transfere dans la parcelle par d�faut (1)
            $Electrovanne->setIdParcelle(null);
            $this->doctrineManager->flush();
        }
        
        foreach($entity->getIdElectrovannes() as $idElectrovanne){ // Pour chaque op�rateurs contenus dans l'entit�, on assigne aux nouvelles parcelles les cl�s �trang�res
            $idElectrovanne->setIdParcelle($entity);
            $entity->addIdElectrovanne($idElectrovanne);
        }
        
        //gestion de l'armoire
        $armoireTab = $this->doctrineManager->getRepository(Armoire::class)->findBy(['idParcelle'=>$GroupAct->getId()]);
        
        foreach($armoireTab as $Armoire){ // Pour chaque armoires contenus dans la parcelle actuel, on les transfere dans la parcelle par d�faut (1)
            $Armoire->setIdParcelle(null);
            $this->doctrineManager->flush();
        }
        
        foreach($entity->getIdArmoires() as $idArmoire){ // Pour chaque armoires contenus dans l'entit�, on assigne aux nouvelles parcelles les cl�s �trang�res
            $idArmoire->setIdParcelle($entity);
            $entity->addIdArmoire($idArmoire);
        }
        
    }
    
    private function _generateRandomPassword() : ?string
    {
        // Génération mot de passe crypté
        $nbChar = 8;
        $chaine ="mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
        srand((double)microtime()*1000000);
        $generatedPassword = '';
        for($i=0; $i<$nbChar; $i++){
            $generatedPassword .= $chaine[rand()%strlen($chaine)];
        }
        return $generatedPassword;
    }
}