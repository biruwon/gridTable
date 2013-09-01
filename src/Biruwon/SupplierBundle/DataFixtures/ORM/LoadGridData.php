<?

namespace Biruwon\SupplierBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

use Biruwon\SupplierBundle\Entity\Country,
    Biruwon\SupplierBundle\Entity\Store,
    Biruwon\SupplierBundle\Entity\Product,
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
            $country = new Country();
            $country->setName($countryName);
            $manager->persist($country);
            $this->addReference('country'.$key, $country);
        }

        //Create stores
        for($i=0; $i<count($countries); $i++){
            for($j=1; $j<=5; $j++){
                $store = new Store();
                $store->setName('Store'.$j);
                $store->setCountry($this->getReference('country'.$i));
                $manager->persist($store);
            }
        }

        //Create products
        for($i=0; $i<999; $i++){
            $product = new Product();
            $product->setName('Product'.$i);
            $product->setPrice(rand(1,10));
            $manager->persist($product);
        }

        $manager->flush();

        //Create order items
        $stores = $manager->getRepository('SupplierBundle:Store')->findAll();
        $products = $manager->getRepository('SupplierBundle:Product')->findAll();

        foreach($stores as $store){
            $orderItem = new OrderItem();

            $orderItem->setStoreId($store->getId());

            $index = array_rand($products);
            $productId = $products[$index]->getId();
            $orderItem->setProductId($productId);

            $amount = rand(1, 50);
            $orderItem->setAmount($amount);

            $revenue = $amount * $products[$index]->getPrice();
            $percentage = $revenue*0.1;
            $cost = $revenue + rand(-$percentage, $percentage);
            $orderItem->setCost($cost);

            $manager->persist($orderItem);
        }

        $manager->flush();
    }
}