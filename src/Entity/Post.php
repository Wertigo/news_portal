<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @var int
     */
    const STATUS_DRAFT = 0;

    /**
     * @var int
     */
    const STATUS_MODERATION_CHECK = 1;

    /**
     * @var int
     */
    const STATUS_PUBLISHED = 2;

    /**
     * @var int
     */
    const STATUS_DECLINED = 3;

    /**
     * @var string
     */
    const STATUS_TEXT_DRAFT = 'Draft';

    /**
     * @var string
     */
    const STATUS_TEXT_MODERATION_CHECK = 'Moderation check';

    /**
     * @var string
     */
    const STATUS_TEXT_PUBLISHED = 'Published';

    /**
     * @var string
     */
    const STATUS_TEXT_DECLINED = 'Declined';


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=511)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=2,
     *     max=100,
     *     minMessage="Title length must by more then {{ limit }} characters",
     *     maxMessage="Title length must by less then {{ limit }} characters"
     * )
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $content;

    /**
     * @ORM\Column(type="bigint")
     */
    private $rating;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToMany(targetEntity="\App\Entity\Tag", inversedBy="posts")
     */
    private $tags;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="post")
     */
    private $comments;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->created_at = new DateTime();
        $this->updated_at = new DateTime();
        $this->comments = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Post
     */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Post
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Post
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     * @return Post
     */
    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     * @return Post
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    public function setTags(array $tags): self
    {
        $this->tags = new ArrayCollection($tags);

        return $this;
    }

    /**
     * @param bool $asString
     * @return mixed
     */
    public function getCreatedAt($asString = true)
    {
        if ($asString) {
            return $this->created_at->format('Y-m-d H:i:s');
        }

        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @param bool $asString
     * @return mixed
     */
    public function getUpdatedAt($asString = true)
    {
        if ($asString) {
            return $this->updated_at->format('Y-m-d H:i:s');
        }

        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamps(): void
    {
        $this->setUpdatedAt(new DateTime('now'));

        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTime('now'));
        }
    }

    /**
     * @return bool
     */
    public function isPostPublished(): bool
    {
        return static::STATUS_PUBLISHED === $this->getStatus();
    }

    /**
     * @return bool
     */
    public function isPostCanBeModerate(): bool
    {
        return static::STATUS_DRAFT === $this->getStatus();
    }

    /**
     * @return bool
     */
    public function isPostAvailableForEditing(): bool
    {
        return Post::STATUS_DRAFT === $this->getStatus();
    }

    /**
     * @return string
     */
    public function getShortContent()
    {
        return substr(strip_tags($this->getContent()), 0, 300) . ' ...';
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getTextStatus(): string
    {
        switch ($this->getStatus()) {
            case static::STATUS_DRAFT :
                return static::STATUS_TEXT_DRAFT;
            case static::STATUS_MODERATION_CHECK :
                return static::STATUS_TEXT_MODERATION_CHECK;
            case static::STATUS_PUBLISHED :
                return static::STATUS_TEXT_PUBLISHED;
            case static::STATUS_DECLINED :
                return static::STATUS_TEXT_DECLINED;
            default:
                return '';
        }
    }
}
