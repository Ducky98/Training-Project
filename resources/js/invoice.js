// Use an IIFE with a guard to prevent multiple executions
(function() {
  // Guard against multiple executions
  if (window.invoiceScriptLoaded) {
    return;
  }

  // Set flag to prevent future executions
  window.invoiceScriptLoaded = true;


  document.addEventListener("DOMContentLoaded", function () {
    // Initialize date picker if not already initialized
    if (!document.querySelector("#date_range").classList.contains("flatpickr-input")) {
      flatpickr("#date_range", {
        mode: "range",
        dateFormat: "d-m-Y",
        onClose: function (selectedDates, dateStr) {
          document.querySelector("#date_range").value = dateStr;
        },
      });
    }

    // Clean up any existing event listeners before adding new ones
    const addItemButton = document.getElementById('add-item');
    if (addItemButton) {
      // Clone the button to remove all event listeners
      const newButton = addItemButton.cloneNode(true);
      addItemButton.parentNode.replaceChild(newButton, addItemButton);

      // Add the click event to the new button
      newButton.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent any default behavior
        addNewInvoiceItem();
      });
    }

    function addNewInvoiceItem() {
      let invoiceItems = document.getElementById("invoice-items");
      let newItem = document.createElement("div");
      newItem.classList.add("mb-3", "invoice-item");

      // Get the last entered patient name/description
      let lastItemName = document.querySelector("#invoice-items .invoice-item:last-child:not([style*='display: none']) input[name='name[]']")?.value || "";

      // Check if GST is included
      let includeGST = document.getElementById('includeGST').checked;

      // Set placeholder based on GST toggle
      let namePlaceholder = includeGST ? "Description of Goods" : "Patient Name";
      let daysPlaceholder = includeGST ? "Qty." : "Days";
      let costPlaceholder = includeGST ? "Price" : "Cost per Day";
      let totalPlaceholder = includeGST ? "Amount" : "Total";
      let shiftPlaceholder = includeGST ? "Unit" : "Shift";

      // Set CG fields and supervisor visibility based on GST toggle
      let cgFieldsDisplay = includeGST ? "none" : "block";
      let supervisorDisplay = includeGST ? "none" : "block";

      newItem.innerHTML = `
    <div class="row mb-3">
      <div class="col-md-12 serial-number">
        <span></span>
      </div>
      <div class="col-md-3">
        <input type="text" class="form-control" name="name[]" placeholder="${namePlaceholder}" value="${lastItemName}">
      </div>
      <div class="col-md-2">
        <input type="number" class="form-control item-cost" name="cost[]" placeholder="${costPlaceholder}">
      </div>
      <div class="col-md-2">
        <input type="number" class="form-control item-qty" name="days[]" placeholder="${daysPlaceholder}">
      </div>
      <div class="col-md-2">
        <input type="number" class="form-control item-total" name="total[]" placeholder="${totalPlaceholder}" readonly>
      </div>
      <div class="col-md-1">
        <button type="button" class="btn btn-danger remove-item">X</button>
      </div>
    </div>
    <div class="row mb-3">
      <div class="col-md-2 cg-field" style="display: ${cgFieldsDisplay}">
        <input type="text"
               class="form-control cg-name"
               name="cg_name[]"
               placeholder="CG Name"
               list="employeeNames" autocomplete="off">
      </div>
      <div class="col-md-2 cg-field" style="display: ${cgFieldsDisplay}">
        <input type="text"
               class="form-control cg-id"
               name="cg_id[]"
               placeholder="CG ID"
               list="employeeIds" autocomplete="off">
      </div>
      <div class="col-md-2 supervisor-field" style="display: ${supervisorDisplay}">
        <input type="text" class="form-control" name="supervisor[]" placeholder="Supervisor">
      </div>
       <div class="col-md-2">
        <input type="text" class="form-control" name="shift[]" placeholder="${shiftPlaceholder}">
      </div>
       <div class="col-md-2">
        <input type="text" class="form-control" name="code[]" placeholder="HSN/SAC Code">
      </div>
    </div>
    <hr>
    `;

      // Add event listeners for the new row
      setupRowEventListeners(newItem);

      // Insert before the add item button
      invoiceItems.appendChild(newItem);

      // Update serial numbers
      updateSerialNumbers();

      // Update totals
      updateTotals();
    }

    // Setup event listeners for a row
    function setupRowEventListeners(row) {
      // Handle CG Name selection
      const cgNameInput = row.querySelector('.cg-name');
      const cgIdInput = row.querySelector('.cg-id');

      if (cgNameInput && cgIdInput) {
        cgNameInput.addEventListener('input', function() {
          const selectedName = this.value;
          const option = Array.from(document.querySelector('#employeeNames').options).find(
            opt => opt.value === selectedName
          );
          if (option) {
            cgIdInput.value = option.dataset.id;
          }
        });

        // Handle CG ID selection
        cgIdInput.addEventListener('input', function() {
          const selectedId = this.value;
          const option = Array.from(document.querySelector('#employeeIds').options).find(
            opt => opt.value === selectedId
          );
          if (option) {
            cgNameInput.value = option.dataset.name;
          }
        });
      }

      // Calculate total when cost or days change
      row.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-cost') || e.target.classList.contains('item-qty')) {
          const cost = parseFloat(row.querySelector('.item-cost').value) || 0;
          const days = parseFloat(row.querySelector('.item-qty').value) || 0;
          row.querySelector('.item-total').value = (cost * days).toFixed(2);
          updateTotals();
        }
      });
    }

    // Use event delegation for the remove button
    // First, remove any existing handlers by cloning the container
    const invoiceItems = document.getElementById('invoice-items');
    if (invoiceItems) {
      // We're using event delegation which attaches to the document,
      // so we'll use a unique event namespace
      if (!window.removeItemHandlerAdded) {
        document.addEventListener("click", function handleRemoveItem(event) {
          if (event.target.classList.contains("remove-item")) {
            // Don't allow removing if it's the last visible item
            const visibleItems = document.querySelectorAll('.invoice-item:not([style*="display: none"])');
            if (visibleItems.length > 1) {
              event.target.closest(".invoice-item").remove();
              updateTotals();
              updateSerialNumbers();
            } else {
              alert("At least one item is required");
            }
          }
        });
        window.removeItemHandlerAdded = true;
      }
    }

    // Update all totals
    function updateTotals() {
      const totals = Array.from(document.querySelectorAll('.item-total'))
        .reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);
      const taxRate = (parseFloat(document.getElementById('tax')?.value) || 0) / 100;
      const discount = parseFloat(document.getElementById('discount')?.value) || 0;

      const subtotal = totals;
      const discountAmount = discount;
      const afterDiscount = subtotal - discountAmount;
      const taxAmount = document.getElementById('includeGST')?.checked ? afterDiscount * taxRate : 0;
      const total = afterDiscount + taxAmount;

      // Update the totals display
      const amountContainer = document.querySelector('.col-md-6.offset-md-6');
      if (amountContainer) {
        const amounts = amountContainer.querySelectorAll('.d-flex span:last-child');
        if (amounts.length >= 4) {
          amounts[0].textContent = `₹${subtotal.toFixed(2)}`;
          amounts[1].textContent = `₹${taxAmount.toFixed(2)}`;
          amounts[2].textContent = `₹${discountAmount.toFixed(2)}`;
          amounts[3].textContent = `₹${total.toFixed(2)}`;
        }
      }
    }

    // Update serial numbers
    function updateSerialNumbers() {
      const rows = document.querySelectorAll("#invoice-items .invoice-item:not([style*='display: none']) .serial-number span");
      rows.forEach((el, index) => {
        el.textContent = `Item #${index + 1}`;
      });
    }

    // Add event listeners for form controls, using the clone technique to ensure single handlers
    const setupSingleEventListener = function(elementId, eventType, handler) {
      const element = document.getElementById(elementId);
      if (element) {
        const newElement = element.cloneNode(true);
        element.parentNode.replaceChild(newElement, element);
        newElement.addEventListener(eventType, handler);
      }
    };

    // Add event listeners with the clone technique
    setupSingleEventListener('discount', 'input', updateTotals);
    setupSingleEventListener('tax', 'input', updateTotals);
    setupSingleEventListener('includeGST', 'change', updateTotals);

    // Add initial empty row - ONLY IF NO ROWS EXIST YET
    if (document.querySelectorAll("#invoice-items .invoice-item:not([style*='display: none'])").length === 0) {
      addNewInvoiceItem();
    } else {
      updateSerialNumbers(); // Still update serial numbers for existing items
      updateTotals(); // Update totals for existing items
    }

    // Setup the GST toggle functionality
    const includeGST = document.getElementById('includeGST');
    if (includeGST) {
      // Remove any existing event listeners
      const newIncludeGST = includeGST.cloneNode(true);
      includeGST.parentNode.replaceChild(newIncludeGST, includeGST);

      // Add new event listener
      newIncludeGST.addEventListener('change', function() {
        updateFieldLabels();
      });

      // Initial update of field labels
      updateFieldLabels();
    }

    // Function to update field labels based on GST toggle
    function updateFieldLabels() {
      const includeGST = document.getElementById('includeGST').checked;

      // Update placeholders for all existing items
      const items = document.querySelectorAll('.invoice-item');
      items.forEach(item => {
        // Update main field placeholders
        const nameInput = item.querySelector('input[name="name[]"]');
        const costInput = item.querySelector('input[name="cost[]"]');
        const daysInput = item.querySelector('input[name="days[]"]');
        const totalInput = item.querySelector('input[name="total[]"]');
        const shiftInput = item.querySelector('input[name="shift[]"]');

        if (nameInput) nameInput.placeholder = includeGST ? "Description of Goods" : "Patient Name";
        if (costInput) costInput.placeholder = includeGST ? "Price" : "Cost per Day";
        if (daysInput) daysInput.placeholder = includeGST ? "Qty." : "Days";
        if (totalInput) totalInput.placeholder = includeGST ? "Amount" : "Total";
        if (shiftInput) shiftInput.placeholder = includeGST ? "Unit" : "Shift";

        // Toggle CG fields and supervisor
        const cgFields = item.querySelectorAll('.cg-field');
        cgFields.forEach(field => {
          field.style.display = includeGST ? 'none' : 'block';
        });

        // Toggle supervisor field
        const supervisorField = item.querySelector('.supervisor-field');
        if (supervisorField) {
          supervisorField.style.display = includeGST ? 'none' : 'block';
        }
      });
    }
  });
})();

document.addEventListener('DOMContentLoaded', function () {
  let includeGST = document.getElementById('includeGST');
  let gstField = document.getElementById('gstField');
  let taxField = document.getElementById('tax-field');

  function toggleGSTField() {
    if (includeGST.checked) {
      gstField.style.display = 'block';
      taxField.style.display = 'block';
      gstField.querySelector('input').disabled = false; // Enable input
      taxField.querySelector('input').disabled = false; // Enable input
    } else {
      gstField.style.display = 'none';
      taxField.style.display = 'none';
      gstField.querySelector('input').disabled = true; // Disable input
      taxField.querySelector('input').disabled = true; // Disable input
    }
  }

  includeGST.addEventListener('change', toggleGSTField);

  // Call it initially to set the correct display state on page load
  toggleGSTField();
});
