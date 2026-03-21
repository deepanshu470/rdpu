<?php
class OfferModel {
    private $file;

    public function __construct() {
        $this->file = DATA_DIR . 'offers.json';
        if (!file_exists($this->file)) {
            file_put_contents($this->file, '[]');
        }
    }

    private function readAll() {
        $content = file_get_contents($this->file);
        return json_decode($content, true) ?: [];
    }

    private function writeAll($data) {
        file_put_contents($this->file, json_encode(array_values($data), JSON_PRETTY_PRINT));
    }

    public function getAll() {
        return $this->readAll();
    }

    public function getActive() {
        $offers = $this->readAll();
        return array_values(array_filter($offers, fn($o) => $o['active']));
    }

    public function getById($id) {
        $offers = $this->readAll();
        foreach ($offers as $o) {
            if ($o['id'] == $id) return $o;
        }
        return null;
    }

    public function validateCode($code) {
        $offers = $this->readAll();
        foreach ($offers as $o) {
            if ($o['active'] && strtoupper($o['code']) === strtoupper($code)) return $o;
        }
        return null;
    }

    public function create($data) {
        $offers = $this->readAll();
        $data['id'] = count($offers) > 0 ? max(array_column($offers, 'id')) + 1 : 1;
        $offers[] = $data;
        $this->writeAll($offers);
        return $data;
    }

    public function update($id, $data) {
        $offers = $this->readAll();
        foreach ($offers as &$o) {
            if ($o['id'] == $id) {
                $data['id'] = $id;
                $o = $data;
                break;
            }
        }
        unset($o);
        $this->writeAll($offers);
    }

    public function delete($id) {
        $offers = $this->readAll();
        $offers = array_values(array_filter($offers, fn($o) => $o['id'] != $id));
        $this->writeAll($offers);
    }
}
