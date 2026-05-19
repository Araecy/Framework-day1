<?php

namespace App\Models;

use Araecy\Framework\Database\Connection;
class book
{
    private ?int $id = null;
    private string $type = "";
    private string $title = "";
    private string $content = "";
    // private DateTime $created_at = "";

    public function save(): void
    {
        $connection = Connection::getConnection();

        $stmt = $connection->pdo->prepare("
            INSERT INTO records (type, title, content)
            VALUES (:type, :title, :content)
        ");
        $stmt->bindValue(':type', $this->getType());
        $stmt->bindValue(':title', $this->getTitle());
        $stmt->bindValue(':content', $this->getContent());

        $stmt->execute();
    }
    public function getType(): string
    {
        return $this->type;
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getContent(): string
    {
        return $this->content;
    }
    public function setType(string $type): void
    {
        $this->type = $type;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}