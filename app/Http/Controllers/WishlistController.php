<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    protected $product=null;
    public function __construct(Product $product){
        $this->product=$product;
    }

    public function wishlist(Request $request){
        // dd($request->all());
        if (empty($request->slug)) {
            request()->session()->flash('error','Produk tidak valid!');
            return back();
        }        
        $product = Product::where('slug', $request->slug)->first();
        // return $product;
        if (empty($product)) {
            request()->session()->flash('error','Produk tidak ditemukan!');
            return back();
        }

        // 💡 BAGIAN INI DITAMBAHKAN/DIUBAH UNTUK LOGIKA TOGGLE 💡
        // Mencari produk di wishlist user yang sedang login
        // Menghapus kondisi `where('cart_id',null)` karena item wishlist tidak selalu terkait cart
        $already_in_wishlist = Wishlist::where('user_id', auth()->user()->id)
                                       ->where('product_id', $product->id)
                                       ->first();
                                       
       if ($already_in_wishlist) {
            $already_in_wishlist->delete();
            request()->session()->flash('error','Produk berhasil dihapus dari wishlist!');
        } else {
            // Jika produk belum ada di wishlist, TAMBAHKAN
            $wishlist = new Wishlist;
            $wishlist->user_id = auth()->user()->id;
            $wishlist->product_id = $product->id;
            $wishlist->price = ($product->price-($product->price*$product->discount)/100);
            
            // Pastikan kolom 'quantity' ada di tabel 'wishlists' dan `$fillable` di model Wishlist
            $wishlist->quantity = 1; 
            $wishlist->amount = $wishlist->price * $wishlist->quantity;

            // Optional: Periksa stok sebelum menambahkan ke wishlist (sudah ada dari kode awalmu)
            if ($product->stock < $wishlist->quantity || $product->stock <= 0) {
                request()->session()->flash('error','Stok tidak cukup!');
                return back();
            }

            $wishlist->save();
            request()->session()->flash('success','Produk berhasil ditambahkan ke wishlist!');
        }
        // 💡 AKHIR Logika TOGGLE 💡

        return back();       
    }  
    
    public function wishlistDelete(Request $request){
        $wishlist = Wishlist::find($request->id);
        if ($wishlist) {
            $wishlist->delete();
            request()->session()->flash('success','Wishlist berhasil dihapus!'); // Mengubah pesan
            return back();  
        }
        request()->session()->flash('error','Terjadi kesalahan, silakan coba lagi.'); // Mengubah pesan
        return back();       
    }     
}