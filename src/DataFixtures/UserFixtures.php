<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // creation de l'admin !
        $user = new User();
        $user->setEmail('admin@deloitte.com');
        $user->setRoles(['ROLE_ADMIN']);
        $password = $this->encoder->encodePassword($user, 'admin123@');
        $user->setPassword($password);
        $user->setNom('Admin');
        $user->setPrenom('Admin');
        $user->setSecteur('Informatique');
        $user->setImageName('user1.png');
        $manager->persist($user);
        $manager->flush();

        {
            // creation 9 utilisateurs! Bam!
            for ($i = 1; $i < 9; $i++) {
                $secteur = array("Informatique", "Recrutement","Comptabilité");
                $rand = array_rand($secteur);
                $userGroup = new User();
                $userGroup->setEmail('user'.$i.'@deloitte.com');
                $userGroup->setRoles(['ROLE_USER']);
                $password = $this->encoder->encodePassword($userGroup, 'admin123@');
                $userGroup->setPassword($password);
                $userGroup->setNom('Nom '.$i);
                $userGroup->setPrenom('Prenom '.$i);
                $userGroup->setSecteur($secteur[$rand]);
                $userGroup->setImageName('user'.$i.'.png');
                $manager->persist($userGroup);
                $manager->flush();
            }
    
            $manager->flush();
        }

    }
}
