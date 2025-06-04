const buttonShuffleClips = document.querySelector('#shuffleClips');
const buttonRandomClip = document.querySelector('a#randomClip');
const buttonFilterClips = document.querySelector('#filterPanel form');

function RandomClip() {
    let randomClipId = allClips[Math.floor(Math.random() * allClipsCount)];
    console.log(randomClipId);
    buttonRandomClip.setAttribute('href', basePath+'/clip/'+randomClipId+'/');
}

if (buttonRandomClip !== null) {   
    buttonRandomClip.addEventListener('click', RandomClip);
}

// Shuffles elements in a list
// By - Yair Even Or
function shuffleClips() {
    var ul = document.querySelector("ul.clips"), // get the list
    temp = ul.cloneNode(true); // clone the list

    // shuffle the cloned list (better performance)
    for (var i = temp.children.length + 1; i--; )
        temp.appendChild( temp.children[Math.random() * i |0] );

    ul.parentNode.replaceChild(temp, ul); // copy shuffled back to 'ul'
}

if (buttonShuffleClips !== null) {   
    buttonShuffleClips.addEventListener('click', shuffleClips);
}



function readUrlParams() {
    let hash = document.location.hash;
    hash = hash.substring(1);
    if (hash.length === 0) {
        return;
    }
    let urlTags = hash.split('+');
    for (var urlTag of urlTags) {
        let query = 'input.tag[value="'+urlTag+'"]';
        let tagCheckbox = document.querySelector(query);
        tagCheckbox.checked = true;
    }
    let filterPanel = document.querySelector('details#filterPanel');
    filterPanel.open = true;
    filterClips();
}



function filterClips() {

    // read checkboxes and fill clips var with filtered clips
    let tagCheckboxesChecked = document.querySelectorAll('input.tag:checked');
    let clips = null;
    let checkedTags = [];
    for (var checkedTag of tagCheckboxesChecked) {
        checkedTags.push(checkedTag.value);
        if (clips === null) {
            clips = tags[checkedTag.value];
        } else if (clips instanceof Set) {
            clips = clips.intersection(tags[checkedTag.value]);
        } else {
            console.log('clips is nor null nor Set');
        }
    }

    // Update URL params
    let path = document.location.pathname;
    hash = '#'+checkedTags.join('+');
    history.replaceState({}, '', path+hash);

    // Manage which clips should be displayed or not
    let lis = document.querySelectorAll('ul.clips li');
    let clipCounter = 0;
    // If no tag is checked
    if (clips === null) {
        for (var li of lis) {
            // li.removeAttribute('data-filter');
            delete li.dataset.filter;
            clipCounter++;
        }
    } else {
        for (var li of lis) {
            if (clips.has(Number(li.id))) {
                // li.setAttribute('data-filter', '1');
                li.dataset.filter = 1;
                clipCounter++;
            } else {
                // li.setAttribute('data-filter', '0');
                li.dataset.filter = 0;
            }
        }
    }

    // Update clip counter
    let clipCount = document.querySelector('#clipCount');
    clipCount.textContent = clipCounter;

}

if (buttonFilterClips !== null) {   
    buttonFilterClips.addEventListener('click', filterClips);
}


document.addEventListener("DOMContentLoaded", readUrlParams());
