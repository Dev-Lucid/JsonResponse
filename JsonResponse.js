
function jsonResponse(newHash,defaultUrl){
    var url = new String(newHash);
    url = url.substring(2,url.length); // remove #!
    if(url == ''){ // use the default url if this is empty
        url = defaultUrl;
    };
    url = url.split('|'); // split up the url into the todo and the paramters
    var data = {};
    data['todo'] = url.shift();  // shift off the todo
    while(url.length > 0){ // loop over the remaining elements and transform into an associative array
        data[url.shift()] = url.shift();
    };

    // send it on up via ajax! :D
    jQuery.ajax('app.php',{
        'data':data,
        'success':function(response){
            var js = '';
            for(var key in response){
                switch(key){
                    case 'title': 
                        document.title = response[key]; 
                        break;
                    case 'keywords': 
                        $('meta[name=keywords]').attr('content',response[key]);
                        break;
                    case 'description': 
                        $('meta[name=description]').attr('content',response[key]);
                        break;
                    case 'javascript': 
                        js = response[key]; 
                        break;
                    case 'append': 
                        for(var selector in response[key]){
                            jQuery(selector).append(response[key][selector]);
                        }
                        break;
                    case 'prepend': 
                        for(var selector in response[key]){
                            jQuery(selector).prepend(response[key][selector]);
                        }
                        break;
                    case 'replace': 
                        for(var selector in response[key]){
                            jQuery(selector).html(response[key][selector]);
                        }
                        break;
                }
            }
            if (js !=''){
                eval(js);
            }
        }
    });
};