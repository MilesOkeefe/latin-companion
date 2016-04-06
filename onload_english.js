$(document).ready(function(){
	function getParameterByName(name){
		name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
		var regexS = "[\\?&]"+name+"=([^&#]*)";
		var regex = new RegExp( regexS );
		var results = regex.exec( window.location.href );
		if( results == null )
			return "";
		else
			return decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	var s = getParameterByName('s');
	if(s != ""){
		$("#sentence").val(s);
		latinify(s);
	}
	$("#sentence").css("padding-right", ($("#translate").outerWidth() + 10) + "px"); //10 is a margin
	var margin = 0;
	if($.browser.webkit){
		$("#translate").css("margin-top", "2px");
		margin = -2;
	}
	if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
		margin = 0;
		$("h1").css("font-size", "48px");
	}
	$("#translate").css("height", $("#sentence").outerHeight()  + margin + "px");
	var words;
	$("#sentence").focus(function(){
		if($("#sentence").val() == "enter an english sentence here"){
			$("#sentence").val(""); 
		}
	});
	$("#sentence").blur(function(){ //unfocus
		if($("#sentence").val() == ""){
			$("#sentence").val("enter an english sentence here"); 
		}
	});
	 $("#sentence").keydown(function(event){	
		if(event.keyCode ==13 /*enter key*/){
			latinify($("#sentence").val());
		}
	});
	$("#translate").click(function(){
		latinify($("#sentence").val());
	});
	function latinify(sentence){
		sentence = sentence.replace(/ +(?= )/g,'');//removes multiple space together
		sentence = sentence.replace(/[\.]|[,]|[;]|[!]/g, ''); //removes punctuation
		$("#results").empty();
		var query_string = sentence.replace(/ /g,'+'); //changes spaces to + signs for query string
		sentence = sentence.replace(/^\s+|\s+$/g,''); //removes white space before and after the first and last words
		words = sentence.split(" ");
		definitions = new Array(words.length);
		for(var i =0; i < words.length; i++){
			$("#results").append('<div id="word-' + i + '" class="word"></div>');
		}
		for(var i =0; i < words.length; i++){
			getLatin(words[i], i);
		}
		if(history.pushState)
			history.pushState({}, document.title, location.protocol + '//' + location.host + location.pathname + "?s=" + query_string); //long version to prevent copying query string
	}
	function getLatin(word, index){
		definitions[index] = "could not connect"; //message to default to if the ajax fails
		$.ajax({
            type: "GET", 
			url: 'newGetWordEnglish.php',
			data: "word=" + word
        }).done(function(data){
        	var id = "#word-" + index;
        	$(id).html(data);
        });
	}
});