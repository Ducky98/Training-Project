<div id="alert" class="alert alert-{{ $type }} position-fixed top-0 end-0 m-3 fade show" role="alert" style="z-index: 9999; display: none;">
  {{ $message }}
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const alertElement = document.getElementById('alert');
    alertElement.style.display = 'block';
    setTimeout(function() {
      alertElement.style.display = 'none';
    }, 3000); // Hide after 3 seconds
  });
</script>
