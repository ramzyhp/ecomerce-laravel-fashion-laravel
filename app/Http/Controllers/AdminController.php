<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Settings;
use App\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama admin.
     * Mengambil semua data yang diperlukan untuk dashboard.
     */
    public function index()
    {
        // 1. Data untuk Chart Pengguna (7 hari terakhir)
        $userData = User::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"))
            ->where('created_at', '>', Carbon::today()->subDays(6))
            ->groupBy('day_name')
            ->orderByRaw("FIELD(day_name, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday')")
            ->get();

        $userChartData = [['Day', 'Users']];
        foreach ($userData as $data) {
            $userChartData[] = [$data->day_name, $data->count];
        }

        // 2. Data untuk 5 Pesanan Terbaru
        $recentOrders = Order::whereDate('created_at', today())->latest()->take(5)->get();
         $salesToday = Order::whereIn('status', ['process', 'completed', 'delivered'])
                   ->whereDate('created_at', today())
                   ->sum('total_amount');

$salesThisMonth = Order::whereIn('status', ['process', 'completed', 'delivered'])
                       ->whereMonth('created_at', now()->month)
                       ->whereYear('created_at', now()->year)
                       ->sum('total_amount');

        // Mengirim semua data ke view
        return view('backend.index')
            ->with('users', json_encode($userChartData))
            ->with('recent_orders', $recentOrders)
            ->with('salesToday', $salesToday)
            ->with('salesThisMonth', $salesThisMonth);
    }

    /**
     * Menampilkan halaman profil pengguna yang sedang login.
     */
    public function profile()
    {
        $profile = auth()->user();
        return view('backend.users.profile')->with('profile', $profile);
    }

    /**
     * Memperbarui profil pengguna.
     */
    public function profileUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $status = $user->update($request->all());

        if ($status) {
            request()->session()->flash('success', 'Profil berhasil diperbarui');
        } else {
            request()->session()->flash('error', 'Terjadi kesalahan, silakan coba lagi!');
        }
        return redirect()->back();
    }

    /**
     * Menampilkan halaman pengaturan website.
     */
    public function settings()
    {
        $data = Settings::first();
        return view('backend.setting')->with('data', $data);
    }

    /**
     * Memperbarui pengaturan website.
     */
    public function settingsUpdate(Request $request)
    {
        $request->validate([
            'short_des'   => 'required|string',
            'description' => 'required|string',
            'photo'       => 'required',
            'logo'        => 'required',
            'address'     => 'required|string',
            'email'       => 'required|email',
            'phone'       => 'required|string',
        ]);

        $settings = Settings::first();
        $status = $settings->update($request->all());

        if ($status) {
            request()->session()->flash('success', 'Pengaturan berhasil diperbarui');
        } else {
            request()->session()->flash('error', 'Terjadi kesalahan, silakan coba lagi');
        }
        return redirect()->route('admin');
    }

    /**
     * Menampilkan halaman ganti password.
     */
    public function changePassword()
    {
        return view('backend.layouts.changePassword');
    }

    /**
     * Menyimpan password baru.
     */
    public function changePasswordStore(Request $request)
    {
        $request->validate([
            'current_password'     => ['required', new MatchOldPassword],
            'new_password'         => ['required', 'min:8'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->id())->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('admin')->with('success', 'Password berhasil diubah');
    }

    /**
     * Membuat symbolic link untuk folder storage.
     * Lebih aman dan sederhana.
     */
    public function storageLink()
    {
        try {
            // Perintah ini aman untuk dijalankan berkali-kali
            Artisan::call('storage:link');
            request()->session()->flash('success', 'Symbolic link berhasil dibuat.');
        } catch (\Exception $e) {
            request()->session()->flash('error', $e->getMessage());
        }
        return redirect()->back();
    }
}