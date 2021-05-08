<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Media;
use App\Entity\Booking;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager as PersistenceObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;
    
    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(PersistenceObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $user = new User();

        $user->setFirstName("Mickael")
            ->setLastName("RANDRIANANTENAINA")
            ->setEmail("admin@gmail.com")
            ->setPhoneNumber("0340740443")
            ->setRoles(['ROLE_ADMIN'])
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
            ->setPassword($this->passwordEncoder->encodePassword($user, "password"));

        $manager->persist($user);

        // Gestion des users.
        for ($i=1; $i < 10 ; $i++) { 
            $user = new User();

            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setPhoneNumber($faker->phoneNumber())
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                ->setPassword($this->passwordEncoder->encodePassword($user, "password"));

            $users[] = $user;
            $manager->persist($user);
            
            // Gestion des ads.
            for ($a = 1; $a <= mt_rand(4, 30); $a++) {
                $ad = new Ad();
                
                $media = new Media();
                $media->setPath('uploads/image.png');
                $user = $users[mt_rand(0, count($users) - 1)];
                
                $ad->setTitle($faker->sentence())
                ->setIntroduction($faker->paragraph(2))
                ->setContent('<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>')
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 5))
                ->addMedia($media)
                ->setAuthor($user);
                
                $manager->persist($ad);

                // Gestion des bookings.
                for ($b=1; $b <= mt_rand(0, 10); $b++) { 
                    $booking = new Booking();

                    $createdAt = $faker->dateTimeBetween('-6 months');
                    $startDate = $faker->dateTimeBetween('-3 months');

                    // Gestion de la date de fin.
                    $duration = mt_rand(3, 10);
                    $endDate = (clone $startDate)->modify("+$duration days");
                    $amount = $ad->getPrice() * $duration;

                    $booker = $users[mt_rand(0, count($users) - 1)];
                    $comment = $faker->paragraph();

                    $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setAmount($amount)
                        ->setComment($comment);

                    $manager->persist($booking);
                }
            }
        }

        $manager->flush();
    }
}