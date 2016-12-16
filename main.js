// global variables

authorsNumber = 1;

// extend standart string class
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};


function addAuthor() {
	var el = document.getElementById("toClone")
	authorFormChunk = el.innerHTML;
	authorFormChunk = authorFormChunk.replaceAll("authors[0]","authors["+authorsNumber+"]");
	var newDiv = document.createElement("doc");
	newDiv.innerHTML = authorFormChunk;
	el.parentNode.insertBefore(newDiv,document.getElementById("plusButton"));
	authorsNumber++;
}