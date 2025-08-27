$(document).ready(function () {
  const $checkbox = $("#accountActivation");
  const $submitBtn = $(".deactivate-account");

  // Disable button initially
  $submitBtn.prop("disabled", true);

  // Checkbox change event
  $checkbox.on("change", function () {
    $submitBtn.prop("disabled", !this.checked)
      .toggleClass("btn-danger", this.checked)
      .toggleClass("btn-secondary", !this.checked);
  });

  // Form submission event
  $("#formAccountDeactivation").on("submit", function (e) {
    e.preventDefault();

    if (!$checkbox.is(":checked")) return;

    Swal.fire({
      text: "Are you sure you want to deactivate your account?",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, Deactivate",
      cancelButtonText: "Cancel",
      customClass: {
        confirmButton: "btn btn-primary me-2 waves-effect waves-light",
        cancelButton: "btn btn-outline-secondary waves-effect"
      },
      buttonsStyling: false
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: deleteEmployeeUrl,
          type: "POST",  // Laravel requires POST with _method DELETE
          data: {
            _token: csrfToken,
            _method: "DELETE" // Laravel interprets this as a DELETE request
          },
          success: function () {
            Swal.fire({
              icon: "success",
              title: "Deleted!",
              text: "The employee has been successfully deleted.",
              customClass: {
                confirmButton: "btn btn-success waves-effect"
              }
            }).then(() => {
              window.location.href = employeeIndexUrl;
            });
          },
          error: function () {
            console.error("AJAX Error:", error);  // Logs the error message
            console.error("Status:", status); // Logs the status code
            console.error("Response:", xhr.responseText); // Logs the full response
            Swal.fire({
              title: "Error",
              text: "Something went wrong! Please try again.",
              icon: "error",
              customClass: {
                confirmButton: "btn btn-danger waves-effect"
              }
            });
          }
        });
      }
    });
  });
});
