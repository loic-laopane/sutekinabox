<?php
/**
 * Created by PhpStorm.
 * User: Etudiant
 * Date: 31/10/2017
 * Time: 10:36
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Product;
use AppBundle\Entity\Supplier;
use AppBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class Fixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $factory = new EncoderFactory(['bcrypt']);
        $encoder = new UserPasswordEncoder($factory);
        $marketing_user = new User();

        $marketing_user->setUsername('mmm')
                        ->setPassword('$2y$13$mnRnq0tFG3ufIOByVfzsH.RX7FRo/brHXWq.tZ4qxsNuj6uJgygRe')
                        ->setFirstname('Janes')
                        ->setLastname('Doe')
                        ->setEmail('marketing@sutekinabox.com')
                        ->setRoles(['ROLE_MARKETING']);
        $manager->persist($marketing_user);

        $achat_user = new User();
        $achat_user->setUsername('aaa')
                    ->setPassword('$2y$13$95RnOX34A49R4IT4HWZppO109uClZFMhU1hEE6kP9aGznVz1PMDJu')
                    ->setFirstname('John')
                    ->setLastname('Doe')
                    ->setEmail('achat@sutekinabox.com')
                    ->setRoles(['ROLE_ACHAT']);
        $manager->persist($achat_user);


        $supplier_list = [];
        for($i=0; $i<10;$i++)
        {
            $supplier = new Supplier();
            $supplier->setSociety('Fournisseur '.$i);
            $supplier->setSiret('siret-'.$i);
            $manager->persist($supplier);
            $supplier_list[] = $supplier;
        }

        $products = $this->getProduct();
        for ($i = 0; $i < 7; $i++) {
            $product = new Product();
            $product->setLabel($products[$i]);
            $product->setReference('ref-'.str_replace(' ', '-', strtolower($product->getLabel())));
            $product->setDescription('Description de '.$product->getLabel());
            $product->setPrice(mt_rand(1, 7));

            $manager->persist($product);
        }

        $manager->flush();
    }

    public function getProduct()
    {
        return [
          'Lacets',
          'Gel boost',
          'Porte-dossard',
            'Manchons',
          'Chaussette strap',
            'Gel effort',
            'Gourde',
            'Ecouteurs blutooth',

        ];
    }
}