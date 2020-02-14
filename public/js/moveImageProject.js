$(document).ready( function(){
    "use strict";

    // $(".container-delete").mouseenter( function (){
    //     $(".delete-img-btn-legend",
    //         // this).stop().animate({"top":"0%",}, 200, 'swing');
    //         this).stop().fadeTo(50, 1);
    // });
    // $(".container-delete").mouseleave( function (){
    //     $(".delete-img-btn-legend",
    //         // this).stop().animate({"top":"100%",}, 200, 'swing');
    //         this).stop().fadeTo(50, 0);
    // });

    $(document).on('click', '.order-move', function(e){
        e.preventDefault();

        // console.log($(this)[0].dataset.order);
        // console.log($(this)[0].offsetParent.offsetParent.children[0].src);
        const $params = $(this)[0].dataset.order.split('-');
        const $p = $params[0];
        const $i = $params[1];
        const $o = $params[2];

        const $regex = /(?:(\w+\.\w+))$/gm;
        const $pathImg = $(this)[0].offsetParent.offsetParent.children[0].src;
        const $img = (RegExp($regex).exec($pathImg))[0];

        // console.log($params);

        const $responseConfirm = confirm('Voulez-vous vraiment modifier l\'odre ?');
        /* response => true (OK) ou false (ANNULER) */

        if ($responseConfirm) {
            // console.log($paramProject);
            // console.log($paramIndexImg);
            // console.log($paramOrder);

            $.ajax({
                url:$url,
                method:"POST",
                data:{
                    project: $p,
                    img: $img,
                    indexImg: $i,
                    order: $o
                },
                dataType: "json",
                success:function(data)
                {
                    if ( data.status === 'success' ) {
                        // console.log(data);
                        $(".project-images").replaceWith(data.html);

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

        }

    });


});
