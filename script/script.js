// spinner function
// import { toast } from 'react-toastify';

function showSpinner() {
  $(".spinner").removeClass("d-none");
  $(".spinner-overlay").removeClass("d-none");
}

function hideSpinner() {
  $(".spinner").addClass("d-none");
  $(".spinner-overlay").addClass("d-none");
}

// convert time
function convertUTCToLocalTime(utcDateString) {
  const utcDate = new Date(utcDateString);
  const localTimeString = utcDate.toLocaleString(); // Converts UTC to local time
  return localTimeString;
}
// toast
function showToast(message) {
  var toast = `
        <div class="toast-container position-fixed top-0 end-0 p-3 ">
            <div id="liveToast" class="toast fade hide" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <svg class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>
                    <strong class="me-auto">Notification</strong>
                    <small>Just Now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        </div>
    `;

  $("body").append(toast);

  var newToast = $(".toast:last");
  newToast.toast("show");
  setTimeout(function () {
    newToast.toast("hide");
    newToast.remove(); // Remove the toast from the DOM after hiding
  }, 3000);
}

function loadSidebar(role) {
  console.log("called");
  // $(`u-user`).removeClass('d-none');
  $(`.u-${role}`).removeClass("d-none");
}
function sidebarActivate(link) {
  var sidebarOption = $(`.sidebar-option[href="${link}"]`);
  sidebarOption.removeClass("sidebar-option");
  sidebarOption.addClass("sidebar-option-active");
}
// current user
function current_user() {
  let data = null;
  $.ajax({
    type: "GET",
    async: false,
    url: "bookhavenapi.sankarsan.xyz/api/v1/auth/current-user",
    headers: {
      Authorization: "Bearer " + getCookie("token"),
    },
    success: function (response) {
      // console.log(response);
      data = response.user;
    },
  });
  return data;
}

// cookie functions

function setCookie(cookieName, cookieValue, expirationDays) {
  const date = new Date();
  date.setTime(date.getTime() + expirationDays * 24 * 60 * 60 * 1000);
  const expires = "expires=" + date.toUTCString();
  document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
}
function getCookie(cookieName) {
  const name = cookieName + "=";
  const decodedCookie = decodeURIComponent(document.cookie);
  const cookieArray = decodedCookie.split(";");

  for (let i = 0; i < cookieArray.length; i++) {
    let cookie = cookieArray[i];
    while (cookie.charAt(0) === " ") {
      cookie = cookie.substring(1);
    }
    if (cookie.indexOf(name) === 0) {
      return cookie.substring(name.length, cookie.length);
    }
  }
  return null; // Return null if the cookie is not found
}

function deleteCookie(cookieName) {
  document.cookie =
    cookieName + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
}
