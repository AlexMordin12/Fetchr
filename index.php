<html>
  <head>
    <link rel="stylesheet" href="microphone/microphone.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<style>

		.bottom{
			height: 20%
		}
		.jumbotron{
			height: 85%;
    		width: 100%;
    	
    		top: 50%;
    		left: 62%;
			background-color: gray;
			color: white;
		}
	</style>
  </head>

  <body style="text-align: center;">
    
    <div class="jumbotron">
      <h1>Fetchr</h1>
      <p>A smarter way to ask for things</p>
      <center><div id="microphone"></div></center>
      <pre id="result"></pre>
      <div id="info"></div>
      <div id="error"></div>
      <div id="conversation"></div>
  </div>

  <div class="page-header">
  <h1>Siri in a browser<br><br> 
  <small><div style="padding-left: 20%; padding-right: 20%">Fetchr is a dynamic input, voice controlled search engine.<br> 
  Utilizing wit.ai API to parse vocal inputs in to meaningful results<br>
  Asking for directions, Getting the weather, opening web pages, and answering questions are some of the Fetchr's features.
  </div></small>
  </h1>
  </div>
  
  <div class="bottom">
    <div class="jumbotron">
      <p>Made at HackJam @ Berkeley</p>
  	</div>
  </div>
  
  <script src="microphone/microphone.min.js"></script>

    <script>
      var mic = new Wit.Microphone(document.getElementById("microphone"));
      var info = function (msg) {
        document.getElementById("info").innerHTML = msg;
      };
      var error = function (msg) {
        document.getElementById("error").innerHTML = msg;
      };
      mic.onready = function () {
        info("Microphone is ready to record");
      };
      mic.onaudiostart = function () {
        info("Recording started");
        error("");
      };
      mic.onaudioend = function () {
        info("Recording stopped, processing started");
      };
      mic.onresult = function (intent, entities) {
        var r = kv("intent", intent);
        for (var k in entities) {
          var e = entities[k];

          if (!(e instanceof Array)) {
            r += kv(k, e.value);
          } else {
            for (var i = 0; i < e.length; i++) {
              r += kv(k, e[i].value);
            }
          }
        }


        switch(intent){

          case "weather":
            if(entities.weather_loc == undefined) {
              var actionUrl = "http://www.google.com/search?q=weather here";
            }
            else{
              var actionUrl = "http://www.google.com/search?q=weather in" + " " +entities.weather_loc.value; 
            }

            OpenInNewTab(actionUrl)
          break;

          case "music":
			var actionUrl = "http://www.youtube.com/results?search_query=" + entities.song.value;
			OpenInNewTab(actionUrl);
          	break;

		
		  case "search":
				
			var actionUrl = "http://www.google.com/search?q=" + entities.term_search.value;
			OpenInNewTab(actionUrl);
		    break;

		  case 'newTab':
		  	OpenInNewTab("http://www.google.com/");
		  break;

		  case "direction":
				if (entities.direction_location == undefined) {
					var actionUrl = "http://maps.google.com/maps/?q=directions to" + " " + entities.direction_destination.value;
					OpenInNewTab(actionUrl);
				}
				else {
					var actionUrl = "http://maps.google.com/maps/?q=directions from" + " " + entities.direction_location.value + " to " + entities.direction_destination.value;
					OpenInNewTab(actionUrl);
				}
			break;

		  case "open":
				if((entities.open_website.value).indexOf(".") < 0) {
					var actionUrl = "http://" + entities.open_website.value.replace(/ /g,'') + ".com";
					OpenInNewTab(actionUrl)
				}
				else {
					var actionUrl = "http://" + entities.open_website.value.replace(/ /g,'');
					OpenInNewTab(actionUrl)
				}
			break;

          default:
        	document.getElementById("result").innerHTML = "I don't understand";  
          break;

          }
         
      };


      mic.onerror = function (err) {
        error("Error: " + err);
      };
      mic.onconnecting = function () {
        info("Microphone is connecting");
      };
      mic.ondisconnected = function () {
        info("Microphone is not connected");
      };

      mic.connect("FFLAC5NG44F6R6TMWTOBXZADE7W5NLWZ");
      
      



      function OpenInNewTab(url) {
        var win = window.open(url, '_blank');
        if(win){
          //Browser has allowed it to be opened
          win.focus();
        }else{
          //Broswer has blocked it
          alert('Please allow popups for this site');
        }
      }
      
      function kv (k, v) {
        if (toString.call(v) !== "[object String]") {
          v = JSON.stringify(v);
        }
        return k + "=" + v + "\n";
      }
    </script>
  </body>
  </html>