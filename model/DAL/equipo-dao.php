<?php

include_once 'database/database.php';
include_once 'model/equipo.php';
include_once 'model/utils.php';

use App\Models\Equipo;

class EquipoDao {

    /**
     * Retorna tots els equips de la base de dades.
     *
     * Obté tots els registres de la taula `equipo`, ordenats pel ID,
     * i crea una llista d'objectes `Equipo`. Si no hi ha registres,
     * retorna un array buit.
     *
     * @param string|array|null $orderBy Ordre desitjat (ex. "nombre DESC, id ASC").
     * @return Equipo[] Llista d'objectes `Equipo` (pot estar buida).
     * @throws \RuntimeException Si la consulta a la base de dades falla.
     */    
    public static function GetEquipoAll($orderBy = null) : array {
        $conn = Database::connect();

        $order = self::EnsureEquipoOrderBy($orderBy, 'id ASC');
        $sql = "SELECT * FROM equipo ORDER BY {$order}";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $equipos = [];
        $rows = $stmt->get_result();
        $equipos = EquipoDao::RecordListoToEquipoList($rows);
        // while ($row = $rows->fetch_assoc()) {
        //     $equipo = EquipoDao::RecordToEquipo($row);
        //     $equipos[] = $equipo;
        // }

        // Tanquem connexió
        $stmt->close();
        $conn->close();

        return $equipos;
    }

    /**
     * Cerca un equip pel seu identificador.
     *
     * @param int $id Identificador únic.
     * @return Equipo|null L'equip trobat o null si no existeix.
     */    
    public static function GetEquipoByID($id) : ?Equipo {
        $conn = Database::connect();

        $stmt = $conn->prepare("SELECT * FROM equipo WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $rows = $stmt->get_result();
        // $equipo = $rows->fetch_object('App\Models\Equipo');
        $row = $rows->fetch_assoc();
        $equipo = ($row) ? EquipoDao::RecordToEquipo($row) : null;

        // Tanquem connexió
        $stmt->close();
        $conn->close();

        return $equipo;
    }

    /**
     * Crea un nou equip a la base de dades.
     *
     * Insereix un registre a la taula `equipo` amb els camps especificats.
     * Els valors de `created_at` i `updated_at` s'estableixen automàticament
     * per la base de dades.
     *
     * @param Equipo $equipo Objecte `Equipo` amb el nom, ciutat i país a guardar.
     * @return int ID del nou Equipo
     * @throws \RuntimeException Si la consulta SQL falla.
     */
    public static function CreateEquipo(Equipo $equipo): int
    {
        $conn = Database::connect();

        // Preparem la consulta (timestamps gestionats per la BD)
        $stmt = $conn->prepare("
            INSERT INTO equipo (nombre, ciudad, pais)
            VALUES (?, ?, ?)
        ");

        if (!$stmt) {
            throw new \RuntimeException("Error preparing statement: " . $conn->error);
        }

        // Bind dels valors
        $nombre = $equipo->getNombre();
        $ciudad = $equipo->getCiudad();
        $pais   = $equipo->getPais();

        $stmt->bind_param('sss', $nombre, $ciudad, $pais);

        // Executem
        $ok = $stmt->execute();
        if (!$ok) {
            throw new \RuntimeException("Error executing insert: " . $stmt->error);
        }

        // Recuperem l'ID generat
        $newId = (int) $conn->insert_id;
        
        // Assignem l'ID autogenerat a l'objecte
        $equipo->setId($newId);

        // Tanquem connexió
        $stmt->close();
        $conn->close();

        return $newId;
    }

    /**
     * Actualitza un equip existent a la base de dades.
     *
     * Modifica els camps `nombre`, `ciudad` i `pais` de l'equip indicat per `id`.
     * La columna `updated_at` s’actualitza automàticament per la BD.
     *
     * Nota: si les dades noves són iguals a les existents, `affected_rows` pot ser 0.
     *
     * @param Equipo $equipo Objecte amb l'ID i els camps a actualitzar.
     * @return int Nombre de files afectades (0, 1…).
     * @throws \InvalidArgumentException Si l'ID no és vàlid.
     * @throws \RuntimeException Si hi ha algun error SQL.
     */
    public static function UpdateEquipo(Equipo $equipo): int
    {
        $id = $equipo->getId();
        if ($id === null || $id <= 0) {
            throw new \InvalidArgumentException('Cal un ID vàlid per actualitzar l’equip.');
        }

        $conn = Database::connect();

        $sql = "
            UPDATE equipo
            SET nombre = ?, ciudad = ?, pais = ?
            WHERE id = ?
        ";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            throw new \RuntimeException("Error preparing statement: " . $conn->error);
        }

        // Bind dels valors
        $nombre = $equipo->getNombre();
        $ciudad = $equipo->getCiudad();
        $pais   = $equipo->getPais();

        $stmt->bind_param('sssi', $nombre, $ciudad, $pais, $id);

        if (!$stmt->execute()) {
            $err = $stmt->error ?: $conn->error;
            $stmt->close();
            $conn->close();
            throw new \RuntimeException("Error executing update: " . $err);
        }

        $affected = $stmt->affected_rows;

        $stmt->close();
        $conn->close();

        return $affected; // 0 si no hi ha canvis, 1 si s’ha actualitzat, etc.
    }

    /**
     * Elimina un equip per ID.
     *
     * @param int $id Identificador de l’equip a eliminar.
     * @return int Nombre de files eliminades (0 si no existeix).
     * @throws \InvalidArgumentException Si l'ID no és vàlid.
     * @throws \RuntimeException Si hi ha algun error SQL (p.ex., restricció de FK).
     */
    public static function DeleteEquipoById(int $id): int
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException('Cal un ID vàlid (> 0) per eliminar l’equip.');
        }

