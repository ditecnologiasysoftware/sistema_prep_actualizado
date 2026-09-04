<?php
require_once __DIR__ . '/clase_variables.php';
require_once __DIR__ . '/clase_mysql.php';

class Entity extends DB_mysql
{
    private static ?self $instance = null;

    /** @var array<string, array{resolved: bool, columns: array<string, bool>, primary: array<int, string>}> */
    private array $schemaCache = [];

    private int $transactionDepth = 0;

    /**
     * Conserva el comportamiento anterior y permite reutilizar una conexión
     * MySQLi existente cuando el proyecto esté listo para compartirla.
     */
    public function __construct(?mysqli $connection = null)
    {
        if ($connection instanceof mysqli) {
            $this->opcion(1);
            $this->Conexion_ID = $connection;
            return;
        }

        parent::__construct();
    }

    /**
     * Crea una única instancia de Entity durante la petición.
     */
    public static function createInstance(?mysqli $connection = null): self
    {
        self::$instance = self::$instance ?: new self($connection);
        return self::$instance;
    }

    /**
     * Ejecuta SQL preparado. Los identificadores y fragmentos SQL deben ser
     * constantes controladas por el desarrollador; los valores van en $params.
     */
    public function rawQuery(string $sql, array $params = []): mysqli_result | bool
    {
        $stmt = $this->executePrepared($sql, $params);
        $this->sql = $this->buildQueryDebug($sql, $params, $this->getParameterTypes($params));

        $result = $stmt->get_result();
        return $result instanceof mysqli_result ? $result : true;
    }

    public function rawSelect(string $sql, array $params = []): array
    {
        $result = $this->rawQuery($sql, $params);
        if (!$result instanceof mysqli_result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /** Devuelve resultados como objetos para las vistas heredadas. */
    public function objects(string $sql, array $params = []): array
    {
        $result = $this->rawQuery($sql, $params);
        if (!$result instanceof mysqli_result) return [];
        $this->Consulta_ID = $result;
        $rows = [];
        while ($row = $result->fetch_object()) $rows[] = $row;
        return $rows;
    }

    /** Devuelve la primera columna de la primera fila. */
    public function scalar(string $sql, array $params = [])
    {
        $result = $this->rawQuery($sql, $params);
        if (!$result instanceof mysqli_result) return null;
        $this->Consulta_ID = $result;
        $row = $result->fetch_row();
        return $row[0] ?? null;
    }

    /** Devuelve una fila asociativa. */
    public function row(string $sql, array $params = []): ?array
    {
        $result = $this->rawQuery($sql, $params);
        if (!$result instanceof mysqli_result) return null;
        $this->Consulta_ID = $result;
        return $result->fetch_assoc() ?: null;
    }

    /** Ejecuta una escritura preparada. */
    public function execute(string $sql, array $params = []): bool
    {
        return $this->rawQuery($sql, $params) !== false;
    }

    public function create(
        string $tablename = '',
        array $formData = [],
        string | array | null $primary = null
    ) {
        if ($tablename === '' || $formData === []) {
            return null;
        }

        $fields = [];
        $values = [];
        $params = [];

        foreach ($formData as $key => $value) {
            $fields[] = $this->quoteIdentifier((string) $key);

            if ($this->isSpecialValue($value)) {
                $values[] = $this->normalizeSpecialValue((string) $value);
                continue;
            }

            $values[] = '?';
            $params[] = $value;
        }

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $this->quoteTable($tablename),
            implode(', ', $fields),
            implode(', ', $values)
        );

        $this->executePrepared($sql, $params);
        $this->sql = $this->buildQueryDebug($sql, $params, $this->getParameterTypes($params));

        $primaryColumns = $this->normalizePrimaryColumns($tablename, $primary);
        $insertId = $this->ultimoid();
        $conditions = [];

        if (count($primaryColumns) === 1 && $insertId) {
            $conditions[$primaryColumns[0]] = $insertId;
        } else {
            foreach ($primaryColumns as $primaryColumn) {
                if (!array_key_exists($primaryColumn, $formData)) {
                    $conditions = [];
                    break;
                }
                $conditions[$primaryColumn] = $formData[$primaryColumn];
            }
        }

        // En tablas sin AUTO_INCREMENT o sin PK recuperable, se conserva un
        // resultado útil sin repetir ni inventar una consulta insegura.
        return $conditions !== []
            ? $this->findOne($tablename, $conditions)
            : (object) $formData;
    }

    public function update(
        string $tablename,
        array $formData,
        int | string | bool | array | null $field = null,
        string | array | null $column = ''
    ) {
        if ($tablename === '') {
            throw new InvalidArgumentException('No se ha especificado una tabla.');
        }

        $conditions = $this->resolveUpdateConditions($tablename, $formData, $field, $column);

        foreach (array_keys($conditions) as $conditionColumn) {
            unset($formData[$conditionColumn]);
        }

        if ($formData === []) {
            throw new InvalidArgumentException('No hay campos para actualizar.');
        }

        $assignments = [];
        $params = [];

        foreach ($formData as $key => $value) {
            $columnSql = $this->quoteIdentifier((string) $key);

            if ($this->isSpecialValue($value)) {
                $assignments[] = $columnSql . ' = ' . $this->normalizeSpecialValue((string) $value);
                continue;
            }

            $assignments[] = $columnSql . ' = ?';
            $params[] = $value;
        }

        $whereParams = [];
        $whereSql = $this->buildEqualityWhere($conditions, $whereParams);
        $params = array_merge($params, $whereParams);

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $this->quoteTable($tablename),
            implode(', ', $assignments),
            $whereSql
        );

        $this->executePrepared($sql, $params);
        $this->sql = $this->buildQueryDebug($sql, $params, $this->getParameterTypes($params));

        return $this->findOne($tablename, $conditions);
    }

