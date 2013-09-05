<?

namespace Biruwon\SupplierBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CountrySelect extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $valueIds = array();
        foreach($options['data'] as $key => $value){
            $valueIds[$value->getId()] = $value->getName();
        }

        $builder->add('countries', 'choice', array(
            'choices' => $valueIds,
            'expanded' => false,
            'multiple' => false,
            'empty_value' => 'Global'
            )
        );
    }

    public function getName()
    {
        return 'country';
    }
}