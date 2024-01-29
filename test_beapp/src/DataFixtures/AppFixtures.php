<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Station;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $cityDisabled = new City();
        $cityDisabled->setId(Uuid::fromString('018d3c56-bb85-76b5-a471-c1ba8bc0cc2c'));
        $cityDisabled->setName('Angers');
        $cityDisabled->setLongitude(-0.563166);
        $cityDisabled->setLatitude(47.478419);

        $manager->persist($cityDisabled);

        $stationForCityDisabled1 = new Station();
        $stationForCityDisabled1->setName('Larevellière');
        $stationForCityDisabled1->setAddress('88 Rue Larevellière, 49100 Angers');
        $stationForCityDisabled1->setLongitude(-1.587301560204856);
        $stationForCityDisabled1->setLatitude(47.211285356465964);
        $stationForCityDisabled1->setCapacity(5);
        $stationForCityDisabled1->setCity($cityDisabled);

        $manager->persist($stationForCityDisabled1);

        $cityEnabled = new City();
        $cityEnabled->setId(Uuid::fromString('018d3c56-bb85-76b5-a471-c1ba8c3c88ed'));
        $cityEnabled->setName('Nantes');
        $cityEnabled->setLongitude(-1.553621);
        $cityEnabled->setLatitude(47.218371);

        $manager->persist($cityEnabled);

        $stationDisabledForCityEnabled1 = new Station();
        $stationDisabledForCityEnabled1->setName('Quai Moncousu');
        $stationDisabledForCityEnabled1->setAddress('7 Quai Moncousu, 44000 Nantes');
        $stationDisabledForCityEnabled1->setLongitude(-1.5526149313688824);
        $stationDisabledForCityEnabled1->setLatitude(47.20935942627275);
        $stationDisabledForCityEnabled1->setCapacity(6);
        $stationDisabledForCityEnabled1->setCity($cityEnabled);

        $manager->persist($stationDisabledForCityEnabled1);

        $stationEnabledForCityEnabled1 = new Station();
        $stationEnabledForCityEnabled1->setName('Bd Victor Hugo');
        $stationEnabledForCityEnabled1->setAddress('15 Bd Victor Hugo, 44200 Nantes');
        $stationEnabledForCityEnabled1->setLongitude(-1.5528012583526798);
        $stationEnabledForCityEnabled1->setLatitude(47.20471291679094);
        $stationEnabledForCityEnabled1->setCapacity(5);
        $stationEnabledForCityEnabled1->setStatus(true);
        $stationEnabledForCityEnabled1->setNumberOfAvailableBicycles(2);
        $stationEnabledForCityEnabled1->setCity($cityEnabled);

        $manager->persist($stationEnabledForCityEnabled1);

        // Second city
        $cityEnabled2 = new City();
        $cityEnabled2->setId(Uuid::fromString('018d3c56-bb85-76b5-a471-c1ba8e5ccd19'));
        $cityEnabled2->setName('Rennes');
        $cityEnabled2->setLongitude(-1.553621);
        $cityEnabled2->setLatitude(47.218374);
        $manager->persist($cityEnabled2);

        $stationEnabled2ForCityEnabled1 = new Station();
        $stationEnabled2ForCityEnabled1->setName('Breil');
        $stationEnabled2ForCityEnabled1->setAddress('10 Rue du Breil, 35000 Rennes');
        $stationEnabled2ForCityEnabled1->setLongitude(-1.6777926);
        $stationEnabled2ForCityEnabled1->setLatitude(48.117266);
        $stationEnabled2ForCityEnabled1->setCapacity(7);
        $stationEnabled2ForCityEnabled1->setStatus(true);
        $stationEnabled2ForCityEnabled1->setCity($cityEnabled2);

        $manager->persist($stationEnabled2ForCityEnabled1);

        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail('admin@tap_and_go.com');
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, 'password'));
        $manager->persist($userAdmin);

        $manager->flush();
    }
}
