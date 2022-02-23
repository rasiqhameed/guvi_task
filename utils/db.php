<?php

require_once __DIR__ . '/../env.php';

function db(): \mysqli
{
    try {
        $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE2,DB_PORT);
        mysqli_set_charset($db, "utf8");
        if ($db == false) {
            err(mysqli_connect_error());
        }
        return $db;
    } catch (\Throwable $th) {
        err($th->getMessage());
    } catch (\Error $e) {
        err($e->getMessage());
    } catch (\Exception $e) {
        err($e->getMessage());
    }
}


class CRUD
{
    private $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function select(string $query, array $params = array()): array
    {
        $result = $this->process($query, $params)->get_result();
        $data = array();
        while ($row = mysqli_fetch_assoc($result))
            $data[] = $row;
        return $data;
    }
    /**
     * insert and return number of rows Affected
     */
    public function insert(string $query, array $params = array()): int
    {
        return $this->process($query, $params)->affected_rows;
    }

    /**
     * insert and return 'insert id'
     */
    public function insertAndGetAutoId(string $query, array $params = array()): int
    {
        return $this->process($query, $params)->insert_id;
    }
    /**
     * returns mysqli_stmt
     */
    public function query(string $query, array $params = array()): mysqli_stmt
    {
        return $this->process($query, $params);
    }

    public function update(string $query, array $params = array()): int
    {
        return $this->process($query, $params)->affected_rows;
    }

    private function _getBinders(array $params = array()): array
    {
        $bind = '';
        foreach ($params as $param) {
            $type = gettype($param);
            if ($type == 'double') {
                $bind .= 'd';
            } else if ($type == 'integer') {
                $bind .= 'd';
            } else {
                $bind .= 's';
            }
        }
        $a_params[] = $bind;
        for ($i = 0; $i < count($params); $i++) {
            $a_params[] = $params[$i];
        }
        return $a_params;
    }

    private function process(string $query, array $params = array()): \mysqli_stmt
    {
        try {
            $stmt = $this->db->prepare($query);
            if (count($params) > 0) {
                $temp = $this->_getBinders($params);
                $binder = array();
                for ($i = 0; $i < count($temp); $i++) {
                    $binder[] = &$temp[$i];
                }
                // var_dump( $binder);
                call_user_func_array(array($stmt, 'bind_param'), $binder);
            }
            $stmt->execute();
            // $stmt->close();
            return $stmt;
        } catch (\Throwable $th) {
            err($th->getMessage() . ' ~ ' . $query);
        } catch (\Error $e) {
            err($e->getMessage() . ' ~ ' . $query);
        } catch (\Exception $e) {
            err($e->getMessage() . ' ~ ' . $query);
        }
    }

    public function __destruct()
    {
        if ($this->db != null)
            $this->db->close();
    }
}