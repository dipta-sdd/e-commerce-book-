$(document).on(`change`, `.addressDivision`, function (e) {
  var id = $(this).val();
  var orderId = $(this).attr("orderId");
  $(`#${orderId}addressDistrict`).html(
    `<option value="0" selected>Select One</option>`
  );
  if (id != `0`) {
    $.map(
      jsonSearch(`districts`, `division_id`, id),
      function (district, indexOrKey) {
        $(`#${orderId}addressDistrict`).append(`
                    <option value="${district.id}">${district.name}</option>
                    `);
      }
    );
  }
});

$(document).on(`change`, `.addressDistrict`, function (e) {
  var id = $(this).val();
  var orderId = $(this).attr("orderId");
  $(`#${orderId}addressUpazila`).html(
    `<option value="0" selected>Select One</option>`
  );
  if (id != `0`) {
    $.map(
      jsonSearch(`upazilas`, `district_id`, id),
      function (district, indexOrKey) {
        $(`#${orderId}addressUpazila`).append(`
                    <option value="${district.id}">${district.name}</option>
                    `);
      }
    );
  }
});
function getAddress(orderId) {
  var address = {
    country: `Bangladesh`,
    division: $(`#${orderId}addressDivision`).val(),
    district: $(`#${orderId}addressDistrict`).val(),
    upazila: $(`#${orderId}addressUpazila`).val(),
    address: $(`#${orderId}addressDetails`).val(),
  };
  if (
    address.division == `0` ||
    address.upazila == `0` ||
    address.address == `` ||
    address.district == `0`
  ) {
    return null;
  }
  return address;
}

function addressInput(orderId) {
  return `
        <hr>
        <div class="row" id="${orderId}addressRow">
            <div class="col-12 mb-1">Delivery Address</div>
            <div class="form-floating col-3">
                <select class="form-select addressCountry" id="addressCountry" aria-label="Floating label select example">
                    <option value="Bangladesh" selected>Bangladesh</option>
                </select>
                <label for="addressCountry">Country</label>
            </div>
            <div class="form-floating col-3">
                <select class="form-select addressDivision" orderId="${orderId}" id="${orderId}addressDivision" aria-label="Floating label select example">
                    <option value="0" selected="">Select One</option>
                    <option value="6">Rangpur</option>
                    <option value="1">Barishal</option>
                    <option value="3">Dhaka</option>
                    <option value="7">Sylhet</option>
                    <option value="8">Mymensingh</option>
                    <option value="5">Rajshahi</option>
                    <option value="2">Chattogram</option>
                    <option value="4">Khulna</option>
                </select>
                <label for="addressDivision">Division</label>
            </div>
            <div class="form-floating col-3">
                <select class="form-select addressDistrict" orderId="${orderId}" id="${orderId}addressDistrict" aria-label="Floating label select example">
                    <option value="0" selected>Select One</option>
                </select>
                <label for="addressDistrict">District</label>
            </div>
            <div class="form-floating col-3">
                <select class="form-select addressUpazila" id="${orderId}addressUpazila" aria-label="Floating label select example">
                    <option value="0" selected>Select One</option>
                </select>
                <label for="addressUpazila">Upazila</label>
            </div>
            <div class="form-floating mt-3 col-10">
                <input type="text" class="form-control addressDetails" id="${orderId}addressDetails" placeholder="name@example.com">
                <label for="addressDetails">Addres</label>
            </div>
            <div class="col-2 center"><button type="button" class="btn btn-outline-primary submitAddress" id="submitAddress" orderId="${orderId}">Save Address</button></div>
        </div>
    `;
}

