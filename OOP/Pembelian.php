<?php
class Pembelian {
    private string $idOrder;
    private string $tipeTiket;
    private float $totalHarga;
    private string $status;
    private string $waktuDibuat;

    public function __construct(string $idOrder, string $tipeTiket, float $totalHarga, string $status, string $waktuDibuat) {
        $this->idOrder = $idOrder;
        $this->tipeTiket = $tipeTiket;
        $this->totalHarga = $totalHarga;
        $this->status = $status;
        $this->waktuDibuat = $waktuDibuat;
    }

    public function getIdOrder(): string {
        return $this->idOrder;
    }

    public function getTipeTiket(): string {
        return $this->tipeTiket;
    }

    public function getTotalHarga(): float {
        return $this->totalHarga;
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getWaktuDibuat(): string {
        return $this->waktuDibuat;
    }
}
