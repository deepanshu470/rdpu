<?php
class ProductModel {
    private $file;

    public function __construct() {
        $this->file = DATA_DIR . 'products.json';
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

    public function getById($id) {
        $products = $this->readAll();
        foreach ($products as $p) {
            if ($p['id'] == $id) return $p;
        }
        return null;
    }

    public function getByCategory($category) {
        $products = $this->readAll();
        if ($category === 'all') return $products;
        return array_values(array_filter($products, fn($p) => $p['category'] === $category));
    }

    public function getFeatured() {
        $products = $this->readAll();
        return array_values(array_filter($products, fn($p) => isset($p['featured']) && $p['featured']));
    }

    public function search($query) {
        $products = $this->readAll();
        $q = strtolower($query);
        return array_values(array_filter($products, fn($p) =>
            strpos(strtolower($p['name']), $q) !== false ||
            strpos(strtolower($p['description']), $q) !== false ||
            strpos(strtolower($p['category']), $q) !== false
        ));
    }

    public function create($data) {
        $products = $this->readAll();
        $existingIds = array_column($products, 'id');
        $data['id'] = count($existingIds) > 0 ? max($existingIds) + 1 : 1;
        while (in_array($data['id'], $existingIds)) {
            $data['id']++;
        }
        $products[] = $data;
        $this->writeAll($products);
        return $data;
    }

    public function update($id, $data) {
        $products = $this->readAll();
        foreach ($products as &$p) {
            if ($p['id'] == $id) {
                $data['id'] = $id;
                $p = $data;
                break;
            }
        }
        unset($p);
        $this->writeAll($products);
    }

    public function delete($id) {
        $products = $this->readAll();
        $products = array_values(array_filter($products, fn($p) => $p['id'] != $id));
        $this->writeAll($products);
    }
}
