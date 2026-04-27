<?php
// src/Models/ExperienceModel.php — Centralise toutes les requêtes SQL liées aux expériences
declare(strict_types=1);

class ExperienceModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupère une expérience par son ID avec jointures entreprise + domaine
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                e.*, 
                ent.adresse AS entreprise_adresse_fk,
                ent.contact_phone AS entreprise_phone_fk,
                ent.contact_email AS entreprise_email_fk,
                ent.site_web AS entreprise_site_fk,
                ent.linkedin_url AS entreprise_linkedin_fk,
                d.nom AS domaine_nom_fk
            FROM experiences e
            LEFT JOIN entreprises ent ON e.entreprise_id = ent.id
            LEFT JOIN domaines d ON e.domaine_id = d.id
            WHERE e.id = :id AND e.deleted_at IS NULL
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $exp = $stmt->fetch();

        if (!$exp)
            return null;

        // Fusion des données pour compatibilité
        $exp['domaine'] = $exp['domaine_nom_fk'] ?? $exp['domaine'];
        $exp['entreprise_adresse'] = $exp['entreprise_adresse_fk'] ?? '';
        $exp['entreprise_phone'] = $exp['entreprise_phone_fk'] ?? $exp['entreprise_phone'] ?? '';
        $exp['entreprise_email'] = $exp['entreprise_email_fk'] ?? $exp['entreprise_email'] ?? '';
        $exp['entreprise_linkedin'] = $exp['entreprise_linkedin_fk'] ?? $exp['entreprise_linkedin'] ?? '';

        return $exp;
    }

    /**
     * Récupère les N dernières expériences approuvées
     */
    public function findLatestApproved(int $limit = 6): array
    {
        $stmt = $this->pdo->prepare("
            SELECT id, type, poste, entreprise_nom AS entreprise
            FROM experiences
            WHERE is_approved = 1 AND deleted_at IS NULL
            ORDER BY id DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Récupère les expériences en attente de modération
     */
    public function findPending(): array
    {
        return $this->pdo->query("
            SELECT id, entreprise_nom, poste, type, etudiant_prenom, etudiant_nom, created_at, email_verified_at
            FROM experiences
            WHERE is_approved = 0 AND deleted_at IS NULL
            ORDER BY created_at ASC
        ")->fetchAll();
    }

    /**
     * Recherche paginée d'expériences approuvées
     */
    public function searchApproved(string $query = '', array $filters = [], int $page = 1, int $pageSize = 20): array
    {
        $params = [];
        $conditions = ['is_approved = 1', 'deleted_at IS NULL'];

        if ($query !== '') {
            $conditions[] = "(poste LIKE :q OR entreprise_nom LIKE :q OR etudiant_nom LIKE :q OR etudiant_prenom LIKE :q)";
            $params[':q'] = "%$query%";
        }
        foreach (['type', 'domaine', 'ville'] as $key) {
            if (!empty($filters[$key])) {
                $conditions[] = "$key = :$key";
                $params[":$key"] = $filters[$key];
            }
        }
        if (!empty($filters['annee'])) {
            $conditions[] = "annee = :annee";
            $params[':annee'] = $filters['annee'];
        }

        $where = 'WHERE ' . implode(' AND ', $conditions);
        $offset = ($page - 1) * $pageSize;

        // Total
        $countSql = "SELECT COUNT(*) FROM experiences $where";
        $stCount = $this->pdo->prepare($countSql);
        $stCount->execute($params);
        $total = (int) $stCount->fetchColumn();

        // Résultats
        $sql = "SELECT id, entreprise_nom, poste, type, etudiant_prenom, etudiant_nom, ville, annee, email_verified_at
                FROM experiences $where
                ORDER BY id DESC LIMIT $pageSize OFFSET $offset";
        $st = $this->pdo->prepare($sql);
        $st->execute($params);

        return [
            'items' => $st->fetchAll(),
            'total' => $total,
        ];
    }

    /**
     * Approuve une expérience
     */
    public function approve(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE experiences SET is_approved = 1 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Soft Delete (met deleted_at = NOW())
     */
    public function softDelete(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE experiences SET deleted_at = NOW() WHERE id = :id AND deleted_at IS NULL");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Restaure depuis la corbeille
     */
    public function restore(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE experiences SET deleted_at = NULL WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Suppression permanente (avec gestion cascade entreprise orpheline)
     */
    public function deletePermanently(int $id): ?int
    {
        // Récupère l'entreprise_id avant suppression
        $stmt = $this->pdo->prepare("SELECT entreprise_id FROM experiences WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        $entrepriseId = $row ? (int) $row['entreprise_id'] : null;

        // Suppression
        $del = $this->pdo->prepare("DELETE FROM experiences WHERE id = :id");
        $del->execute([':id' => $id]);

        // Retourne l'entreprise_id pour vérification cascade
        return $entrepriseId;
    }

    /**
     * Expériences dans la corbeille
     */
    public function findTrashed(): array
    {
        return $this->pdo->query("
            SELECT id, entreprise_nom, poste, type, etudiant_prenom, etudiant_nom, deleted_at
            FROM experiences
            WHERE deleted_at IS NOT NULL
            ORDER BY deleted_at DESC
        ")->fetchAll();
    }

    /**
     * Vérifie un token d'email et met à jour email_verified_at
     */
    public function verifyEmailToken(string $token): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, email_verified_at FROM experiences WHERE email_verification_token = :token");
        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch();

        if (!$row)
            return null;

        if ($row['email_verified_at'] === null) {
            $update = $this->pdo->prepare("UPDATE experiences SET email_verified_at = NOW(), email_verification_token = NULL WHERE id = :id");
            $update->execute([':id' => $row['id']]);
            $row['just_verified'] = true;
        } else {
            $row['just_verified'] = false;
        }

        return $row;
    }

    /**
     * Trouve le prochain ID disponible (gap filling)
     */
    public function findNextAvailableId(): ?int
    {
        $check = $this->pdo->query("SELECT id FROM experiences WHERE id = 1");
        if ($check->rowCount() === 0)
            return 1;

        $gap = $this->pdo->query("
            SELECT t1.id + 1 AS gap_id 
            FROM experiences t1 
            LEFT JOIN experiences t2 ON t1.id + 1 = t2.id 
            WHERE t2.id IS NULL 
            ORDER BY t1.id ASC LIMIT 1
        ")->fetch();

        return $gap ? (int) $gap['gap_id'] : null;
    }

    /**
     * Compteur de stats pour le dashboard admin
     */
    public function getStats(): array
    {
        return [
            'total' => (int) $this->pdo->query("SELECT COUNT(*) FROM experiences")->fetchColumn(),
            'companies' => (int) $this->pdo->query("SELECT COUNT(*) FROM entreprises")->fetchColumn(),
        ];
    }

    /**
     * Récupère les valeurs distinctes pour les filtres (domaines, villes, années)
     */
    public function getFilterMeta(): array
    {
        $domaines = $this->pdo->query("SELECT DISTINCT domaine FROM experiences WHERE domaine IS NOT NULL AND domaine != '' AND deleted_at IS NULL AND is_approved = 1 ORDER BY domaine")->fetchAll(PDO::FETCH_COLUMN);
        $villes = $this->pdo->query("SELECT DISTINCT ville FROM experiences WHERE ville IS NOT NULL AND ville != '' AND deleted_at IS NULL AND is_approved = 1 ORDER BY ville")->fetchAll(PDO::FETCH_COLUMN);
        $annees = $this->pdo->query("SELECT DISTINCT annee FROM experiences WHERE deleted_at IS NULL AND is_approved = 1 ORDER BY annee DESC")->fetchAll(PDO::FETCH_COLUMN);

        return [
            'domaines' => $domaines,
            'villes' => $villes,
            'annees' => $annees,
        ];
    }
}
