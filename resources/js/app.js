import './bootstrap';
import '../css/app.css';

// Import jQuery FIRST before Select2
import $ from 'jquery';
window.$ = window.jQuery = $;
// Import Flatpickr
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Swal from "sweetalert2";
window.Swal = Swal;



import Select2 from 'select2';

// Initialize Select2 on jQuery
Select2($);
import 'select2/dist/css/select2.min.css';
import '../assets/css/select2.css'



// Import assets
import.meta.glob([
  '../assets/img/**',
  '../assets/vendor/fonts/**'
]);
import './datatable.js'
import './invoice.js'
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
      .then(registration => console.log('ServiceWorker registration successful'))
      .catch(err => console.log('ServiceWorker registration failed: ', err));
  });
}