function addressInputProfile(orderId) {
  return `
        <div class="row me-1" id="${orderId}addressRow">
            <div class="form-floating col-3">
                <select class="form-select addressCountry" id="addressCountry" aria-label="Floating label select example">
                    <option value="Bangladesh" selected>Bangladesh</option>
                </select>
                <label for="addressCountry">Country</label>
            </div>
            <div class="form-floating col-3">
                <select class="form-select addressDivision" orderId="${orderId}" id="${orderId}addressDivision" aria-label="Floating label select example">
                    <option value="0" selected="">Select One</option>
                    <option value="6">Rangpur</option>
                    <option value="1">Barishal</option>
                    <option value="3">Dhaka</option>
                    <option value="7">Sylhet</option>
                    <option value="8">Mymensingh</option>
                    <option value="5">Rajshahi</option>
                    <option value="2">Chattogram</option>
                    <option value="4">Khulna</option>
                </select>
                <label for="addressDivision">Division</label>
            </div>
            <div class="form-floating col-3">
                <select class="form-select addressDistrict" orderId="${orderId}" id="${orderId}addressDistrict" aria-label="Floating label select example">
                    <option value="0" selected>Select One</option>
                </select>
                <label for="addressDistrict">District</label>
            </div>
            <div class="form-floating col-3">
                <select class="form-select addressUpazila" id="${orderId}addressUpazila" aria-label="Floating label select example">
                    <option value="0" selected>Select One</option>
                </select>
                <label for="addressUpazila">Upazila</label>
            </div>
            <div class="form-floating mt-3 col-12">
                <input type="text" class="form-control addressDetails" id="${orderId}addressDetails" placeholder="name@example.com">
                <label for="addressDetails">Addres</label>
            </div>
        </div>
    `;
}

function showAddressProfile(orderId, address) {
  $(`#${orderId}addressDivision`).val(address.division);
  $.map(
    jsonSearch(`districts`, `division_id`, `${address.division}`),
    function (district, indexOrKey) {
      $(`#${orderId}addressDistrict`).append(`
            <option value="${district.id}">${district.name}</option>
        `);
    }
  );
  $(`#${orderId}addressDistrict`).val(address.district);
  $.map(
    jsonSearch(`upazilas`, `district_id`, `${address.district}`),
    function (district, indexOrKey) {
      $(`#${orderId}addressUpazila`).append(`
                <option value="${district.id}">${district.name}</option>
                `);
    }
  );
  $(`#${orderId}addressUpazila`).val(address.upazila);
  $(`#${orderId}addressDetails`).val(address.address);
}

function showAddress(address) {
  console.log();
  return `
    ${address.address} , ${
    jsonSearchId("upazilas", address.upazila.toString())[0].name
  } ,
        ${jsonSearchId("districts", address.district.toString())[0].name} ,
        ${jsonSearchId("divisions", address.division.toString())[0].name} ,
        ${address.country} 
    
    `;
}

$(document).on("click", ".submitAddress", function (e) {
  var orderId = $(this).attr("orderId");
  var address = getAddress(orderId);
  if (address) {
    $.ajax({
      type: "POST",
      url: "https://bookhavenapi.sankarsan.xyz/api/v1/order/address",
      data: JSON.stringify({
        orderId: orderId,
        address: address,
      }),
      contentType: "application/json",
      headers: {
        Authorization: "Bearer " + getCookie("token"),
      },
      success: function (response) {
        showToast(response.message);
        $(`#${orderId}addressRow`).html(
          `<hr><p><strong>Deliver Address : </strong> ${showAddress(
            response.order.address
          )} </p>`
        );
      },
    });
  }
});

// Function to perform the search within the JSON data

function jsonSearchAll(from) {
  var searchResults = null;
  $.ajax({
    url: `../JSON/cbss.${from}.json`, // Replace with your JSON file path
    dataType: "json",
    async: false,
    success: function (data) {
      searchResults = data;
    },
    error: function (xhr, status, error) {
      console.log("Error fetching JSON:", error);
    },
  });
  return searchResults;
}
function jsonSearch(from, searchTerm, search) {
  var searchResults = null;
  $.ajax({
    url: `/JSON/cbss.${from}.json`, // Replace with your JSON file path
    dataType: "json",
    async: false,
    success: function (data) {
      searchResults = data.filter(function (item) {
        return item[searchTerm] === search;
      });
    },
    error: function (xhr, status, error) {
      console.error("Error fetching JSON:", error);
    },
  });
  // console.log(searchResults);
  return searchResults;
}

function jsonSearchId(from, search) {
  var searchResults = null;
  $.ajax({
    url: `/JSON/cbss.${from}.json`, // Replace with your JSON file path
    dataType: "json",
    async: false,
    success: function (data) {
      searchResults = data.filter(function (item) {
        return item.id === search;
      });
    },
    error: function (xhr, status, error) {
      console.error("Error fetching JSON:", error);
    },
  });
  return searchResults;
}