        $conn = Database::connect();

        $sql = "DELETE FROM equipo WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $conn->close();
            throw new \RuntimeException("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param('i', $id);

        if (!$stmt->execute()) {
            $err = $stmt->error ?: $conn->error;
            $stmt->close();
            $conn->close();
            throw new \RuntimeException("Error executing delete: " . $err);
        }

        $affected = $stmt->affected_rows;

        $stmt->close();
        $conn->close();

        return $affected; // 0 = no trobat, 1 = eliminat
    }

    /**
     * Elimina un equip a partir de l’objecte.
     *
     * @param Equipo $equipo Objecte amb l’ID a eliminar.
     * @return int Nombre de files eliminades.
     * @throws \InvalidArgumentException Si l'ID no és vàlid.
     * @throws \RuntimeException Si hi ha algun error SQL.
     */
    public static function DeleteEquipo(?Equipo $equipo): int
    {
        if ($equipo === null) {
            throw new \InvalidArgumentException('No s’ha proporcionat cap objecte Equipo.');
        }

        $id = $equipo->getId();
        if ($id === null || $id <= 0) {
            throw new \InvalidArgumentException('L’objecte Equipo no té un ID vàlid per eliminar.');
        }
        return self::DeleteEquipoById($id);
    }

    /**
      * Converteix un registre de la taula `equipo` i el converteix
      * en una instància de la classe `Equipo`. Si no troba registre retorna `null`
      *
      * @param $row fila de la taula equipo
      * @return Equipo|null Retorna l'objecte `Equipo` si existeix, o `null` si no es troba.
     */
    protected static function RecordToEquipo($row){
        // Converteix els timestamps (string) a DateTimeImmutable
        $created = $row['created_at'] ? new \DateTimeImmutable($row['created_at']) : null;
        $updated = $row['updated_at'] ? new \DateTimeImmutable($row['updated_at']) : null;

        return new \App\Models\Equipo(
            (int) $row['id'],
            $row['nombre'],
            $row['ciudad'],
            $row['pais'],
            $created,
            $updated
        );
    }

    /**
      * Converteix un llistat de registres de la taula `equipo` i el converteix
      * en llistat de la classe `Equipo`. Si no troba registre retorna `Equipo[]`
      *
      * @param $rows llistat de registres de la taula `equipo`
      * @return Equipo[]] Retorna un llistat d'objectes `Equipo`
     */ 
    protected static function RecordListoToEquipoList($rows) : array {
        $equipos = [];
        while ($row = $rows->fetch_assoc()) {
            $equipo = EquipoDao::RecordToEquipo($row);
            $equipos[] = $equipo;
        }
        return $equipos;
    }

    /**
     * Construeix un fragment segur d'ORDER BY per a la taula `equipo`.
     *
     * Accepta:
     *  - string: "nombre DESC, id ASC"
     *  - array:  ['nombre' => 'DESC', 'id' => 'ASC']
     *  - array:  [ ['field' => 'nombre', 'dir' => 'DESC'], ['field'=>'id','dir'=>'ASC'] ]
     *  - null:   usa el per defecte
     *
     * Torna només el fragment sense la paraula `ORDER BY`, llest per interpolar.
     *
     * @param string|array|null $orderBy
     * @param string $default Eg. "id ASC"
     * @return string
     */
    protected static function EnsureEquipoOrderBy(string|array|null $orderBy, string $default = 'id ASC'): string
    {
        // Whitelist de columnes permeses
        $allowed = [
            'id' => 'id',
            'nombre' => 'nombre',
            'ciudad' => 'ciudad',
            'pais' => 'pais',
            'created_at' => 'created_at',
            'updated_at' => 'updated_at',
        ];
        return Utils::EnsureOrderBy($orderBy, $allowed, $default);
    }

}