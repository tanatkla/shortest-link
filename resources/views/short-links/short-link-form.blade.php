 @extends('layouts.app')
 @section('content')
   <form method="POST" action="{{ route('shortest-links.store') }}" class="container my-5">
     @csrf
     <div class="row justify-content-center">
       <div class="col-md-8 bg-white p-4 rounded shadow-sm">
         <div class="mb-3">
           <label for="original_link" class="form-label">ลิงก์ที่ต้องการแปลง<span class="text-danger">*</span></label>
           <input type="text" id="original_link" name="original_link" class="form-control"
             value="{{ old('original_link', isset($d) ? $d->original_url : null) }}" autofocus>
           @error('original_link')
             <div class="text-danger mt-1">{{ $message }}</div>
           @enderror
         </div>
         <div class="mb-3">
           <label for="short_link" class="form-label">ชื่อลิงก์ใหม่<span class="text-danger">*</span></label>
           <div class="input-group">
             <input type="text" id="url" name="url" class="form-control text-secondary" readonly
               value="{{ $prefix_url }}" style="border-top-right-radius: 0; border-bottom-right-radius: 0;" disabled>
             <input type="text" id="short_link" name="short_link" class="form-control"
               value="{{ old('short_link', isset($d) ? $d->short_url : null) }}"
               style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
             <button type="button" id="random_short_url" class="btn btn-secondary">สุ่มชื่อ</button>
           </div>
           @error('short_link')
             <div class="text-danger mt-1">{{ $message }}</div>
           @enderror
         </div>
         <div class="mb-3">
           <label for="link_name" class="form-label">ชื่อ / รายละเอียดลิงก์<span class="text-danger">*</span></label>
           <input type="text" id="link_name" name="link_name" class="form-control"
             value="{{ old('link_name', isset($d) ? $d->link_name : null) }}">
           @error('link_name')
             <div class="text-danger mt-1">{{ $message }}</div>
           @enderror
         </div>
         <div class="mb-3">
           <label for="is_expire" class="form-label">ต้องการตั้งค่าวันที่หมดอายุหรือไม่<span class="text-danger">*</span></label>
           <div class="form-check">
             <input class="form-check-input" type="radio" name="is_expire" id="is_expire_yes" value="1"
               {{ old('is_expire', isset($d) ? $d->is_expire : '') == '1' ? 'checked' : '' }}>
             <label class="form-check-label" for="is_expire_yes">ต้องการ</label>
           </div>
           <div class="form-check">
             <input class="form-check-input" type="radio" name="is_expire" id="is_expire_no" value="0"
               {{ old('is_expire', isset($d) ? $d->is_expire : '') == '0' ? 'checked' : '' }}>
             <label class="form-check-label" for="is_expire_no">ไม่ต้องการ</label>
           </div>
           @error('is_expire')
             <div class="text-danger mt-1">{{ $message }}</div>
           @enderror
         </div>

         <div class="mb-3" id="expire_section" style="display: none;">
           <label for="expire_date" class="form-label">วันที่หมดอายุของลิงก์</label>
           <input type="text" id="expire_date" name="expire_date" class="form-control js-flatpickr flatpickr-input"
             value="{{ old('expire_date', isset($d->expire_date) ? date('d/m/Y', strtotime($d->expire_date)) : null) }}"
             placeholder="เลือกวันที่" autocomplete="off">
           @error('expire_date')
             <div class="text-danger mt-1">{{ $message }}</div>
           @enderror
         </div>

         <input type="hidden" id="id" name="id" value="{{ isset($d) ? $d->id : null }}">

         <div class="d-flex justify-content-end mt-4">
           <a href="{{ route('shortest-links.index') }}" class="btn btn-secondary me-2">กลับ</a>
           <button type="submit" class="btn btn-primary">
             {{ isset($d) ? 'บันทึก' : 'แปลง' }}
           </button>
         </div>
       </div>
     </div>
   </form>
 @endsection
 @include('short-links.components.date_search_script')
 @push('scripts')
   <script>
     $(document).ready(function() {
       var initialIsExpireValue = $("input[name='is_expire']:checked").val();
       if (initialIsExpireValue == 1) {
         $('#expire_section').show();
       } else {
         $('#expire_section').hide();
       }
       $("input[name='is_expire']").change(function() {
         var selectedValue = $(this).val();
         if (selectedValue == 1) {
           $('#expire_section').show();
         } else {
           $('#expire_section').hide();
         }
       });
     });


     $('#random_short_url').click(function() {
       var random_url = Math.random().toString(36).substr(2, 7);
       $('#short_link').val(random_url);
     });

     $('#short_link').on('input', function() {
       var input = $(this);
       var pattern = /^[a-zA-Z0-9ก-๏\s]+$/;
       if (!pattern.test(input.val())) {
         input.val(input.val().replace(/[^\w\s-ก-๏]/g, '').replace(/\s+/g, '-'));
       }
     });
   </script>
 @endpush