    /**
     * Elimina todos los registros que coincidan con uno o varios valores.
     * Cuando $value es un arreglo genera correctamente un IN (?, ...).
     */
    public function deleteAll(string $tablename, mixed $value, string $column = ''): bool
    {
        if ($tablename === '') {
            throw new InvalidArgumentException('No se ha especificado una tabla.');
        }

        $column = $column !== '' ? $column : $this->defaultPrimary($tablename);
        $values = is_array($value) ? array_values($value) : [$value];

        if ($values === []) {
            return false;
        }

        $placeholders = implode(', ', array_fill(0, count($values), '?'));
        $sql = sprintf(
            'DELETE FROM %s WHERE %s IN (%s)',
            $this->quoteTable($tablename),
            $this->quoteIdentifier($column),
            $placeholders
        );

        $stmt = $this->executePrepared($sql, $values);
        $this->sql = $this->buildQueryDebug($sql, $values, $this->getParameterTypes($values));

        return $stmt->affected_rows > 0;
    }

    /**
     * Compatibilidad temporal con módulos heredados que todavía llaman
     * remove(). Las nuevas bajas deben utilizar softDelete() cuando la tabla
     * disponga de fecha_eliminado.
     */
    public function remove(string $tablename, mixed $value, string $column = ''): bool
    {
        return $this->deleteAll($tablename, $value, $column);
    }

    /**
     * Aplica la regla de baja lógica del sistema y conserva el registro.
     */
    public function softDelete(
        string $tablename,
        mixed $value,
        string $column = '',
        string $deletedColumn = 'fecha_eliminado'
    ) {
        if (!$this->columnExists($tablename, $deletedColumn, true)) {
            throw new InvalidArgumentException(
                "La tabla '$tablename' no contiene el campo '$deletedColumn'."
            );
        }

        $column = $column !== '' ? $column : $this->defaultPrimary($tablename);
        return $this->update($tablename, [$deletedColumn => 'NOW()'], $value, $column);
    }

    public function findOne(
        string $tablename,
        mixed $parameter = null,
        string | array | null $primary = ''
    ) {
        if ($parameter === null || (is_array($parameter) && $parameter === [])) {
            return null;
        }

        if (is_array($parameter)) {
            $conditions = $parameter;
        } else {
            $primaryColumns = $this->normalizePrimaryColumns($tablename, $primary);
            if (count($primaryColumns) !== 1) {
                throw new InvalidArgumentException(
                    "La tabla '$tablename' tiene una llave compuesta; envíe las condiciones en un arreglo."
                );
            }
            $conditions = [$primaryColumns[0] => $parameter];
        }

        $params = [];
        $whereSql = $this->buildEqualityWhere($conditions, $params);
        $sql = sprintf(
            'SELECT * FROM %s WHERE %s LIMIT 1',
            $this->quoteTable($tablename),
            $whereSql
        );

        $stmt = $this->executePrepared($sql, $params);
        $result = $stmt->get_result();

        if (!$result instanceof mysqli_result || $result->num_rows === 0) {
            return null;
        }

        return $result->fetch_object();
    }

    /**
     * Alias conservado por compatibilidad.
     */
    public function findFirst(
        string $tablename,
        mixed $parameter = null,
        string | array | null $primary = ''
    ) {
        return $this->findOne($tablename, $parameter, $primary);
    }

