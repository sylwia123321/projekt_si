<?php
/**
 * Tags data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer.
 *
 * @implements DataTransformerInterface<mixed, mixed>
 */
class TagsDataTransformer implements DataTransformerInterface
{
    /**
     * Constructor.
     *
     * @param TagRepository Tag repository
     */
    public function __construct(private readonly TagRepository $tagRepository)
    {
    }

    /**
     * Transform array of tags to string of tag titles.
     *
     * @param Collection<int, Tag> $value Tags entity collection
     *
     * @return string Result
     */
    public function transform($value): string
    {
        if ($value->isEmpty()) {
            return '';
        }

        $tagTitles = [];

        foreach ($value as $tag) {
            $tagTitles[] = $tag->getTitle();
        }

        return implode(', ', $tagTitles);
    }

    /**
     * Transform string of tag names into array of Tag entities.
     *
     * @param string $value String of tag names
     *
     * @return array<int, Tag> Result
     */
    public function reverseTransform($value): array
    {
        $tagTitles = array_map('trim', explode(',', $value));
        $existingTags = $this->tagRepository->findByTitles($tagTitles);

        $tags = [];
        foreach ($tagTitles as $tagTitle) {
            $tag = $existingTags[strtolower($tagTitle)] ?? null;
            if (!$tag) {
                $tag = new Tag();
                $tag->setTitle($tagTitle);
                $this->tagRepository->save($tag);
            }
            $tags[] = $tag;
        }

        return $tags;
    }
}
