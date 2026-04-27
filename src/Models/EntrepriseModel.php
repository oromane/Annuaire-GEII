<?php
// src/Models/EntrepriseModel.php — Centralise les requêtes liées aux entreprises
declare(strict_types=1);

class EntrepriseModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Trouve une entreprise par son nom exact
     */
    public function findByName(string $nom): ?int
    {
        $stmt = $this->pdo->prepare("SELECT id FROM entreprises WHERE nom = :nom");
        $stmt->execute([':nom' => $nom]);
        $id = $stmt->fetchColumn();
        return $id !== false ? (int) $id : null;
    }

    /**
     * Crée une entreprise et retourne son ID
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO entreprises (nom, adresse, ville, contact_email, contact_phone, linkedin_url)
            VALUES (:nom, :addr, :ville, :email, :phone, :linkedin)
        ");
        $stmt->execute([
            ':nom' => $data['nom'],
            ':addr' => $data['adresse'] ?? null,
            ':ville' => $data['ville'] ?? null,
            ':email' => $data['contact_email'] ?? null,
            ':phone' => $data['contact_phone'] ?? null,
            ':linkedin' => $data['linkedin_url'] ?? null,
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Trouve ou crée une entreprise, retourne l'ID
     */
    public function findOrCreate(string $nom, array $extraData = []): int
    {
        $existing = $this->findByName($nom);
        if ($existing !== null)
            return $existing;
        return $this->create(array_merge(['nom' => $nom], $extraData));
    }

    /**
     * Vérifie si une entreprise est orpheline (plus aucune expérience liée)
     */
    public function isOrphan(int $id): bool
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM experiences WHERE entreprise_id = :id");
        $stmt->execute([':id' => $id]);
        return (int) $stmt->fetchColumn() === 0;
    }

    /**
     * Supprime une entreprise orpheline
     */
    public function deleteIfOrphan(int $id): bool
    {
        if ($this->isOrphan($id)) {
            $stmt = $this->pdo->prepare("DELETE FROM entreprises WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        }
        return false;
    }

    /**
     * Liste toutes les entreprises avec contacts
     */
    public function findAllWithContacts(int $limit = 20, bool $random = false): array
    {
        $order = $random ? 'ORDER BY RAND()' : 'ORDER BY nom ASC';
        $stmt = $this->pdo->prepare("
            SELECT e.id, e.nom AS entreprise_nom, e.ville, e.contact_email AS entreprise_email,
                   e.contact_phone AS entreprise_phone, e.site_web AS entreprise_site,
                   e.linkedin_url AS entreprise_linkedin,
                   (SELECT GROUP_CONCAT(DISTINCT ex.domaine) FROM experiences ex WHERE ex.entreprise_id = e.id AND ex.deleted_at IS NULL) AS domaine
            FROM entreprises e
            $order
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
