<?php

namespace App\Models;

use Araecy\Framework\Database\Connection;
use PDO;

class Account
{
    public function __construct(
        private ?int $id = null,
        private string $naam = '',
        private string $email = '',
        private string $adres = '',
        private string $woonplaats = '',
        private string $telefoonnummer = '',
        private string $geboortedatum = '',
        private string $geslacht = 'man',
        private string $wachtwoord_hash = '',
        private bool $has_ticket = false,
    ) {}

    public static function findByEmail(string $email): ?static
    {
        $pdo = Connection::getConnection()->pdo;
        $stmt = $pdo->prepare('SELECT * FROM accounts WHERE Email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? static::fromRow($row) : null;
    }

    public static function findById(int $id): ?static
    {
        $pdo = Connection::getConnection()->pdo;
        $stmt = $pdo->prepare('SELECT * FROM accounts WHERE Id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? static::fromRow($row) : null;
    }

    public static function getAll(): array
    {
        $pdo = Connection::getConnection()->pdo;
        $stmt = $pdo->query('SELECT * FROM accounts');
        return array_map(fn($row) => static::fromRow($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function count(): int
    {
        $pdo = Connection::getConnection()->pdo;
        return (int) $pdo->query('SELECT COUNT(*) FROM accounts')->fetchColumn();
    }

    public function save(): void
    {
        $pdo = Connection::getConnection()->pdo;
        if ($this->id === null) {
            $stmt = $pdo->prepare(
                'INSERT INTO accounts (Naam, Email, Adres, Woonplaats, Telefoonnummer, Geboortedatum, Geslacht, Wachtwoord_hash, has_Ticket)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            $stmt->execute([
                $this->naam, $this->email, $this->adres, $this->woonplaats,
                $this->telefoonnummer, $this->geboortedatum, $this->geslacht,
                $this->wachtwoord_hash, (int) $this->has_ticket,
            ]);
            $this->id = (int) $pdo->lastInsertId();
        } else {
            $stmt = $pdo->prepare(
                'UPDATE accounts SET Naam=?, Email=?, Adres=?, Woonplaats=?, Telefoonnummer=?,
                 Geboortedatum=?, Geslacht=?, Wachtwoord_hash=?, has_Ticket=? WHERE Id=?'
            );
            $stmt->execute([
                $this->naam, $this->email, $this->adres, $this->woonplaats,
                $this->telefoonnummer, $this->geboortedatum, $this->geslacht,
                $this->wachtwoord_hash, (int) $this->has_ticket, $this->id,
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = Connection::getConnection()->pdo;
        $pdo->prepare('DELETE FROM accounts WHERE Id = ?')->execute([$this->id]);
        $this->id = null;
    }

    private static function fromRow(array $row): static
    {
        return new static(
            id: (int) $row['Id'],
            naam: $row['Naam'],
            email: $row['Email'],
            adres: $row['Adres'],
            woonplaats: $row['Woonplaats'],
            telefoonnummer: $row['Telefoonnummer'],
            geboortedatum: $row['Geboortedatum'],
            geslacht: $row['Geslacht'],
            wachtwoord_hash: $row['Wachtwoord_hash'],
            has_ticket: (bool) $row['has_Ticket'],
        );
    }

    public function getId(): ?int { return $this->id; }
    public function getNaam(): string { return $this->naam; }
    public function getEmail(): string { return $this->email; }
    public function getAdres(): string { return $this->adres; }
    public function getWoonplaats(): string { return $this->woonplaats; }
    public function getTelefoonnummer(): string { return $this->telefoonnummer; }
    public function getGeboortedatum(): string { return $this->geboortedatum; }
    public function getGeslacht(): string { return $this->geslacht; }
    public function getWachtwoordHash(): string { return $this->wachtwoord_hash; }
    public function hasTicket(): bool { return $this->has_ticket; }

    public function setNaam(string $naam): void { $this->naam = $naam; }
    public function setAdres(string $adres): void { $this->adres = $adres; }
    public function setWoonplaats(string $woonplaats): void { $this->woonplaats = $woonplaats; }
    public function setTelefoonnummer(string $tel): void { $this->telefoonnummer = $tel; }
    public function setGeboortedatum(string $gbd): void { $this->geboortedatum = $gbd; }
    public function setGeslacht(string $geslacht): void { $this->geslacht = $geslacht; }
    public function setWachtwoordHash(string $hash): void { $this->wachtwoord_hash = $hash; }
    public function setHasTicket(bool $val): void { $this->has_ticket = $val; }
}