    public function deleteOne(
        string $tablename,
        mixed $parameter,
        string | array | null $primary = ''
    ): bool {
        if (is_array($parameter)) {
            $conditions = $parameter;
        } else {
            $primaryColumns = $this->normalizePrimaryColumns($tablename, $primary);
            if (count($primaryColumns) !== 1) {
                throw new InvalidArgumentException(
                    "La tabla '$tablename' tiene una llave compuesta; envíe las condiciones en un arreglo."
                );
            }
            $conditions = [$primaryColumns[0] => $parameter];
        }

        $params = [];
        $whereSql = $this->buildEqualityWhere($conditions, $params);
        $sql = sprintf(
            'DELETE FROM %s WHERE %s LIMIT 1',
            $this->quoteTable($tablename),
            $whereSql
        );

        $stmt = $this->executePrepared($sql, $params);
        $this->sql = $this->buildQueryDebug($sql, $params, $this->getParameterTypes($params));

        return $stmt->affected_rows > 0;
    }

    public function first(string $tablename, string $primary = '')
    {
        $primary = $primary !== '' ? $primary : $this->defaultPrimary($tablename);
        $sql = sprintf(
            'SELECT * FROM %s ORDER BY %s ASC LIMIT 1',
            $this->quoteTable($tablename),
            $this->quoteIdentifier($primary)
        );

        $stmt = $this->executePrepared($sql);
        $result = $stmt->get_result();

        return $result instanceof mysqli_result && $result->num_rows > 0
            ? $result->fetch_object()
            : null;
    }

    public function last(string $tablename, string $primary = '')
    {
        $primary = $primary !== '' ? $primary : $this->defaultPrimary($tablename);
        $sql = sprintf(
            'SELECT * FROM %s ORDER BY %s DESC LIMIT 1',
            $this->quoteTable($tablename),
            $this->quoteIdentifier($primary)
        );

        $stmt = $this->executePrepared($sql);
        $result = $stmt->get_result();

        return $result instanceof mysqli_result && $result->num_rows > 0
            ? $result->fetch_object()
            : null;
    }

