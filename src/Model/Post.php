<?php
namespace App\Model;

class Post extends DefaultModel
{
    protected static string $table = 'post';
    protected static array $fields = ['subject', 'content'];

    protected ?string $subject = null;
    protected ?string $content = null;

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): Post
    {
        $this->subject = $subject;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): Post
    {
        $this->content = $content;
        return $this;
    }
}
