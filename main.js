// global variables

authorsNumber = 1;

function addAuthor() {
	var el = document.getElementById("toClone")
	authorFormChunk = el.innerHTML;
	authorFormChunk = authorFormChunk.replace("authors[0]","authors["+authorsNumber+"]");
	var newDiv = document.createElement("doc");
	newDiv.innerHTML = authorFormChunk;
	el.parentNode.insertBefore(newDiv,document.getElementById("plusButton"));
	authorsNumber++;
}