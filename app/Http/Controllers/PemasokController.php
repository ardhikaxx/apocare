<?php

namespace App\Http\Controllers;

use App\Exports\PemasokExport;
use App\Models\Pemasok;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PemasokController extends Controller
{
    public function index(Request $request)
    {
        $pemasok = Pemasok::orderBy('nama')->get();
        return view('pages.master.pemasok.index', compact('pemasok'));
    }

    public function create()
    {
        return view('pages.master.pemasok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|unique:pemasok,email',
            'telepon' => 'nullable|string',
        ]);

        $kode = 'SUP-' . str_pad((Pemasok::withTrashed()->max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT);

        Pemasok::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'kontak_person' => $request->kontak_person,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'npwp' => $request->npwp,
            'termin_pembayaran' => $request->termin_pembayaran ?? 0,
            'limit_kredit' => $request->limit_kredit ?? 0,
            'status_aktif' => $request->status_aktif ?? true,
            'catatan' => $request->catatan,
            'dibuat_oleh' => auth()->id(),
        ]);

        return redirect()->route('master.pemasok.index')->with('success', 'Pemasok berhasil ditambahkan');
    }

    public function edit(Pemasok $pemasok)
    {
        return view('pages.master.pemasok.edit', compact('pemasok'));
    }

    public function update(Request $request, Pemasok $pemasok)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|unique:pemasok,email,' . $pemasok->id,
        ]);

        $pemasok->update([
            'nama' => $request->nama,
            'kontak_person' => $request->kontak_person,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'provinsi' => $request->provinsi,
            'kode_pos' => $request->kode_pos,
            'npwp' => $request->npwp,
            'termin_pembayaran' => $request->termin_pembayaran ?? 0,
            'limit_kredit' => $request->limit_kredit ?? 0,
            'status_aktif' => $request->status_aktif ?? true,
            'catatan' => $request->catatan,
            'diubah_oleh' => auth()->id(),
        ]);

        return redirect()->route('master.pemasok.index')->with('success', 'Pemasok berhasil diperbarui');
    }

    public function destroy(Pemasok $pemasok)
    {
        $pemasok->update(['diubah_oleh' => auth()->id()]);
        $pemasok->delete();
        return redirect()->route('master.pemasok.index')->with('success', 'Pemasok berhasil dihapus');
    }

    public function exportExcel()
    {
        return Excel::download(new PemasokExport(), 'pemasok.xlsx');
    }

    public function exportCsv()
    {
        return Excel::download(new PemasokExport(), 'pemasok.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function exportPdf()
    {
        $pemasok = Pemasok::orderBy('nama')->get();
        $pdf = Pdf::loadView('print.pemasok', compact('pemasok'));
        return $pdf->download('pemasok.pdf');
    }
}
