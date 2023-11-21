@component('components.head', ['title' => 'Thanh toán'])
@endcomponent

<main class="max-w-[1360px] mx-auto px-16 my-10 mt-[137px]">
  
    {{-- product --}}
    <form action="{{route('handle-checkout')}}" method="post">
      @csrf
        <div class="col-span-1 bg-gray-100 p-2">
          <div class="flex items-center justify-between my-2 pb-2 border-b border-slate-300">
            <h3 class="font-semibold uppercase">Sản phẩm</h3>
          </div>
          <input type="hidden" name="products" value="{{ json_encode($products) }}">
          <input type="hidden" name="totalPrice" value="{{ $totalPrice }}">
          @foreach ($products as $product)
            <div class="flex justify-between items-center py-2 my-2 border-b border-slate-300">
              <div class="flex items-center gap-4">
                <img src={{ $product->SP_HinhAnh }} alt="" class="w-20">
                <div class="flex flex-col items-start text-14 font-medium">
                  <span>{{$product->SP_Ten}}</span>
                </div>
              </div>
              <div class="text-14 font-medium">{{number_format($product->SP_Gia * $product->CTGH_SoLuong, 0, ',', '.')}}</div>
            </div>
          @endforeach
          <div class="flex justify-end text-18 font-semibold ">
            <p>Tổng cộng: <span class="text-primary-500">{{number_format($totalPrice, 0, ',', '.')}}</span></p>
          </div>
        </div>
        <div class="flex items-center justify-between my-6">
          <a href="{{route('cart')}}" class="text-blue-500">Giỏ hàng</a>
          <button type="submit" class="bg-primary-500 text-white px-4 py-2 text-18 uppercase font-medium rounded-md transition-all duration-300 hover:bg-primary-700">Đặt Hàng</button>
        </div>
    </form>
  {{-- </div> --}}

</main>


<x-footer :js-file="''" />