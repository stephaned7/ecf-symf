<?php
namespace App\Form\Extension;

use App\Form\ReservationType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class ReservationTypeExtension extends AbstractTypeExtension
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getExtendedTypes(): iterable
    {
        return [ReservationType::class];
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'preSubmit']);
    }

    public function preSubmit(FormEvent $event): void
    {
        $data = $event->getData();
        $currentUser = $this->security->getUser();
        
        if (is_array($data)) {
            $data['user'] = $currentUser->getId();
        }

        $event->setData($data);
    }
}
