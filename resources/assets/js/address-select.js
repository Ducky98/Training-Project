$(document).ready(function () {
  // Country-State Logic
  let $countrySelect = $("#country");
  let oldCountry = $('input[name="old_country"]').val();
  let oldState = $('input[name="old_state"]').val();

  $countrySelect.prop("disabled", true);

  $.ajax({
    url: window.baseURL + "/api/country/all",
    method: "GET",
    xhrFields: { withCredentials: true },
    success: function (response) {
      $countrySelect.empty();

      let sortedCountries = response.sort((a, b) => {
        if (a.name === "India") return -1;
        if (b.name === "India") return 1;
        return a.name.localeCompare(b.name);
      });

      $.each(sortedCountries, function (index, country) {
        let isSelected = country.iso2 === oldCountry || (!oldCountry && country.name === "India");
        $("<option>")
          .val(country.iso2)
          .text(country.name)
          .prop("selected", isSelected)
          .appendTo($countrySelect);
      });

      $countrySelect.prop("disabled", false).select2({
        placeholder: "Select a country",
      });

      $countrySelect.val(oldCountry || "IN").trigger("change");

      fetchStates(oldCountry || "IN", oldState);
    },
    error: function (error) {
      console.error("Error fetching countries:", error);
    },
  });

  function fetchStates(countryCode, selectedState = null) {
    let $stateSelect = $("#state");
    $stateSelect.prop("disabled", true).html('<option value="">Select State</option>');

    if (!countryCode) return;

    $.ajax({
      url: window.baseURL + "/api/states/" + countryCode,
      method: "GET",
      xhrFields: { withCredentials: true },
      success: function (response) {
        $stateSelect.prop("disabled", false).html('<option value="">Select State</option>');

        $.each(response, function (index, state) {
          $("<option>")
            .val(state.name)
            .text(state.name)
            .prop("selected", state.name === selectedState)
            .appendTo($stateSelect);
        });

        $stateSelect.select2({ placeholder: "Select a state" });

        if (selectedState) {
          $stateSelect.val(selectedState).trigger("change");
        }
      },
      error: function (error) {
        console.error("Error fetching states:", error);
        $stateSelect.prop("disabled", true);
      },
    });
  }

  $("#country").on("change", function () {
    fetchStates($(this).val());
  });
});
