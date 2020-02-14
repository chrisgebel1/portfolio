$(document).ready(function(){

   $(document).on('click', '#mix-projects', function (e) {
       e.preventDefault();

       $.ajax({
           url: $url,
           method: "POST",
           dataType: "json",
           success: function(data)
           {
               if ( data.status === 'success' ) {
                   $("#mix-change").replaceWith(data.html);
               } else if ( data.status === 'error' ) {
                   console.log(data);
               }
           },
           error:function(x,e) {
               if (x.status===0) {
                   alert('You are offline!!\n Please Check Your Network.');
               } else if(x.status===404) {
                   alert('Requested URL not found.');
               } else if(x.status===500) {
                   alert('Internel Server Error.');
               } else if(e==='parsererror') {
                   alert('Error.\nParsing JSON Request failed.');
               } else if(e==='timeout'){
                   alert('Request Time out.');
               } else {
                   alert('Unknow Error.\n'+x.responseText);
               }
           }

       });
   });
});