const WATCHED_LIST_COOKIE_NAME = 'watched';
const FAVORITES_LIST_COOKIE_NAME = 'favorites';

function setCookie(cookieName, value, expirationDays) {
    const d = new Date();
    d.setTime(d.getTime() + (expirationDays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cookieName + "=" + value + ";" + expires + ";path=/";
}

function getCookie(cookieName) {
    let name = cookieName + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function getJsonDecodedArrayFromCookie(cookieName) {
    let list = [];
    const existing = getCookie(cookieName);
    if (existing) {
        try {
            list = JSON.parse(existing) ?? [];
        } catch {
            list = [];
        }
    }
    return list;
}

function manageWatchedList(titleId) {
    let watched = getJsonDecodedArrayFromCookie(WATCHED_LIST_COOKIE_NAME);

    const index = watched.indexOf(titleId);

    let isWatched = true;
    if (index === -1) {
        watched.push(titleId);
    } else {
        isWatched = false;
        watched.splice(index, 1);
    }

    setCookie(WATCHED_LIST_COOKIE_NAME, JSON.stringify(watched), 365);
    changeWatchedButtonState(titleId, isWatched);
}

function isTitleWatched(titleId) {
    let watched  = getJsonDecodedArrayFromCookie(WATCHED_LIST_COOKIE_NAME);
    const index = watched.indexOf(titleId);
    return index !== -1;

}

function changeWatchedButtonState(titleId, isWatched = null)
{
    if (isWatched === null) {
        isWatched = isTitleWatched(titleId);
    }
    console.log('isWatched', isWatched);
    let btn = document.getElementById('watchedBtn');
    if (isWatched) {
        btn.innerHTML = '<span class="material-symbols-outlined">remove</span>Remove from Watched';
    } else {
        btn.innerHTML = '<span class="material-symbols-outlined">add</span>Add to Watched';
    }
}

function manageFavoritesList(titleId) {
    let favorites = getJsonDecodedArrayFromCookie(FAVORITES_LIST_COOKIE_NAME);

    const index = favorites.indexOf(titleId);

    let isFavorite = true;
    if (index === -1) {
        favorites.push(titleId);
    } else {
        isFavorite = false;
        favorites.splice(index, 1);
    }

    setCookie(FAVORITES_LIST_COOKIE_NAME, JSON.stringify(favorites), 365);
    changeFavoriteButtonState(titleId, isFavorite);
}

function isTitleInFavorites(titleId) {
    let favorites  = getJsonDecodedArrayFromCookie(FAVORITES_LIST_COOKIE_NAME);
    const index = favorites.indexOf(titleId);
    return index !== -1;
}

function changeFavoriteButtonState(titleId, isFavorite = null)
{
    if (isFavorite === null) {
        isFavorite = isTitleInFavorites(titleId);
    }
    console.log('isFavorite', isFavorite);
    let btn = document.getElementById('favoriteBtn');
    if (isFavorite) {
        btn.innerHTML = '<span class="material-symbols-outlined">remove</span>Remove from Liked';
    } else {
        btn.innerHTML = '<span class="material-symbols-outlined">favorite</span>Add to Liked';
    }
}

