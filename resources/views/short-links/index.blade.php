 @extends('layouts.app')
 @section('content')
   <div id="copy-success-message" class="alert alert-success position-fixed top-0 end-0 mt-3 me-3"
     style="min-width: 200px; display:none;">
     <div class="d-flex align-items-center">
       <i class="fa-regular fa-circle-check me-2"></i>
       <p class="mb-0">{{ __('คัดลอกสำเร็จ !!') }}</p>
     </div>
   </div>

   <div class="container py-5">
     <form action="{{ route('shortest-links.index') }}" method="GET" class="text-end mb-4">
       <input name="data_search" type="text" value="{{ $data_search }}" class="form-control d-inline-block me-2 w-25"
         placeholder="{{ __('ค้นหา') }}">
       <button type="submit" class="btn btn-primary">
         <i class="fa-solid fa-magnifying-glass me-1"></i>{{ __('ค้นหา') }}
       </button>
       <button id="clear-search-input" class="btn btn-secondary">
         <i class="fa-regular fa-circle-xmark me-1"></i>{{ __('ล้างข้อมูล') }}
       </button>
     </form>
     <div class="table-responsive">
       <table class="table table-striped table-hover align-middle">
         <thead class="table-light">
           <tr>
             <th scope="col" style="width: 5%;">{{ __('#') }}</th>
             @if (Auth::user()->role == 'ADMIN')
               <th scope="col" style="width: 15%;">{{ __('ชื่อผู้สร้าง') }}</th>
             @endif
             <th scope="col" style="width: 15%;">{{ __('ชื่อของลิงก์') }}</th>
             <th scope="col" style="width: 20%;">{{ __('ลิงก์ Original') }}</th>
             <th scope="col" style="width: 27%;">{{ __('ลิงก์ Short') }}</th>
             <th scope="col" class="text-center" style="width: 10%;">{{ __('วันหมดอายุ') }}</th>
             <th scope="col" style="width: 8%;">{{ __('เครื่องมือ') }}</th>
           </tr>
         </thead>
         <tbody>
           @if (count($lists) > 0)
             @foreach ($lists as $index => $item)
               <tr>
                 <td>{{ $lists->firstItem() + $index }}</td>
                 @if (Auth::user()->role == 'ADMIN')
                   <td>
                     {{ $item->user?->name ?? '-' }}
                   </td>
                 @endif
                 <td>{{ $item->link_name }}</td>
                 <td>
                   <div class="shortened-url-container">
                     <span class="shortened-url" data-full-url="{{ $item->original_url }}"
                       data-short-url="@shortenURL($item->original_url)">
                       @if (strlen($item->original_url) > 20)
                         <span>{{ substr($item->original_url, 0, 20) }}...</span>
                       @else
                         {{ $item->original_url }}
                       @endif
                     </span>
                     @if (strlen($item->original_url) > 20)
                       <a href="#" class="show-full-url-link text-xs underline">ดูเพิ่มเติม</a>
                       <a href="#" class="hide-full-url-link text-xs underline" style="display: none;">ย่อ</a>
                     @endif
                   </div>
                 </td>
                 <td>
                   {{ $item->short_path_name }}
                   <button onclick="copyToClipboard('{{ $item->short_path_name }}')" class="btn btn-link p-0">
                     <i class="fa-regular fa-copy text-primary"></i>
                   </button>
                 </td>
                 <td class="text-center">
                   {{ $item->is_expire ? ($item->expire_date ? date('d/m/Y', strtotime($item->expire_date)) : '-') : '-' }}
                 </td>
                 <td>
                   <a href="{{ route('shortest-links.edit', ['shortest_link' => $item->id]) }}"
                     class="btn btn-link text-primary p-0 me-2">
                     <i class="fa-solid fa-pencil"></i>
                   </a>
                   <button class="btn btn-link text-danger p-0 delete-link"
                     data-route-delete="{{ route('shortest-links.destroy', ['shortest_link' => $item->id]) }}"
                     data-value="{{ $item->id }}">
                     <i class="fa-solid fa-trash"></i>
                   </button>
                 </td>
               </tr>
             @endforeach
           @else
             <tr>
               <td colspan="7" class="text-center">{{ __('ไม่มีรายการ') }}</td>
             </tr>
           @endif
         </tbody>
       </table>
     </div>
     <div class="mt-4 d-flex justify-content-end">
       {{ $lists->links() }}
     </div>
   </div>
 @endsection

 @push('scripts')
   <script>
     function copyToClipboard(text) { // copy link

       const textArea = document.createElement('textarea');
       textArea.value = text;
       document.body.appendChild(textArea);
       textArea.select();
       document.execCommand('copy');
       document.body.removeChild(textArea);

       const copySuccessMessage = document.getElementById('copy-success-message');
       console.log(copySuccessMessage)
       copySuccessMessage.style.display = 'block';

       setTimeout(function() {
         copySuccessMessage.style.display = 'none';
       }, 3000);
     } // end copy link

     function shortenURL(url, maxLength = 20) {
       if (url.length <= maxLength) {
         return url;
       }
       return url.slice(0, maxLength) + '...';
     }

     $(document).ready(function() {
       $('.delete-link').on('click', function(e) { // ajax delete link
         var token = $("meta[name='csrf-token']").attr("content");
         var route_delete = $(this).attr('data-route-delete');
         Swal.fire({ // confirm delete
           title: 'ยืนยันการลบ ?',
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'ยืนยัน',
           cancelButtonText: 'ยกเลิก'
         }).then((result) => {
           if (result.isConfirmed) {
             var id = $(this).data("value");
             var token = $("meta[name='csrf-token']").attr("content");
             $.ajax({
               url: route_delete,
               type: 'DELETE',
               data: {
                 "id": id,
                 "_token": token,
               },
               success: function() { 
                 Swal.fire(
                   'ลบสำเร็จ!',
                   '',
                   'success'
                 ).then(function() {
                   location.reload();
                 });
               }
             });
           }
         });
       }); 

       $('.shortened-url-container').each(function() { // name original link
         console.log(98888)
         const container = $(this);
         const fullURL = container.find('.shortened-url').data('full-url');
         const showFullURLLink = container.find('.show-full-url-link');
         const hideFullURLLink = container.find('.hide-full-url-link');

         showFullURLLink.on('click', function(event) {

           event.preventDefault();
           container.find('.shortened-url').text(fullURL);
           showFullURLLink.hide();
           hideFullURLLink.show();
         });

         hideFullURLLink.on('click', function(event) {
           event.preventDefault();
           container.find('.shortened-url').text(shortenURL(fullURL, 20));
           hideFullURLLink.hide();
           showFullURLLink.show();
         });
       });

       $('#clear-search-input').on('click', function(e) {
         e.preventDefault();
         $('#search').val('');
         window.location = window.location.href.split("?")[0];
       });

     });
   </script>
 @endpush
