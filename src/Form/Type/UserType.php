<?php
/**
 * UserType.
 */

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserType.
 */
class UserType extends AbstractType
{
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * Initializes the UserType form type with necessary dependencies.
     *
     * @param TranslatorInterface $translator The translator service for translating form labels
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email', EmailType::class, ['label' => $this->translator->trans('label.email'), 'required' => true])->add('password', PasswordType::class, ['label' => $this->translator->trans('label.password'), 'required' => true])->add('roles', ChoiceType::class, ['choices' => ['User' => 'ROLE_USER', 'Admin' => 'ROLE_ADMIN'], 'multiple' => true, 'expanded' => true, 'label' => $this->translator->trans('label.roles')]);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }
}
