<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixture extends Fixture
{
    //On déclare un propriété (privée parce qu'elle ne concerne que la fixture) qui va nous permettre d'accéder
    // au UserPasswordHasherInterface partout dans les méthodes de la classe
    private $encoder;

    //On "mémorise" le UserPasswordHasherInterface injecté dans la propriété de classe de sorte qu'on y aura accès depuis toutes les méthodes de class
    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->encoder = $userPasswordHasherInterface;
    }
    public function load(ObjectManager $manager)
    {
        // ADMINISTRATEUR
        // on instancie un utilisateur
        $user = new User();
        // on renseigne la propriete email a l'aide du setter
        $user->setPseudo('Tarantule');
        $user->setFirstname('Quentin');
        $user->setEmail('admin@admin.com');
        $user->setName('Admin');
        // Gestion du password
        $plainPassword = "pass"; // le password en clair que l'on veut encoder
        $encodePassword = $this->encoder->hashPassword($user, $plainPassword);// on crypt le password pour le memorise dans le __costruct
        $user->setPassword($encodePassword);// on reseigne la proprioete password de l'utilisateur avec le setter
        // determiner le rôle
        $user->setRoles(["ROLE_USER", "ROLE_ASSO" ,"ROLE_ADMIN"]);
        $user->setIsVerified(1);
        // on memorise l'instance d'utilisateur afin de l'ajouter ulterieurement dans la BDD
        $manager->persist($user);
        // on met en BDD
        

        //***************************** */

        // ASSO
        $user = new User();
        $user->setPseudo('Tyss');
        $user->setFirstname('Quentin');
        $user->setEmail('asso@asso.com');
        $user->setName('Asso');
        $plainPassword = "pass";
        $encodePassword = $this->encoder->hashPassword($user, $plainPassword);
        $user->setPassword($encodePassword);
        $user->setRoles(["ROLE_USER", "ROLE_ASSO"]);
        $user->setIsVerified(1);
        $manager->persist($user);
        $manager->flush();
    
        //***************************** */

        // SIMPLE USERS
        $user = new User();
        $user->setPseudo('Wise');
        $user->setFirstname('Quentin');
        $user->setEmail('user@user.com');
        $user->setName('User');
        $plainPassword = "pass";
        $encodePassword = $this->encoder->hashPassword($user, $plainPassword);
        $user->setPassword($encodePassword);
        $user->setRoles(["ROLE_USER"]);
        $user->setIsVerified(1);
        $manager->persist($user);
        $manager->flush();
    }
}
