<?php

namespace App\Http\Controllers;

use App\Models\asosiasimasjaki;
use App\Models\Asosiasimasjaki as ModelsAsosiasimasjaki;
use App\Models\bujkkonsultan;
use App\Models\bujkkonsultansub;
use App\Models\bujkkontraktor;
use App\Models\bujkkontraktorsub;
// use App\Models\asosiasimasjaki;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class BujkkontraktorController extends Controller
{
    //

    public function index()
    {
        $user = Auth::user();

        return view('frontend.03_masjaki_jakon.01_bujkkontraktor.index', [
            'title' => 'BUJK Konstruksi & Konsultasi Konstruksi',
            'user' => $user, // Mengirimkan data paginasi ke view
        ]);
    }

    public function asosiasimasjaki(Request $request)
    {

        $databujkkontraktor = bujkkontraktor::select('asosiasimasjaki_id', DB::raw('count(*) as jumlah'))
        ->groupBy('asosiasimasjaki_id')
        ->with('namaasosiasi') // Pastikan ada relasi ke tabel asosiasi
        ->get();

        $databujkkonsultan = bujkkonsultan::select('asosiasimasjaki_id', DB::raw('count(*) as jumlah'))
        ->groupBy('asosiasimasjaki_id')
        ->with('namaasosiasi') // Pastikan ada relasi ke tabel asosiasi
        ->get();

        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');

        $user = Auth::user();
        // $data = asosiasimasjaki::paginate(15);

        $databujkkontraktorpaginate = bujkkontraktor::paginate(15);
        $databujkkonsultanpaginate = bujkkonsultan::paginate(15);

        $query = bujkkonsultan::query();
        $query = bujkkontraktor::query();

        if ($search) {
            $query->where('namaasosiasi', 'LIKE', "%{$search}%");
                //   ->orWhere('alamat', 'LIKE', "%{$search}%")
                //   ->orWhere('email', 'LIKE', "%{$search}%")
                //   ->orWhere('nib', 'LIKE', "%{$search}%");
        }

        $data = $query->paginate($perPage);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('frontend.03_masjaki_jakon.05_asosiasimasjaki.partials.table', compact('data'))->render()
            ]);
        }

        return view('frontend.03_masjaki_jakon.05_asosiasimasjaki.index', [
            'title' => 'Asosiasi Konstruksi dan Konsultasi Konstruksi',
            'user' => $user, // Mengirimkan data paginasi ke view
            'data' => $data, // Mengirimkan data paginasi ke view
            'perPage' => $perPage,
            'search' => $search,
            'databujkkontraktor' => $databujkkontraktor,
            'databujkkontraktorpaginate' => $databujkkontraktorpaginate,
            'databujkkonsultanpaginate' => $databujkkonsultanpaginate,
            'databujkkonsultan' => $databujkkonsultan,
        ]);
    }

    // MENU BACKEND JASA KONSTRUKSI
    // ------------------------------------------------------------------------------------------------
            public function beasosiasi(Request $request)
        {
            $perPage = $request->input('perPage', 15);
            $search = $request->input('search');

            $query = asosiasimasjaki::query();

            if ($search) {
                $query->where('namaasosiasi', 'LIKE', "%{$search}%");

            }

            $data = $query->paginate($perPage);

            if ($request->ajax()) {
                return response()->json([
                    'html' => view('backend.04_datajakon.03_asosiasimasjaki.partials.table', compact('data'))->render()
                ]);
            }

            return view('backend.04_datajakon.03_asosiasimasjaki.index', [
                'title' => 'Asosiasi Mas Jaki Blora',
                'data' => $data,
                'perPage' => $perPage,
                'search' => $search
            ]);
        }

        // BACKEND ASOSIASI SHOW

        public function beasosiasishow($namaasosiasi)
        {
            $datasosiasi = asosiasimasjaki::where('namaasosiasi', $namaasosiasi)->first();
        // Ambil data user saat ini
            $user = Auth::user();

        return view('backend.04_datajakon.01_bujkkonstruksi.show', [
            'title' => 'Data Asosiasi Mas Jaki',
            'data' => $datasosiasi,
        ]);
        }

        public function beasosiasidelete($namaasosiasi)
            {
            // Cari item berdasarkan judul
            $entry = asosiasimasjaki::where('namaasosiasi', $namaasosiasi)->first();

            if ($entry) {
            // Jika ada file header yang terdaftar, hapus dari storage
            // if (Storage::disk('public')->exists($entry->header)) {
                //     Storage::disk('public')->delete($entry->header);
            // }

            // Hapus entri dari database
            $entry->delete();

            // Redirect atau memberi respons sesuai kebutuhan
            return redirect('/beasosiasi')->with('delete', 'Data Berhasil Di Hapus !');

            }

            return redirect()->back()->with('error', 'Item not found');
            }



