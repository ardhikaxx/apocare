<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HakAksesSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $permissions = [
            // Dashboard
            ['kode' => 'dashboard.view', 'nama' => 'Lihat Dashboard', 'modul' => 'Dashboard'],

            // Master data
            ['kode' => 'master.pemasok.view', 'nama' => 'Lihat Pemasok', 'modul' => 'Pemasok'],
            ['kode' => 'master.pemasok.create', 'nama' => 'Tambah Pemasok', 'modul' => 'Pemasok'],
            ['kode' => 'master.pemasok.update', 'nama' => 'Ubah Pemasok', 'modul' => 'Pemasok'],
            ['kode' => 'master.pemasok.delete', 'nama' => 'Hapus Pemasok', 'modul' => 'Pemasok'],
            ['kode' => 'master.pemasok.export', 'nama' => 'Export Pemasok', 'modul' => 'Pemasok'],

            ['kode' => 'master.kategori.view', 'nama' => 'Lihat Kategori', 'modul' => 'Kategori'],
            ['kode' => 'master.kategori.create', 'nama' => 'Tambah Kategori', 'modul' => 'Kategori'],
            ['kode' => 'master.kategori.update', 'nama' => 'Ubah Kategori', 'modul' => 'Kategori'],
            ['kode' => 'master.kategori.delete', 'nama' => 'Hapus Kategori', 'modul' => 'Kategori'],
            ['kode' => 'master.kategori.export', 'nama' => 'Export Kategori', 'modul' => 'Kategori'],

            ['kode' => 'master.satuan.view', 'nama' => 'Lihat Satuan', 'modul' => 'Satuan'],
            ['kode' => 'master.satuan.create', 'nama' => 'Tambah Satuan', 'modul' => 'Satuan'],
            ['kode' => 'master.satuan.update', 'nama' => 'Ubah Satuan', 'modul' => 'Satuan'],
            ['kode' => 'master.satuan.delete', 'nama' => 'Hapus Satuan', 'modul' => 'Satuan'],
            ['kode' => 'master.satuan.export', 'nama' => 'Export Satuan', 'modul' => 'Satuan'],

            ['kode' => 'master.produk.view', 'nama' => 'Lihat Produk', 'modul' => 'Produk'],
            ['kode' => 'master.produk.create', 'nama' => 'Tambah Produk', 'modul' => 'Produk'],
            ['kode' => 'master.produk.update', 'nama' => 'Ubah Produk', 'modul' => 'Produk'],
            ['kode' => 'master.produk.delete', 'nama' => 'Hapus Produk', 'modul' => 'Produk'],
            ['kode' => 'master.produk.export', 'nama' => 'Export Produk', 'modul' => 'Produk'],

            // Pelanggan & Dokter & Karyawan
            ['kode' => 'pelanggan.view', 'nama' => 'Lihat Pelanggan', 'modul' => 'Pelanggan'],
            ['kode' => 'pelanggan.create', 'nama' => 'Tambah Pelanggan', 'modul' => 'Pelanggan'],
            ['kode' => 'pelanggan.update', 'nama' => 'Ubah Pelanggan', 'modul' => 'Pelanggan'],
            ['kode' => 'pelanggan.delete', 'nama' => 'Hapus Pelanggan', 'modul' => 'Pelanggan'],
            ['kode' => 'pelanggan.export', 'nama' => 'Export Pelanggan', 'modul' => 'Pelanggan'],

            ['kode' => 'dokter.view', 'nama' => 'Lihat Dokter', 'modul' => 'Dokter'],
            ['kode' => 'dokter.create', 'nama' => 'Tambah Dokter', 'modul' => 'Dokter'],
            ['kode' => 'dokter.update', 'nama' => 'Ubah Dokter', 'modul' => 'Dokter'],
            ['kode' => 'dokter.delete', 'nama' => 'Hapus Dokter', 'modul' => 'Dokter'],

            ['kode' => 'karyawan.view', 'nama' => 'Lihat Karyawan', 'modul' => 'Karyawan'],
            ['kode' => 'karyawan.create', 'nama' => 'Tambah Karyawan', 'modul' => 'Karyawan'],
            ['kode' => 'karyawan.update', 'nama' => 'Ubah Karyawan', 'modul' => 'Karyawan'],
            ['kode' => 'karyawan.delete', 'nama' => 'Hapus Karyawan', 'modul' => 'Karyawan'],

            // Persediaan
            ['kode' => 'persediaan.stok.view', 'nama' => 'Lihat Stok', 'modul' => 'Persediaan'],
            ['kode' => 'persediaan.penyesuaian.view', 'nama' => 'Lihat Penyesuaian', 'modul' => 'Persediaan'],
            ['kode' => 'persediaan.penyesuaian.create', 'nama' => 'Tambah Penyesuaian', 'modul' => 'Persediaan'],
            ['kode' => 'persediaan.penyesuaian.delete', 'nama' => 'Hapus Penyesuaian', 'modul' => 'Persediaan'],
            ['kode' => 'persediaan.opname.view', 'nama' => 'Lihat Opname', 'modul' => 'Persediaan'],
            ['kode' => 'persediaan.opname.create', 'nama' => 'Tambah Opname', 'modul' => 'Persediaan'],
            ['kode' => 'persediaan.opname.delete', 'nama' => 'Hapus Opname', 'modul' => 'Persediaan'],

            // Transaksi
            ['kode' => 'transaksi.penjualan.view', 'nama' => 'Lihat Penjualan', 'modul' => 'Penjualan'],
            ['kode' => 'transaksi.penjualan.create', 'nama' => 'Tambah Penjualan', 'modul' => 'Penjualan'],
            ['kode' => 'transaksi.penjualan.delete', 'nama' => 'Hapus Penjualan', 'modul' => 'Penjualan'],

            ['kode' => 'transaksi.pembelian.view', 'nama' => 'Lihat Pembelian', 'modul' => 'Pembelian'],
            ['kode' => 'transaksi.pembelian.create', 'nama' => 'Tambah Pembelian', 'modul' => 'Pembelian'],
            ['kode' => 'transaksi.pembelian.delete', 'nama' => 'Hapus Pembelian', 'modul' => 'Pembelian'],

            ['kode' => 'transaksi.retur.view', 'nama' => 'Lihat Retur', 'modul' => 'Retur'],
            ['kode' => 'transaksi.retur.create', 'nama' => 'Tambah Retur', 'modul' => 'Retur'],
            ['kode' => 'transaksi.retur.delete', 'nama' => 'Hapus Retur', 'modul' => 'Retur'],

            // Resep
            ['kode' => 'resep.view', 'nama' => 'Lihat Resep', 'modul' => 'Resep'],
            ['kode' => 'resep.create', 'nama' => 'Tambah Resep', 'modul' => 'Resep'],
            ['kode' => 'resep.delete', 'nama' => 'Hapus Resep', 'modul' => 'Resep'],

            // Laporan
            ['kode' => 'laporan.penjualan.view', 'nama' => 'Lihat Laporan Penjualan', 'modul' => 'Laporan'],
            ['kode' => 'laporan.penjualan.export', 'nama' => 'Export Laporan Penjualan', 'modul' => 'Laporan'],
            ['kode' => 'laporan.pembelian.view', 'nama' => 'Lihat Laporan Pembelian', 'modul' => 'Laporan'],
            ['kode' => 'laporan.pembelian.export', 'nama' => 'Export Laporan Pembelian', 'modul' => 'Laporan'],
            ['kode' => 'laporan.persediaan.view', 'nama' => 'Lihat Laporan Persediaan', 'modul' => 'Laporan'],
            ['kode' => 'laporan.persediaan.export', 'nama' => 'Export Laporan Persediaan', 'modul' => 'Laporan'],
            ['kode' => 'laporan.keuangan.view', 'nama' => 'Lihat Laporan Keuangan', 'modul' => 'Laporan'],
            ['kode' => 'laporan.keuangan.export', 'nama' => 'Export Laporan Keuangan', 'modul' => 'Laporan'],

            // Pengguna & Akses
            ['kode' => 'pengguna.view', 'nama' => 'Lihat Pengguna', 'modul' => 'Pengguna'],
            ['kode' => 'pengguna.create', 'nama' => 'Tambah Pengguna', 'modul' => 'Pengguna'],
            ['kode' => 'pengguna.update', 'nama' => 'Ubah Pengguna', 'modul' => 'Pengguna'],
            ['kode' => 'pengguna.delete', 'nama' => 'Hapus Pengguna', 'modul' => 'Pengguna'],
            ['kode' => 'pengguna.peran', 'nama' => 'Kelola Peran', 'modul' => 'Pengguna'],
            ['kode' => 'pengguna.hakakses', 'nama' => 'Kelola Hak Akses', 'modul' => 'Pengguna'],
        ];

        foreach ($permissions as $permission) {
            DB::table('hak_akses')->updateOrInsert(
                ['kode' => $permission['kode']],
                [
                    'nama' => $permission['nama'],
                    'modul' => $permission['modul'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
