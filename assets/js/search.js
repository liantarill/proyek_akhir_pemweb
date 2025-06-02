document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const vehicleGrid = document.getElementById("vehicleGrid");
  const vehicleCards = document.querySelectorAll(".vehicle-card");
  const searchResults = document.getElementById("searchResults");
  const resultCount = document.getElementById("resultCount");
  const vehicleCount = document.getElementById("vehicleCount");
  const noResults = document.getElementById("noResults");
  const clearSearch = document.getElementById("clearSearch");

  let currentSearch = "";

  function performSearch() {
    currentSearch = searchInput.value.toLowerCase().trim();
    filterVehicles();
  }

  function filterVehicles() {
    let visibleCount = 0;

    vehicleCards.forEach((card) => {
      const searchData = card.getAttribute("data-search");

      let matchesSearch = true;

      if (currentSearch) {
        matchesSearch = searchData.includes(currentSearch);
      }

      if (matchesSearch) {
        card.style.display = "block";
        visibleCount++;
      } else {
        card.style.display = "none";
      }
    });

    updateSearchResults(visibleCount);
  }

  function updateSearchResults(count) {
    if (currentSearch) {
      searchResults.style.display = "block";
      resultCount.textContent = count;
      clearSearch.style.display = "inline-block";
    } else {
      searchResults.style.display = "none";
      clearSearch.style.display = "none";
    }

    if (count === 0 && currentSearch) {
      noResults.style.display = "block";
      vehicleGrid.style.display = "none";
    } else {
      noResults.style.display = "none";
      vehicleGrid.style.display = "grid";
    }

    vehicleCount.textContent = count + " Kendaraan Tersedia";
  }

  clearSearch.addEventListener("click", function () {
    searchInput.value = "";
    currentSearch = "";

    vehicleCards.forEach((card) => {
      card.style.display = "block";
    });

    updateSearchResults(vehicleCards.length);
  });

  let searchTimeout;
  searchInput.addEventListener("input", function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(performSearch, 300);
  });
});
