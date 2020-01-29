// AJAX change role \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$('.role-select').each( function () {
    $(this).change( function(e){
        e.preventDefault();

        let selectedRole = this.getAttribute('data-role');
        let response = confirm('Voulez-vous vraiment changer le rôle de l\'utilisateur');

        // console.log(response);

        if ( !response ) { /* si on annule le changement de rôle */
            $(this).val(selectedRole);
        }



        // const $indexImage = this.getAttribute('data-indexImage');
        // const $url = this.href;
        // console.log($indexImage);
        // console.log($url);
        //
        // axios.get($url).then( function(response){
        //     // console.log(response);
        //     if ( response.status == 200 )
        //     {
        //         $( "#imageIndex" + $indexImage ).remove();
        //     }
        // });


    } );
} )
// FIN AJAX change role \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
