// AJAX change role \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$('.role-select').each( function () {
    $(this).change( function(e){
        e.preventDefault();

        const $object = $(this);
        const $indexUser = this.getAttribute('id');
        const $originURL = window.location.origin;
        // console.log(OriginURL);

        const $selectedRole = this.getAttribute('data-role');
        const $responseConfirm = confirm('Voulez-vous vraiment changer le rôle de l\'utilisateur');
        /* response => true (OK) ou false (ANNULER) */

        if ( !$responseConfirm ) {
            /* si on annule le changement de rôle on remet le bon rôle dans le select */
            $(this).val($selectedRole);
        } else {
            /* sinon le select garde la valeur sélectionner et on attribut le nouveau rôle au data-role */
            const $newRole = $("option:selected", this).val();
            const $url = $originURL + "/admin/user/ajaxedit/" + $indexUser + "/role/" + $newRole;
            // console.log($url);

            function flash(response)
            {
                $( "div.flash-message" ).empty().append(
                    '            <div class="alert alert-success text-center" style="font-size: .8rem;">\n' +
                                    response.data.message +
                    '            </div>\n'
                );
            }

            axios.get($url).then( function(response){
                // console.log(response);

                if ( response.status === 200 )
                {
                    $object.attr('data-role', $newRole);
                    flash(response);
                } else if ( response.status === 403 )
                {
                    flash(response);
                }
            });


        }


    } );
} );
// FIN AJAX change role \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
