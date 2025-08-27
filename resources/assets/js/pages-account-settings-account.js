// resources/assets/js/pages-account-settings-account.js
'use strict';
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

export const initImageCropper = (config = {}) => {
  const defaults = {
    uploadImageId: 'uploadedAvatar',
    fileInputId: 'upload',
    resetButtonClass: 'account-image-reset',
    previewInputId: 'avatar_preview',
    aspectRatio: 1,
    width: 400,
    height: 400,
    maxSize: 800 * 1024 // 800KB
  };

  const options = { ...defaults, ...config };
  let cropper = null;

  const $accountUserImage = document.getElementById(options.uploadImageId);
  const $fileInput = document.getElementById(options.fileInputId);
  const $resetFileInput = document.querySelector(`.${options.resetButtonClass}`);
  const $avatarPreviewInput = document.getElementById(options.previewInputId);
  if (!$accountUserImage || !$fileInput) return;

  const defaultImage = $accountUserImage.src;

  // Create and append modal
  const modalHTML = `
    <div class="modal fade" id="cropperModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Crop Image</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="img-container">
              <img id="cropperImage" src="" class="w-100">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-sm btn-primary" id="cropButton">Crop & Save</button>
          </div>
        </div>
      </div>
    </div>
  `;

  document.body.insertAdjacentHTML('beforeend', modalHTML);

  const $cropperModal = document.getElementById('cropperModal');
  const $cropperImage = document.getElementById('cropperImage');
  const modal = new bootstrap.Modal($cropperModal);

  const handleFileSelect = (e) => {
    const file = e.target.files[0];

    if (!file) return;

    // Validate file type
    const allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];
    if (!allowedTypes.includes(file.type)) {
      alert('Invalid file type. Only PNG, JPEG, and GIF are allowed.');
      return;
    }

    // Validate file size
    if (file.size > options.maxSize) {
      alert('File size exceeds 800KB.');
      return;
    }

    // Read and display file
    const reader = new FileReader();
    reader.onload = (e) => {
      $cropperImage.src = e.target.result;
      modal.show();
    };
    reader.readAsDataURL(file);
  };

  const initCropper = () => {
    cropper = new Cropper($cropperImage, {
      aspectRatio: options.aspectRatio,
      viewMode: 2,
      dragMode: 'move',
      autoCropArea: 1,
      restore: false,
      guides: true,
      center: true,
      highlight: false,
      cropBoxMovable: true,
      cropBoxResizable: true,
      toggleDragModeOnDblclick: false
    });
  };

  // Event Listeners
  $fileInput.addEventListener('change', handleFileSelect);

  $cropperModal.addEventListener('shown.bs.modal', initCropper);

  $cropperModal.addEventListener('hidden.bs.modal', () => {
    if (cropper) {
      cropper.destroy();
      cropper = null;
    }
  });

  document.getElementById('cropButton').addEventListener('click', () => {
    if (!cropper) return;

    const canvas = cropper.getCroppedCanvas({
      width: options.width,
      height: options.height
    });

    canvas.toBlob((blob) => {
      // Create a File object from the blob
      const file = new File([blob], 'cropped_avatar.jpg', { type: 'image/jpeg' });

      // Create a new FileList containing the cropped image
      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);

      // Update the file input with the cropped image
      $fileInput.files = dataTransfer.files;
      console.log($fileInput.files,$fileInput)

      // Update preview image
      const url = URL.createObjectURL(blob);
      console.log(url)
      $accountUserImage.src = url;

      // Store base64 in hidden input for preview
      $avatarPreviewInput.value = canvas.toDataURL();

      modal.hide();
    }, 'image/jpeg');
  });

  if ($resetFileInput) {
    $resetFileInput.addEventListener('click', () => {
      $fileInput.value = '';
      $accountUserImage.src = defaultImage;
      $avatarPreviewInput.value = '';
      if (cropper) {
        cropper.destroy();
        cropper = null;
      }
    });
  }
};

document.addEventListener('DOMContentLoaded', () => {
  initImageCropper({
    aspectRatio: 1,
    width: 400,
    height: 400,
    maxSize: 800 * 1024 // 800KB
  });
});

document.addEventListener('DOMContentLoaded', function () {
  const oldDate = document.querySelector('input[name="policeVerificationDate"]')?.value;

  flatpickr("#policeVerificationDate", {
    enableTime: false,
    dateFormat: "d-m-Y",
    maxDate: "today",
    defaultDate: oldDate ? oldDate : null,
    onReady: function(selectedDates, dateStr, instance) {
      if (!oldDate) {
        instance.input.placeholder = "Select Date";
      }
    }
  });

  $('#languages').select2({
    allowClear: true,
    maximumSelectionLength: 4
  });

});
