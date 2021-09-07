<?php

namespace App\DataFixtures;

use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VideoFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $date = new \DateTimeImmutable();

        $video = new Video();
        $video->setTitre('La plage');
        $video->setDescription('La plage tout le monde aime');
        $video->setVideoName('Beach - 79773.mp4');
        $video->setCategorie('nature');
        $video->setUpdatedAt($date);
        $manager->persist($video);
        $manager->flush();
        $video = new Video();

        $video->setTitre('Le Chat');
        $video->setDescription('miaou miaou miaou');
        $video->setVideoName('Cat - 66004.mp4');
        $video->setCategorie('nature');
        $video->setUpdatedAt($date);
        $manager->persist($video);
        $manager->flush();
        $video = new Video();

        $video->setTitre('En pleine nature');
        $video->setDescription('Les merveille du monde ');
        $video->setVideoName('Fog - 53358.mp4');
        $video->setCategorie('nature');
        $video->setUpdatedAt($date);
        $manager->persist($video);
        $manager->flush();
        $video = new Video();

        $video->setTitre('Le lac Bleu');
        $video->setDescription('Au bord de la ville');
        $video->setVideoName('Lake - 67201.mp4');
        $video->setCategorie('nature');
        $video->setUpdatedAt($date);
        $manager->persist($video);
        $manager->flush();
        $video = new Video();

        $video->setTitre('Le sable');
        $video->setDescription('ralenti sur le sable');
        $video->setVideoName('Sand - 73847.mp4');
        $video->setCategorie('nature');
        $video->setUpdatedAt($date);
        $manager->persist($video);
        $manager->flush();
    }
}
