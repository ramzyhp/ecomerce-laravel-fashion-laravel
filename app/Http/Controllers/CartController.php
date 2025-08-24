<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Cart;
use App\Models\Coupon; 
use Illuminate\Support\Str;
use Helper; 

class CartController extends Controller
{
    public function index()
    {
        $coupons = Coupon::where('status', 'active')->get();

        return view('frontend.pages.cart')->with('coupons', $coupons);
    }

    protected $product=null;
    public function __construct(Product $product){
        $this->product=$product;
    }

    public function addToCart(Request $request){
        // dd($request->all());
        if (empty($request->slug)) {
            request()->session()->flash('error','Invalid Products');
            return back();
        }        
        $product = Product::where('slug', $request->slug)->first();
        // return $product;
        if (empty($product)) {
            request()->session()->flash('error','Invalid Products');
            return back();
        }

        $already_cart = Cart::where('user_id', auth()->user()->id)->where('order_id',null)->where('product_id', $product->id)->first();
        // return $already_cart;
        if($already_cart) {
            // dd($already_cart);
            $already_cart->quantity = $already_cart->quantity + 1;
            // Harga per item dikalikan dengan kuantitas baru untuk mendapatkan amount
            $after_price=($already_cart->product->price-($already_cart->product->price*$already_cart->product->discount)/100);
            $already_cart->amount = $after_price * $already_cart->quantity;
            
            // return $already_cart->quantity;
            if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) return back()->with('error','Stok tidak cukup!.'); // Mengubah pesan
            $already_cart->save();
            
        }else{
            
            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->price = ($product->price-($product->price*$product->discount)/100);
            $cart->quantity = 1;
            $cart->amount=$cart->price*$cart->quantity;
            if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) return back()->with('error','Stok tidak cukup!.'); // Mengubah pesan
            $cart->save();
            // $wishlist=Wishlist::where('user_id',auth()->user()->id)->where('cart_id',null)->update(['cart_id'=>$cart->id]); // Ini dari implementasi awal
        }
        request()->session()->flash('success','Produk berhasil ditambahkan ke keranjang'); // Mengubah pesan
        return back();       
    }  

    public function singleAddToCart(Request $request){
        $request->validate([
            'slug'      =>  'required',
            'quant'      =>  'required',
        ]);
        // dd($request->quant[1]);


        $product = Product::where('slug', $request->slug)->first();
        if($product->stock <$request->quant[1]){
            return back()->with('error','Stok tidak cukup, Anda bisa menambahkan produk lain.'); // Mengubah pesan
        }
        if ( ($request->quant[1] < 1) || empty($product) ) {
            request()->session()->flash('error','Produk tidak valid!'); // Mengubah pesan
            return back();
        }    

        $already_cart = Cart::where('user_id', auth()->user()->id)->where('order_id',null)->where('product_id', $product->id)->first();

        // return $already_cart;

        if($already_cart) {
            $already_cart->quantity = $already_cart->quantity + $request->quant[1];
            // $already_cart->price = ($product->price * $request->quant[1]) + $already_cart->price ;
            $after_price=($already_cart->product->price-($already_cart->product->price*$already_cart->product->discount)/100);
            $already_cart->amount = ($after_price * $already_cart->quantity); // Menghitung ulang amount berdasarkan kuantitas baru

            if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) return back()->with('error','Stok tidak cukup!.'); // Mengubah pesan

            $already_cart->save();
            
        }else{
            
            $cart = new Cart;
            $cart->user_id = auth()->user()->id;
            $cart->product_id = $product->id;
            $cart->price = ($product->price-($product->price*$product->discount)/100);
            $cart->quantity = $request->quant[1];
            $cart->amount=($product->price * $request->quant[1]);
            if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) return back()->with('error','Stok tidak cukup!.'); // Mengubah pesan
            // return $cart;
            $cart->save();
        }
        request()->session()->flash('success','Produk berhasil ditambahkan ke keranjang.'); // Mengubah pesan
        return back();       
    } 
    
    public function cartDelete(Request $request){
        $cart = Cart::find($request->id);
        if ($cart) {
            $cart->delete();
            request()->session()->flash('success','Keranjang berhasil dihapus!'); // Mengubah pesan
            return back();  
        }
        request()->session()->flash('error','Terjadi kesalahan, silakan coba lagi.'); // Mengubah pesan
        return back();       
    }     

    public function cartUpdate(Request $request){
        // dd($request->all()); // Kamu bisa uncomment ini untuk melihat data yang dikirim

        // 💡 BAGIAN INI DITAMBAHKAN/DIUBAH UNTUK UPDATE KUANTITAS DI DATABASE 💡
        if($request->quant){ // Memastikan ada data kuantitas yang dikirim
            $error = array(); // Untuk menyimpan pesan error
            $success = ''; // Untuk pesan sukses
            
            foreach ($request->quant as $key => $quant) {
                $cart_id = $request->qty_id[$key]; // Dapatkan cart_id dari hidden input
                $cart = Cart::find($cart_id); // Cari item keranjang

                if ($cart && $quant > 0) { // Pastikan item keranjang ditemukan dan kuantitas > 0
                    $product = Product::find($cart->product_id); // Ambil produk terkait
                    $stock = $product->stock; // Ambil stok produk

                    if ($quant > $stock) {
                        // Jika kuantitas melebihi stok, tambahkan ke array error
                        $error[] = 'Stok tidak cukup untuk produk ' . $product->title . ' (Max: ' . $stock . ')!';
                        // Lanjutkan ke item berikutnya, jangan langsung redirect
                        continue; 
                    }
                    
                    $cart->quantity = $quant; // Update kuantitas
                    $after_price=($product->price-($product->price*$product->discount)/100); // Harga setelah diskon
                    $cart->amount = $after_price * $quant; // Hitung ulang amount
                    $cart->save(); // Simpan perubahan
                    $success = 'Keranjang berhasil diperbarui!'; // Set pesan sukses
                } else if ($cart && $quant <= 0) {
                    // Jika kuantitas 0 atau kurang, hapus item dari keranjang
                    $cart->delete();
                    $success = 'Keranjang berhasil diperbarui (item dihapus)!'; // Set pesan sukses
                } else {
                    $error[] = 'Item keranjang tidak valid!'; // Item tidak ditemukan
                }
            }
            
            // Setelah loop selesai, baru redirect dengan semua pesan
            if (!empty($error)) {
                return back()->with('error', implode('<br>', $error))->with('success', $success); // Tampilkan semua error
            } else {
                return back()->with('success', $success);
            }
        } else {
            // Jika tidak ada data kuantitas yang dikirim
            return back()->with('error', 'Keranjang tidak valid!'); // Mengubah pesan
        }    
    }

    // public function addToCart(Request $request){
    //     // return $request->all();
    //     if(Auth::check()){
    //         $qty=$request->quantity;
    //         $this->product=$this->product->find($request->pro_id);
    //         if($this->product->stock < $qty){
    //             return response(['status'=>false,'msg'=>'Out of stock','data'=>null]);
    //         }
    //         if(!$this->product){
    //             return response(['status'=>false,'msg'=>'Product not found','data'=>null]);
    //         }
    //         // $session_id=session('cart')['session_id'];
    //         // if(empty($session_id){
    //         //     $session_id=\Str::random(30);
    //         //     // dd($session_id);
    //         //     session()->put('session_id',$session_id);
    //         // }
    //         $current_item=array(
    //             'user_id'=>auth()->user()->id,
    //             'id'=>$this->product->id,
    //             // 'session_id'=>$session_id,
    //             'title'=>$this->product->title,
    //             'summary'=>$this->product->summary,
    //             'link'=>route('product-detail',$this->product->slug),
    //             'price'=>$this->product->price,
    //             'photo'=>$this->product->photo,
    //         );
            
    //         $price=$this->product->price;
    //         if($this->product->discount){
    //             $price=($price-($price*$this->product->discount)/100);
    //         }
    //         $current_item['price']=$price;

    //         $cart=session('cart') ? session('cart') : null;

    //         if($cart){
    //             // if anyone alreay order products
    //             $index=null;
    //             foreach($cart as $key=>$value){
    //                 if($value['id']==$this->product->id){
    //                     $index=$key;
    //                 break;
    //                 }
    //             }
    //             if($index!==null){
    //                 $cart[$index]['quantity']=$qty;
    //                 $cart[$index]['amount']=ceil($qty*$price);
    //                 if($cart[$index]['quantity']<=0){
    //                     unset($cart[$index]);
    //                 }
    //             }
    //             else{
    //                 $current_item['quantity']=$qty;
    //                 $current_item['amount']=ceil($qty*$price);
    //                 $cart[]=$current_item;
    //             }
    //         }
    //         else{
    //             $current_item['quantity']=$qty;
    //             $current_item['amount']=ceil($qty*$price);
    //             $cart[]=$current_item;
    //         }

    //         session()->put('cart',$cart);
    //         return response(['status'=>true,'msg'=>'Cart successfully updated','data'=>$cart]);
    //     }
    //     else{
    //         return response(['status'=>false,'msg'=>'You need to login first','data'=>null]);
    //     }
    // }

    // public function removeCart(Request $request){
    //     $index=$request->index;
    //     // return $index;
    //     $cart=session('cart');
    //     unset($cart[$index]);
    //     session()->put('cart',$cart);
    //     return redirect()->back()->with('success','Successfully remove item');
    // }

    public function checkout(Request $request){
        // $cart=session('cart');
        // $cart_index=\Str::random(10);
        // $sub_total=0;
        // foreach($cart as $cart_item){
        //     $sub_total+=$cart_item['amount'];
        //     $data=array(
        //         'cart_id'=>$cart_index,
        //         'user_id'=>$request->user()->id,
        //         'product_id'=>$cart_item['id'],
        //         'quantity'=>$cart_item['quantity'],
        //         'amount'=>$cart_item['amount'],
        //         'status'=>'new',
        //         'price'=>$cart_item['price'],
        //     );

        //     $cart=new Cart();
        //     $cart->fill($data);
        //     $cart->save();
        // }
        return view('frontend.pages.checkout');
    }
}