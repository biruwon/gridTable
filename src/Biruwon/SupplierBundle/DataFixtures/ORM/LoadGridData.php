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
        echo "Creating countries...\n";
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
        echo "Creating stores...\n";
        $storeIndex = 0;
        for($i=1; $i<=count($countries); $i++){
            for($j=1; $j<=20; $j++){

                $storeIndex++;
                $store = new Store('Store'.$j, $this->getReference('country'.$i));
                $manager->persist($store);
                $this->addReference('store'.$storeIndex, $store);
            }
        }

        $manager->flush();

        //Create products
        echo "Creating products...\n";
        for($i=1; $i<=1000; $i++){

            $product = new Product('Product'.$i, rand(1, 10));
            $manager->persist($product);
        }

        //Create orders
        echo "Creating store orders...\n";
        for($i=1; $i<=(count($countries)*20); $i++){

            for($j=1; $j<=10; $j++){
                $order = new Order($this->getReference('store'.$i));
                $manager->persist($order);
            }
        }

        $manager->flush();

        $orders = $manager->getRepository('SupplierBundle:Order')->findAll();
        $products = $manager->getRepository('SupplierBundle:Product')->findAll();

        //Create order item
        echo "Creating orders items... (It takes a moment)\n";
        foreach($orders as $order){
            $numOrders = rand(1, 10);
            for($i=1; $i<=$numOrders; $i++){

                $index = array_rand($products);
                $product = $products[$index];

                $amount = rand(50, 500);

                $revenue = $amount * $products[$index]->getPrice();
                $percentageMax = $revenue*0.1;
                $percentageMin = $revenue*0.05;
                $cost = $revenue + rand(-$percentageMin, $percentageMax);

                $orderItem = new OrderItem($product, $order, $cost, $amount);

                $order->getItems()->add($orderItem);
                $manager->persist($orderItem);
            }
        }

        $manager->flush();
    }
}