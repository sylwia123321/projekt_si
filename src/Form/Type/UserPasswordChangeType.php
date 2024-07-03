<?php
/**
 * UserPasswordChangeType.
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserPasswordChangeType.
 */
class UserPasswordChangeType extends AbstractType
{
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * Initializes the form type with necessary dependencies.
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
        $builder->add('currentPassword', PasswordType::class, ['label' => $this->translator->trans('label.current_password'), 'required' => true, 'mapped' => false, 'constraints' => [new NotBlank(['message' => $this->translator->trans('validators.current_password')])]])->add('newPassword', RepeatedType::class, ['type' => PasswordType::class, 'first_options' => ['label' => $this->translator->trans('label.new_password')], 'second_options' => ['label' => $this->translator->trans('label.repeat_new_password')], 'invalid_message' => $this->translator->trans('validators.password_mismatch'), 'required' => true, 'mapped' => false, 'constraints' => [new NotBlank(['message' => $this->translator->trans('validators.new_password')]), new Length(['min' => 6, 'minMessage' => $this->translator->trans('validators.password_length', ['{{ limit }}' => 6]), 'max' => 4096]), new NotCompromisedPassword()]]);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
