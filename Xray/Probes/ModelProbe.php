<?php

namespace Libra\Zendo\Xray\Probes;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use PDO;

/**
 * Collects data about SQL statements executed with PDO
 */
class ModelProbe extends Probe
{
    protected array $queries = [];

    protected array $middleware = [];
    protected array $reflection = [];

    public function __construct()
    {
        DB::listen(function (QueryExecuted $query) {
            $this->addQuery($query);
        });
    }

    /**
     * @param QueryExecuted $query
     */
    public function addQuery(QueryExecuted $query)
    {
        $sql = $query->sql;
        $bindings = $query->bindings;
        $time = $query->time;
        $connection = $query->connection;
        $config = $connection->getConfig();

        $explainResults = [];
        if ($this->config['explain']) {
            $pdo = $connection->getPdo();
            $bindings = $connection->prepareBindings($bindings);

            $statement = $pdo->prepare('EXPLAIN ' . $sql);
            $statement->execute($bindings);
            $explainResults = $statement->fetchAll(PDO::FETCH_CLASS);
        }

        $this->queries[] = [
            'sql' => $sql,
            'bindings' => $bindings,
            'time' => $time / 1000,
            'explain' => $explainResults,
            'connection' => $query->connectionName,
            'driver' => $config['driver'],
            'database' => $config['database'],
            'schema' => $config['schema'] ?? '',
        ];
    }

    /**
     * @return array
     */
    public function collect(): array
    {
        $totalTime = 0;
        $queries = $this->queries;

        $statements = [];
        foreach ($queries as $key => $query) {
            $totalTime += $query['time'];

            $statements[$key] = [
                'sql' => $query['sql'],
                'bindings' => $query['bindings'],
                'duration' => $this->formatDuration($query['time']),
                'connection' => $query['connection'],
                'database' => $query['database'],
                'schema' => $query['schema'],
                'driver' => $query['driver'],
            ];

            //Add the results from the explain as new rows
            foreach ($query['explain'] as $explain) {
                $statements[$key]['explain'][] = [
                    'sql' => ' - EXPLAIN #' . $explain->id . ': `' . $explain->table . '` (' . $explain->select_type . ')',
                    'type' => 'explain',
                    'params' => $explain,
                    'row_count' => $explain->rows,
                    'stmt_id' => $explain->id,
                ];
            }
        }

        return [
            'duration' => $this->formatDuration($totalTime),
            'statements' => $statements,
        ];
    }
}
