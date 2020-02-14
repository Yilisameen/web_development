function handleEnter(e) {
    var keycode = (e.keyCode ? e.keyCode : e.which);
    if (keycode == '13') {
        e.preventDefault();
        searchImage();
    }
}

function searchImage() {
    var instance = document.getElementById("searchKey").value;
    document.getElementById("searchKey").value = "";
    searchFromAPI (instance);
}

function cleanHistory() {
    //clean the pics
    var pics = document.getElementById("searchResults");
    while (pics.firstChild) {
        pics.removeChild(pics.firstChild);
    }
    //clean the related words
    var words = document.getElementById("relatedWords");
    while (words.firstChild) {
        words.removeChild(words.firstChild);
    }
}

function searchFromAPI (instance) {
    cleanHistory();

    //fetch related words
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var myArr = Object.keys(JSON.parse(this.responseText));
            showRelated(myArr);
        }
    };
    var relatedURL = "https://oydnv6aq1g.execute-api.us-east-1.amazonaws.com/prod/concepts?instance=" + instance + "&topK=10";
    xhttp.open("GET", relatedURL, true);
    xhttp.send();

    //fetch images
    var imageHttp = new XMLHttpRequest();
    imageHttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var valueArr = JSON.parse(this.responseText).value
            showImage(valueArr)
        }
    };
    var imageURL = "https://api.cognitive.microsoft.com/bing/v7.0/images/search?q=" + instance + "&count=150";
    // + "&count=12&offset=0&mkt=en-us&safeSearch=Moderate";
    imageHttp.open("GET", imageURL, true);
    imageHttp.setRequestHeader("Ocp-Apim-Subscription-Key","85d20169ea8a457d805ac96efc5f7bf8");
    imageHttp.send();
}

function showRelated(arr) {
    var src = document.getElementById("relatedWords");
    var i;
    for(i = 0; i < arr.length; i++){
        var b = document.createElement("button");
        b.innerHTML= arr[i];
        b.addEventListener("click", function () {
            searchFromAPI(this.innerHTML);
        });
        src.appendChild(b);
    }
}

function showImage(arr) {
    var src = document.getElementById("searchResults");
    var i;
    for(i = 0; i < arr.length; i++) {
        var img = document.createElement("img");
        img.src = arr[i].contentUrl;
        img.addEventListener("click", function () {
            addToBoard(this)
        });
        src.appendChild(img);
    }
}

function addToBoard(element) {
    var src = document.getElementById("board");
    var img = document.createElement("img");
    img.src = element.src;
    src.appendChild(img);
}

