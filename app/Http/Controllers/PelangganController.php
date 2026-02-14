<?php

namespace App\Http\Controllers;

use App\Exports\PelangganExport;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('kode', 'like', "%$search%")
                    ->orWhere('telepon', 'like', "%$search%");
            });
        }

        $pelanggan = $query->orderBy('nama')->get();
        return view('pages.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('pages.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'nullable|string',
        ]);

        $kode = 'PLG-' . str_pad((Pelanggan::withTrashed()->max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);

        Pelanggan::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'jenis_pelanggan' => $request->jenis_pelanggan ?? 'REGULAR',
            'jenis_identitas' => $request->jenis_identitas,
            'nomor_identitas' => $request->nomor_identitas,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'persentase_diskon' => $request->persentase_diskon ?? 0,
            'limit_kredit' => $request->limit_kredit ?? 0,
            'termin_pembayaran' => $request->termin_pembayaran ?? 0,
            'status_aktif' => $request->status_aktif ?? true,
            'catatan' => $request->catatan,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pages.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        $pelanggan->update([
            'nama' => $request->nama,
            'jenis_pelanggan' => $request->jenis_pelanggan,
            'jenis_identitas' => $request->jenis_identitas,
            'nomor_identitas' => $request->nomor_identitas,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'persentase_diskon' => $request->persentase_diskon ?? 0,
            'limit_kredit' => $request->limit_kredit ?? 0,
            'termin_pembayaran' => $request->termin_pembayaran ?? 0,
            'status_aktif' => $request->status_aktif ?? true,
            'catatan' => $request->catatan,
            'diubah_oleh' => auth()->id(),
        ]);

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->update(['diubah_oleh' => auth()->id()]);
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus');
    }

    public function exportExcel()
    {
        return Excel::download(new PelangganExport(), 'pelanggan.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new PelangganExport(), 'pelanggan.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf()
    {
        $pelanggan = Pelanggan::orderBy('nama')->get();
        $pdf = Pdf::loadView('print.pelanggan', compact('pelanggan'));
        return $pdf->download('pelanggan.pdf');
    }
}
