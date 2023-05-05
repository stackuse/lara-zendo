<?php

namespace Libra\Zendo\Xray\Traces;

/**
 * Stores collected data into files
 */
class FileTrace extends Trace
{
    protected string $dirname;

    /**
     * {@inheritdoc}
     */
    public function send($data)
    {
        $this->dirname = rtrim($this->config['path'], DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if (!file_exists($this->dirname)) {
            mkdir($this->dirname, 0777, true);
        }
        $id = 'trace' . $data['time']['start'];
        file_put_contents($this->makeFilename($id), json_encode($data));
    }

    /**
     * @param string $id
     * @return string
     */
    public function makeFilename(string $id): string
    {
        return $this->dirname . basename($id) . ".json";
    }
}
