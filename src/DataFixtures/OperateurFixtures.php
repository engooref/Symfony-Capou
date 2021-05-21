<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Operateur;
use App\Entity\Groupe;
use App\Entity\Centrale;
use App\Entity\Piquet;
use App\Entity\DonneesPiquet;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Zenstruck\Foundry\Factory;
use Faker;



class OperateurFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        // CREATION DE GROUPES
        if(!$manager->getRepository(Groupe::class)->findOneById('1')){
            $manager->getConnection()->exec("ALTER TABLE Groupe AUTO_INCREMENT = 1;");
            
            $groupe = new Groupe();
            
            $manager->persist($groupe);
            $manager->flush();
        }
        
        // CREATION D'OPERATEURS
         for ($i = 0; $i < 20; $i++) {
             $user = new Operateur();
             $user->setRoles(array('ROLE_ADMIN'));
             $user->setEmail("googleMeVoleMesDonnees@MaisCestDesFixtures".$i.".fr");
             $user->setPassword($this->encoder->encodePassword($user, "Admin"));
             $user->setVerifiedbyadmin(1);
             $user->setIsFirstConnexion(1);
             $user->setIdGroupe($manager->getRepository(Groupe::class)->findOneById(1));
             $manager->persist($user);
             $manager->flush();
        }
        
        
        // CREATION DE CENTRALE
        if(!$manager->getRepository(Centrale::class)->findOneById(1)){
            $manager->getConnection()->exec("ALTER TABLE Centrale AUTO_INCREMENT = 1;");
            $centrale = new Centrale();
            $centrale->setId(1);
            $centrale->setIp("192.120.0.5");
            $manager->persist($centrale);
            $manager->flush();
        }
        
        // CREATION DE PIQUETS
        for($i=0; $i<30; $i++){
            $manager->getConnection()->exec("ALTER TABLE Piquet AUTO_INCREMENT = 1;");
            $piquet = new Piquet();
            $piquet->setId($i);
            $piquet->setIdCentrale($manager->getRepository(Centrale::class)->findOneById('1'));
            $piquet->setEtat(1);
            $manager->persist($piquet);
            $manager->flush();
        
            // CREATION DE DONNEES PIQUETS
            $donneesPiquet = new DonneesPiquet();
            $donneesPiquet->setIdPiquet($manager->getRepository(Piquet::class)->findOneById($i));
            $donneesPiquet->setHorodatage($faker->dateTimeBetween($startDate = '-'.$i.' minutes', $endDate = 'now', $timezone = null)); // DateTime('2003-03-15 02:00:49', 'Africa/Lagos')
            $donneesPiquet->setHumidite([$faker->numberBetween(0,35), $faker->numberBetween(0,35), $faker->numberBetween(0,35), $faker->numberBetween(0,35)]);
            $donneesPiquet->setTemperature($faker->numberBetween(0,35));
            $donneesPiquet->setGps("50");
            $donneesPiquet->setBatterie(50);
            $manager->persist($donneesPiquet);
            $manager->flush();
        }
        $manager->getConnection()->exec("SELECT horodatage FROM `donnees_piquet` WHERE 1 ORDER BY horodatage");
        $manager->flush();
    }
}

