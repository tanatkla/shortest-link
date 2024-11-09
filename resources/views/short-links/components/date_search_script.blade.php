@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css"
  integrity="sha512-MQXduO8IQnJVq1qmySpN87QQkiR1bZHtorbJBD0tzy7/0U9+YIC93QWHeGTEoojMVHWWNkoCp8V6OzVSYrX0oQ=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"
  integrity="sha512-K/oyQtMXpxI4+K0W7H25UopjM8pzq0yrVdFdG21Fh5dBe91I40pDd9A4lzNlHPHBIP2cwZuoxaUSX0GJSObvGA=="
  crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
  function initializeFlatpickr() {
      var dateInputs = document.querySelectorAll('.js-flatpickr');

      dateInputs.forEach(function(input) {
        flatpickr(input, {
          dateFormat: 'd/m/Y',
        });

        input.addEventListener('input', function() {});

      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      initializeFlatpickr();
    });
</script>
@endpush
