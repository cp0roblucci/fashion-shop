@extends('pages.admin.layout.main')

{{-- set title --}}
@section('title', 'Create new Category')
@section('path', 'Thêm mới / Loại Sản Phẩm')

@section('slidebar')
  @include('pages.admin.layout.slidebar')
@endsection

@section('content')
  <div class="mb-2">
    @section('header')
      @include('pages.admin.layout.header')
    @endsection

    <div>
      <div class="py-4 pt-2 ml-2 text-24 font-sora text-[#5432a8]">Thêm mới Loại Sản Phẩm</div>
      @if(session('create-success'))
          <div id="message" class="flex absolute top-12 right-7">
            <div  class="bg-slate-200 rounded-lg border-l-8 border-l-blue-500 opacity-80">
              <div class="py-4 text-blue-100 relative before:absolute before:bottom-0 before:content-[''] before:bg-blue-500 before:h-0.5 before:w-full before:animate-before">
                <span class="px-4">{{ session('create-success') }}</span>
              </div>
            </div>
          </div>
        @endif
    </div>
    <div class="grid grid-cols-2 gap-8 border">
      <div class="col-span-2 p-4 w-2/3">
        <form action="" method="post">
          @csrf

          <div class="mt-4 ">
            <label
              for="species"
              class="text-slate-500 text-14"
            >
              Loại
            </label><br>
            <div class="border-[1.5px] mt-1">
              <input
                type="text"
                name="species"
                placeholder="Tên"
                class="pb-6 pt-1 w-32 outline-none focus-within:border-blue-500 px-2 placeholder:text-14 text-14"
              >
            </div>
          </div>

          <button
            type="submit"
            class="border-2 border-blue-500 p-2 px-6 mt-4 flex hover:bg-slate-100"
          >
            Thêm
          </button>

        </form>
      </div>

      <div class="border w-full ml-4 ">
        <h2 class="text-24 text-center font-sora text-primary-purple">Danh sách các Loại Sản Phẩm</h2>
        <div class="mx-2 leading-8">
          @foreach ($loaisanpham as $key => $value)
            <div class="flex">
              <h2 class="mr-2">{{ ++$key }}.</h2>
              <h4 class="font-medium">{{ $value->LSP_Ten}}</h4>
            </div>
          @endforeach
        </div>

    </div >
  </div>

@endsection

@section('footer')
  @include('pages.admin.layout.footer')
@endsection
