<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\EquipoService;
use App\Model\Equipo;
use App\Model\DAL\EquipoDao;

class EquipoController
{
    // public function __construct(private EquipoService $service)
    // {
    //     // Si no uses contenidor DI, crea'l a mà:
    //     // $this->service = new EquipoService();
    // }

    /**
     * GET /equipo
     * Llista tots els equips, amb ordenació opcional (?sort=nombre,-ciudad, etc.)
     */
    public static function index(): void
    {
        // $sort = $_GET['sort'] ?? 'id'; // ex: "id", "nombre", "-nombre"
        // $orderBy = $this->normalizeSort($sort); // converteix en "ORDER BY ..." segur

        $equipos = EquipoDao::GetEquipoAll();

        render('equipo/index', ['title' => 'Equips', 'equipos' => $equipos, 'includeDatatable' => true]);
    }

    /**
     * GET /equipo/{id}
     * Mostra un equip.
     */
    public function show(int $id): void
    {
        $equipo = EquipoDao::GetEquipoByID($id);

        if (!$equipo) {
            $this->notFound("No s'ha trobat l'equip #{$id}");
            return;
        }

        render('equipo/show', ['title' => 'Equip '.$equipo->GetNombre(), 'equipo' => $equipo, 'baseUri' => BASE_URI]);
    }

    /**
     * GET /equipo/{id}/edit
     * Edita un equip.
     */
    public function edit(int $id): void
    {
        $equipo = EquipoDao::GetEquipoByID($id);

        if (!$equipo) {
            $this->notFound("No s'ha trobat l'equip #{$id}");
            return;
        }

        render('equipo/edit', ['title' => 'Equip '.$equipo->GetNombre(), 'equipo' => $equipo, 'baseUri' => BASE_URI]);
    }

    /**
     * POST /equipo/{id}/update
     * Edita un equip.
     */
    public function update(int $id): void
    {
        $equipo = EquipoDao::GetEquipoByID($id);
        if (!$equipo) {
            $this->notFound("No s'ha trobat l'equip #{$id}");
            return;
        }
        $errors = $this->GetEquipoDataFromRequest($_POST, $equipo);
        if ($errors) {
            $this->redirect(BASE_URI . '/equipo/' . $newId . '/edit');
            return;
        }

        $updated = EquipoDao::UpdateEquipo($equipo);
        if ($updated > 0) {
            flash('ok', 'Equip actualitzat amb exit!');
        }
        // Redirigeix a la fitxa
        $this->redirect(BASE_URI . '/equipo/' . $id);
    }

    /**
     * GET /equipo/create
     * Mostra el formulari de creació.
     */
    public function create(): void
    {
        // Recupera “old” i “errors” de sessió (patró típic sense framework)
        // session_start();
        $old    = $_SESSION['old']    ?? [];
        $errors = $_SESSION['errors'] ?? [];
        unset($_SESSION['old'], $_SESSION['errors']);

        $equipo = new Equipo();
        $equipo->setPais("España");
        render('equipo/create', ['title' => 'Creant nou equip', 'equipo' => $equipo, 'baseUri' => BASE_URI]);
    }

    /**
     * POST /equipo
     * Desa un nou equip.
     */
    public function store(): void
    {
        $equipo = new Equipo();
        $errors = $this->GetEquipoDataFromRequest($_POST, $equipo);
        if ($errors) {
            $this->redirect('/equipo/create');
            return;
        }

        $newId = EquipoDao::CreateEquipo($equipo);
        flash('ok', 'Equip creat amb exit!');
        // Redirigeix a la fitxa
        $this->redirect(BASE_URI . '/equipo/' . $newId);
    }

    /**
     * GET /equipo/{id}/delete
     * Elimina un equip.
     */
    public function destroy(int $id): void
    {
        $equipo = EquipoDao::GetEquipoByID($id);

        if (!$equipo) {
            $this->notFound("No s'ha trobat l'equip #{$id}");
            return;
        }

        $deleted = EquipoDao::DeleteEquipo($equipo);
        if ($deleted > 0) {
            flash('ok', 'Equip eliminat amb exit!');
        }
        // Redirigeix a la fitxa
        $this->redirect(BASE_URI . '/equipo');        
    }

    /**
     * Obté les dades de l'equip.
     */
    private function GetEquipoDataFromRequest($data, $equipo)
    {
        // Sanititza/valida ràpid (pots substituir per una capa de validació teva)
        $nombre = trim((string)($data['nombre'] ?? ''));
        $ciudad = trim((string)($data['ciudad'] ?? ''));
        $pais   = trim((string)($data['pais']   ?? ''));
        
        $errors = [];
        if ($nombre === '' || mb_strlen($nombre) > 100) $errors['nombre'] = 'Nom requerit (≤100).';
        if ($ciudad === '' || mb_strlen($ciudad) > 100) $errors['ciudad'] = 'Ciutat requerida (≤100).';
        if ($pais   === '' || mb_strlen($pais)   > 100) $errors['pais']   = 'País requerit (≤100).';
        
        if ($errors) {
            session_start();
            $_SESSION['errors'] = $errors;
            $_SESSION['old']    = ['nombre' => $nombre, 'ciudad' => $ciudad, 'pais' => $pais];
            $this->redirect('/equipo/create');
            return $errors;
        }
        else {
            if ($equipo == null) { $equipo = new Equipo(); }
            $equipo->SetNombre($nombre);
            $equipo->SetCiudad($ciudad);
            $equipo->SetPais($pais);
        }

        return $errors;
    }


    /* ===================== Helpers senzills ===================== */

    // Normalitza ?sort=... a una clàusula “ORDER BY …” segura
    private function normalizeSort(string $sort): string
    {
        // Permesos (mapa => columna BD)
        $allowed = [
            'id'     => 'id',
            'nombre' => 'nombre',
            'ciudad' => 'ciudad',
            'pais'   => 'pais',
        ];

        // Suporta múltiples, ex: "nombre,-ciudad"
        $parts = array_filter(array_map('trim', explode(',', $sort)));
        if (!$parts) $parts = ['id'];

        $clauses = [];
        foreach ($parts as $p) {
            $dir   = str_starts_with($p, '-') ? 'DESC' : 'ASC';
            $key   = ltrim($p, '-+');
            $col   = $allowed[$key] ?? null;
            if ($col) {
                $clauses[] = $col . ' ' . $dir;
            }
        }

        if (!$clauses) $clauses[] = 'id ASC';

        return 'ORDER BY ' . implode(', ', $clauses);
    }

    private function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        // Si tens layout, el pots incloure aquí
        require __DIR__ . "/../../view/{$view}";
    }

    private function redirect(string $to): void
    {
        header('Location: ' . $to, true, 302);
        exit;
    }

    private function notFound(string $message = 'Not found'): void
    {
        http_response_code(404);
        $this->render('errors/404.php', ['message' => $message]);
    }
}
