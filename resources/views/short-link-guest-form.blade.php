@extends('layouts.guest')
@section('content')
  <div class="card d-flex w-100 p-4 flex-column  justify-content-center align-items-center bg-light">
    @if (Route::has('login'))
      <div class="position-fixed top-0 end-0 p-3">
        @auth
          <a href="{{ route('shortest-links.index') }}" class="text-decoration-none text-white">{{ __('หน้าหลัก') }}</a>
        @else
          @if (Route::has('register'))
            <a href="{{ route('register') }}" class="text-decoration-none text-white">{{ __('ลงทะเบียน') }}</a>
          @endif
          <a href="{{ route('login') }}" class="ms-3 text-decoration-none text-white">{{ __('เข้าสู่ระบบ') }}</a>
        @endauth
      </div>
    @endif
    <div class="container text-center">
      <div class="d-flex justify-content-center mt-5">
        <img src="{{ asset('images/short_link_logo.png') }}" alt="Logo Short Link" class="img-fluid">
      </div>
      <form id="convert-link">
        <div class="card mt-4 shadow-sm">
          <div class="card-body">
            <label for="original_link" class="form-label">{{ __('ลิงก์ที่ต้องการแปลง') }}</label>
            <div class="input-group">
              <input id="original_link" name="original_link" type="text" class="form-control" autofocus>
              <button type="button" class="btn btn-primary d-flex align-items-center" id="convert-btn">
                <i class="fa-solid fa-shuffle me-2"></i>{{ __('แปลงลิงก์') }}
              </button>
            </div>
            <div class="text-center mt-4">
              @guest
                <p class="small text-muted">
                  {{ __('*หมายเหตุ* สามารถตั้งค่าชื่อและวันที่หมดอายุลิงก์ของคุณได้ โดยเข้าสู่ระบบเพื่อใช้งาน หรือหากไม่มีบัญชีสำหรับใช้งาน') }}
                  <a class="small text-decoration-underline text-primary"
                    href="{{ route('register') }}">{{ __('กรุณาคลิกที่นี่') }}</a>
                </p>
              @endguest
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    function isURLValid(url) {
      const urlPattern = /^(http(s)?:\/\/)?[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}(\S*)$/;
      return urlPattern.test(url);
    }

    function swalError(data) {
      Swal.fire({
        icon: 'error',
        title: 'มีข้อผิดพลาด',
        text: data,
      }).then((result) => {
        //
      });
    }

    $('#original_link').on('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        $('#convert-btn').click();
      }
    });

    $('#convert-btn').on('click', function(e) {
      console.log(2222222);
      const url = $('#original_link').val();
      if (!isURLValid(url)) {
        return swalError('รูปแบบลิงก์ไม่ถูกต้อง');
      }

      var formData = new FormData($('#convert-link')[0]);
      var originalLinkValue = $('#original_link').val().trim();
      if (originalLinkValue === '') {
        return swalError('กรุณากรอกลิงก์ที่ต้องการแปลง');
      }

      axios.post("{{ route('short-link-guests.store') }}", formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          }
        })
        .then(function(response) {
          if (response.data.success) {
            Swal.fire({
              title: response.data.data,
              icon: 'success',
              showCloseButton: true,
              showConfirmButton: false,
              html: '<button id="copy_button" class="btn btn-primary">คัดลอก</button>',
              customClass: {
                title: 'custom-title',
              },
            });

            $('#copy_button').on('click', function() {
              const textArea = $('<textarea>');
              textArea.val(response.data.data);
              $('body').append(textArea);
              textArea.select();
              document.execCommand('copy');
              textArea.remove();
              Swal.close();
              $('#original_link').val('');
            });

            $('.swal2-close').on('click', function() {
              $('#original_link').val('');
            });
          }
        })
        .catch(function(error) {
          //
        });
    });
  </script>
@endpush
