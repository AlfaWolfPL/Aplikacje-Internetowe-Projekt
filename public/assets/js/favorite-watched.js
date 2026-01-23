const form = document.getElementById("searchForm");
const filterMode = document.getElementById("filterMode");

const cbFavorites = document.getElementById("cbFavorites");
const cbMarked   = document.getElementById("cbMarked");

function clearOtherFilters() {
    const q = document.querySelector('input[name="q"]');
    if (q) q.value = "";

    const cat = document.querySelector('select[name="category"]');
    if (cat) cat.value = "";

    const platform = document.querySelector('select[name="platform"]');
    if (platform) platform.value = "";

}

cbFavorites.addEventListener("change", () => {
    if (cbFavorites.checked) {
        cbMarked.checked = false;
        clearOtherFilters();
        filterMode.value = "favorites";
    } else {
        filterMode.value = "";
    }
    form.submit();
});

cbMarked.addEventListener("change", () => {
    if (cbMarked.checked) {
        cbFavorites.checked = false;
        clearOtherFilters();
        filterMode.value = "watched";
    } else {
        filterMode.value = "";
    }
    form.submit();
});