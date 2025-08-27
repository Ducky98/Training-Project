<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TabbedNavigation extends Component
{
  public array $navItems;

  /**
   * Create a new component instance.
   *
   * @param array $navItems List of tabs (icon, label, href)
   */
  public function __construct(array $navItems)
  {
    $this->navItems = $navItems;
  }

  /**
   * Get the view / contents that represent the component.
   */
  public function render()
  {
    return view('components.tabbed-navigation');
  }
}
