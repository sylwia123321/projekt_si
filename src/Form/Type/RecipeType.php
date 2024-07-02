<?php
/**
 * Recipe type.
 */

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RecipeType.
 */
class RecipeType extends AbstractType
{
    private TranslatorInterface $translator;

    /**
     * Constructor.
     *
     * @param TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(TranslatorInterface $translator, private readonly TagsDataTransformer $tagsDataTransformer)
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
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => $this->translator->trans('label.title'),
                'required' => true,
                'attr' => ['max_length' => 255],
            ]
        );
        $builder->add(
            'description',
            TextareaType::class,
            [
                'label' => $this->translator->trans('label.description'),
                'required' => true,
                'attr' => ['rows' => 5],
            ]
        );
        $builder->add(
            'ingredients',
            TextareaType::class,
            [
                'label' => $this->translator->trans('label.ingredients'),
                'required' => true,
                'attr' => ['rows' => 5],
            ]
        );
        $builder->add(
            'instructions',
            TextareaType::class,
            [
                'label' => $this->translator->trans('label.instructions'),
                'required' => true,
                'attr' => ['rows' => 5],
            ]
        );
        $builder->add(
            'comment',
            TextareaType::class,
            [
                'label' => $this->translator->trans('label.comment'),
                'required' => false,
                'attr' => ['rows' => 5],
            ]
        );
        $builder->add(
            'category',
            EntityType::class,
            [
                'class' => Category::class,
                'choice_label' => function ($category): string {
                    return $category->getTitle();
                },
                'label' => $this->translator->trans('label.category'),
                'placeholder' => $this->translator->trans('label.none'),
                'required' => true,
            ]
        );
        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => $this->translator->trans('label.tags'),
                'required' => false,
                'attr' => ['max_length' => 128],
            ]
        );

        $builder->get('tags')->addModelTransformer(
            $this->tagsDataTransformer
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Recipe::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'recipe';
    }
}
