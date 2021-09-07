<?php

namespace App\DataFixtures;

use App\Entity\Photo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PhotoFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $date = new \DateTimeImmutable();

        $photo = new Photo();
        $photo->setTitre('Oiseaux');
        $photo->setDescription("l'oiseau est beau");
        $photo->setImageName("bird-6577119_1920.jpg");
        $photo->setUpdatedAt($date);
        $manager->persist($photo);
        $manager->flush();

        $photo = new Photo();
        $photo->setTitre('Papillon');
        $photo->setDescription("un battement d'ailes");
        $photo->setImageName("butterflies-1127666_1920.jpg");
        $photo->setUpdatedAt($date);
        $manager->persist($photo);
        $manager->flush();

        $photo = new Photo();
        $photo->setTitre('Coucher de soleil');
        $photo->setDescription("le ciel rose");
        $photo->setImageName("field-6574455_1920.jpg");
        $photo->setUpdatedAt($date);
        $manager->persist($photo);
        $manager->flush();

        $photo = new Photo();
        $photo->setTitre('Colibri');
        $photo->setDescription("le Polonisateur");
        $photo->setImageName("hummingbird-2139279_1920.jpg");
        $photo->setUpdatedAt($date);
        $manager->persist($photo);
        $manager->flush();

        $photo = new Photo();
        $photo->setTitre('Nature sauvage');
        $photo->setDescription("A la pêche");
        $photo->setImageName("white-tailed-eagle-2015098_1920.jpg");
        $photo->setUpdatedAt($date);
        $manager->persist($photo);
        $manager->flush();
    }
}
