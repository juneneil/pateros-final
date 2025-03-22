// Function to toggle the sidebar
function toggleSidebar() {
  var menu = document.getElementById("menuBar");
  var body = document.body; // Get the body element
  var nav = document.getElementById("navBar");

  var profileNavbar = document.getElementById("profileNavbar");

  if (window.innerWidth > 767) {
    // For larger screens (Desktop)
    if (menu.style.left === "-250px" || menu.style.left === "") {
      menu.style.left = "0"; // Show the sidebar by moving it into view
      body.style.marginLeft = "250px"; // Move the body to the right
      nav.style.marginLeft = "-250px"; // Fixed in place

      profileNavbar.style.left = "250";
      profileNavbar.style.marginRight = "250";
    } else {
      menu.style.left = "-250px"; // Hide the sidebar by moving it out of view
      body.style.marginLeft = "0"; // Reset the body's margin
      nav.style.marginLeft = "-250px"; // Fixed in place

      profileNavbar.style.left = "250";
      profileNavbar.style.marginRight = "250";
    }
  } else {
    // For smaller screens (Mobile)
    if (menu.style.left === "-200px" || menu.style.left === "") {
      menu.style.left = "0"; // Show the sidebar by moving it into view
      nav.style.marginLeft = "0"; // Fixed in place
    } else {
      menu.style.left = "-200px"; // Hide the sidebar by moving it out of view
    }
  }
}
