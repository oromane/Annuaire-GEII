<?php
// src/Models/DomaineModel.php — Centralise les requêtes liées aux domaines
declare(strict_types=1);

class DomaineModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Trouve un domaine par son nom exact
     */
    public function findByName(string $nom): ?int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM domaines WHERE nom = :nom");
        $stmt->execute([':nom' => $nom]);
        $id = $stmt->fetchColumn();
        return $id !== false ? (int) $id : null;
    }

    /**
     * Crée un domaine et retourne son ID
     */
    public function create(string $nom): int
    {
        $stmt = $this->pdo->prepare("INSERT INTO domaines (nom) VALUES (:nom)");
        $stmt->execute([':nom' => $nom]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Trouve ou crée un domaine, retourne l'ID
     */
    public function findOrCreate(string $nom): int
    {
        $existing = $this->findByName($nom);
        if ($existing !== null)
            return $existing;
        return $this->create($nom);
    }

    /**
     * Liste tous les domaines triés
     */
    public function findAll(): array
    {
        return $this->pdo->query("SELECT id, nom FROM domaines ORDER BY nom ASC")->fetchAll();
    }
}