    public function select(
        string $tablename,
        array $columns,
        string | array $conditionals = '',
        array $params = [],
        ?int $page = null,
        ?int $limit = null
    ) {
        if ($tablename === '' || $columns === []) {
            return null;
        }

        $fields = array_map(fn($column) => $this->normalizeSelectColumn((string) $column), $columns);
        $sql = sprintf('SELECT %s FROM %s', implode(', ', $fields), $this->quoteTable($tablename));

        if (is_array($conditionals) && $conditionals !== []) {
            $params = [];
            $sql .= ' WHERE ' . $this->buildEqualityWhere($conditionals, $params);
        } elseif (is_string($conditionals) && trim($conditionals) !== '') {
            $this->assertSafeSqlFragment($conditionals, 'condición');
            $sql .= ' WHERE ' . $conditionals;
        }

        if ($page !== null && $limit !== null) {
            if ($page < 1 || $limit < 1) {
                throw new InvalidArgumentException('La página y el límite deben ser mayores que cero.');
            }

            $offset = ($page - 1) * $limit;
            $sql .= ' LIMIT ? OFFSET ?';
            $params[] = $limit;
            $params[] = $offset;
        } elseif ($page !== null) {
            // Compatibilidad: históricamente el quinto argumento solo funciona
            // como LIMIT; selectOne() depende de este comportamiento.
            if ($page < 1) {
                throw new InvalidArgumentException('El límite debe ser mayor que cero.');
            }
            $sql .= ' LIMIT ?';
            $params[] = $page;
        }

        $stmt = $this->executePrepared($sql, $params);
        $result = $stmt->get_result();

        if (!$result instanceof mysqli_result) {
            return [];
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function selectOne(
        string $tablename,
        array $columns,
        string | array $conditional = '',
        array $params = []
    )
    {
        $result = $this->select($tablename, $columns, $conditional, $params, 1);
        return !empty($result) ? (object) $result[0] : null;
    }

    public function selectOneById(string $tablename, array $columns, mixed $id, string $primary = '')
    {
        $primary = $primary !== '' ? $primary : $this->defaultPrimary($tablename);
        return $this->selectOne($tablename, $columns, $this->quoteIdentifier($primary) . ' = ?', [$id]);
    }

    public function field(
        string $tablename,
        string $column,
        string $conditional = '',
        array $params = [],
        string $type = ''
    ) {
        if ($tablename === '' || $column === '') {
            return null;
        }

        $result = $this->select($tablename, [$column], $conditional, $params, 1);
        if (empty($result)) {
            return null;
        }

        return $type === 'json'
            ? json_decode($result[0][$column] ?? 'null')
            : ($result[0][$column] ?? null);
    }

    public function fieldById(string $tablename, string $column, mixed $id, string $primary = '')
    {
        $primary = $primary !== '' ? $primary : $this->defaultPrimary($tablename);
        return $this->field($tablename, $column, $this->quoteIdentifier($primary) . ' = ?', [$id]);
    }

    public function count(
        string $tablename,
        string | array $conditionals = '',
        array $params = [],
        string $primary = ''
    ): int {
        if ($tablename === '') {
            throw new InvalidArgumentException('No se ha especificado una tabla.');
        }

        // COUNT(*) funciona también con tablas de llave primaria compuesta.
        $result = $this->select($tablename, ['COUNT(*) AS count'], $conditionals, $params);
        return (int) ($result[0]['count'] ?? 0);
    }

    public function exists(string $tablename, string | array $conditionals, array $params = []): bool
    {
        return $this->count($tablename, $conditionals, $params) > 0;
    }

    public function createOrUpdate(
        string $tablename,
        array $formData,
        mixed $id,
        string | array | null $primary = ''
    ) {
        $primaryColumns = $this->normalizePrimaryColumns($tablename, $primary);
        $conditions = [];

        if (is_array($id)) {
            $conditions = $id;
        } elseif ($id !== null && $id !== '') {
            if (count($primaryColumns) !== 1) {
                throw new InvalidArgumentException(
                    "La tabla '$tablename' tiene una llave compuesta; envíe el identificador en un arreglo."
                );
            }
            $conditions[$primaryColumns[0]] = $id;
        } else {
            foreach ($primaryColumns as $primaryColumn) {
                if (!array_key_exists($primaryColumn, $formData)) {
                    $conditions = [];
                    break;
                }
                $conditions[$primaryColumn] = $formData[$primaryColumn];
            }
        }

        if ($conditions !== [] && $this->exists($tablename, $conditions)) {
            return $this->update($tablename, $formData, $conditions);
        }

        return $this->create($tablename, $formData, $primary);
    }

    /**
     * Construye una versión legible de una consulta únicamente para auditoría
     * o depuración. Nunca debe ejecutarse nuevamente.
     */
    public function buildQueryDebug($sql, $params, $types)
    {
        $params = array_values((array) $params);
        $types = (string) $types;
        $offset = 0;

        foreach ($params as $index => $param) {
            $position = strpos($sql, '?', $offset);
            if ($position === false) {
                break;
            }

            if ($param === null) {
                $paramValue = 'NULL';
            } elseif (is_bool($param)) {
                $paramValue = $param ? '1' : '0';
            } elseif (($types[$index] ?? '') === 's' || is_string($param)) {
                $paramValue = "'" . $this->escapeDebugValue((string) $param) . "'";
            } elseif (is_int($param) || is_float($param)) {
                $paramValue = (string) $param;
            } else {
                $paramValue = "'[valor no representable]'";
            }

            $sql = substr_replace($sql, $paramValue, $position, 1);
            $offset = $position + strlen($paramValue);
        }

        return $sql;
    }

    public function verifyExistence(
        string $tablename,
        array $conditionals,
        mixed $exceptId = null,
        string | array | null $primaryKey = null
    ): bool {
        $params = [];
        $conditionsSql = $this->buildEqualityWhere($conditionals, $params);

        if ($exceptId !== null) {
            $primaryColumns = $this->normalizePrimaryColumns($tablename, $primaryKey);

            if (is_array($exceptId)) {
                $exceptParams = [];
                $exceptSql = $this->buildEqualityWhere($exceptId, $exceptParams);
                $conditionsSql .= ' AND NOT (' . $exceptSql . ')';
                $params = array_merge($params, $exceptParams);
            } else {
                if (count($primaryColumns) !== 1) {
                    throw new InvalidArgumentException(
                        "La tabla '$tablename' tiene una llave compuesta; envíe exceptId como arreglo."
                    );
                }
                $conditionsSql .= ' AND ' . $this->quoteIdentifier($primaryColumns[0]) . ' != ?';
                $params[] = $exceptId;
            }
        }

        return $this->count($tablename, $conditionsSql, $params) > 0;
    }

    public function findAll(string $tablename, array $conditions = [], bool $excludeDeleted = true): array
    {
        $params = [];
        $whereParts = [];

        if ($conditions !== []) {
            $whereParts[] = $this->buildEqualityWhere($conditions, $params);
        }

        if ($excludeDeleted && $this->columnExists($tablename, 'fecha_eliminado', true)) {
            $whereParts[] = $this->quoteIdentifier('fecha_eliminado') . ' IS NULL';
        }

        $sql = 'SELECT * FROM ' . $this->quoteTable($tablename);
        if ($whereParts !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }

        $stmt = $this->executePrepared($sql, $params);
        $result = $stmt->get_result();

        return $result instanceof mysqli_result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getCombo(
        string $tableName,
        string $idColumn,
        string $nameColumn,
        array $params = [],
        string $orderBy = ''
    ): array {
        $bindParams = [];
        $whereParts = [];

        if ($this->columnExists($tableName, 'fecha_eliminado', true)) {
            $whereParts[] = $this->quoteIdentifier('fecha_eliminado') . ' IS NULL';
        }

        $filterColumn = $params['filter_column'] ?? null;
        $filterValue = $params['filter_value'] ?? null;

        if ($filterColumn !== null && $filterColumn !== '' && $filterValue !== null && $filterValue !== '') {
            $whereParts[] = $this->quoteQualifiedIdentifier((string) $filterColumn) . ' LIKE ?';
            $bindParams[] = '%' . $filterValue . '%';
        }

        $sql = sprintf(
            'SELECT %s AS id, %s AS valor FROM %s',
            $this->quoteQualifiedIdentifier($idColumn),
            $this->quoteQualifiedIdentifier($nameColumn),
            $this->quoteTable($tableName)
        );

        if ($whereParts !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }

        $sql .= ' ORDER BY ' . $this->normalizeOrderBy($orderBy !== '' ? $orderBy : $nameColumn);

        if (isset($params['inicio'], $params['limite'])) {
            $inicio = (int) $params['inicio'];
            $limite = (int) $params['limite'];
            if ($inicio < 0 || $limite < 1) {
                throw new InvalidArgumentException('Los parámetros de paginación no son válidos.');
            }
            $sql .= ' LIMIT ?, ?';
            $bindParams[] = $inicio;
            $bindParams[] = $limite;
        }

        $stmt = $this->executePrepared($sql, $bindParams);
        $result = $stmt->get_result();

        return $this->resultToObjects($result);
    }

    public function getList(string $tableName, array $params = [], string $orderBy = 'nombre'): array
    {
        $bindParams = [];
        $whereParts = $this->buildListFilters($tableName, $params, $bindParams);
        $sql = 'SELECT * FROM ' . $this->quoteTable($tableName);

        if ($whereParts !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }

        if (!$this->columnExists($tableName, $this->extractFirstOrderColumn($orderBy), true)) {
            $orderBy = $this->defaultPrimary($tableName);
        }
        $sql .= ' ORDER BY ' . $this->normalizeOrderBy($orderBy);

        if (isset($params['inicio'], $params['limite'])) {
            $inicio = (int) $params['inicio'];
            $limite = (int) $params['limite'];
            if ($inicio < 0 || $limite < 1) {
                throw new InvalidArgumentException('Los parámetros de paginación no son válidos.');
            }
            $sql .= ' LIMIT ?, ?';
            $bindParams[] = $inicio;
            $bindParams[] = $limite;
        }

        $stmt = $this->executePrepared($sql, $bindParams);
        return $this->resultToObjects($stmt->get_result());
    }

    public function getCount(string $tableName, array $params = []): int
    {
        $bindParams = [];
        $whereParts = $this->buildListFilters($tableName, $params, $bindParams);
        $sql = 'SELECT COUNT(*) AS total FROM ' . $this->quoteTable($tableName);

        if ($whereParts !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $whereParts);
        }

        $stmt = $this->executePrepared($sql, $bindParams);
        $result = $stmt->get_result();
        $row = $result instanceof mysqli_result ? $result->fetch_assoc() : null;

        return (int) ($row['total'] ?? 0);
    }

    public function transaction(callable $callback): mixed
    {
        $initialDepth = $this->transactionDepth;
        $this->beginTransaction();

        try {
            $result = $callback($this);
            $this->commitTransaction();
            return $result;
        } catch (Throwable $throwable) {
            // Evita ocultar la excepción original si el fallo ocurrió al
            // confirmar y el nivel ya regresó al estado inicial.
            if ($this->transactionDepth > $initialDepth) {
                $this->rollbackTransaction();
            }
            throw $throwable;
        }
    }

    public function beginTransaction(): void
    {
        $connection = $this->connection();

        if ($this->transactionDepth === 0) {
            if (!$connection->begin_transaction()) {
                throw new RuntimeException('No fue posible iniciar la transacción.');
            }
        } else {
            $savepoint = 'entity_sp_' . $this->transactionDepth;
            if (!$connection->query('SAVEPOINT ' . $savepoint)) {
                throw new RuntimeException('No fue posible crear el punto de guardado.');
            }
        }

        $this->transactionDepth++;
    }

    public function commitTransaction(): void
    {
        if ($this->transactionDepth < 1) {
            throw new LogicException('No hay una transacción activa para confirmar.');
        }

        $this->transactionDepth--;
        $connection = $this->connection();

        if ($this->transactionDepth === 0) {
            if (!$connection->commit()) {
                throw new RuntimeException('No fue posible confirmar la transacción.');
            }
            return;
        }

        $savepoint = 'entity_sp_' . $this->transactionDepth;
        if (!$connection->query('RELEASE SAVEPOINT ' . $savepoint)) {
            throw new RuntimeException('No fue posible liberar el punto de guardado.');
        }
    }

    public function rollbackTransaction(): void
    {
        if ($this->transactionDepth < 1) {
            throw new LogicException('No hay una transacción activa para revertir.');
        }

        $this->transactionDepth--;
        $connection = $this->connection();

        if ($this->transactionDepth === 0) {
            if (!$connection->rollback()) {
                throw new RuntimeException('No fue posible revertir la transacción.');
            }
            return;
        }

        $savepoint = 'entity_sp_' . $this->transactionDepth;
        if (!$connection->query('ROLLBACK TO SAVEPOINT ' . $savepoint)) {
            throw new RuntimeException('No fue posible revertir al punto de guardado.');
        }
    }

    public function isInTransaction(): bool
    {
        return $this->transactionDepth > 0;
    }

    private function connection(): mysqli
    {
        $connection = $this->obtenerconexion();
        if (!$connection instanceof mysqli) {
            throw new RuntimeException('No existe una conexión MySQLi válida.');
        }

        return $connection;
    }

    private function executePrepared(string $sql, array $params = []): mysqli_stmt
    {
        $connection = $this->connection();
        $stmt = $connection->prepare($sql);

        if (!$stmt instanceof mysqli_stmt) {
            throw new RuntimeException('No fue posible preparar la consulta: ' . $connection->error);
        }

        if ($params !== []) {
            $boundValues = array_values($params);
            $types = $this->getParameterTypes($boundValues);
            $arguments = [$types];

            foreach ($boundValues as $index => &$boundValue) {
                $arguments[] = &$boundValue;
            }
            unset($boundValue);

            if (!call_user_func_array([$stmt, 'bind_param'], $arguments)) {
                throw new RuntimeException('No fue posible vincular los parámetros: ' . $stmt->error);
            }
        }

        if (!$stmt->execute()) {
            throw new RuntimeException('No fue posible ejecutar la consulta: ' . $stmt->error);
        }

        return $stmt;
    }

    private function getParameterTypes(array $params): string
    {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param) || is_bool($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } elseif (is_string($param) || $param === null) {
                $types .= 's';
            } else {
                throw new InvalidArgumentException(
                    'Entity solo permite valores escalares o null como parámetros preparados.'
                );
            }
        }

        return $types;
    }

