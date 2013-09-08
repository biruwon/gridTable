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
            $this->addReference('country'.($key+1), $country);
        }

        $manager->flush();

        //Create stores
        $storeIndex = 0;
        for($i=1; $i<=count($countries); $i++){
            for($j=1; $j<=10; $j++){

                $storeIndex++;
                $store = new Store('Store'.$j, $this->getReference('country'.$i));
                $manager->persist($store);
                $this->addReference('store'.$storeIndex, $store);
            }
        }

        $manager->flush();

        //Create products
        for($i=1; $i<=1000; $i++){

            $product = new Product('Product'.$i, rand(1, 10));
            $manager->persist($product);
        }

        //Create orders
        for($i=1; $i<=(count($countries)*10); $i++){

            for($j=1; $j<=10; $j++){
                $order = new Order($this->getReference('store'.$i));
                $manager->persist($order);
            }
        }

        $manager->flush();

        $orders = $manager->getRepository('SupplierBundle:Order')->findAll();
        $products = $manager->getRepository('SupplierBundle:Product')->findAll();

        //Create order
        foreach($orders as $order){
            $numOrders = rand(1, 10);
            for($i=$numOrders; $i<=10; $i++){

                $index = array_rand($products);
                $product = $products[$index];

                $amount = rand(1, 100);

                $revenue = $amount * $products[$index]->getPrice();
                $percentage = $revenue*0.1;
                $cost = $revenue + rand(-$percentage, $percentage);

                $orderItem = new OrderItem($product, $order, $cost, $amount);

                $order->getItems()->add($orderItem);
                $manager->persist($orderItem);
            }
        }

        $manager->flush();
    }
}