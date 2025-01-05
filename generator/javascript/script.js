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

// document.addEventListener('DOMContentLoaded', shuffleClips, false);

buttonShuffleClips.addEventListener('click', shuffleClips);