    private function isSpecialValue(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return in_array(strtolower(trim($value)), ['null', 'now()', 'curdate()'], true);
    }

    private function normalizeSpecialValue(string $value): string
    {
        return match (strtolower($value)) {
            'null' => 'NULL',
            'now()' => 'NOW()',
            'curdate()' => 'CURDATE()',
            default => throw new InvalidArgumentException('La expresión SQL especial no está permitida.'),
        };
    }

    private function quoteIdentifier(string $identifier): string
    {
        $identifier = trim($identifier);
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/D', $identifier)) {
            throw new InvalidArgumentException("Identificador SQL no válido: '$identifier'.");
        }

        return '`' . $identifier . '`';
    }

    private function quoteQualifiedIdentifier(string $identifier): string
    {
        $parts = explode('.', trim($identifier));
        if ($parts === []) {
            throw new InvalidArgumentException('El identificador SQL está vacío.');
        }

        return implode('.', array_map(fn($part) => $this->quoteIdentifier($part), $parts));
    }

    private function quoteTable(string $tablename): string
    {
        return $this->quoteQualifiedIdentifier($tablename);
    }

    private function normalizeSelectColumn(string $column): string
    {
        $column = trim($column);
        if ($column === '*') {
            return '*';
        }

        if (preg_match(
            '/^(?<column>[A-Za-z_][A-Za-z0-9_]*(?:\.[A-Za-z_][A-Za-z0-9_]*)?)(?:\s+AS\s+(?<alias>[A-Za-z_][A-Za-z0-9_]*))?$/i',
            $column,
            $matches
        )) {
            $normalized = $this->quoteQualifiedIdentifier($matches['column']);
            if (!empty($matches['alias'])) {
                $normalized .= ' AS ' . $this->quoteIdentifier($matches['alias']);
            }
            return $normalized;
        }

        if (preg_match(
            '/^(?<function>COUNT|SUM|MIN|MAX|AVG)\(\s*(?<column>\*|[A-Za-z_][A-Za-z0-9_]*(?:\.[A-Za-z_][A-Za-z0-9_]*)?)\s*\)(?:\s+AS\s+(?<alias>[A-Za-z_][A-Za-z0-9_]*))?$/i',
            $column,
            $matches
        )) {
            $argument = $matches['column'] === '*'
                ? '*'
                : $this->quoteQualifiedIdentifier($matches['column']);
            $normalized = strtoupper($matches['function']) . '(' . $argument . ')';
            if (!empty($matches['alias'])) {
                $normalized .= ' AS ' . $this->quoteIdentifier($matches['alias']);
            }
            return $normalized;
        }

        // Conserva expresiones avanzadas existentes, pero bloquea separadores y
        // comentarios que no deben formar parte de una columna SELECT.
        $this->assertSafeSqlFragment($column, 'columna SELECT');
        return $column;
    }

    private function normalizeOrderBy(string $orderBy): string
    {
        $parts = array_map('trim', explode(',', $orderBy));
        $normalized = [];

        foreach ($parts as $part) {
            if (!preg_match(
                '/^(?<column>[A-Za-z_][A-Za-z0-9_]*(?:\.[A-Za-z_][A-Za-z0-9_]*)?)(?:\s+(?<direction>ASC|DESC))?$/i',
                $part,
                $matches
            )) {
                throw new InvalidArgumentException("Ordenamiento SQL no válido: '$part'.");
            }

            $item = $this->quoteQualifiedIdentifier($matches['column']);
            if (!empty($matches['direction'])) {
                $item .= ' ' . strtoupper($matches['direction']);
            }
            $normalized[] = $item;
        }

        return implode(', ', $normalized);
    }

    private function assertSafeSqlFragment(string $fragment, string $context): void
    {
        if (preg_match('/(;|--|#|\/\*|\*\/|\x00)/', $fragment)) {
            throw new InvalidArgumentException("El fragmento de $context contiene elementos no permitidos.");
        }
    }

    private function buildEqualityWhere(array $conditions, array &$params): string
    {
        if ($conditions === []) {
            throw new InvalidArgumentException('Debe especificar al menos una condición.');
        }

        $parts = [];
        foreach ($conditions as $column => $value) {
            $columnSql = $this->quoteQualifiedIdentifier((string) $column);

            if ($value === null) {
                $parts[] = $columnSql . ' IS NULL';
                continue;
            }

            $parts[] = $columnSql . ' = ?';
            $params[] = $value;
        }

        return implode(' AND ', $parts);
    }

    private function resolveUpdateConditions(
        string $tablename,
        array $formData,
        int | string | bool | array | null $field,
        string | array | null $column
    ): array {
        if (is_array($field)) {
            if ($field === []) {
                throw new InvalidArgumentException('Las condiciones de actualización están vacías.');
            }
            return $field;
        }

        $primaryColumns = $this->normalizePrimaryColumns($tablename, $column);

        if ($field !== null) {
            if (count($primaryColumns) !== 1) {
                throw new InvalidArgumentException(
                    "La tabla '$tablename' tiene una llave compuesta; envíe las condiciones en un arreglo."
                );
            }
            return [$primaryColumns[0] => $field];
        }

        $conditions = [];
        foreach ($primaryColumns as $primaryColumn) {
            if (!array_key_exists($primaryColumn, $formData)) {
                throw new InvalidArgumentException(
                    "No se encontró el campo '$primaryColumn' en los datos para actualizar."
                );
            }
            $conditions[$primaryColumn] = $formData[$primaryColumn];
        }

        return $conditions;
    }

    private function normalizePrimaryColumns(
        string $tablename,
        string | array | null $primary = null
    ): array {
        if (is_array($primary)) {
            if ($primary === []) {
                throw new InvalidArgumentException('La definición de llave primaria está vacía.');
            }
            foreach ($primary as $primaryColumn) {
                $this->quoteIdentifier((string) $primaryColumn);
            }
            return array_values($primary);
        }

        if (is_string($primary) && trim($primary) !== '') {
            $this->quoteIdentifier($primary);
            return [$primary];
        }

        $metadata = $this->getSchemaMetadata($tablename);
        if ($metadata['primary'] !== []) {
            return $metadata['primary'];
        }

        return [$this->defaultPrimaryByConvention($tablename)];
    }

    private function defaultPrimary(string $tablename): string
    {
        $primaryColumns = $this->normalizePrimaryColumns($tablename, null);
        if ($primaryColumns === []) {
            throw new RuntimeException("No fue posible determinar la llave primaria de '$tablename'.");
        }

        return $primaryColumns[0];
    }

    private function defaultPrimaryByConvention(string $tablename): string
    {
        $table = $this->getUnqualifiedTableName($tablename);
        $position = strpos($table, '_');

        if ($position === false || $position === strlen($table) - 1) {
            throw new RuntimeException("No fue posible deducir la llave primaria de '$tablename'.");
        }

        return 'id_' . substr($table, $position + 1);
    }

    /**
     * Obtiene columnas y llaves primarias reales una sola vez por tabla y
     * petición. Si el servidor no permite introspección, conserva el patrón
     * histórico como respaldo.
     */
    private function getSchemaMetadata(string $tablename): array
    {
        $table = $this->getUnqualifiedTableName($tablename);
        if (isset($this->schemaCache[$table])) {
            return $this->schemaCache[$table];
        }

        $metadata = ['resolved' => false, 'columns' => [], 'primary' => []];

        try {
            $sql = 'SELECT COLUMN_NAME, COLUMN_KEY '
                . 'FROM INFORMATION_SCHEMA.COLUMNS '
                . 'WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? '
                . 'ORDER BY ORDINAL_POSITION';
            $stmt = $this->executePrepared($sql, [$table]);
            $result = $stmt->get_result();

            if ($result instanceof mysqli_result) {
                while ($row = $result->fetch_assoc()) {
                    $column = (string) $row['COLUMN_NAME'];
                    $metadata['columns'][$column] = true;
                    if (($row['COLUMN_KEY'] ?? '') === 'PRI') {
                        $metadata['primary'][] = $column;
                    }
                }
                $metadata['resolved'] = true;
            }
        } catch (Throwable $throwable) {
            // La introspección mejora la clase, pero no debe impedir que siga
            // funcionando con permisos de BD limitados o instalaciones antiguas.
            $metadata['resolved'] = false;
        }

        $this->schemaCache[$table] = $metadata;
        return $metadata;
    }

    private function columnExists(string $tablename, string $column, bool $fallback): bool
    {
        $this->quoteIdentifier($column);
        $metadata = $this->getSchemaMetadata($tablename);

        return $metadata['resolved']
            ? isset($metadata['columns'][$column])
            : $fallback;
    }

    private function getUnqualifiedTableName(string $tablename): string
    {
        $parts = explode('.', trim($tablename));
        $table = (string) end($parts);
        $this->quoteIdentifier($table);
        return $table;
    }

    private function buildListFilters(string $tableName, array $filters, array &$bindParams): array
    {
        $whereParts = [];

        if ($this->columnExists($tableName, 'fecha_eliminado', true)) {
            $whereParts[] = $this->quoteIdentifier('fecha_eliminado') . ' IS NULL';
        }

        foreach ($filters as $key => $value) {
            if ($key === 'inicio' || $key === 'limite' || $value === '') {
                continue;
            }

            $columnSql = $this->quoteQualifiedIdentifier((string) $key);
            if ($value === null) {
                $whereParts[] = $columnSql . ' IS NULL';
            } elseif (is_string($value)) {
                $whereParts[] = $columnSql . ' LIKE ?';
                $bindParams[] = '%' . $value . '%';
            } else {
                $whereParts[] = $columnSql . ' = ?';
                $bindParams[] = $value;
            }
        }

        return $whereParts;
    }

    private function extractFirstOrderColumn(string $orderBy): string
    {
        $first = trim(explode(',', $orderBy)[0] ?? '');
        $column = preg_split('/\s+/', $first)[0] ?? '';
        $parts = explode('.', $column);
        return (string) end($parts);
    }

    private function resultToObjects(mysqli_result | bool $result): array
    {
        if (!$result instanceof mysqli_result) {
            return [];
        }

        $objects = [];
        while ($row = $result->fetch_object()) {
            $objects[] = $row;
        }
        return $objects;
    }

    private function escapeDebugValue(string $value): string
    {
        $connection = $this->obtenerconexion();
        return $connection instanceof mysqli
            ? $connection->real_escape_string($value)
            : addslashes($value);
    }
}
