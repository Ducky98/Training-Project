<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PhotoUpload extends Component
{
  public string $uploadRoute;
  public ?string $existingFile;
  public string $inputName;
  public string $label;
  public string $defaultImage;
  public bool $isAvatar;

  /**
   * Create a new component instance.
   *
   * @param string $uploadRoute The route to handle the file upload.
   * @param string|null $existingFile The existing file path if available.
   * @param string $inputName The name of the file input field.
   * @param string $label The label for the file upload.
   * @param bool $isAvatar If true, uses avatar styling; otherwise, document styling.
   */
  public function __construct(string $uploadRoute, ?string $existingFile, string $inputName, string $label, bool $isAvatar = false)
  {
    $this->uploadRoute = $uploadRoute;
    $this->existingFile = $existingFile;
    $this->inputName = $inputName;
    $this->label = $label;
    $this->isAvatar = $isAvatar;
    $this->defaultImage = $isAvatar ? asset('assets/img/avatars/1.png') : asset('assets/img/avatars/1.png');
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render()
  {
    return view('components.photo-upload');
  }
}
