<?php
class OrderModel {
    private $file;

    public function __construct() {
        $this->file = DATA_DIR . 'orders.json';
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
        $orders = $this->readAll();
        return array_reverse($orders);
    }

    public function getById($id) {
        $orders = $this->readAll();
        foreach ($orders as $o) {
            if ($o['id'] === $id) return $o;
        }
        return null;
    }

    public function create($data) {
        $orders = $this->readAll();
        $data['id']        = 'ORD' . strtoupper(substr(md5(uniqid()), 0, 8));
        $data['status']    = 'pending';
        $data['createdAt'] = date('Y-m-d H:i:s');
        $orders[] = $data;
        $this->writeAll($orders);
        return $data;
    }

    public function getTotalRevenue() {
        $orders = $this->readAll();
        return array_sum(array_column($orders, 'total'));
    }

    public function getCount() {
        return count($this->readAll());
    }
}
