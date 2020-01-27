$( document ).ready(function() {
    // function pour le formulaire (SELECT) \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    var $type = $('#project_type');

    // cas pour la modification d'un projet \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    var selectedTypeModif = $type.children("option:selected").val();
    // alert(selectedTypeModif);
    if (selectedTypeModif === undefined || selectedTypeModif === "") {
        $("#project_category").css("display", "none");
    } else {
        $("#project_category").css("display", "block");
    }

    // cas pour la création d'un projet \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    // When type gets selected ...
    $type.change(function() {
        var selectedType = $(this).children("option:selected").val();

        // ... retrieve the corresponding form.
        var $form = $(this).closest('form');
        // Simulate form data, but only include the selected type value.
        var data = {};
        data[$type.attr('name')] = $type.val();
        // Submit data via AJAX to the form's action path.
        $.ajax({
            url : $form.attr('action'),
            type: $form.attr('method'),
            data : data,
            success: function(html) {
                // Replace current position field ...
                $('#project_category').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html).find('#project_category')
                );
                // Position field now displays the appropriate categories.

                if (selectedType !== "") {
                    $("#project_category").css("display", "block");
                } else {
                    $("#project_category").css("display", "none");
                }

            }
        });
    });
    // FIN function pour le formulaire (SELECT) \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\


    // function pour le formulaire (INPUT FILE) \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    bsCustomFileInput.init()
    // FIN function pour le formulaire (INPUT FILE) \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\



    // AJAX delete image \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    $('.delete-img-btn').each( function () {
        $(this).click( function(e){
            e.preventDefault();

            const $indexImage = this.getAttribute('data-indexImage');
            const $url = this.href;
            console.log($indexImage);
            console.log($url);

            axios.get($url).then( function(response){
                // console.log(response);
                if ( response.status == 200 )
                {
                    $( "#imageIndex" + $indexImage ).remove();
                }
            });


        } );
    } )
    // FIN AJAX delete image \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

});