// HALAMAN FRONTEND MENU BUJK KONTRAKTOR
    public function bujkkontraktor(Request $request)
{
    $perPage = $request->input('perPage', 15);
    $search = $request->input('search');

    $query = bujkkontraktor::query();

    if ($search) {
        $query->where('namalengkap', 'LIKE', "%{$search}%")
              ->orWhere('alamat', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('no_telepon', 'LIKE', "%{$search}%");
    }

    $data = $query->paginate($perPage);

    if ($request->ajax()) {
        return response()->json([
            'html' => view('frontend.03_masjaki_jakon.01_bujkkontraktor.partials.table', compact('data'))->render()
        ]);
    }


    return view('frontend.03_masjaki_jakon.01_bujkkontraktor.bujkkontraktor', [
        'title' => 'BUJK Konstruksi',
        'data' => $data,
        'perPage' => $perPage,
        'search' => $search
    ]);
}

    public function bujkkontraktorshow($namalengkap)
    {
        $databujkkontraktor = bujkkontraktor::where('namalengkap', $namalengkap)->first();

        if (!$databujkkontraktor) {
            // Tangani jika kegiatan tidak ditemukan
            return redirect()->back()->with('error', 'Kegiatan tidak ditemukan.');
        }

        // Menggunakan paginate() untuk pagination
        $subdata = bujkkontraktorsub::where('bujkkontraktor_id', $databujkkontraktor->id)->paginate(50);

          // Menghitung nomor urut mulai
            $start = ($subdata->currentPage() - 1) * $subdata->perPage() + 1;


    // Ambil data user saat ini
    $user = Auth::user();

    return view('frontend.03_masjaki_jakon.01_bujkkontraktor.bujkkontraktorshow', [
        'title' => 'Data Bujk Konstruksi',
        'data' => $databujkkontraktor,
        'subData' => $subdata,  // Jika Anda ingin mengirimkan data sub kontraktor juga
        'user' => $user,
        'start' => $start,
    ]);
}


public function asosiasikonstruksishow($namaasosiasi)
{
    // Cari asosiasi berdasarkan namaasosiasi
    $asosiasi = asosiasimasjaki::where('namaasosiasi', $namaasosiasi)->first();

    // Jika asosiasi tidak ditemukan, tampilkan 404
    if (!$asosiasi) {
        return abort(404, 'Asosiasi tidak ditemukan');
    }

    $user = Auth::user();
        // Ambil semua data dari tabel bujkkontraktor berdasarkan asosiasi_id
        $databujkkontraktor = bujkkontraktor::where('asosiasimasjaki_id', $asosiasi->id)->get(['id', 'namalengkap', 'no_telepon']);
        // $databujkkontraktorpaginate = bu::where('asosiasimasjaki_id', $asosiasi->id)->paginate(10);


        // Return ke view dengan format yang diminta
        return view('frontend.03_masjaki_jakon.05_asosiasimasjaki.showasosiasikontraktor', [
            'title' => 'Asosiasi Konstruksi dan Konsultasi Konstruksi',
            'user' => $user,
            'data' => $databujkkontraktor,
       ]);
    }


public function asosiasikonsultanshow($namaasosiasi)
{
    // Cari asosiasi berdasarkan namaasosiasi
    $asosiasi = asosiasimasjaki::where('namaasosiasi', $namaasosiasi)->first();

    // Jika asosiasi tidak ditemukan, tampilkan 404
    if (!$asosiasi) {
        return abort(404, 'Asosiasi tidak ditemukan');
    }

    $user = Auth::user();
        // Ambil semua data dari tabel bujkkontraktor berdasarkan asosiasi_id
        $databujkkonsultan = bujkkonsultan::where('asosiasimasjaki_id', $asosiasi->id)->get(['id', 'namalengkap', 'no_telepon']);
        // $databujkkontraktorpaginate = bu::where('asosiasimasjaki_id', $asosiasi->id)->paginate(10);


        // Return ke view dengan format yang diminta
        return view('frontend.03_masjaki_jakon.05_asosiasimasjaki.showasosiasikonsultan', [
            'title' => 'Asosiasi Konstruksi dan Konsultasi Konstruksi',
            'user' => $user,
            'data' => $databujkkonsultan,
       ]);
    }

// ------------------------------------------------------------------------------------------
// MENU BACKEND BUJK KONSTRUKSI DAN KONSULTASI

public function bebujkjakon()
{

    $user = Auth::user();

    return view('backend.04_datajakon.index', [
        'title' => 'Data BUJK Konstruksi dan Konsultasi Konstruksi ',
        // 'data' => $data, // Mengirimkan data paginasi ke view
        'user' => $user, // Mengirimkan data paginasi ke view
    ]);
}



// MENU 1 BUJK KONSTRUKSI

// public function bebujkkonstruksi()
// {
//     $data = bujkkontraktor::paginate(15); // Menggunakan paginate() untuk pagination
//     $user = Auth::user();

//     return view('backend.04_datajakon.01_bujkkonstruksi.index', [
//         'title' => 'Data BUJK Konstruksi',
//         'data' => $data, // Mengirimkan data paginasi ke view
//         'user' => $user, // Mengirimkan data paginasi ke view

//     ]);
// }

public function bebujkkonstruksi(Request $request)
{
    $perPage = $request->input('perPage', 15);
    $search = $request->input('search');

    $query = bujkkontraktor::query();

    if ($search) {
        $query->where('namalengkap', 'LIKE', "%{$search}%")
              ->orWhere('alamat', 'LIKE', "%{$search}%")
              ->orWhere('no_telepon', 'LIKE', "%{$search}%")
              ->orWhere('email', 'LIKE', "%{$search}%")
              ->orWhere('nomorindukberusaha', 'LIKE', "%{$search}%")
              ->orWhere('pju', 'LIKE', "%{$search}%")
              ->orWhere('no_akte', 'LIKE', "%{$search}%")
              ->orWhere('tanggal', 'LIKE', "%{$search}%")
              ->orWhere('nama_notaris', 'LIKE', "%{$search}%")
              ->orWhere('no_pengesahan', 'LIKE', "%{$search}%")
              ->orWhereHas('bujkkontraktorsub', function ($q) use ($search) {
                $q->where('nama_pengurus', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('asosiasimasjaki', function ($q) use ($search) {
                $q->where('namaasosiasi', 'LIKE', "%{$search}%");
            });
    }

    $data = $query->paginate($perPage);

    if ($request->ajax()) {
        return response()->json([
            'html' => view('backend.04_datajakon.01_bujkkonstruksi.partials.table', compact('data'))->render()
        ]);
    }

    return view('backend.04_datajakon.01_bujkkonstruksi.index', [
        'title' => 'BUJK Konstruksi',
        'data' => $data,
        'perPage' => $perPage,
        'search' => $search
    ]);
}



// MENU CREATE BUJK KONTRAKTOR ===============================================================================
public function bebujkkonstruksicreate()
{
    // Cari data undang-undang berdasarkan nilai 'judul'
    // $jakonjabatanfungsional = profiljakonpersonil::where('id', $id)->firstOrFail();
    $user = Auth::user();
    $asosiasimasjaki = asosiasimasjaki::all();  // Ambil semua pengguna

    // Tampilkan form update dengan data yang ditemukan
    return view('backend.04_datajakon.01_bujkkonstruksi.create', [
        // 'data' => $jakonjabatanfungsional,
        'user' => $user,
        'asosiasimasjaki' => $asosiasimasjaki,
        'title' => 'Create BUJK Kontruksi'
    ]);
}

// -------------------- CREATE MENU JABATAN FUNGSIONAL   ----------------------
public function bebujkkonstruksicreatenew(Request $request)
{
    // Ambil data asosiasi dari database untuk digunakan di form
    $asosiasimasjaki = asosiasimasjaki::all();

    // Validasi input form
    $validatedData = $request->validate([
        'asosiasimasjaki_id' => 'required|integer|exists:asosiasimasjaki,id',
        'namalengkap' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_telepon' => 'required|string|max:255',
        'email' => 'required|email',
        'nomorindukberusaha' => 'required|string|max:255',
        'pju' => 'required|string|max:255',
        'no_akte' => 'required|string|max:255',
        'tanggal' => 'required|date',
        'nama_notaris' => 'required|string|max:255',
        'no_pengesahan' => 'required|string|max:255',
    ], [
        'asosiasimasjaki_id.required' => 'Asosiasi harus dipilih!',
        'namalengkap.required' => 'Nama Lengkap wajib diisi!',
        'alamat.required' => 'Alamat wajib diisi!',
        'no_telepon.required' => 'Nomor Telepon wajib diisi!',
        'email.required' => 'Email wajib diisi!',
        'nomorindukberusaha.required' => 'Nomor Induk Berusaha wajib diisi!',
        'pju.required' => 'PJU wajib diisi!',
        'no_akte.required' => 'No Akte wajib diisi!',
        'tanggal.required' => 'Tanggal wajib diisi!',
        'nama_notaris.required' => 'Nama Notaris wajib diisi!',
        'no_pengesahan.required' => 'No Pengesahan wajib diisi!',
    ]);

    // Mengambil ID pertama dari BujkkontraktorSub
    $bujkkontraktorsub_id = bujkkonsultansub::first()->id;

    // Menyimpan data ke dalam tabel bujkkontraktor
    Bujkkontraktor::create([
        'bujkkontraktorsub_id' => $bujkkontraktorsub_id, // ID dari kontraktor sub
        'asosiasimasjaki_id' => $asosiasimasjaki, // Asosiasi yang dipilih
        'namalengkap' => $validatedData['namalengkap'],
        'alamat' => $validatedData['alamat'],
        'no_telepon' => $validatedData['no_telepon'],
        'email' => $validatedData['email'],
        'nomorindukberusaha' => $validatedData['nomorindukberusaha'],
        'pju' => $validatedData['pju'],
        'no_akte' => $validatedData['no_akte'],
        'tanggal' => $validatedData['tanggal'],
        'nama_notaris' => $validatedData['nama_notaris'],
        'no_pengesahan' => $validatedData['no_pengesahan'],
    ]);

    // Flash session untuk menampilkan pesan sukses
    session()->flash('create', 'Data Berhasil Dibuat!');

    // Redirect ke halaman yang sesuai
    return redirect('/bebujkkonstruksi');
}

// BUJKKONTRAKTOR SHOW

public function bebujkkonstruksishow($namalengkap)
{
    $databujkkontraktor = bujkkontraktor::where('namalengkap', $namalengkap)->first();
// Ambil data user saat ini
    $user = Auth::user();

return view('backend.04_datajakon.01_bujkkonstruksi.show', [
    'title' => 'Data Bujk Konstruksi',
    'data' => $databujkkontraktor,
]);
}


// DATA SHOW SUB KLASIFIKASI LAYANAN
public function bebujkkonstruksiklasifikasi($namalengkap)
{
    $databujk = bujkkontraktor::where('namalengkap', $namalengkap)->first();

    if (!$databujk) {
        return abort(404, 'Data sub-klasifikasi tidak ditemukan');
    }

        // Menggunakan paginate() untuk pagination
        $datasublayanan = bujkkontraktorsub::where('bujkkontraktor_id', $databujk->id)->paginate(50);

    return view('backend.04_datajakon.01_bujkkonstruksi.showklasifikasi', [
        'title' => 'Data Klasifikasi Layanan',
        'subdata' => $datasublayanan,
        'data' => $databujk,
        'user' => Auth::user()
    ]);
}

// BUJK KONTRAKTOR UPDATE DAN CREATE UPDATE ------------------------------------------------------------------------------------

public function bebujkkonstruksiupdate($id)
{
    // Cari data undang-undang berdasarkan nilai 'judul'
    $jakonkonstruksi = bujkkontraktor::where('id', $id)->firstOrFail();
    $asosiasimasjakiList = asosiasimasjaki::all(); // Ambil semua asosiasi

    $user = Auth::user();

    // Tampilkan form update dengan data yang ditemukan
    return view('backend.04_datajakon.01_bujkkonstruksi.update', [
        'data' => $jakonkonstruksi,
        'user' => $user,
        'asosiasimasjakiList' => $asosiasimasjakiList,
        'title' => 'Update BUJK Kontraktor'
    ]);
}


// MENU EROR BELUM DI PERBAIKI -----------------------------------------------
// -------------------- UPDATE DATA MENU BUJK KONTRAKTOR  ----------------------
public function bebujkkonstruksicreateupdate(Request $request, $id)
{
    // Validasi input dengan pesan kustom
    $validatedData = $request->validate([
        // 'bujkkontraktorsub_id' => 'required|string|max:255', // Validasi untuk ID kontraktor
        'asosiasimasjaki_id' => 'required|exists:asosiasimasjaki,id', // Validasi untuk ID asosiasi
        'namalengkap' => 'required|string|max:255', // Validasi untuk Nama Lengkap
        'alamat' => 'required|string', // Validasi untuk Alamat
        'no_telepon' => 'required|string|max:255', // Validasi untuk No Telepon
        'email' => 'required|email', // Validasi untuk Email
        'nomorindukberusaha' => 'required|string|max:255', // Validasi untuk Nomor Induk Berusaha
        'pju' => 'required|string|max:255', // Validasi untuk PJU
        'no_akte' => 'required|string|max:255', // Validasi untuk No Akte
        'tanggal' => 'required|date', // Validasi untuk Tanggal
        'nama_notaris' => 'required|string|max:255', // Validasi untuk Nama Notaris
        'no_pengesahan' => 'required|string|max:255', // Validasi untuk No Pengesahan
    ], [

        'asosiasimasjaki_id.required' => 'Asosiasi harus dipilih!',
        'namalengkap.required' => 'Nama Lengkap wajib diisi!',
        'alamat.required' => 'Alamat wajib diisi!',
        'no_telepon.required' => 'Nomor Telepon wajib diisi!',
        'email.required' => 'Email wajib diisi!',
        'email.email' => 'Format email tidak valid!',
        'nomorindukberusaha.required' => 'Nomor Induk Berusaha wajib diisi!',
        'pju.required' => 'PJU wajib diisi!',
        'no_akte.required' => 'No Akte wajib diisi!',
        'tanggal.required' => 'Tanggal wajib diisi!',
        'tanggal.date' => 'Format Tanggal tidak valid!',
        'nama_notaris.required' => 'Nama Notaris wajib diisi!',
        'no_pengesahan.required' => 'No Pengesahan wajib diisi!',
    ]);

    // Cari data strukturdinas berdasarkan nilai 'judul'
    $jakonkontraktor = bujkkontraktor::where('id', $id)->firstOrFail();

    // Gunakan $validatedData untuk update, agar lebih jelas dan rapi

    // Proses update setelah data tervalidasi
    $jakonkontraktor->update([
        'asosiasimasjaki_id' => $validatedData['asosiasimasjaki_id'] ?? $jakonkontraktor->asosiasimasjaki_id, // Jika asosiasimasjaki_id tidak ada, gunakan data sebelumnya
        'namalengkap' => $validatedData['namalengkap'] ?? $jakonkontraktor->namalengkap, // Jika namalengkap tidak ada, gunakan data sebelumnya
        'alamat' => $validatedData['alamat'] ?? $jakonkontraktor->alamat, // Jika alamat tidak ada, gunakan data sebelumnya
        'no_telepon' => $validatedData['no_telepon'] ?? $jakonkontraktor->no_telepon, // Jika no_telepon tidak ada, gunakan data sebelumnya
        'email' => $validatedData['email'] ?? $jakonkontraktor->email, // Jika email tidak ada, gunakan data sebelumnya
        'nomorindukberusaha' => $validatedData['nomorindukberusaha'] ?? $jakonkontraktor->nomorindukberusaha, // Jika nomorindukberusaha tidak ada, gunakan data sebelumnya
        'pju' => $validatedData['pju'] ?? $jakonkontraktor->pju, // Jika pju tidak ada, gunakan data sebelumnya
        'no_akte' => $validatedData['no_akte'] ?? $jakonkontraktor->no_akte, // Jika no_akte tidak ada, gunakan data sebelumnya
        'tanggal' => $validatedData['tanggal'] ?? $jakonkontraktor->tanggal, // Jika tanggal tidak ada, gunakan data sebelumnya
        'nama_notaris' => $validatedData['nama_notaris'] ?? $jakonkontraktor->nama_notaris, // Jika nama_notaris tidak ada, gunakan data sebelumnya
        'no_pengesahan' => $validatedData['no_pengesahan'] ?? $jakonkontraktor->no_pengesahan, // Jika no_pengesahan tidak ada, gunakan data sebelumnya
    ]);
    // Flash session untuk menampilkan pesan sukses
    session()->flash('update', 'Data Berhasil Diupdate!');

    // Redirect ke halaman yang sesuai
    return redirect('/bebujkkonstruksi');
}




public function bebujkkonstruksidelete($namalengkap)
{
// Cari item berdasarkan judul
$entry = bujkkontraktor::where('namalengkap', $namalengkap)->first();

if ($entry) {
// Jika ada file header yang terdaftar, hapus dari storage
// if (Storage::disk('public')->exists($entry->header)) {
    //     Storage::disk('public')->delete($entry->header);
// }

// Hapus entri dari database
$entry->delete();

// Redirect atau memberi respons sesuai kebutuhan
return redirect('/bebujkkonstruksi')->with('delete', 'Data Berhasil Di Hapus !');

}

return redirect()->back()->with('error', 'Item not found');
}


public function bebujkkonstruksiklasifikasidelete($id)
{
// Cari item berdasarkan judul
$entry = bujkkontraktorsub::where('id', $id)->first();

if ($entry) {
// Jika ada file header yang terdaftar, hapus dari storage
// if (Storage::disk('public')->exists($entry->header)) {
    //     Storage::disk('public')->delete($entry->header);
// }

// Hapus entri dari database

$parentId = $entry->bujkkontraktor_id; // Sesuaikan dengan nama kolom di database
$entry->delete();

return redirect('/bebujkkonstruksi')->with('delete', 'Data Berhasil Dihapus!');

}

return redirect()->back()->with('error', 'Item not found');
}



}


