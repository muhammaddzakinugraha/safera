<?php
require_once 'Pembelian.php';

class PengelolaPembelian {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function ambilPembelianPengguna(int $idPengguna): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                pembelian_tiket.order_id,
                pembelian_tiket.total_amount,
                pembelian_tiket.status,
                pembelian_tiket.created_at,
                tipe_tiket.name AS ticket_name
            FROM 
                pembelian_tiket
            JOIN 
                tipe_tiket ON pembelian_tiket.ticket_type_id = tipe_tiket.id
            WHERE 
                pembelian_tiket.user_id = :id_pengguna
            ORDER BY 
                pembelian_tiket.created_at DESC
        ");
        $stmt->execute(['id_pengguna' => $idPengguna]);
        $hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pembelian = [];
        foreach ($hasil as $baris) {
            $pembelian[] = new Pembelian(
                $baris['order_id'],
                $baris['ticket_name'],
                (float) $baris['total_amount'],
                $baris['status'],
                $baris['created_at']
            );
        }
        return $pembelian;
    }
}
