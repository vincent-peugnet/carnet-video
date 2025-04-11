const buttonShuffleClips = document.getElementById("shuffleClips");


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

buttonShuffleClips.addEventListener('click', shuffleClips);




const buttonFilterClips = document.getElementById('filterForm');

function filterClips() {

    let tagCheckboxesChecked = document.querySelectorAll('input.tag:checked');

    let clips = null;
    for (var checkedTag of tagCheckboxesChecked) {

        if (clips === null) {
            clips = tags[checkedTag.value];
        } else if (clips instanceof Set) {
            clips = clips.intersection(tags[checkedTag.value]);
        } else {
            console.log('clips is nor null nor Set');
        }
    }

    let lis = document.querySelectorAll('ul.clips li');

    // If no tag is checked
    if (clips === null) {
        for (var li of lis) {
            li.removeAttribute('data-filter');
        }
        return;
    }

    for (var li of lis) {
        if (clips.has(Number(li.id))) {
            li.setAttribute('data-filter', '1');
        } else {
            li.setAttribute('data-filter', '0');
        }
    }

}

buttonFilterClips.addEventListener('click', filterClips);


