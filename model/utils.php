<?php

class Utils{
    /**
     * Converts DateTime to DB timestamp string
     */
    public static function DateTimeToTimestamp($fecha) : string {
        return $fecha->format('Y-m-d H:i:s');
    }

    /**
     * Construeix un fragment segur d'ORDER BY (versió genèrica).
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
     * @param array $allowed Whitelist de columnes permeses
     * @param string $default Eg. "id ASC"
     * @return string
     */
    public static function EnsureOrderBy(string|array|null $orderBy, array $allowed, string $default = 'id ASC'): string
    {
        $norm = [];

        $push = function (string $field, ?string $dir) use (&$norm, $allowed) {
            $f = strtolower(trim($field));
            if (!isset($allowed[$f])) {
                return; // ignora camps no permesos
            }
            $d = strtoupper(trim((string)$dir));
            $d = ($d === 'DESC') ? 'DESC' : 'ASC'; // default ASC
            // Protecció extra: no acceptem cap altre token
            $norm[] = sprintf('`%s` %s', $allowed[$f], $d);
        };

        if (is_string($orderBy) && $orderBy !== '') {
            // Ex: "nombre DESC, id asc"
            foreach (explode(',', $orderBy) as $part) {
                $part = trim($part);
                if ($part === '') continue;
                // Separa per espais (com a màxim 2 tokens: camp i direcció)
                $tokens = preg_split('/\s+/', $part);
                $field = $tokens[0] ?? '';
                $dir   = $tokens[1] ?? null;
                $push($field, $dir);
            }
        } elseif (is_array($orderBy)) {
            // Pot ser associatiu ['campo' => 'DESC'] o llista [['field'=>'campo','dir'=>'DESC']]
            $isAssoc = array_keys($orderBy) !== range(0, count($orderBy) - 1);
            if ($isAssoc) {
                foreach ($orderBy as $field => $dir) {
                    $push((string)$field, is_string($dir) ? $dir : null);
                }
            } else {
                foreach ($orderBy as $item) {
                    if (is_array($item)) {
                        $push((string)($item['field'] ?? ''), isset($item['dir']) ? (string)$item['dir'] : null);
                    }
                }
            }
        }

        if (empty($norm)) {
            // Comprovem que el default sigui segur (mateixa lògica)
            return self::EnsureOrderBy($default, $allowed, 'id ASC');
        }

        return implode(', ', $norm);
    }

}