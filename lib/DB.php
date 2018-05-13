<?php
namespace lib;

class DB
{
    private $pdo;

    /**
     * DB constructor.
     *
     * @param array $params
     */
    public function __construct(array $params)
    {
        $dsn = "mysql:dbname={$params['db']};host={$params['host']}";
        $user = $params['user'];
        $password = $params['pass'];

        try {
            $this->pdo = new \PDO($dsn, $user, $password);
        } catch (\PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
            exit;
        }

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param string $sql
     * @param array $params
     *
     * @return array|bool
     */
    public function exec($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);

        try {
            $result = $stmt->execute($params);
        } catch (\Exception $e) {
            echo "Ошибка запроса к БД: " . $e->getMessage();
            exit;
        }

        return $result;
    }

    /**
     * @param string $sql
     * @param array $params
     *
     * @return array|bool
     */
    public function select($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);

        try {
            $result = $stmt->execute($params);
        } catch (\Exception $e) {
            echo "Ошибка запроса к БД: " . $e->getMessage();
            exit;
        }

        return $result ? $stmt->fetchAll(\PDO::FETCH_ASSOC) : $stmt->errorInfo();
    }

    /**
     * @param $sql
     * @param array $params
     *
     * @return bool|string
     */
    public function insert($sql, $params = [])
    {
        $result = $this->exec($sql, $params);

        return $result ? $this->pdo->lastInsertId() : false;
    }

    /**
     * @param string $sql
     * @param array $params
     *
     * @return bool
     */
    public function update($sql, $params = [])
    {
        return $this->exec($sql, $params);
    }

    /**
     * @param string $sql
     * @param array $params
     *
     * @return bool
     */
    public function delete($sql, $params = [])
    {
        return $this->exec($sql, $params);
    }

    /**
     * @param string $sql
     * @param array $rows
     *
     * @return bool
     */
    public function saveBatch($sql, array $rows)
    {
        $stmt = $this->pdo->prepare($sql);

        try {
            $result = true;
            foreach ($rows as $row) {
                $result = $result && $stmt->execute($row);
            }
        } catch (\Exception $e) {
            echo "Ошибка запроса к БД: " . $e->getMessage();
            exit;
        }

        return $result;
    }
}