<?php

namespace App\DataFixtures;

use \DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Operateur;
use App\Entity\Parcelle;
use App\Entity\Piquet;
use App\Entity\DonneesPiquet;
use App\Entity\ElectroVanne;
use App\Entity\DonneesVanne;
use App\Entity\Armoire;
use App\Entity\DonneesArmoire;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Zenstruck\Foundry\Factory;
use Faker;



class AllFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        // CREATION DE PARCELLES
        if(!$manager->getRepository(Parcelle::class)->findOneById('1')){
            $manager->getConnection()->exec("ALTER TABLE parcelle AUTO_INCREMENT = 1;");
            $parcelle1 = new Parcelle();
            $parcelle1->setLabel("Parcelle 1");
            $parcelle2 = new Parcelle();
            $parcelle2->setLabel("Parcelle 2");
            $parcelle3 = new Parcelle();
            $parcelle3->setLabel("Parcelle 3");
            $parcelle_armoire = new Parcelle();
            $parcelle_armoire->setLabel("Parcelle armoire");
            $manager->persist($parcelle1);
            $manager->persist($parcelle2);
            $manager->persist($parcelle3);
            $manager->persist($parcelle_armoire);
            $manager->flush();
        }
        
        // CREATION D'OPERATEURS
        $manager->getConnection()->exec("ALTER TABLE operateur AUTO_INCREMENT = 1;");
         for ($i = 0; $i < 20; $i++) {
             $user = new Operateur();
             $user->setEmail("FakeData@FakeData".$i.".fr");
             $user->setPassword($this->encoder->encodePassword($user, "Capou")); // Mot de passe défini : Capou
             $user->setVerifiedbyadmin(1);
             $user->setIsFirstConnexion(1);
             if($i < 10) $user->setIdParcelle($manager->getRepository(Parcelle::class)->findOneById(2));
             else $user->setIdParcelle($manager->getRepository(Parcelle::class)->findOneById(1));
             $user->setCreatedAt(new DateTime());
             $manager->persist($user);
             $manager->flush();
        }
        
        // CREATION D'UNE ARMOIRE
        $armoire = new Armoire();
        $armoire->setId(170);
        $armoire->setEtat(2); // 2 : mode GSM
        $armoire->setIp("10.59.0.1");
        $armoire->setIdParcelle($manager->getRepository(Parcelle::class)->findOneById(4));
        $manager->persist($armoire);
        $manager->flush();
            
        // CREATION DE DONNEES ARMOIRE
        $manager->getConnection()->exec("ALTER TABLE donnees_armoire AUTO_INCREMENT = 1;");
        $donneesArmoire = new DonneesArmoire();
        $donneesArmoire->setIdArmoire($manager->getRepository(Armoire::class)->findOneById(170));
        $donneesArmoire->setBatterie($faker->numberBetween(0,100));
        $manager->persist($donneesArmoire);
        $manager->flush();
        
        // CREATION D'ELECTROVANNES
        for($i=1; $i<4; $i++){
            $manager->getConnection()->exec("ALTER TABLE electro_vanne AUTO_INCREMENT = 1;");
            $electrovanne = new ElectroVanne();
            $electrovanne->setId($i);
            $electrovanne->setEtat(1);
            $electrovanne->setIp("10.60.0." . $i);
            switch ($i) {
            case 1:
                $electrovanne->setIdParcelle($manager->getRepository(Parcelle::class)->findOneById(1));
                $parcelle1->addIdElectrovanne($electrovanne);
                $manager->persist($parcelle1);
                break;
            case 2:
                $electrovanne->setIdParcelle($manager->getRepository(Parcelle::class)->findOneById(2));
                $parcelle2->addIdElectrovanne($electrovanne);
                $manager->persist($parcelle2);
                break;
            case 3:
                $electrovanne->setIdParcelle($manager->getRepository(Parcelle::class)->findOneById(3));
                $parcelle3->addIdElectrovanne($electrovanne);
                $manager->persist($parcelle3);
                break;
            }
            $manager->persist($electrovanne);
            $manager->flush();
            
            // CREATION DE DONNEES ELECTROVANNES
            $manager->getConnection()->exec("ALTER TABLE donnees_vanne AUTO_INCREMENT = 1;");
            $donneesVanne = new DonneesVanne();
            $donneesVanne->setIdVanne($manager->getRepository(ElectroVanne::class)->findOneById($i));
            $latitude = $faker->randomFloat($nbMaxDecimals = 5, $min = 44.05, $max = 44.06);
            $longitude = $faker->randomFloat($nbMaxDecimals = 5, $min = 1.31, $max = 1.315);
            $donneesVanne->setLatitude($latitude);
            $donneesVanne->setLongitude($longitude);
            $donneesVanne->setHorodatage($faker->dateTimeBetween($startDate = '-'.$i.' minutes', $endDate = 'now', $timezone = null)); // DateTime('2003-03-15 02:00:49', 'Africa/Lagos');
            $donneesVanne->setBatterie($faker->numberBetween(0,100));
            $manager->persist($donneesVanne);
            $manager->flush();
        }
        
        // CREATION DE PIQUETS
        for($i=1; $i<30; $i++){
            $manager->getConnection()->exec("ALTER TABLE piquet AUTO_INCREMENT = 1;");
            $piquet = new Piquet();
            $piquet->setId($i);
            $piquet->setEtat(1);
            if($i < 11) {
                $piquet->setIdParcelle($manager->getRepository(Parcelle::class)->findOneById(1));
                $piquet->setIdMaitreRadio($manager->getRepository(ElectroVanne::class)->findOneById(1));
                $parcelle1->addIdPiquet($piquet);
                $manager->persist($parcelle1);
            }
            else if($i < 21) {
                $piquet->setIdParcelle($manager->getRepository(Parcelle::class)->findOneById(2));
                $piquet->setIdMaitreRadio($manager->getRepository(ElectroVanne::class)->findOneById(2));
                $parcelle2->addIdPiquet($piquet);
                $manager->persist($parcelle2);
            }
            else {
                $piquet->setIdParcelle($manager->getRepository(Parcelle::class)->findOneById(3));
                $piquet->setIdMaitreRadio($manager->getRepository(ElectroVanne::class)->findOneById(3));
                $parcelle3->addIdPiquet($piquet);
                $manager->persist($parcelle3);
            }
            $manager->persist($piquet);
            $manager->flush();
            
            // CREATION DE DONNEES PIQUETS
            $manager->getConnection()->exec("ALTER TABLE donnees_piquet AUTO_INCREMENT = 1;");
            $donneesPiquet = new DonneesPiquet();
            $donneesPiquet->setIdPiquet($manager->getRepository(Piquet::class)->findOneById($i));
            $donneesPiquet->setTemperature($faker->numberBetween(0,35));
            $latitude = $faker->randomFloat($nbMaxDecimals = 5, $min = 44.05, $max = 44.06);
            $longitude = $faker->randomFloat($nbMaxDecimals = 5, $min = 1.31, $max = 1.315);
            $donneesPiquet->setLatitude($latitude);
            $donneesPiquet->setLongitude($longitude);
            $donneesPiquet->setHorodatage($faker->dateTimeBetween($startDate = '-'.$i.' minutes', $endDate = 'now', $timezone = null)); // DateTime('2003-03-15 02:00:49', 'Africa/Lagos')
            $donneesPiquet->setHumidite([$faker->numberBetween(0,35), $faker->numberBetween(0,35), $faker->numberBetween(0,35), $faker->numberBetween(0,35)]);
            $donneesPiquet->setBatterie($faker->numberBetween(0,100));
            $manager->persist($donneesPiquet);
            $manager->flush();
        }

        $user = new Operateur();
        $user->setRoles(array('ROLE_ADMIN'));
        $user->setEmail("admin@admin.fr");
        $user->setPassword($this->encoder->encodePassword($user, "Admin")); // Mot de passe défini : Admin
        $user->setVerifiedbyadmin(1);
        $user->setIsFirstConnexion(0);
        $user->setCreatedAt(new DateTime());
        $user->setIdParcelle($manager->getRepository(Parcelle::class)->findOneById(2));
        $manager->persist($user);
        $manager->flush();
    }
}
