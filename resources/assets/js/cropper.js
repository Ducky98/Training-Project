import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

export const initImageCropper = (config = {}) => {
  const defaults = {
    aspectRatio: 1,
    width: 400,
    height: 400,
    maxSize: 2 * 1024 * 1024 // 2MB
  };

  const options = { ...defaults, ...config };

  document.querySelectorAll('.account-file-input').forEach(($fileInput) => {
    const inputName = $fileInput.getAttribute('name');
    const $accountUserImage = document.getElementById(`uploadedAvatar_${inputName}`);
    const $avatarPreviewInput = document.getElementById(`avatar_preview_${inputName}`);
    const defaultImage = $accountUserImage.src;

    if (!$accountUserImage || !$fileInput) return;

    // Create and append modal if it doesn't exist
    if (!document.getElementById('cropperModal')) {
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
    }

    const $cropperModal = document.getElementById('cropperModal');
    const $cropperImage = document.getElementById('cropperImage');
    const modal = new bootstrap.Modal($cropperModal);
    let cropper = null;

    const handleFileSelect = (e) => {
      const file = e.target.files[0];

      if (!file) return;

      // Validate file type
      const allowedImageTypes = ['image/png', 'image/jpeg', 'image/gif'];
      const allowedFileTypes = [...allowedImageTypes, 'application/pdf'];

      if (!allowedFileTypes.includes(file.type)) {
        alert('Invalid file type. Only PNG, JPEG, GIF, and PDF are allowed.');
        return;
      }

      // Validate file size
      if (file.size > options.maxSize) {
        alert('File size exceeds 2MB.');
        return;
      }

      // If the file is a PDF, skip cropping
      if (file.type === 'application/pdf') {
        $accountUserImage.src = '/assets/img/pdf-placeholder.png'; // Placeholder for PDFs
        $avatarPreviewInput.value = '';
        return;
      }

      // Read and display file for cropping
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
        autoCropArea: 1,
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
        const file = new File([blob], 'cropped_image.jpg', { type: 'image/jpeg' });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        $fileInput.files = dataTransfer.files;

        // Update preview image
        const url = URL.createObjectURL(blob);
        $accountUserImage.src = url;
        $avatarPreviewInput.value = canvas.toDataURL();

        modal.hide();
      }, 'image/jpeg');
    });

    document.querySelectorAll('.account-image-reset').forEach(($resetButton) => {
      $resetButton.addEventListener('click', () => {
        if ($resetButton.getAttribute('data-target') === inputName) {
          $fileInput.value = '';
          $accountUserImage.src = defaultImage;
          $avatarPreviewInput.value = '';
          if (cropper) {
            cropper.destroy();
            cropper = null;
          }
        }
      });
    });
  });
};
