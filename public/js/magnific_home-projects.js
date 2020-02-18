$(document).ready(function(){
    // Magnific popup calls
    $('#portfolio').magnificPopup({
        delegate: '.div-portfolio a',
        type: 'image',
        closeOnContentClick: false,
        closeBtnInside: false,
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1]
        },
        image: {
            tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
            titleSrc: function(item) {
                return item.el.attr('title') + ' &middot; <a class="image-source-link" href="'+item.el.attr('data-source')+'" <!--target="_blank"--> plus d\'infos</a>';
            }
        },
        zoom: {
            enabled: true,
        }
    });

});