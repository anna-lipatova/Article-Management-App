const prevButton = document.getElementById("prev-button");
const nextButton = document.getElementById("next-button");
const pageCounter = document.getElementById("page-counter");
const articles = document.getElementsByClassName("article");

const createButton = document.getElementById("create-button");
const dialog = document.getElementById("modal");
const articleNameInput = document.getElementById("article-name");
const modalSubmitButton = document.getElementById("modal-submit");
const modalCancelButton = document.getElementById("modal-cancel");

/// !!! all the buttons with class show-snapshot-button
const showSnapshotButton = document.getElementsByClassName("show-snapshot-button");

const maxPageItems = 10;
let currentPage = 1; // F04) User can see the total number of pages on the screen. 
let articleCount = articles.length;
let maxPageCount = Math.ceil(articleCount / maxPageItems);

const resetArticlesTable = async () => {
  const response = await fetch('./reset-articles', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    }
  });

  if (!response.ok) {
    console.log("Reset failed");
    return;
  }

  window.location.reload();
}


/// !!!
const createSnapshot = async (id) => {
  const response = await fetch('../article-snapshot', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ id: id })
  });

  if (!response.ok) {
    console.log("Snapshot failed");
    return;
  }

  const dialog = document.createElement("dialog");
  dialog.textContent = "Snapshot created successfully";

  document.body.appendChild(dialog);
  dialog.showModal();

  setTimeout(() => {
    dialog.close();
  }, 2000);
}


/// !!!
const showSnapshots = async (button, id) => {
  const response = await fetch('./article-snapshots-list', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ id: id })
  });

  if (!response.ok) {
    console.log("Snapshot failed");
    return;
  }

  /// echo json_encode($snapshots); from showSnapshots($id)
  const snapshots = await response.json();
  if (!snapshots) {
    return
  }

  const article = document.getElementById("article-" + id);
  const snapshotList = document.createElement("ul");
  snapshotList.classList.add("snapshot-list");
  snapshotList.id = "snapshot-list-" + id;
  article.after(snapshotList); // add to the end of the article

  snapshots.forEach(snapshot => {
    const snapshotItem = document.createElement("li");
    const snapshotLink = document.createElement("a"); //link
    snapshotLink.textContent = snapshot.created_at;
    snapshotLink.href = `./article-snapshot/${snapshot.id}`;
    snapshotItem.appendChild(snapshotLink);
    snapshotList.appendChild(snapshotItem);
  });

  button.textContent = "Hide snapshots";
}


/// !!!
const hideSnapshots = (button, id) => {
  const snapshotList = document.getElementById("snapshot-list-" + id);
  if (snapshotList) {
    snapshotList.remove();
  }

  button.textContent = "Show snapshots";
}

/// !!!
const hideAllSnapshots = () => {
  const snapshotLists = document.getElementsByClassName("snapshot-list");
  for (let i = 0; i < snapshotLists.length; i++) {
    snapshotLists[i].remove();
  }

  for (let i = 0; i < showSnapshotButton.length; i++) {
    showSnapshotButton[i].textContent = "Show snapshots";
  }
}

/// !!! 
const toggleSnapshots = async (event, id) => {
  const snapshotList = document.getElementById("snapshot-list-" + id);
  if (snapshotList) {
    hideSnapshots(event.target, id); //target = clicked element, article id
  } else {
    showSnapshots(event.target, id);
  }
}


// F08) If a user clicks the link, the respective article is removed. 
// The list of all articles is refreshed, and the current page is 
// preserved unless the current page is the last page with no articles.
// using AJAX with HTTP DELETE request. The list update must be 
// handled on client side using JavaScript, you are not allowed to 
// perform full page reload.
const deleteArticle = async (id) => {
  const response = await fetch('./article-delete', {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ id: id })
  });

  if (!response.ok) {
    console.log("Delete failed");
    return;
  }

  document.getElementById("article-" + id).remove();
  articleCount--;

  maxPageCount = Math.ceil(articleCount / maxPageItems);
  if (currentPage > maxPageCount) {
    prevPage();
  }

  if (currentPage <= maxPageCount) {
    changePage();
  }
}




// F03) When the user is on the first/last 
// page Previous/Next button is hidden. 
const navigationButtonsRefresh = () => {
  if (currentPage == 1) {
    prevButton.classList.add('hidden');
  } else {
    prevButton.classList.remove('hidden');
  }

  if (currentPage == maxPageCount) {
    nextButton.classList.add('hidden');
  } else {
    nextButton.classList.remove('hidden');
  }
}


/// !!!
// F01) User can view a paged list of articles by navigating 
// to ./articles. Each page contains at most 10 articles. 
const changePage = () => {
  for (let i = 0; i < articles.length; i++) {
    articles[i].classList.add("d-none");
  }

  const firstItem = (currentPage - 1) * maxPageItems;
  for (let i = firstItem; i < currentPage * maxPageItems && i < articleCount; i++) {
    articles[i].classList.remove("d-none");
  }

  /// !!!
  hideAllSnapshots();
  navigationButtonsRefresh();
}



// F02) User can move to next/previous page by clicking 
// Next/Previous buttons respectively.
const nextPage = () => {
  if (currentPage < maxPageCount) {
    // F04)
    pageCounter.textContent = ++currentPage;
    changePage();
  }
}

const prevPage = () => {
  if (currentPage > 1) {
    // F04)
    pageCounter.textContent = --currentPage;
    changePage();
  }
}

if (prevButton || nextButton) {
  prevButton.addEventListener("click", prevPage);
  nextButton.addEventListener("click", nextPage);
  navigationButtonsRefresh();
}


if (articles?.length > 0) {
  changePage();
}

if (dialog) {
  // F10) If a user clicks Create article button a dialog is shown.
  createButton.addEventListener("click", () => {
    articleNameInput.value = "";
    modalSubmitButton.disabled = true;
    dialog.showModal();
  });

  // F11) If a user clicks on the Cancel button, the dialog is closed.
  modalCancelButton.addEventListener("click", () => {
    articleNameInput.value = "";
    modalSubmitButton.disabled = true;
    dialog.close();
  });
}


// F12) The Create button is enabled only if the text 
// input for the article name is not empty.
// F24) 
const checkArticleName = () => {
  if (articleNameInput.value === "") {
    modalSubmitButton.disabled = true;
  } else {
    modalSubmitButton.disabled = false;
  }
}

if (articleNameInput) {
  checkArticleName();
  articleNameInput.addEventListener("input", () => {
    checkArticleName();
  });
}