<?

namespace Biruwon\SupplierBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Biruwon\SupplierBundle\Entity\Country,
    Biruwon\SupplierBundle\Entity\Store,
    Biruwon\SupplierBundle\Entity\Product,
    Biruwon\SupplierBundle\Entity\Order,
    Biruwon\SupplierBundle\Entity\OrderItem;

class LoadGridData extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        //Create countries
        $countries = array(
            'Argentina', 'Australia', 'België', 'Brasil', 'Canada', 'Chile', 'Colombia',
            'Deutschland', 'España', 'France', 'India', 'Ireland', 'Italia','Nederland',
            'New Zealand', 'Portugal', 'Schweiz', 'Sverige', 'United Kingdom','USA'
            );

        foreach($countries as $key => $countryName){

            $country = new Country($countryName);
            $manager->persist($country);
            $this->addReference('country'.$key, $country);
        }

        //Create stores
        for($i=0; $i<count($countries); $i++){
            for($j=1; $j<=5; $j++){

                $store = new Store('Store'.$j, $this->getReference('country'.$i));
                $manager->persist($store);
            }
        }

        //Create products
        for($i=1; $i<=1000; $i++){

            $product = new Product('Product'.$i, rand(1, 10));
            $manager->persist($product);
        }

        $manager->flush();

        $stores = $manager->getRepository('SupplierBundle:Store')->findAll();
        $products = $manager->getRepository('SupplierBundle:Product')->findAll();

        //Create order
        foreach($stores as $store){

            $order = new Order($store);
            $manager->persist($order);
            $manager->flush();
            //var_dump($order);

            for($i=1; $i<=3; $i++){
                
                $index = array_rand($products);
                $product = $products[$index];

                $amount = rand(1, 50);

                $revenue = $amount * $products[$index]->getPrice();
                $percentage = $revenue*0.1;
                $cost = $revenue + rand(-$percentage, $percentage);

                $orderItem = new OrderItem($product, $order, $amount, $cost);

                $order->getItems()->add($orderItem);
                // $manager->persist($order);
                var_dump($orderItem);
                $manager->persist($orderItem);
            }
        }

        $manager->flush();
    }
}